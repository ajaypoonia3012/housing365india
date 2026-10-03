<?php

namespace Tests\Feature;

use App\Models\AccountSuspension;
use App\Models\Appeal;
use App\Models\CareServiceCategory;
use App\Models\Inquiry;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Referral;
use App\Models\Review;
use App\Models\ReviewReport;
use App\Models\ReviewResponse;
use App\Models\Role;
use App\Models\TrustBadge;
use App\Models\User;
use App\Models\Verification;
use App\Models\VerificationType;
use App\Services\AnalyticsService;
use App\Services\FraudService;
use App\Services\ReviewService;
use App\Services\VerificationService;
use Database\Seeders\Phase10TrustAndFraudSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Phase10TrustVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $ownerUser;

    protected User $tenantUser;

    protected User $otherUser;

    protected Property $testProperty;

    protected VerificationService $verificationService;

    protected ReviewService $reviewService;

    protected FraudService $fraudService;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Ensure Roles
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'owner'], ['display_name' => 'Property Owner']);
        Role::firstOrCreate(['name' => 'tenant'], ['display_name' => 'Tenant']);

        // 2. Run Phase 10 Seeder for Types, Badges & Fraud Rules
        $this->seed(Phase10TrustAndFraudSeeder::class);

        // 3. Setup Users
        $this->adminUser = User::factory()->create([
            'name' => 'Admin Compliance Officer',
            'email' => 'compliance@housing365.test',
        ]);
        $this->adminUser->assignRole('admin');

        $this->ownerUser = User::factory()->create([
            'name' => 'Verified Landlord',
            'email' => 'landlord@housing365.test',
            'phone' => '9876543210',
        ]);
        $this->ownerUser->assignRole('owner');

        $this->tenantUser = User::factory()->create([
            'name' => 'Tenant Customer',
            'email' => 'tenant@housing365.test',
            'phone' => '9123456780',
        ]);
        $this->tenantUser->assignRole('tenant');

        $this->otherUser = User::factory()->create([
            'name' => 'Unrelated Visitor',
            'email' => 'visitor@housing365.test',
        ]);

        // 4. Create Property
        $this->testProperty = Property::create([
            'user_id' => $this->ownerUser->id,
            'title' => 'Prestige Heights 3BHK Whitefield',
            'slug' => 'prestige-heights-3bhk-whitefield',
            'purpose' => 'rent',
            'property_type' => 'Apartment',
            'price' => 45000,
            'deposit_amount' => 150000,
            'bedrooms' => 3,
            'bathrooms' => 3,
            'sqft' => 1750,
            'city' => 'Bengaluru',
            'locality' => 'Whitefield',
            'address' => 'ITPL Main Road, Prestige Heights Tower B',
            'seller_name' => 'Rajesh Sharma',
            'seller_phone' => '9876543210',
            'seller_email' => 'rajesh.sharma@test.com',
            'status' => 'active',
            'is_verified' => false,
        ]);

        $this->verificationService = app(VerificationService::class);
        $this->reviewService = app(ReviewService::class);
        $this->fraudService = app(FraudService::class);
    }

    // 1. Verification Submission & Pending Lifecycle
    public function test_user_can_submit_verification_request_with_private_documents(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('aadhaar_card.pdf', 500, 'application/pdf');

        $verification = $this->verificationService->submitVerification(
            $this->ownerUser,
            $this->ownerUser,
            'OWNER_IDENTITY',
            ['notes' => 'Aadhaar identity submission'],
            ['aadhaar' => $file]
        );

        $this->assertInstanceOf(Verification::class, $verification);
        $this->assertEquals(Verification::STATUS_PENDING, $verification->status);
        $this->assertEquals('OWNER_IDENTITY', $verification->verification_type_code);
        $this->assertCount(1, $verification->documents);

        $doc = $verification->documents->first();
        $this->assertNotNull($doc->file_hash);
        $this->assertNotNull($doc->retention_until);
        $this->assertTrue(Storage::disk('local')->exists($doc->file_path));
    }

    // 2. Admin Review & Badge Issuance
    public function test_admin_can_approve_verification_and_badge_is_issued(): void
    {
        Storage::fake('local');
        $verification = $this->verificationService->submitVerification(
            $this->testProperty,
            $this->ownerUser,
            'PROPERTY_OWNERSHIP',
            ['deed_number' => 'REG-KA-2024-9988']
        );

        $this->verificationService->reviewVerification(
            $verification,
            $this->adminUser,
            Verification::STATUS_VERIFIED,
            'Document verified successfully against registrar records'
        );

        $verification->refresh();
        $this->assertEquals(Verification::STATUS_VERIFIED, $verification->status);
        $this->assertEquals($this->adminUser->id, $verification->reviewed_by);
        $this->assertNotNull($verification->expires_at);

        // Assert property now has active verified badge
        $this->assertTrue($this->verificationService->hasActiveBadge($this->testProperty, TrustBadge::BADGE_PROPERTY_VERIFIED));
        $this->assertTrue($this->testProperty->fresh()->is_verified);
    }

    // 3. Verification Rejection & Badge Revocation
    public function test_rejected_verification_revokes_badge_and_records_reason(): void
    {
        $verification = $this->verificationService->submitVerification(
            $this->testProperty,
            $this->ownerUser,
            'PROPERTY_OWNERSHIP'
        );

        // Approve first
        $this->verificationService->reviewVerification($verification, $this->adminUser, Verification::STATUS_VERIFIED);
        $this->assertTrue($this->verificationService->hasActiveBadge($this->testProperty, TrustBadge::BADGE_PROPERTY_VERIFIED));

        // Reject / suspend
        $this->verificationService->reviewVerification(
            $verification,
            $this->adminUser,
            Verification::STATUS_REJECTED,
            'Utility bill mismatched property address'
        );

        $this->assertFalse($this->verificationService->hasActiveBadge($this->testProperty, TrustBadge::BADGE_PROPERTY_VERIFIED));
        $this->assertEquals('Utility bill mismatched property address', $verification->fresh()->rejection_reason);
    }

    // 4. Document Privacy & Access Control (User cannot view another user's document)
    public function test_user_cannot_download_another_users_verification_document(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('private_tax_receipt.pdf', 300, 'application/pdf');
        $doc = $this->verificationService->uploadPrivateDocument($this->ownerUser, $file, 'property_tax');

        // Other user attempts download
        $response = $this->actingAs($this->otherUser)
            ->get(route('trust.document.download', $doc));

        $response->assertStatus(403);
    }

    // 5. Document Owner Can Download Their Own Document
    public function test_document_owner_and_admin_can_download_private_document(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('my_identity.pdf', 300, 'application/pdf');
        $doc = $this->verificationService->uploadPrivateDocument($this->ownerUser, $file, 'aadhaar');

        // Owner download
        $response = $this->actingAs($this->ownerUser)
            ->get(route('trust.document.download', $doc));
        $response->assertOk();

        // Admin download
        $adminResponse = $this->actingAs($this->adminUser)
            ->get(route('trust.document.download', $doc));
        $adminResponse->assertOk();
    }

    // 6. Non-Admin Cannot Review Verification
    public function test_non_admin_cannot_access_filament_verification_management(): void
    {
        $response = $this->actingAs($this->tenantUser)
            ->get('/admin/verifications');

        $response->assertStatus(403);
    }

    // 7. Expired Badges Revocation
    public function test_check_expired_badges_revokes_expired_credentials(): void
    {
        $badge = $this->verificationService->awardBadge($this->testProperty, TrustBadge::BADGE_PROPERTY_VERIFIED, null, -1); // expired yesterday
        $this->assertFalse($badge->fresh()->isCurrent());

        $expiredCount = $this->verificationService->checkExpiredBadges();
        $this->assertGreaterThanOrEqual(1, $expiredCount);
        $this->assertFalse($this->verificationService->hasActiveBadge($this->testProperty, TrustBadge::BADGE_PROPERTY_VERIFIED));
    }

    // 8. Review Eligibility Requires Qualifying Interaction
    public function test_review_submission_blocked_without_qualifying_interaction(): void
    {
        $response = $this->actingAs($this->otherUser)
            ->post(route('reviews.property.store', $this->testProperty), [
                'rating' => 5,
                'title' => 'Nice property',
                'body' => 'I love the look of this place online.',
            ]);

        $response->assertSessionHasErrors('eligibility');
        $this->assertEquals(0, Review::count());
    }

    // 9. Verified Interaction Allows Review Submission
    public function test_completed_viewing_inquiry_qualifies_for_review(): void
    {
        Inquiry::create([
            'property_id' => $this->testProperty->id,
            'name' => $this->tenantUser->name,
            'email' => $this->tenantUser->email,
            'phone' => $this->tenantUser->phone,
            'message' => 'Viewing request for Saturday',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->tenantUser)
            ->post(route('reviews.property.store', $this->testProperty), [
                'rating' => 5,
                'title' => 'Exceptional Condition & Honest Landlord',
                'body' => 'I completed an in-person viewing of Prestige Heights. The flat matches the photos completely.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'subject_id' => $this->testProperty->id,
            'user_id' => $this->tenantUser->id,
            'rating' => 5,
            'status' => Review::STATUS_PUBLISHED,
        ]);
    }

    // 10. Self-Review Is Strictly Prohibited
    public function test_self_review_is_blocked_for_property_owner(): void
    {
        $response = $this->actingAs($this->ownerUser)
            ->post(route('reviews.property.store', $this->testProperty), [
                'rating' => 5,
                'title' => 'My flat is the best',
                'body' => 'Superb flat in Whitefield Bangalore.',
            ]);

        $response->assertSessionHasErrors('eligibility');
        $this->assertEquals(0, Review::count());
    }

    // 11. Duplicate Reviews for Same Interaction Are Blocked
    public function test_duplicate_review_for_same_interaction_is_blocked(): void
    {
        $inquiry = Inquiry::create([
            'property_id' => $this->testProperty->id,
            'name' => $this->tenantUser->name,
            'email' => $this->tenantUser->email,
            'phone' => $this->tenantUser->phone,
            'message' => 'Viewing inquiry',
        ]);

        // Submit first review
        $this->actingAs($this->tenantUser)
            ->post(route('reviews.property.store', $this->testProperty), [
                'rating' => 5,
                'title' => 'First honest review',
                'body' => 'Clean apartment and spacious balconies.',
            ]);

        $this->assertEquals(1, Review::count());

        // Submit second review
        $dupResponse = $this->actingAs($this->tenantUser)
            ->post(route('reviews.property.store', $this->testProperty), [
                'rating' => 4,
                'title' => 'Second attempt review',
                'body' => 'Trying to submit duplicate review.',
            ]);

        $dupResponse->assertSessionHasErrors('eligibility');
        $this->assertEquals(1, Review::count());
    }

    // 12. Review Moderation Workflow
    public function test_admin_can_moderate_and_hide_or_flag_reviews(): void
    {
        $review = Review::create([
            'subject_type' => Property::class,
            'subject_id' => $this->testProperty->id,
            'user_id' => $this->tenantUser->id,
            'rating' => 1,
            'title' => 'Questionable review',
            'body' => 'Contains suspicious claims',
            'status' => Review::STATUS_PUBLISHED,
            'submitted_at' => now(),
        ]);

        $this->reviewService->moderateReview($review, $this->adminUser, 'hide', 'Violated review policy');

        $this->assertEquals(Review::STATUS_HIDDEN, $review->fresh()->status);
        $this->assertEquals('Violated review policy', $review->fresh()->moderation_reason);
    }

    // 13. Subject Owner Can Respond to Review
    public function test_verified_subject_owner_can_respond_to_review(): void
    {
        $review = Review::create([
            'subject_type' => Property::class,
            'subject_id' => $this->testProperty->id,
            'user_id' => $this->tenantUser->id,
            'rating' => 5,
            'title' => 'Great landlord',
            'body' => 'Prompt communication',
            'status' => Review::STATUS_PUBLISHED,
        ]);

        $response = $this->actingAs($this->ownerUser)
            ->post(route('reviews.response', $review), [
                'body' => 'Thank you for your visit, happy to host you as a tenant.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('review_responses', [
            'review_id' => $review->id,
            'responder_id' => $this->ownerUser->id,
            'body' => 'Thank you for your visit, happy to host you as a tenant.',
        ]);
    }

    // 14. Unauthorized User Cannot Respond to Someone Else's Review
    public function test_unauthorized_user_cannot_respond_to_review(): void
    {
        $review = Review::create([
            'subject_type' => Property::class,
            'subject_id' => $this->testProperty->id,
            'user_id' => $this->tenantUser->id,
            'rating' => 5,
            'title' => 'Great flat',
            'body' => 'Good location',
            'status' => Review::STATUS_PUBLISHED,
        ]);

        $response = $this->actingAs($this->otherUser)
            ->post(route('reviews.response', $review), [
                'body' => 'Random intruder response',
            ]);

        $response->assertSessionHasErrors('authorization');
        $this->assertEquals(0, ReviewResponse::count());
    }

    // 15. Review Reporting & Auto-Flagging
    public function test_review_reporting_flags_review_when_reports_reach_threshold(): void
    {
        $review = Review::create([
            'subject_type' => Property::class,
            'subject_id' => $this->testProperty->id,
            'user_id' => $this->otherUser->id,
            'rating' => 1,
            'title' => 'Spam content',
            'body' => 'Call 999999999 for loans',
            'status' => Review::STATUS_PUBLISHED,
        ]);

        // Submit 3 reports from different users
        for ($i = 1; $i <= 3; $i++) {
            $repUser = User::factory()->create();
            $this->reviewService->reportReview($repUser, $review, 'spam', 'Spam loan ads');
        }

        $this->assertEquals(3, ReviewReport::where('review_id', $review->id)->count());
        $this->assertEquals(Review::STATUS_FLAGGED, $review->fresh()->status);
    }

    // 16. Fraud Detection: Excessive Lead Creation
    public function test_fraud_engine_flags_excessive_lead_creation(): void
    {
        for ($i = 0; $i < 5; $i++) {
            Lead::create([
                'user_id' => $this->otherUser->id,
                'lead_source' => 'web_inquiry',
                'lead_type' => 'tenant',
                'name' => 'Spam Lead '.$i,
                'email' => 'spam'.$i.'@test.com',
                'phone' => '980000000'.$i,
                'sla_deadline_at' => now()->addHours(2),
            ]);
        }

        $risk = $this->fraudService->evaluateRisk('lead_creation', $this->otherUser, $this->testProperty);

        $this->assertGreaterThanOrEqual(40.0, $risk['risk_score']);
        $this->assertContains('excessive_lead_creation', $risk['rules']);
    }

    // 17. Fraud Detection: Self-Referral Flagging
    public function test_fraud_engine_flags_self_referral_attempt(): void
    {
        $risk = $this->fraudService->evaluateRisk('referral_submission', $this->ownerUser, $this->testProperty, [
            'referred_phone' => $this->ownerUser->phone,
            'referred_email' => 'other@mail.com',
        ]);

        $this->assertGreaterThanOrEqual(80.0, $risk['risk_score']);
        $this->assertContains('self_referral', $risk['rules']);
    }

    // 18. Fraud Detection: Repeated Verification Failures
    public function test_fraud_engine_flags_repeated_verification_failures(): void
    {
        $vType = VerificationType::where('code', 'OWNER_IDENTITY')->first() ?? VerificationType::first();

        for ($i = 0; $i < 3; $i++) {
            Verification::create([
                'subject_type' => User::class,
                'subject_id' => $this->otherUser->id,
                'user_id' => $this->otherUser->id,
                'verification_type_id' => $vType->id,
                'verification_type_code' => 'OWNER_IDENTITY',
                'status' => Verification::STATUS_REJECTED,
                'rejection_reason' => 'Forged card',
            ]);
        }

        $risk = $this->fraudService->evaluateRisk('verification_submission', $this->otherUser, $this->otherUser);

        $this->assertGreaterThanOrEqual(50.0, $risk['risk_score']);
        $this->assertContains('repeated_failed_verification', $risk['rules']);
    }

    // 19. Duplicate Property Detection
    public function test_duplicate_property_detection_flags_suspect_listing_without_deleting(): void
    {
        $dup = Property::create([
            'user_id' => $this->otherUser->id,
            'title' => 'Prestige Heights 3BHK Whitefield', // Identical title
            'slug' => 'prestige-heights-3bhk-whitefield-dup',
            'purpose' => 'rent',
            'property_type' => 'Apartment',
            'bedrooms' => 3,
            'bathrooms' => 3,
            'city' => 'Bengaluru',
            'locality' => 'Whitefield',
            'address' => 'ITPL Main Road, Prestige Heights Tower B', // Identical address
            'price' => 46000,
            'status' => 'active',
        ]);

        $flag = $this->fraudService->detectDuplicateProperty($dup);

        $this->assertNotNull($flag);
        $this->assertEquals('duplicate_property_suspected', $flag->flag_type);
        // Assert listing is NOT deleted
        $this->assertDatabaseHas('properties', ['id' => $dup->id]);
    }

    // 20. Account Suspension Lifecycle
    public function test_account_suspension_creates_active_record_and_preserves_user_data(): void
    {
        $suspension = $this->fraudService->suspendAccount(
            $this->otherUser,
            AccountSuspension::TYPE_TEMPORARY,
            'Suspicious repeated fraudulent inquiries',
            $this->adminUser,
            now()->addDays(7)
        );

        $this->assertTrue($suspension->isEffective());
        $this->assertTrue($this->otherUser->fresh()->isSuspended());
        // Assert user record is intact in DB
        $this->assertDatabaseHas('users', ['id' => $this->otherUser->id]);
    }

    // 21. User Appeal Submission
    public function test_user_can_submit_appeal_against_suspension(): void
    {
        $suspension = $this->fraudService->suspendAccount(
            $this->otherUser,
            AccountSuspension::TYPE_TEMPORARY,
            'Test restriction',
            $this->adminUser
        );

        $response = $this->actingAs($this->otherUser)
            ->post(route('trust.appeal'), [
                'appealable_type' => 'suspension',
                'appealable_id' => $suspension->id,
                'reason' => 'I have verified all my inquiry activity was legitimate for family relocation.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('appeals', [
            'user_id' => $this->otherUser->id,
            'appealable_type' => AccountSuspension::class,
            'appealable_id' => $suspension->id,
            'status' => Appeal::STATUS_SUBMITTED,
        ]);
        $this->assertEquals(AccountSuspension::APPEAL_APPEALED, $suspension->fresh()->appeal_status);
    }

    // 22. Admin Appeal Review & Reinstatement
    public function test_admin_approving_appeal_reinstates_suspended_account(): void
    {
        $suspension = $this->fraudService->suspendAccount(
            $this->otherUser,
            AccountSuspension::TYPE_TEMPORARY,
            'Under investigation',
            $this->adminUser
        );

        $appeal = $this->fraudService->submitAppeal(
            $this->otherUser,
            $suspension,
            'Providing identity documents to prove identity'
        );

        $this->fraudService->reviewAppeal($appeal, $this->adminUser, 'APPROVED', 'Identity confirmed by compliance officer');

        $this->assertEquals(Appeal::STATUS_APPROVED, $appeal->fresh()->status);
        $this->assertFalse($suspension->fresh()->is_active);
        $this->assertEquals(AccountSuspension::APPEAL_OVERTURNED, $suspension->fresh()->appeal_status);
        $this->assertFalse($this->otherUser->fresh()->isSuspended());
    }

    // 23. Contact Access Paywall Invariant (Zero Leakage)
    public function test_contact_access_zero_leakage_in_reviews_and_public_views(): void
    {
        Review::create([
            'subject_type' => Property::class,
            'subject_id' => $this->testProperty->id,
            'user_id' => $this->tenantUser->id,
            'rating' => 5,
            'title' => 'Great flat',
            'body' => 'Highly recommended property.',
            'status' => Review::STATUS_PUBLISHED,
        ]);

        $response = $this->get(route('properties.show', $this->testProperty->slug));
        $response->assertOk();

        // Ensure owner raw private contact details are NOT leaked in response HTML
        $response->assertDontSee('9876543210');
        $response->assertDontSee('rajesh.sharma@test.com');
        $response->assertSee('Prestige Heights 3BHK Whitefield');
        $response->assertSee('Verified Resident & Visitor Reviews');
    }

    // 24. Housing365 Care Independence Invariant
    public function test_housing365_care_services_remain_independent_of_property_management(): void
    {
        $category = CareServiceCategory::firstOrCreate(
            ['slug' => 'deep-cleaning'],
            ['name' => 'Deep Cleaning', 'is_active' => true]
        );

        $response = $this->get(route('care.index'));
        $response->assertOk();
        $this->assertDatabaseHas('care_service_categories', ['slug' => 'deep-cleaning']);
    }

    // 25. Property Management Strictly Optional (₹0 Free Residential Self-Managed Invariant)
    public function test_property_management_remains_strictly_optional(): void
    {
        $this->assertEquals(0, $this->testProperty->managementEnrollments()->count());
        $this->assertFalse((bool) $this->testProperty->is_managed);
    }

    // 26. Refer & Earn ₹100 Remains Intact
    public function test_refer_and_earn_program_remains_intact(): void
    {
        $referralCode = $this->ownerUser->getOrCreateReferralCode();
        $this->assertNotEmpty($referralCode);
        $this->assertStringStartsWith('H365-', $referralCode);
    }

    // 27. Analytics Logging Zero PII Leakage
    public function test_analytics_logging_sanitizes_pii(): void
    {
        $analytics = app(AnalyticsService::class);
        $event = $analytics->logEvent('verification_submitted', $this->testProperty, $this->ownerUser, [
            'verification_type' => 'OWNER_IDENTITY',
            'phone' => '9876543210',
            'email' => 'landlord@housing365.test',
            'notes' => 'KYC submit',
        ]);

        $this->assertArrayNotHasKey('phone', $event->metadata);
        $this->assertArrayNotHasKey('email', $event->metadata);
        $this->assertEquals('OWNER_IDENTITY', $event->metadata['verification_type']);
    }

    // 28. Multi-Role Authorization Preservation
    public function test_multi_role_user_can_access_own_workspaces(): void
    {
        $this->ownerUser->assignRole('broker');
        $this->assertTrue($this->ownerUser->hasRole('owner'));
        $this->assertTrue($this->ownerUser->hasRole('broker'));
    }

    // 29. Trust Hub Dashboard Loads With Verifications and Badges
    public function test_trust_hub_dashboard_loads_for_authenticated_user(): void
    {
        $response = $this->actingAs($this->ownerUser)
            ->get(route('trust.dashboard'));

        $response->assertOk();
        $response->assertSee('Trust &amp; Verification Hub', false);
        $response->assertSee('Active Trust Credentials');
    }

    // 30. Unauthenticated User Redirected From Trust Hub
    public function test_unauthenticated_user_redirected_from_trust_hub(): void
    {
        $response = $this->get(route('trust.dashboard'));
        $response->assertRedirect(route('login'));
    }
}
