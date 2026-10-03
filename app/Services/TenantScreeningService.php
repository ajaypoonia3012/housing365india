<?php

namespace App\Services;

use App\Contracts\DocumentVerificationProviderInterface;
use App\Contracts\IdentityVerificationProviderInterface;
use App\Models\Property;
use App\Models\ServiceAuditLog;
use App\Models\TenantApplication;
use App\Models\TenantScreening;
use App\Models\User;
use App\Models\VerificationDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TenantScreeningService
{
    public function __construct(
        protected IdentityVerificationProviderInterface $identityProvider,
        protected DocumentVerificationProviderInterface $documentProvider
    ) {}

    /**
     * Create a rental application for a property.
     */
    public function createApplication(User $tenant, Property $property, array $data): TenantApplication
    {
        return DB::transaction(function () use ($tenant, $property, $data) {
            $appNumber = 'APP-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -6));

            $application = TenantApplication::create([
                'application_number' => $appNumber,
                'property_id' => $property->id,
                'tenant_id' => $tenant->id,
                'monthly_income' => $data['monthly_income'] ?? null,
                'occupation' => $data['occupation'] ?? null,
                'employer_name' => $data['employer_name'] ?? null,
                'proposed_move_in' => $data['proposed_move_in'] ?? null,
                'tenancy_months' => $data['tenancy_months'] ?? 11,
                'status' => TenantApplication::STATUS_SUBMITTED,
                'notes' => $data['notes'] ?? null,
            ]);

            // Auto-initiate a consent-gated screening record
            $this->createScreening($tenant, $application, $property);

            ServiceAuditLog::record($application, 'application_submitted', $tenant, [
                'property_id' => $property->id,
                'application_number' => $appNumber,
            ]);

            return $application;
        });
    }

    /**
     * Initialize a tenant screening record with CONSENT_REQUIRED status.
     */
    public function createScreening(User $tenant, ?TenantApplication $application = null, ?Property $property = null): TenantScreening
    {
        $screeningNumber = 'SCR-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -6));

        $screening = TenantScreening::create([
            'screening_number' => $screeningNumber,
            'tenant_application_id' => $application?->id,
            'tenant_id' => $tenant->id,
            'property_id' => $property?->id ?? $application?->property_id,
            'status' => TenantScreening::STATUS_CONSENT_REQUIRED,
            'id_verification_status' => 'PENDING',
        ]);

        ServiceAuditLog::record($screening, 'screening_initiated', $tenant, [
            'status' => TenantScreening::STATUS_CONSENT_REQUIRED,
        ]);

        return $screening;
    }

    /**
     * Record explicit, informed consent for screening and KYC.
     */
    public function grantConsent(TenantScreening $screening, User $tenant, string $ipAddress, string $purpose): TenantScreening
    {
        if ($screening->tenant_id !== $tenant->id && ! $tenant->hasRole('admin')) {
            throw new InvalidArgumentException('Unauthorized: Only the applicant tenant may grant consent.');
        }

        $screening->update([
            'status' => TenantScreening::STATUS_CONSENTED,
            'consent_granted_at' => now(),
            'consent_ip_address' => $ipAddress,
            'consent_purpose' => $purpose,
        ]);

        ServiceAuditLog::record($screening, 'consent_granted', $tenant, [
            'ip' => $ipAddress,
            'purpose' => $purpose,
        ]);

        return $screening->fresh();
    }

    /**
     * Securely store a verification document on private disk with masked identifier.
     */
    public function uploadDocument(
        TenantScreening $screening,
        User $user,
        UploadedFile|string $file,
        string $documentType,
        ?string $rawNumber = null,
        ?string $originalFilename = null
    ): VerificationDocument {
        if ($screening->tenant_id !== $user->id && ! $user->hasRole('admin')) {
            throw new InvalidArgumentException('Unauthorized: Cannot upload documents for another user.');
        }

        $filePath = '';
        $fileName = $originalFilename;

        if ($file instanceof UploadedFile) {
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('verification_documents', 'local');
        } else {
            // String path or mock path for testing
            $filePath = $file;
            $fileName = $fileName ?? basename($file);
        }

        $masked = $rawNumber ? VerificationDocument::maskIdentifier($rawNumber, $documentType) : null;

        $doc = VerificationDocument::create([
            'tenant_screening_id' => $screening->id,
            'user_id' => $user->id,
            'document_type' => strtolower($documentType),
            'document_number_masked' => $masked,
            'file_path' => $filePath,
            'original_filename' => $fileName,
            'ocr_status' => VerificationDocument::OCR_PENDING,
            'verification_status' => VerificationDocument::STATUS_PENDING,
        ]);

        $screening->update([
            'status' => TenantScreening::STATUS_DOCUMENTS_SUBMITTED,
        ]);

        ServiceAuditLog::record($doc, 'document_uploaded', $user, [
            'document_type' => $documentType,
            'masked_number' => $masked,
        ]);

        // Process OCR abstraction
        $this->processOcr($doc);

        return $doc->fresh();
    }

    /**
     * Execute OCR extraction and document verification via provider abstraction.
     */
    public function processOcr(VerificationDocument $document): VerificationDocument
    {
        $ocrResult = $this->documentProvider->extractDocumentData($document);
        $verifyResult = $this->documentProvider->verifyDocument($document);

        $document->update([
            'ocr_status' => $ocrResult['ocr_status'] ?? VerificationDocument::OCR_SUCCESS,
            'ocr_extracted_data' => $ocrResult,
            'document_number_masked' => $ocrResult['document_number_masked'] ?? $document->document_number_masked,
            'verification_status' => $verifyResult['status'] ?? VerificationDocument::STATUS_VERIFIED,
            'rejection_reason' => $verifyResult['reason'] ?? null,
            'verified_at' => ($verifyResult['verified'] ?? false) ? now() : null,
        ]);

        ServiceAuditLog::record($document, 'ocr_and_verification_processed', null, [
            'ocr_status' => $document->ocr_status,
            'verification_status' => $document->verification_status,
        ]);

        return $document->fresh();
    }

    /**
     * Execute overall identity and background screening checks.
     */
    public function runVerificationChecks(TenantScreening $screening): TenantScreening
    {
        $idResult = $this->identityProvider->requestVerification($screening);

        $screening->update([
            'status' => TenantScreening::STATUS_VERIFICATION_IN_PROGRESS,
            'id_verification_status' => 'IN_PROGRESS',
        ]);

        // Evaluate provider status
        $statusCheck = $this->identityProvider->checkVerificationStatus($idResult['provider_reference']);

        if ($statusCheck['verified'] ?? false) {
            $screening->update([
                'status' => TenantScreening::STATUS_VERIFIED,
                'id_verification_status' => 'VERIFIED',
                'completed_at' => now(),
                'expires_at' => now()->addMonths(6),
            ]);
        } else {
            $screening->update([
                'status' => TenantScreening::STATUS_FAILED,
                'id_verification_status' => 'FAILED',
                'reviewer_notes' => $statusCheck['error'] ?? 'Background verification checks failed.',
            ]);
        }

        ServiceAuditLog::record($screening, 'verification_checks_completed', null, [
            'status' => $screening->fresh()->status,
        ]);

        return $screening->fresh();
    }

    /**
     * Manual reviewer adjudication (Admin / Operations).
     */
    public function reviewScreening(TenantScreening $screening, User $reviewer, string $status, ?string $notes = null): TenantScreening
    {
        $screening->update([
            'status' => $status,
            'reviewer_id' => $reviewer->id,
            'reviewer_notes' => $notes,
            'completed_at' => in_array($status, [TenantScreening::STATUS_VERIFIED, TenantScreening::STATUS_FAILED]) ? now() : null,
        ]);

        ServiceAuditLog::record($screening, 'screening_reviewed', $reviewer, [
            'decision_status' => $status,
            'notes' => $notes,
        ]);

        return $screening->fresh();
    }
}
