<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\ContactAccessPackage;
use App\Models\ContactAccessPurchase;
use App\Models\ContactUnlock;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ContactAccessService
{
    public function __construct(
        protected PaymentGatewayInterface $paymentGateway
    ) {}

    /**
     * Retrieve all active contact access packages.
     */
    public function getAvailablePackages(): Collection
    {
        return ContactAccessPackage::query()
            ->active()
            ->get();
    }

    /**
     * Initiate a package purchase and prepare gateway order.
     */
    public function initiatePurchase(User $user, ContactAccessPackage $package): array
    {
        if (! $package->is_active) {
            throw new InvalidArgumentException('This Contact Access package is currently unavailable.');
        }

        $purchase = ContactAccessPurchase::create([
            'user_id' => $user->id,
            'contact_access_package_id' => $package->id,
            'order_reference' => 'CA-'.strtoupper(Str::random(10)),
            'credits_granted' => $package->credits,
            'credits_remaining' => $package->credits,
            'amount_paid' => $package->price,
            'currency' => $package->currency ?? 'INR',
            'payment_gateway' => 'sandbox',
            'payment_status' => 'pending',
        ]);

        $orderPayload = $this->paymentGateway->createOrder($purchase);

        return [
            'purchase' => $purchase,
            'order' => $orderPayload,
        ];
    }

    /**
     * Confirm a payment server-side and activate contact credits.
     */
    public function confirmPayment(ContactAccessPurchase $purchase, array $paymentData): bool
    {
        if ($purchase->isPaid()) {
            return true;
        }

        $isValid = $this->paymentGateway->verifyPayment($purchase, $paymentData);

        if (! $isValid) {
            $purchase->update([
                'payment_status' => 'failed',
            ]);

            return false;
        }

        $validityDays = $purchase->package?->validity_days;
        $expiresAt = $validityDays ? now()->addDays($validityDays) : null;

        $purchase->update([
            'payment_status' => 'paid',
            'gateway_payment_id' => $paymentData['gateway_payment_id'] ?? null,
            'paid_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        return true;
    }

    /**
     * Check if a user can unlock a property (either already unlocked, owner, or has credits).
     */
    public function canUnlock(User $user, Property $property): bool
    {
        if ($property->isUnlockedBy($user)) {
            return true;
        }

        return $user->availableContactCredits() > 0;
    }

    /**
     * Unlock owner contact for a property.
     * Prevents duplicate charging if already unlocked.
     */
    public function unlockContact(User $user, Property $property): array
    {
        // 1. Check if already unlocked (zero duplicate charge)
        $existingUnlock = ContactUnlock::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->where('status', 'active')
            ->first();

        if ($existingUnlock) {
            return [
                'success' => true,
                'already_unlocked' => true,
                'message' => 'Contact already unlocked.',
                'contact' => [
                    'name' => $property->seller_name ?? 'Property Owner',
                    'phone' => $property->seller_phone ?? null,
                    'email' => $property->seller_email ?? null,
                ],
                'credits_remaining' => $user->availableContactCredits(),
            ];
        }

        // 2. Check if owner viewing own property
        if ((! empty($property->seller_email) && strtolower($property->seller_email) === strtolower($user->email)) ||
            (! empty($property->seller_phone) && $property->seller_phone === $user->phone)) {
            return [
                'success' => true,
                'already_unlocked' => true,
                'message' => 'You are the owner of this property.',
                'contact' => [
                    'name' => $property->seller_name ?? 'Property Owner',
                    'phone' => $property->seller_phone ?? null,
                    'email' => $property->seller_email ?? null,
                ],
                'credits_remaining' => $user->availableContactCredits(),
            ];
        }

        // 3. Atomically consume 1 credit from earliest valid purchase
        return DB::transaction(function () use ($user, $property) {
            $existing = ContactUnlock::where('user_id', $user->id)
                ->where('property_id', $property->id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                return [
                    'success' => true,
                    'already_unlocked' => true,
                    'message' => 'Contact already unlocked.',
                    'contact' => [
                        'name' => $property->seller_name ?? 'Property Owner',
                        'phone' => $property->seller_phone ?? null,
                        'email' => $property->seller_email ?? null,
                    ],
                    'credits_remaining' => $user->availableContactCredits(),
                ];
            }

            $purchase = ContactAccessPurchase::where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->where('credits_remaining', '>', 0)
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->orderBy('created_at', 'asc')
                ->lockForUpdate()
                ->first();

            if (! $purchase) {
                return [
                    'success' => false,
                    'require_credits' => true,
                    'message' => 'You do not have any available Contact Credits. Please purchase a Contact Access package to view owner contact details.',
                    'credits_remaining' => 0,
                ];
            }

            // Deduct 1 credit
            $purchase->decrement('credits_remaining', 1);

            // Record unlock
            ContactUnlock::create([
                'user_id' => $user->id,
                'property_id' => $property->id,
                'contact_access_purchase_id' => $purchase->id,
                'unlocked_at' => now(),
                'status' => 'active',
            ]);

            return [
                'success' => true,
                'already_unlocked' => false,
                'message' => 'Owner contact unlocked successfully.',
                'contact' => [
                    'name' => $property->seller_name ?? 'Property Owner',
                    'phone' => $property->seller_phone ?? null,
                    'email' => $property->seller_email ?? null,
                ],
                'credits_remaining' => $user->availableContactCredits(),
            ];
        });
    }
}
