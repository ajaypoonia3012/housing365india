<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\VendorPayout;
use App\Models\WorkOrder;
use App\Services\Housing365CareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorPortalController extends Controller
{
    public function __construct(
        protected Housing365CareService $careService
    ) {}

    /**
     * Vendor Jobs Overview.
     */
    public function jobs(Request $request)
    {
        $user = Auth::user();
        if (! $user->hasRole('vendor') && ! $user->hasRole('admin')) {
            abort(403, 'Vendor persona required to access jobs.');
        }

        $assignedRequests = ServiceRequest::with(['category', 'property', 'latestQuote', 'workOrder'])
            ->where('assigned_vendor_id', $user->id)
            ->latest()
            ->paginate(10);

        $workOrders = WorkOrder::with(['serviceRequest.category', 'property', 'quote'])
            ->where('vendor_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('vendor.jobs', compact('assignedRequests', 'workOrders', 'user'));
    }

    /**
     * Show detailed Job page for vendor.
     */
    public function showJob(int $id)
    {
        $user = Auth::user();
        $serviceRequest = ServiceRequest::with(['category', 'property', 'quotes', 'workOrder'])
            ->findOrFail($id);

        if ($serviceRequest->assigned_vendor_id !== $user->id && ! $user->hasRole('admin')) {
            abort(403, 'You are not assigned to this job.');
        }

        $existingQuote = $serviceRequest->quotes()->where('vendor_id', $user->id)->latest()->first();

        return view('vendor.job-detail', compact('serviceRequest', 'existingQuote', 'user'));
    }

    /**
     * Vendor submits quote.
     */
    public function submitQuote(Request $request, int $id)
    {
        $user = Auth::user();
        $serviceRequest = ServiceRequest::findOrFail($id);

        $validated = $request->validate([
            'labour_amount' => 'required|numeric|min:0',
            'materials_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'other_charges' => 'nullable|numeric|min:0',
            'estimated_duration' => 'required|string|max:50',
            'notes' => 'nullable|string|max:2000',
            'valid_until' => 'nullable|date',
        ]);

        try {
            $quote = $this->careService->submitQuote($serviceRequest, $user, $validated);

            return redirect()->route('vendor.jobs.show', $serviceRequest->id)
                ->with('success', 'Quote #'.$quote->quote_number.' submitted for ₹'.number_format($quote->total_amount, 2).'! Awaiting customer approval.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Vendor starts work on work order.
     */
    public function startWork(Request $request, int $workOrderId)
    {
        $user = Auth::user();
        $workOrder = WorkOrder::findOrFail($workOrderId);

        $beforePhotos = [];
        if ($request->hasFile('before_photos')) {
            foreach ($request->file('before_photos') as $file) {
                $path = $file->store('work-orders', 'public');
                $beforePhotos[] = '/storage/'.$path;
            }
        }

        try {
            $this->careService->startWorkOrder($workOrder, $user, $beforePhotos ?: null, $request->notes);

            return redirect()->route('vendor.jobs.show', $workOrder->service_request_id)
                ->with('success', 'Work order marked as In-Progress.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Vendor completes job and submits for Quality Control review.
     */
    public function completeWork(Request $request, int $workOrderId)
    {
        $user = Auth::user();
        $workOrder = WorkOrder::findOrFail($workOrderId);

        if ($workOrder->vendor_id !== $user->id && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized to complete this work order.');
        }

        $afterPhotos = [];
        if ($request->hasFile('after_photos')) {
            foreach ($request->file('after_photos') as $file) {
                $path = $file->store('work-orders', 'public');
                $afterPhotos[] = '/storage/'.$path;
            }
        }

        try {
            $this->careService->completeWorkOrder(
                $workOrder,
                $user,
                $afterPhotos,
                $request->input('work_notes'),
                $request->input('materials_used')
            );

            return redirect()->route('vendor.jobs.show', $workOrder->service_request_id)
                ->with('success', 'Job completed and submitted for QC review.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Vendor accepts assigned service request offer.
     */
    public function acceptOffer(Request $request, int $id)
    {
        $user = Auth::user();
        $serviceRequest = ServiceRequest::findOrFail($id);

        if ($serviceRequest->assigned_vendor_id !== $user->id && ! $user->hasRole('admin')) {
            abort(403, 'You are not assigned to this job offer.');
        }

        try {
            $this->careService->vendorAcceptAssignment($serviceRequest, $user);

            return redirect()->route('vendor.jobs.show', $serviceRequest->id)
                ->with('success', 'Assignment accepted! You may now submit an assessment quote.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Vendor rejects assigned service request offer with reason.
     */
    public function rejectOffer(Request $request, int $id)
    {
        $user = Auth::user();
        $serviceRequest = ServiceRequest::findOrFail($id);

        if ($serviceRequest->assigned_vendor_id !== $user->id && ! $user->hasRole('admin')) {
            abort(403, 'You are not assigned to this job offer.');
        }

        $reason = $request->input('reason', 'Vendor unavailable at requested time.');

        try {
            $this->careService->vendorRejectAssignment($serviceRequest, $user, $reason);

            return redirect()->route('vendor.jobs')
                ->with('info', 'Assignment offer declined. The job has been returned for re-triage.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Vendor updates Work Order Status (e.g. VENDOR_EN_ROUTE, WAITING_FOR_PARTS).
     */
    public function updateStatus(Request $request, int $workOrderId)
    {
        $user = Auth::user();
        $workOrder = WorkOrder::findOrFail($workOrderId);

        if ($workOrder->vendor_id !== $user->id && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized to modify this work order.');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:VENDOR_EN_ROUTE,IN_PROGRESS,WAITING_FOR_PARTS',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->careService->updateWorkOrderStatus($workOrder, $user, $validated['status'], [
                'work_notes' => $validated['notes'] ?? $workOrder->work_notes,
            ]);

            return redirect()->route('vendor.jobs.show', $workOrder->service_request_id)
                ->with('success', 'Work order status updated to '.$validated['status'].'.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Vendor Payout Ledger & History.
     */
    public function payouts(Request $request)
    {
        $user = Auth::user();
        if (! $user->hasRole('vendor') && ! $user->hasRole('admin')) {
            abort(403, 'Vendor persona required.');
        }

        $payouts = VendorPayout::with(['workOrder.serviceRequest.category', 'careInvoice'])
            ->where('vendor_id', $user->id)
            ->latest()
            ->paginate(15);

        $totalEarned = VendorPayout::where('vendor_id', $user->id)
            ->where('status', VendorPayout::STATUS_PAID)
            ->sum('net_amount');

        $pendingPayout = VendorPayout::where('vendor_id', $user->id)
            ->whereIn('status', [VendorPayout::STATUS_PENDING, VendorPayout::STATUS_APPROVED, VendorPayout::STATUS_PROCESSING])
            ->sum('net_amount');

        return view('vendor.payouts', compact('payouts', 'totalEarned', 'pendingPayout', 'user'));
    }
}
