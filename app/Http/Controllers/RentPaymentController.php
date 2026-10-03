<?php

namespace App\Http\Controllers;

use App\Models\RentLedger;
use App\Models\RentPayment;
use App\Services\RentPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentPaymentController extends Controller
{
    public function __construct(
        protected RentPaymentService $rentService
    ) {}

    /**
     * Show rent ledger detail & payment screen for tenant.
     */
    public function showLedger(int $ledgerId)
    {
        $user = Auth::user();
        $ledger = RentLedger::with(['property', 'account', 'payments'])->findOrFail($ledgerId);

        $isTenant = (int) $ledger->tenant_id === (int) $user->id;
        $isOwner = (int) $ledger->owner_id === (int) $user->id || ($ledger->property && $ledger->property->isOwnedBy($user));
        $isAdmin = $user->hasRole('admin');

        if (! $isTenant && ! $isOwner && ! $isAdmin) {
            abort(403, 'Unauthorized access to this rent ledger.');
        }

        return view('rent.ledger-show', compact('ledger', 'user', 'isTenant', 'isOwner'));
    }

    /**
     * Tenant initiates rent payment.
     */
    public function pay(Request $request, int $ledgerId)
    {
        $user = Auth::user();
        $ledger = RentLedger::findOrFail($ledgerId);

        if ((int) $ledger->tenant_id !== (int) $user->id && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized: You are not the assigned tenant for this rent bill.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'nullable|string|in:upi,netbanking,card',
            'idempotency_key' => 'nullable|string|max:100',
        ]);

        $amount = (float) $validated['amount'];
        $method = $validated['payment_method'] ?? 'upi';
        $key = $validated['idempotency_key'] ?? null;

        $result = $this->rentService->initiatePayment($ledger, $user, $amount, $method, $key);

        return view('rent.checkout', [
            'payment' => $result['payment'],
            'gatewayOrder' => $result['gateway_order'],
            'ledger' => $ledger,
            'user' => $user,
        ]);
    }

    /**
     * Server-side payment confirmation callback.
     */
    public function confirm(Request $request, int $paymentId)
    {
        $user = Auth::user();
        $payment = RentPayment::with('ledger')->findOrFail($paymentId);

        if ((int) $payment->tenant_id !== (int) $user->id && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized payment transaction.');
        }

        $validated = $request->validate([
            'gateway_payment_id' => 'required|string',
            'gateway_signature' => 'nullable|string',
        ]);

        $confirmed = $this->rentService->confirmPayment($payment, $validated);

        if ($confirmed->status === RentPayment::STATUS_PAID) {
            return redirect()->route('rent.ledgers.show', $confirmed->rent_ledger_id)
                ->with('success', 'Rent payment verified and credited successfully! Receipt generated.');
        }

        return redirect()->route('rent.ledgers.show', $confirmed->rent_ledger_id)
            ->with('error', 'Payment verification failed: '.$confirmed->failure_reason);
    }

    /**
     * Webhook receiver for automated payment status sync.
     */
    public function webhook(Request $request)
    {
        $signature = $request->header('X-Razorpay-Signature', $request->header('Signature', ''));
        $payload = $request->all();

        $success = $this->rentService->handleWebhook($payload, $signature);

        return response()->json(['status' => $success ? 'success' : 'ignored'], $success ? 200 : 400);
    }

    /**
     * Tenant workspace list of rent bills.
     */
    public function tenantLedgers()
    {
        $user = Auth::user();
        $ledgers = RentLedger::with(['property', 'account'])
            ->where('tenant_id', $user->id)
            ->orderBy('due_date', 'desc')
            ->paginate(10);

        return view('rent.tenant-ledgers', compact('ledgers', 'user'));
    }

    /**
     * Owner workspace overview of active tenancies and rent collections.
     */
    public function ownerRentOverview()
    {
        $user = Auth::user();
        $ledgers = RentLedger::with(['property', 'tenant', 'account'])
            ->where('owner_id', $user->id)
            ->orderBy('due_date', 'desc')
            ->paginate(10);

        return view('rent.owner-overview', compact('ledgers', 'user'));
    }
}
