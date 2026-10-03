<?php

namespace App\Contracts;

use App\Models\VerificationDocument;

interface DocumentVerificationProviderInterface
{
    /**
     * Extract structured fields from a document via OCR abstraction.
     *
     * @return array{ocr_status: string, document_number_masked?: string, name?: string, dob?: string, metadata?: array}
     */
    public function extractDocumentData(VerificationDocument $document): array;

    /**
     * Verify document validity and integrity with provider.
     *
     * @return array{verified: bool, status: string, confidence_score?: float, reason?: string}
     */
    public function verifyDocument(VerificationDocument $document): array;
}
