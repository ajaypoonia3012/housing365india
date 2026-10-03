<?php

namespace Database\Seeders;

use App\Models\FraudRule;
use App\Models\TrustBadge;
use App\Models\VerificationType;
use Illuminate\Database\Seeder;

class Phase10TrustAndFraudSeeder extends Seeder
{
    /**
     * Seed Phase 10 verification types, badges, and fraud rules.
     * STRICT INVARIANT: ZERO fake customer reviews or fabricated ratings are seeded.
     */
    public function run(): void
    {
        // 1. Configurable Verification Types
        $verificationTypes = [
            [
                'code' => 'OWNER_IDENTITY',
                'name' => 'Owner Identity Verification',
                'subject_type' => 'USER',
                'description' => 'Aadhaar, Passport, or government identity check for property owners.',
                'required_documents' => ['government_id'],
                'validity_days' => 365,
            ],
            [
                'code' => 'OWNER_CONTACT',
                'name' => 'Owner Contact Verification',
                'subject_type' => 'USER',
                'description' => 'Direct mobile OTP and email verification check.',
                'required_documents' => [],
                'validity_days' => 365,
            ],
            [
                'code' => 'PROPERTY_OWNERSHIP',
                'name' => 'Property Ownership Document Verification',
                'subject_type' => 'PROPERTY',
                'description' => 'Title deed, sale deed, or property tax receipt verification.',
                'required_documents' => ['title_deed', 'property_tax_receipt'],
                'validity_days' => 730,
            ],
            [
                'code' => 'PROPERTY_DOCUMENT',
                'name' => 'Property Utility Document Check',
                'subject_type' => 'PROPERTY',
                'description' => 'Electricity or water bill verification for property location address.',
                'required_documents' => ['utility_bill'],
                'validity_days' => 365,
            ],
            [
                'code' => 'BROKER_IDENTITY',
                'name' => 'Broker Identity & Profile Verification',
                'subject_type' => 'BROKER',
                'description' => 'Identity and professional profile verification for real estate advisors.',
                'required_documents' => ['government_id'],
                'validity_days' => 365,
            ],
            [
                'code' => 'BROKER_LICENSE',
                'name' => 'Broker RERA Registration Check',
                'subject_type' => 'BROKER',
                'description' => 'State RERA agent registration certification verification.',
                'required_documents' => ['rera_certificate'],
                'validity_days' => 365,
            ],
            [
                'code' => 'BUILDER_IDENTITY',
                'name' => 'Builder Corporate Identity Verification',
                'subject_type' => 'BUILDER',
                'description' => 'Company incorporation and authorized signatory verification.',
                'required_documents' => ['incorporation_certificate'],
                'validity_days' => 730,
            ],
            [
                'code' => 'BUILDER_RERA',
                'name' => 'Builder Project RERA Verification',
                'subject_type' => 'BUILDER',
                'description' => 'Project RERA registration number and official approval verification.',
                'required_documents' => ['rera_project_certificate'],
                'validity_days' => 1095,
            ],
            [
                'code' => 'PG_OPERATOR_IDENTITY',
                'name' => 'PG / Co-living Operator Verification',
                'subject_type' => 'PG_OPERATOR',
                'description' => 'Operator identity and municipal business compliance verification.',
                'required_documents' => ['government_id', 'commercial_establishment_license'],
                'validity_days' => 365,
            ],
            [
                'code' => 'PG_PROPERTY',
                'name' => 'PG Property Space Verification',
                'subject_type' => 'PG_PROPERTY',
                'description' => 'Physical facility, occupancy rules, and safety verification.',
                'required_documents' => ['lease_agreement', 'safety_compliance'],
                'validity_days' => 365,
            ],
            [
                'code' => 'COWORKING_OPERATOR',
                'name' => 'Coworking Space Operator Verification',
                'subject_type' => 'COWORKING_OPERATOR',
                'description' => 'Workspace operator lease and corporate presence verification.',
                'required_documents' => ['lease_agreement', 'gstin_certificate'],
                'validity_days' => 730,
            ],
            [
                'code' => 'VENDOR_IDENTITY',
                'name' => 'Care Service Vendor Identity Check',
                'subject_type' => 'VENDOR',
                'description' => 'KYC and background verification for Housing365 Care service technicians.',
                'required_documents' => ['aadhaar', 'police_clearance'],
                'validity_days' => 365,
            ],
            [
                'code' => 'VENDOR_BUSINESS',
                'name' => 'Care Vendor Business Verification',
                'subject_type' => 'VENDOR',
                'description' => 'Trade license and enterprise verification for maintenance providers.',
                'required_documents' => ['trade_license'],
                'validity_days' => 365,
            ],
            [
                'code' => 'BANK_ACCOUNT',
                'name' => 'Bank Account Verification',
                'subject_type' => 'ALL',
                'description' => 'Penny drop / cancelled cheque verification for rent or vendor payouts.',
                'required_documents' => ['cancelled_cheque'],
                'validity_days' => 1095,
            ],
            [
                'code' => 'UPI',
                'name' => 'UPI VPA Verification',
                'subject_type' => 'ALL',
                'description' => 'Name matching on UPI Virtual Payment Address.',
                'required_documents' => [],
                'validity_days' => 1095,
            ],
        ];

        foreach ($verificationTypes as $vt) {
            VerificationType::updateOrCreate(['code' => $vt['code']], $vt);
        }

        // 2. Trust Badges Catalogue
        $trustBadges = [
            [
                'slug' => TrustBadge::BADGE_IDENTITY_VERIFIED,
                'name' => 'Identity Verified',
                'description' => 'Government identity verification completed via official documentation.',
                'criteria' => 'Successful KYC document verification.',
            ],
            [
                'slug' => TrustBadge::BADGE_PROPERTY_VERIFIED,
                'name' => 'Property Verified',
                'description' => 'Property ownership, location address, or utility documentation confirmed.',
                'criteria' => 'Ownership document or electricity bill validated.',
            ],
            [
                'slug' => TrustBadge::BADGE_BUSINESS_VERIFIED,
                'name' => 'Business Verified',
                'description' => 'Corporate entity or trade license verified.',
                'criteria' => 'Valid business registration credentials.',
            ],
            [
                'slug' => TrustBadge::BADGE_BROKER_VERIFIED,
                'name' => 'Verified Broker',
                'description' => 'Real estate broker identity and credential validation complete.',
                'criteria' => 'Government ID and RERA agent registration confirmation.',
            ],
            [
                'slug' => TrustBadge::BADGE_BUILDER_VERIFIED,
                'name' => 'Verified Builder',
                'description' => 'Corporate builder entity authorized and vetted.',
                'criteria' => 'Incorporation and active project records validated.',
            ],
            [
                'slug' => TrustBadge::BADGE_RERA_SUBMITTED,
                'name' => 'RERA Details Submitted',
                'description' => 'Builder project RERA registration number submitted for review.',
                'criteria' => 'RERA number entered by builder.',
            ],
            [
                'slug' => TrustBadge::BADGE_RERA_VERIFIED,
                'name' => 'RERA Verified',
                'description' => 'RERA registration confirmed against state regulatory authority records.',
                'criteria' => 'Confirmed matching RERA project certification.',
            ],
            [
                'slug' => TrustBadge::BADGE_PG_VERIFIED,
                'name' => 'Verified PG',
                'description' => 'Hostel/PG premises and operator credential check complete.',
                'criteria' => 'Operator KYC and space verification confirmed.',
            ],
            [
                'slug' => TrustBadge::BADGE_VENDOR_VERIFIED,
                'name' => 'Verified Vendor',
                'description' => 'Housing365 Care service technician background check complete.',
                'criteria' => 'Technician KYC, police clearance, and skill assessment.',
            ],
            [
                'slug' => TrustBadge::BADGE_HOUSING365_CARE_PROVIDER,
                'name' => 'Housing365 Care Partner',
                'description' => 'Official authorized maintenance provider on the Housing365 network.',
                'criteria' => 'Passed Housing365 quality assurance protocols.',
            ],
        ];

        foreach ($trustBadges as $tb) {
            TrustBadge::updateOrCreate(['slug' => $tb['slug']], $tb);
        }

        // 3. Fraud and Marketplace Risk Rules
        $fraudRules = [
            [
                'code' => 'excessive_lead_creation',
                'name' => 'Excessive Lead Submissions',
                'rule_type' => 'rate_abuse',
                'threshold' => 5,
                'action' => FraudRule::ACTION_SEND_TO_REVIEW,
                'description' => 'Flags accounts creating more than 5 inquiries or leads within 1 hour.',
            ],
            [
                'code' => 'self_referral',
                'name' => 'Self-Referral Attempt',
                'rule_type' => 'referral_fraud',
                'threshold' => 1,
                'action' => FraudRule::ACTION_REQUIRE_VERIFICATION,
                'description' => 'Flags referral submissions containing the referring users own phone or email.',
            ],
            [
                'code' => 'repeated_failed_verification',
                'name' => 'Repeated Verification Failures',
                'rule_type' => 'verification_abuse',
                'threshold' => 3,
                'action' => FraudRule::ACTION_SEND_TO_REVIEW,
                'description' => 'Flags accounts that have 3 or more rejected verification requests.',
            ],
            [
                'code' => 'unusual_payment',
                'name' => 'Payment Signature Anomalies',
                'rule_type' => 'payment_risk',
                'threshold' => 2,
                'action' => FraudRule::ACTION_MONITOR,
                'description' => 'Monitors repeated signature mismatch errors during monetization checkouts.',
            ],
            [
                'code' => 'duplicate_property_listing',
                'name' => 'Suspected Duplicate Listing',
                'rule_type' => 'marketplace_quality',
                'threshold' => 1,
                'action' => FraudRule::ACTION_MONITOR,
                'description' => 'Flags listings with identical or highly similar address and specs for human review.',
            ],
        ];

        foreach ($fraudRules as $fr) {
            FraudRule::updateOrCreate(['code' => $fr['code']], $fr);
        }
    }
}
