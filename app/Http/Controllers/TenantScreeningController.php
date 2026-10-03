<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\TenantScreening;
use App\Models\VerificationDocument;
use App\Services\TenantScreeningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TenantScreeningController extends Controller
{
    public function __construct(
        protected TenantScreeningService $screeningService
    ) {}

    /**
     * Submit rental application for a property.
     */
    public function apply(Request $request, int $propertyId)
    {
        $user = Auth::user();
        $property = Property::findOrFail($propertyId);

        $validated = $request->validate([
            'monthly_income' => 'nullable|numeric|min:0',
            'occupation' => 'nullable|string|max:100',
            'employer_name' => 'nullable|string|max:150',
            'proposed_move_in' => 'nullable|date',
            'tenancy_months' => 'nullable|integer|min:1|max:60',
            'notes' => 'nullable|string|max:1000',
        ]);

        $application = $this->screeningService->createApplication($user, $property, $validated);

        return redirect()->route('tenant.screenings.show', $application->screening->id)
            ->with('success', 'Application submitted! Please review verification consent to proceed.');
    }

    /**
     * View tenant screening details and consent status.
     */
    public function showScreening(int $screeningId)
    {
        $user = Auth::user();
        $screening = TenantScreening::with(['application', 'property', 'documents'])->findOrFail($screeningId);

        // Security Authorization: Only applicant tenant, property owner, or admin
        $isTenant = (int) $screening->tenant_id === (int) $user->id;
        $isOwner = $screening->property && $screening->property->isOwnedBy($user);
        $isAdmin = $user->hasRole('admin');

        if (! $isTenant && ! $isOwner && ! $isAdmin) {
            abort(403, 'Unauthorized access to this screening record.');
        }

        return view('tenant.screening-show', compact('screening', 'user', 'isTenant', 'isOwner'));
    }

    /**
     * Tenant grants consent for background/KYC verification.
     */
    public function grantConsent(Request $request, int $screeningId)
    {
        $user = Auth::user();
        $screening = TenantScreening::findOrFail($screeningId);

        if ((int) $screening->tenant_id !== (int) $user->id && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized: Only the applicant tenant may grant verification consent.');
        }

        $request->validate([
            'confirm_consent' => 'required|accepted',
            'consent_purpose' => 'nullable|string|max:255',
        ]);

        $purpose = $request->input('consent_purpose', 'Residential Tenancy Background & Identity Verification');
        $this->screeningService->grantConsent($screening, $user, $request->ip(), $purpose);

        return back()->with('success', 'Consent recorded. Please upload your identification documents.');
    }

    /**
     * Upload identification or income proof document.
     */
    public function uploadDocument(Request $request, int $screeningId)
    {
        $user = Auth::user();
        $screening = TenantScreening::findOrFail($screeningId);

        if ((int) $screening->tenant_id !== (int) $user->id && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized: Cannot upload documents for another user.');
        }

        $validated = $request->validate([
            'document_type' => 'required|string|in:aadhaar,pan,passport,driving_licence,voter_id,salary_slip,bank_statement',
            'document_file' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png',
            'document_number' => 'nullable|string|max:50',
        ]);

        $doc = $this->screeningService->uploadDocument(
            $screening,
            $user,
            $request->file('document_file'),
            $validated['document_type'],
            $validated['document_number'] ?? null
        );

        // Run automated checks if consented
        if ($screening->status === TenantScreening::STATUS_DOCUMENTS_SUBMITTED) {
            $this->screeningService->runVerificationChecks($screening);
        }

        return back()->with('success', 'Document uploaded securely and queued for verification.');
    }

    /**
     * Access-controlled private document download.
     */
    public function downloadDocument(int $documentId)
    {
        $user = Auth::user();
        $document = VerificationDocument::with('screening.property')->findOrFail($documentId);

        $isDocOwner = (int) $document->user_id === (int) $user->id;
        $isPropertyOwner = $document->screening?->property?->isOwnedBy($user);
        $isAdmin = $user->hasRole('admin');

        if (! $isDocOwner && ! $isPropertyOwner && ! $isAdmin) {
            abort(403, 'Unauthorized: Access to this private document is restricted.');
        }

        if (! Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'Document file not found.');
        }

        return Storage::disk('local')->download($document->file_path, $document->original_filename);
    }

    /**
     * Property Owner views applicants for their property.
     */
    public function ownerApplicants(int $propertyId)
    {
        $user = Auth::user();
        $property = Property::with(['tenantApplications.tenant', 'tenantApplications.screening.documents'])->findOrFail($propertyId);

        if (! $property->isOwnedBy($user) && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized access to applicant records.');
        }

        return view('owner.applicants', compact('property', 'user'));
    }
}
