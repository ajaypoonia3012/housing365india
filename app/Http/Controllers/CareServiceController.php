<?php

namespace App\Http\Controllers;

use App\Models\CareInvoice;
use App\Models\CareServiceCategory;
use App\Models\Property;
use App\Models\ServiceQuote;
use App\Models\ServiceRequest;
use App\Services\Housing365CareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CareServiceController extends Controller
{
    public function __construct(
        protected Housing365CareService $careService
    ) {}

    /**
     * Public Housing365 Care Showcase Page.
     */
    public function index()
    {
        $categories = CareServiceCategory::active()->get();
        $emergencyCategories = CareServiceCategory::emergency()->get();

        return view('care.index', compact('categories', 'emergencyCategories'));
    }

    /**
     * Category Landing Page (e.g. /care/plumbing, /care/electrical).
     */
    public function category(string $slug)
    {
        $category = CareServiceCategory::where('slug', $slug)->firstOrFail();
        $relatedCategories = CareServiceCategory::active()
            ->where('id', '!=', $category->id)
            ->take(4)
            ->get();

        return view('care.category', compact('category', 'relatedCategories'));
    }

    /**
     * Show the authenticated Service Request creation form.
     */
    public function createForm(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login')->with('message', 'Please sign in to request Housing365 Care services.');
        }

        $categories = CareServiceCategory::active()->get();

        // User's owned properties or related properties
        $cleanPhone = $user->phone ? preg_replace('/[^0-9]/', '', $user->phone) : null;
        $properties = Property::query()
            ->when($cleanPhone, function ($query, $phone) use ($user) {
                $query->where(function ($q) use ($phone, $user) {
                    $q->where('seller_phone', 'like', "%{$phone}%")
                        ->orWhere('seller_email', $user->email)
                        ->orWhere('user_id', $user->id);
                });
            }, function ($query) use ($user) {
                $query->where('seller_email', $user->email)
                    ->orWhere('user_id', $user->id);
            })
            ->get();

        $selectedPropertyId = $request->query('property_id');
        $selectedCategorySlug = $request->query('category');

        return view('care.request', compact('categories', 'properties', 'selectedPropertyId', 'selectedCategorySlug'));
    }

    /**
     * Store new Service Request.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            abort(401);
        }

        $validated = $request->validate([
            'service_category_id' => 'required|exists:care_service_categories,id',
            'property_id' => 'nullable|exists:properties,id',
            'title' => 'nullable|string|max:150',
            'description' => 'required|string|max:3000',
            'priority' => 'nullable|in:low,normal,high,emergency',
            'preferred_date' => 'nullable|date',
            'preferred_time_window' => 'nullable|string|max:50',
            'contact_preference' => 'nullable|string|max:50',
            'photos.*' => 'nullable|image|max:10240',
        ]);

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('care-requests', 'public');
                $photos[] = '/storage/'.$path;
            }
        }
        $validated['photos'] = $photos;

        try {
            $serviceRequest = $this->careService->createRequest($user, $validated);

            return redirect()->route('care.requests.show', $serviceRequest->id)
                ->with('success', 'Your Housing365 Care service request ('.$serviceRequest->request_number.') has been submitted! Our operations team is triaging your request.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display service request status and quote/work order details.
     */
    public function show(int $id)
    {
        $user = Auth::user();
        $serviceRequest = ServiceRequest::with([
            'category',
            'property',
            'assignedVendor',
            'quotes.vendor',
            'workOrder',
        ])->findOrFail($id);

        if (! $serviceRequest->isAuthorizedUser($user) && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized access to this service request.');
        }

        return view('care.show', compact('serviceRequest', 'user'));
    }

    /**
     * Customer approves a received quote.
     */
    public function approveQuote(Request $request, int $quoteId)
    {
        $user = Auth::user();
        $quote = ServiceQuote::with('serviceRequest')->findOrFail($quoteId);

        if (! $quote->serviceRequest->isAuthorizedUser($user) && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized to approve this quote.');
        }

        try {
            $workOrder = $this->careService->approveQuote($quote, $user);

            return redirect()->route('care.requests.show', $quote->service_request_id)
                ->with('success', 'Quote approved! Work order #'.$workOrder->work_order_number.' has been issued to the assigned vendor.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Customer declines a received quote with reason.
     */
    public function declineQuote(Request $request, int $quoteId)
    {
        $user = Auth::user();
        $quote = ServiceQuote::with('serviceRequest')->findOrFail($quoteId);

        if (! $quote->serviceRequest->isAuthorizedUser($user) && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized to decline this quote.');
        }

        $reason = $request->input('reason', 'Customer requested revisions or alternative quote.');

        try {
            $this->careService->customerRejectQuote($quote, $user, $reason);

            return redirect()->route('care.requests.show', $quote->service_request_id)
                ->with('info', 'Quote declined. Our operations team has been notified to re-triage your request.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Quality Control review submission.
     */
    public function reviewQc(Request $request, int $id)
    {
        $user = Auth::user();
        $serviceRequest = ServiceRequest::findOrFail($id);

        if (! $serviceRequest->isAuthorizedUser($user) && ! $user->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'decision' => 'required|in:approve,rework_required,dispute',
            'qc_notes' => 'nullable|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $decision = $validated['decision'];
        if ($decision === 'dispute') {
            $decision = 'reject';
        }

        $this->careService->reviewQualityControl($serviceRequest, $decision, $validated['qc_notes'] ?? null, $user);

        if (! empty($validated['rating'])) {
            $serviceRequest->update([
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null,
            ]);
        }

        $msg = match ($decision) {
            'approve' => 'Quality control approved! Invoice has been generated for final settlement.',
            'rework_required' => 'Rework requested. The assigned vendor has been notified to resolve pending items.',
            default => 'Dispute logged. Our senior operations lead will review the work.',
        };

        return redirect()->route('care.requests.show', $serviceRequest->id)->with('success', $msg);
    }

    /**
     * Customer confirms job resolution or requests rework.
     */
    public function confirmResolution(Request $request, int $id)
    {
        $user = Auth::user();
        $serviceRequest = ServiceRequest::findOrFail($id);

        if (! $serviceRequest->isAuthorizedUser($user) && ! $user->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'is_resolved' => 'required|boolean',
            'feedback' => 'nullable|string|max:1000',
        ]);

        try {
            $this->careService->customerConfirmResolution($serviceRequest, $user, (bool) $validated['is_resolved'], $validated['feedback'] ?? null);

            $msg = $validated['is_resolved']
                ? 'Thank you! You have confirmed the issue is resolved and the service request is closed.'
                : 'Rework request submitted! The vendor has been instructed to address the unfinished work.';

            return redirect()->route('care.requests.show', $serviceRequest->id)->with('success', $msg);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show Care Invoice.
     */
    public function showInvoice(int $id)
    {
        $user = Auth::user();
        $invoice = CareInvoice::with([
            'serviceRequest.category',
            'serviceRequest.property',
            'workOrder',
            'vendor',
            'user',
        ])->findOrFail($id);

        if (! $invoice->serviceRequest->isAuthorizedUser($user) && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        return view('care.invoice-show', compact('invoice', 'user'));
    }

    /**
     * Process Care Invoice payment.
     */
    public function payInvoice(Request $request, int $id)
    {
        $user = Auth::user();
        $invoice = CareInvoice::with('serviceRequest')->findOrFail($id);

        if (! $invoice->serviceRequest->isAuthorizedUser($user) && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized to pay this invoice.');
        }

        $validated = $request->validate([
            'gateway_order_id' => 'required|string',
            'gateway_payment_id' => 'required|string',
            'gateway_signature' => 'required|string',
            'payment_method' => 'nullable|string',
            'idempotency_key' => 'nullable|string',
        ]);

        try {
            $this->careService->processInvoicePayment($invoice, $user, $validated);

            return redirect()->route('care.invoices.show', $invoice->id)
                ->with('success', 'Payment successful! Invoice #'.$invoice->invoice_number.' has been settled.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
