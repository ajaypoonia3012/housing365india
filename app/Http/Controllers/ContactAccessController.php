<?php

namespace App\Http\Controllers;

use App\Models\ContactAccessPackage;
use App\Models\ContactAccessPurchase;
use App\Models\Property;
use App\Services\ContactAccessService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactAccessController extends Controller
{
    public function __construct(
        protected ContactAccessService $contactAccessService
    ) {}

    /**
     * Get active contact access packages (JSON for modal / paywall).
     */
    public function packages(): JsonResponse
    {
        $packages = $this->contactAccessService->getAvailablePackages();

        return response()->json([
            'success' => true,
            'packages' => $packages,
            'user_credits' => Auth::check() ? Auth::user()->availableContactCredits() : 0,
        ]);
    }

    /**
     * Initiate a Contact Access package purchase.
     */
    public function purchase(Request $request): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => 'Please sign in to purchase Contact Access.',
            ], 401);
        }

        $validated = $request->validate([
            'package_id' => 'required|exists:contact_access_packages,id',
        ]);

        $package = ContactAccessPackage::findOrFail($validated['package_id']);

        if (! $package->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'This package is currently unavailable.',
            ], 422);
        }

        $result = $this->contactAccessService->initiatePurchase(Auth::user(), $package);

        return response()->json([
            'success' => true,
            'purchase_id' => $result['purchase']->id,
            'order_reference' => $result['purchase']->order_reference,
            'amount' => $result['purchase']->amount_paid,
            'currency' => $result['purchase']->currency,
            'order' => $result['order'],
        ]);
    }

    /**
     * Server-side verify payment confirmation and activate credits.
     */
    public function verify(Request $request): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => 'Authentication required.',
            ], 401);
        }

        $validated = $request->validate([
            'purchase_id' => 'required|exists:contact_access_purchases,id',
            'gateway_order_id' => 'required|string',
            'gateway_payment_id' => 'required|string',
            'signature' => 'required|string',
        ]);

        $purchase = ContactAccessPurchase::where('id', $validated['purchase_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $verified = $this->contactAccessService->confirmPayment($purchase, [
            'gateway_order_id' => $validated['gateway_order_id'],
            'gateway_payment_id' => $validated['gateway_payment_id'],
            'signature' => $validated['signature'],
        ]);

        if (! $verified) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed. Invalid transaction signature.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Contact Access activated successfully.',
            'credits_remaining' => Auth::user()->fresh()->availableContactCredits(),
        ]);
    }

    /**
     * Unlock owner contact for a property.
     * Enforces authentication, credit check, and duplicate unlock protection.
     */
    public function unlockContact(Request $request, int|string $id): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => 'Please sign in to unlock direct owner contact details.',
            ], 401);
        }

        $property = Property::findOrFail($id);
        $user = Auth::user();

        $result = $this->contactAccessService->unlockContact($user, $property);

        if (! $result['success']) {
            $packages = $this->contactAccessService->getAvailablePackages();

            return response()->json([
                'success' => false,
                'require_credits' => true,
                'message' => $result['message'],
                'packages' => $packages,
                'credits_remaining' => 0,
            ], 402);
        }

        return response()->json($result);
    }
}
