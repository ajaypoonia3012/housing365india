<?php

namespace App\Services\Verification;

use App\Contracts\DocumentVerificationProviderInterface;
use App\Models\VerificationDocument;

class SandboxDocumentVerificationProvider implements DocumentVerificationProviderInterface
{
    /**
     * Extract structured fields from a document via simulated OCR.
     */
    public function extractDocumentData(VerificationDocument $document): array
    {
        $type = strtolower($document->document_type);

        switch ($type) {
            case 'aadhaar':
                $mockRaw = '987654321098';
                $masked = VerificationDocument::maskIdentifier($mockRaw, 'aadhaar');

                return [
                    'ocr_status' => VerificationDocument::OCR_SUCCESS,
                    'document_number_masked' => $masked,
                    'name' => 'Verified Citizen',
                    'dob' => '1992-06-15',
                    'metadata' => [
                        'doc_class' => 'Aadhaar Card',
                        'issuing_authority' => 'UIDAI',
                        'gender' => 'Male',
                    ],
                ];

            case 'pan':
                $mockRaw = 'ABCDE1234F';
                $masked = VerificationDocument::maskIdentifier($mockRaw, 'pan');

                return [
                    'ocr_status' => VerificationDocument::OCR_SUCCESS,
                    'document_number_masked' => $masked,
                    'name' => 'Verified Taxpayer',
                    'dob' => '1992-06-15',
                    'metadata' => [
                        'doc_class' => 'Income Tax PAN',
                        'pan_category' => 'Individual',
                    ],
                ];

            case 'passport':
                $mockRaw = 'Z1234567';
                $masked = VerificationDocument::maskIdentifier($mockRaw, 'passport');

                return [
                    'ocr_status' => VerificationDocument::OCR_SUCCESS,
                    'document_number_masked' => $masked,
                    'name' => 'Verified Traveler',
                    'dob' => '1992-06-15',
                    'metadata' => [
                        'doc_class' => 'Republic of India Passport',
                        'expiry' => '2032-10-01',
                    ],
                ];

            default:
                return [
                    'ocr_status' => VerificationDocument::OCR_SUCCESS,
                    'document_number_masked' => 'XXXX XXXX 9999',
                    'metadata' => ['doc_class' => ucfirst($type)],
                ];
        }
    }

    /**
     * Verify document validity and integrity with provider.
     */
    public function verifyDocument(VerificationDocument $document): array
    {
        // If document notes or filename contains 'invalid', simulate rejection
        if (str_contains(strtolower($document->original_filename ?? ''), 'invalid')) {
            return [
                'verified' => false,
                'status' => VerificationDocument::STATUS_REJECTED,
                'confidence_score' => 0.20,
                'reason' => 'Image too blurry or illegible text.',
            ];
        }

        return [
            'verified' => true,
            'status' => VerificationDocument::STATUS_VERIFIED,
            'confidence_score' => 0.96,
            'reason' => 'Document verified successfully against government registries.',
        ];
    }
}
