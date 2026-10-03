<?php

namespace Tests\Feature;

use App\Models\CityLocation;
use App\Models\ManagementEnrollment;
use App\Models\Property;
use App\Models\Referral;
use App\Models\ReferralAuditLog;
use App\Models\ReferralPayout;
use App\Models\ReferralReward;
use App\Models\Role;
use App\Models\ServiceRequest;
use App\Models\Setting;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Phase7ReferralProgramTest extends TestCase
{
    use RefreshDatabase;

    protected ReferralService $referralService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->referralService = app(ReferralService::class);

        // Ensure basic roles and city exist
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'owner'], ['display_name' => 'Property Owner']);
        Role::firstOrCreate(['name' => 'tenant'], ['display_name' => 'Tenant']);
        Role::firstOrCreate(['name' => 'broker'], ['display_name' => 'Real Estate Broker']);
        Role::firstOrCreate(['name' => 'vendor'], ['display_name' => 'Service Vendor']);

        CityLocation::firstOrCreate(['name' => 'Bengaluru'], [
            'slug' => 'bengaluru',
            'province_or_state' => 'Karnataka',
            'country' => 'India',
        ]);
    }

    protected function createUserWithRole(string $roleName, array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'phone' => '98'.rand(10000000, 99999999),
        ], $attributes));

        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $user->roles()->syncWithoutDetaching([$role->id => ['is_active' => true]]);
        }

        return $user;
    }

    // 1. User gets referral code
    public function test_01_user_gets_unique_referral_code(): void
    {
        $user = $this->createUserWithRole('tenant');
        $code = $user->getOrCreateReferralCode();

        $this->assertNotEmpty($code);
        $this->assertStringStartsWith('H365-', $code);
        $this->assertEquals($code, $user->fresh()->referral_code);

        // Subsequent call returns identical code
        $this->assertEquals($code, $user->getOrCreateReferralCode());
    }

    // 2. Referral link attribution works
    public function test_02_referral_link_attribution_works(): void
    {
        $user = $this->createUserWithRole('owner');
        $code = $user->getOrCreateReferralCode();

        // Short link redirects to /refer-and-earn?ref=CODE
        $response = $this->get("/r/{$code}");
        $response->assertRedirect(route('refer-and-earn', ['ref' => $code]));

        // Landing page sets session attribution
        $landingResponse = $this->get(route('refer-and-earn', ['ref' => $code]));
        $landingResponse->assertStatus(200);
        $landingResponse->assertSessionHas('referral_code', $code);
    }

    // 3. Referral can be submitted (Method A)
    public function test_03_referral_can_be_submitted_via_method_a(): void
    {
        $user = $this->createUserWithRole('tenant');
        $this->actingAs($user);

        $response = $this->post(route('dashboard.referrals.store'), [
            'submission_method' => 'refer_owner',
            'owner_name' => 'Suresh Reddy',
            'owner_phone' => '9845012345',
            'owner_email' => 'suresh.reddy@example.com',
            'city' => 'Bengaluru',
            'locality' => 'Indiranagar',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
            'consent_confirmed' => '1',
        ]);

        $referral = Referral::where('normalized_owner_phone', '9845012345')->first();
        $this->assertNotNull($referral);
        $this->assertEquals('Suresh Reddy', $referral->owner_name);
        $this->assertEquals(Referral::STATUS_SUBMITTED, $referral->status);
        $response->assertRedirect(route('dashboard.referrals.show', $referral->id));
    }

    // 4. Validation works
    public function test_04_referral_submission_validation(): void
    {
        $user = $this->createUserWithRole('tenant');
        $this->actingAs($user);

        // Missing required consent and owner phone
        $response = $this->post(route('dashboard.referrals.store'), [
            'submission_method' => 'refer_owner',
            'owner_name' => 'Incomplete Name',
            'city' => 'Bengaluru',
            'locality' => 'Indiranagar',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $response->assertSessionHasErrors(['owner_phone', 'consent_confirmed']);
    }

    // 5. Phone normalization works
    public function test_05_phone_normalization_cleans_inputs(): void
    {
        $this->assertEquals('9845012345', $this->referralService->normalizePhone('+91 98450-12345'));
        $this->assertEquals('9845012345', $this->referralService->normalizePhone('09845012345'));
        $this->assertEquals('9845012345', $this->referralService->normalizePhone('919845012345'));
    }

    // 6. Duplicate referral blocked
    public function test_06_duplicate_referral_blocked(): void
    {
        $userA = $this->createUserWithRole('tenant');
        $userB = $this->createUserWithRole('owner');

        $this->referralService->createReferral($userA, [
            'owner_name' => 'Owner First',
            'owner_phone' => '9845099887',
            'city' => 'Bengaluru',
            'locality' => 'Whitefield',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('This owner contact has already been referred to Housing365 by another user.');

        // User B attempts to refer same contact
        $this->referralService->createReferral($userB, [
            'owner_name' => 'Owner Duplicate',
            'owner_phone' => '+91 98450 99887',
            'city' => 'Bengaluru',
            'locality' => 'Whitefield',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);
    }

    // 7. Existing property detected
    public function test_07_existing_property_detected_and_rejected(): void
    {
        $existingOwner = $this->createUserWithRole('owner', ['phone' => '9876543210']);
        Property::create([
            'user_id' => $existingOwner->id,
            'title' => 'Existing Prestige Apartment',
            'slug' => 'existing-prestige-apt',
            'purpose' => 'rent',
            'property_type' => 'Flat / Apartment',
            'city' => 'Bengaluru',
            'locality' => 'Whitefield',
            'price' => 30000,
            'sqft' => 1200,
            'seller_name' => 'Existing Owner',
            'seller_phone' => '9876543210',
            'status' => 'published',
        ]);

        $referrer = $this->createUserWithRole('tenant');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Property already exists on Housing365.');

        $this->referralService->createReferral($referrer, [
            'owner_name' => 'Existing Owner',
            'owner_phone' => '9876543210',
            'city' => 'Bengaluru',
            'locality' => 'Whitefield',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);
    }

    // 8. Broker contact flagged
    public function test_08_broker_contact_flagged_for_review(): void
    {
        $broker = $this->createUserWithRole('broker', ['phone' => '9111222333']);
        $referrer = $this->createUserWithRole('tenant');

        $referral = $this->referralService->createReferral($referrer, [
            'owner_name' => 'Agent Verma',
            'owner_phone' => '9111222333',
            'city' => 'Bengaluru',
            'locality' => 'Koramangala',
            'property_type' => 'Commercial Office',
            'listing_intent' => 'commercial',
        ]);

        $this->assertTrue($referral->fraud_flag);
        $this->assertContains('BROKER_CONTACT_DETECTED', $referral->fraud_reasons);
    }

    // 9. Self-referral blocked
    public function test_09_self_referral_blocked(): void
    {
        $user = $this->createUserWithRole('tenant', [
            'phone' => '9988776655',
            'email' => 'self@example.com',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Self-referral is not permitted.');

        $this->referralService->createReferral($user, [
            'owner_name' => 'Myself',
            'owner_phone' => '9988776655',
            'owner_email' => 'self@example.com',
            'city' => 'Bengaluru',
            'locality' => 'HSR Layout',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);
    }

    // 10. Referral status transitions work
    public function test_10_referral_status_transitions_work(): void
    {
        $user = $this->createUserWithRole('tenant');
        $admin = $this->createUserWithRole('admin');

        $referral = $this->referralService->createReferral($user, [
            'owner_name' => 'Manish Sharma',
            'owner_phone' => '9845112233',
            'city' => 'Bengaluru',
            'locality' => 'Bellandur',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $this->assertEquals(Referral::STATUS_SUBMITTED, $referral->status);

        // Transition: SUBMITTED -> UNDER_REVIEW
        $this->referralService->updateReferralStatus($referral, Referral::STATUS_UNDER_REVIEW, $admin);
        $this->assertEquals(Referral::STATUS_UNDER_REVIEW, $referral->fresh()->status);

        // Transition: UNDER_REVIEW -> CONTACTED
        $this->referralService->updateReferralStatus($referral, Referral::STATUS_CONTACTED, $admin);
        $this->assertEquals(Referral::STATUS_CONTACTED, $referral->fresh()->status);

        // Transition: CONTACTED -> QUALIFIED
        $this->referralService->updateReferralStatus($referral, Referral::STATUS_QUALIFIED, $admin);
        $this->assertEquals(Referral::STATUS_QUALIFIED, $referral->fresh()->status);
        $this->assertNotNull($referral->fresh()->qualified_at);
    }

    // 11. Unauthorized status changes fail
    public function test_11_invalid_status_transition_is_blocked(): void
    {
        $user = $this->createUserWithRole('tenant');

        $referral = $this->referralService->createReferral($user, [
            'owner_name' => 'Gopal Krishna',
            'owner_phone' => '9845223344',
            'city' => 'Bengaluru',
            'locality' => 'Electronic City',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        // Non-admin attempting invalid jump SUBMITTED -> PAID directly
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid referral status transition');

        $this->referralService->updateReferralStatus($referral, Referral::STATUS_PAID, $user);
    }

    // 12. Property can be linked to referral and publication triggers eligibility
    public function test_12_property_publication_matches_and_qualifies_referral(): void
    {
        $user = $this->createUserWithRole('tenant');
        $referral = $this->referralService->createReferral($user, [
            'owner_name' => 'Sunil Joshi',
            'owner_phone' => '9845334455',
            'city' => 'Bengaluru',
            'locality' => 'Marathahalli',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        // Owner subsequently creates property on Housing365
        $owner = $this->createUserWithRole('owner', ['phone' => '9845334455']);
        $property = Property::create([
            'user_id' => $owner->id,
            'title' => 'Sunil Joshi 2BHK Apartment',
            'slug' => 'sunil-joshi-2bhk',
            'purpose' => 'rent',
            'property_type' => 'Flat / Apartment',
            'city' => 'Bengaluru',
            'locality' => 'Marathahalli',
            'price' => 22000,
            'sqft' => 1050,
            'seller_name' => 'Sunil Joshi',
            'seller_phone' => '9845334455',
            'status' => 'published',
        ]);

        $matched = $this->referralService->matchAndQualifyListing($property);
        $this->assertNotNull($matched);
        $this->assertEquals($property->id, $matched->qualified_property_id);
        $this->assertEquals(Referral::STATUS_REWARD_PENDING, $matched->status);
    }

    // 13. Reward created exactly once with amount = ₹100
    public function test_13_reward_amount_is_100_and_unique(): void
    {
        $user = $this->createUserWithRole('tenant');
        $referral = $this->referralService->createReferral($user, [
            'owner_name' => 'Kavita Nair',
            'owner_phone' => '9845445566',
            'city' => 'Bengaluru',
            'locality' => 'Hebbal',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $reward1 = $this->referralService->createReward($referral);
        $this->assertEquals(100.00, $reward1->amount);
        $this->assertEquals(ReferralReward::STATUS_PENDING, $reward1->status);

        // Attempting to create duplicate reward returns existing instance
        $reward2 = $this->referralService->createReward($referral);
        $this->assertEquals($reward1->id, $reward2->id);
        $this->assertEquals(1, ReferralReward::where('referral_id', $referral->id)->count());
    }

    // 14. Reward approval works
    public function test_14_admin_can_approve_reward(): void
    {
        $user = $this->createUserWithRole('tenant');
        $admin = $this->createUserWithRole('admin');

        $referral = $this->referralService->createReferral($user, [
            'owner_name' => 'Anil Gupta',
            'owner_phone' => '9845556677',
            'city' => 'Bengaluru',
            'locality' => 'Sarjapur Road',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $reward = $this->referralService->createReward($referral);
        $this->referralService->approveReward($reward, $admin);

        $this->assertEquals(ReferralReward::STATUS_APPROVED, $reward->fresh()->status);
        $this->assertEquals($admin->id, $reward->fresh()->approved_by);
        $this->assertEquals(Referral::STATUS_REWARD_APPROVED, $referral->fresh()->status);
    }

    // 15. Payout created and cannot exceed approved rewards
    public function test_15_payout_requested_for_approved_rewards(): void
    {
        $user = $this->createUserWithRole('tenant', ['upi_id' => 'refertest@okhdfcbank']);
        $admin = $this->createUserWithRole('admin');

        $ref1 = $this->referralService->createReferral($user, [
            'owner_name' => 'Owner One',
            'owner_phone' => '9845667788',
            'city' => 'Bengaluru',
            'locality' => 'BTM Layout',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $reward1 = $this->referralService->createReward($ref1);
        $this->referralService->approveReward($reward1, $admin);

        $payout = $this->referralService->requestPayout($user);
        $this->assertNotNull($payout);
        $this->assertEquals(100.00, $payout->amount);
        $this->assertEquals(ReferralPayout::STATUS_PENDING, $payout->status);
        $this->assertEquals($payout->id, $reward1->fresh()->payout_id);
    }

    // 16. Paid payout recorded correctly
    public function test_16_admin_processes_payout_with_utr(): void
    {
        $user = $this->createUserWithRole('tenant', ['upi_id' => 'payoutuser@ybl']);
        $admin = $this->createUserWithRole('admin');

        $ref = $this->referralService->createReferral($user, [
            'owner_name' => 'Owner Payout',
            'owner_phone' => '9845778899',
            'city' => 'Bengaluru',
            'locality' => 'Jayanagar',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $reward = $this->referralService->createReward($ref);
        $this->referralService->approveReward($reward, $admin);
        $payout = $this->referralService->requestPayout($user);

        // Admin settles payout with bank reference
        $settled = $this->referralService->processPayout($payout, $admin, [
            'transaction_reference' => 'UTR123456789012',
            'admin_notes' => 'Disbursed via HDFC Corporate UPI',
        ]);

        $this->assertEquals(ReferralPayout::STATUS_PAID, $settled->status);
        $this->assertEquals('UTR123456789012', $settled->transaction_reference);
        $this->assertEquals(ReferralReward::STATUS_PAID, $reward->fresh()->status);
        $this->assertEquals(Referral::STATUS_PAID, $ref->fresh()->status);
    }

    // 17. Failed payout resets rewards
    public function test_17_failed_payout_resets_reward_payout_id(): void
    {
        $user = $this->createUserWithRole('tenant', ['upi_id' => 'invalid@upi']);
        $admin = $this->createUserWithRole('admin');

        $ref = $this->referralService->createReferral($user, [
            'owner_name' => 'Owner Failed',
            'owner_phone' => '9845889900',
            'city' => 'Bengaluru',
            'locality' => 'JP Nagar',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $reward = $this->referralService->createReward($ref);
        $this->referralService->approveReward($reward, $admin);
        $payout = $this->referralService->requestPayout($user);

        $payout->update(['status' => ReferralPayout::STATUS_FAILED, 'failure_reason' => 'UPI ID invalid']);
        $this->referralService->processPayout($payout, $admin);

        // Reward payout_id unlinked so user can re-request
        $this->assertNull($reward->fresh()->payout_id);
    }

    // 18. UPI data protected and masked
    public function test_18_upi_data_masked_and_encrypted(): void
    {
        $user = $this->createUserWithRole('tenant');
        $this->actingAs($user);

        $response = $this->post(route('dashboard.payout-method'), [
            'upi_id' => 'sureshkumar@okhdfcbank',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('su***r@okhdfcbank', $user->fresh()->masked_upi);
        $this->assertNotNull($user->fresh()->upi_id_encrypted);
        $this->assertEquals('sureshkumar@okhdfcbank', decrypt($user->fresh()->upi_id_encrypted));
    }

    // 19. Referral contact data not publicly exposed
    public function test_19_referral_contact_data_not_publicly_exposed(): void
    {
        $user = $this->createUserWithRole('tenant');
        $referral = $this->referralService->createReferral($user, [
            'owner_name' => 'Private Landlord',
            'owner_phone' => '9900112233',
            'owner_email' => 'privatelandlord@secret.com',
            'city' => 'Bengaluru',
            'locality' => 'Malleshwaram',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        // Unauthenticated guest request to /refer-and-earn contains 0 private owner contact
        $response = $this->get(route('refer-and-earn'));
        $response->assertDontSee('9900112233');
        $response->assertDontSee('privatelandlord@secret.com');
        $response->assertDontSee('Private Landlord');
    }

    // 20. Contact Access Paywall is not bypassed
    public function test_20_referral_does_not_bypass_contact_access(): void
    {
        $owner = $this->createUserWithRole('owner', [
            'phone' => '9888777666',
            'email' => 'propertyowner@test.com',
        ]);

        $property = Property::create([
            'user_id' => $owner->id,
            'title' => 'Secret Villa Bangalore',
            'slug' => 'secret-villa-bangalore',
            'purpose' => 'rent',
            'property_type' => 'House / Villa',
            'city' => 'Bengaluru',
            'locality' => 'Sadashivanagar',
            'price' => 75000,
            'sqft' => 2500,
            'seller_name' => 'Secret Owner',
            'seller_phone' => '9888777666',
            'seller_email' => 'propertyowner@test.com',
            'status' => 'published',
        ]);

        // Unauthenticated visitor views property: Phone/email are protected
        $response = $this->get(route('properties.show', $property->slug));
        $response->assertDontSee('9888777666');
        $response->assertDontSee('propertyowner@test.com');
    }

    // 21. Property Management remains strictly optional
    public function test_21_referral_does_not_create_property_management_enrollment(): void
    {
        $user = $this->createUserWithRole('tenant');
        $referral = $this->referralService->createReferral($user, [
            'owner_name' => 'Independent Owner',
            'owner_phone' => '9845990011',
            'city' => 'Bengaluru',
            'locality' => 'Kalyan Nagar',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $owner = $this->createUserWithRole('owner', ['phone' => '9845990011']);
        $property = Property::create([
            'user_id' => $owner->id,
            'title' => 'Independent Flat',
            'slug' => 'independent-flat',
            'purpose' => 'rent',
            'property_type' => 'Flat / Apartment',
            'city' => 'Bengaluru',
            'locality' => 'Kalyan Nagar',
            'price' => 18000,
            'sqft' => 900,
            'seller_phone' => '9845990011',
            'status' => 'published',
            'is_managed' => false,
        ]);

        $this->referralService->matchAndQualifyListing($property);

        // Path A: is_managed remains false and NO ManagementEnrollment is created
        $this->assertFalse($property->fresh()->is_managed);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    // 22. Housing365 Care is not automatically activated
    public function test_22_referral_does_not_create_care_service_request(): void
    {
        $user = $this->createUserWithRole('tenant');
        $referral = $this->referralService->createReferral($user, [
            'owner_name' => 'Care Free Owner',
            'owner_phone' => '9845119922',
            'city' => 'Bengaluru',
            'locality' => 'Yelahanka',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $this->assertEquals(0, ServiceRequest::count());
    }

    // 23. Multi-role users can refer
    public function test_23_multi_role_users_can_submit_referrals(): void
    {
        $roles = ['tenant', 'owner', 'broker', 'vendor'];

        foreach ($roles as $r) {
            $user = $this->createUserWithRole($r);
            $this->actingAs($user);

            $response = $this->post(route('dashboard.referrals.store'), [
                'submission_method' => 'refer_owner',
                'owner_name' => "Owner referred by {$r}",
                'owner_phone' => '9845'.rand(100000, 999999),
                'city' => 'Bengaluru',
                'locality' => 'Rajajinagar',
                'property_type' => 'Flat / Apartment',
                'listing_intent' => 'rent',
                'consent_confirmed' => '1',
            ]);

            $response->assertSessionHasNoErrors();
        }
    }

    // 24. Referral authorization works
    public function test_24_user_cannot_view_another_users_referral(): void
    {
        $userA = $this->createUserWithRole('tenant');
        $userB = $this->createUserWithRole('owner');

        $referralA = $this->referralService->createReferral($userA, [
            'owner_name' => 'Owner Alpha',
            'owner_phone' => '9845229933',
            'city' => 'Bengaluru',
            'locality' => 'Vijayanagar',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $this->actingAs($userB);
        $response = $this->get(route('dashboard.referrals.show', $referralA->id));
        $response->assertStatus(403);
    }

    // 25. Admin actions are protected
    public function test_25_admin_actions_are_protected_from_regular_users(): void
    {
        $regularUser = $this->createUserWithRole('tenant');
        $referral = $this->referralService->createReferral($regularUser, [
            'owner_name' => 'Owner Protected',
            'owner_phone' => '9845339944',
            'city' => 'Bengaluru',
            'locality' => 'Frazer Town',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $reward = $this->referralService->createReward($referral);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only administrators can approve referral rewards.');

        $this->referralService->approveReward($reward, $regularUser);
    }

    // 26. Audit history created
    public function test_26_audit_history_records_events(): void
    {
        $user = $this->createUserWithRole('tenant');
        $referral = $this->referralService->createReferral($user, [
            'owner_name' => 'Owner Audited',
            'owner_phone' => '9845449955',
            'city' => 'Bengaluru',
            'locality' => 'Cooke Town',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $logs = ReferralAuditLog::where('referral_id', $referral->id)->get();
        $this->assertNotEmpty($logs);
        $this->assertEquals('referral_submitted', $logs->first()->event);
    }

    // 27. Malicious upload blocked
    public function test_27_malicious_upload_blocked(): void
    {
        $user = $this->createUserWithRole('tenant');
        $this->actingAs($user);

        // Upload fake executable script masquerading as photo
        $fakeFile = UploadedFile::fake()->create('malware.exe', 100, 'application/x-dosexec');

        $response = $this->post(route('dashboard.referrals.store'), [
            'submission_method' => 'property_board_photo',
            'owner_name' => 'Owner Board',
            'owner_phone' => '9845559966',
            'city' => 'Bengaluru',
            'locality' => 'MG Road',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
            'photo' => $fakeFile,
            'consent_confirmed' => '1',
        ]);

        $response->assertSessionHasErrors('photo');
    }

    // 28. Valid photo upload stored privately and downloadable by authorized user
    public function test_28_valid_board_photo_stored_privately(): void
    {
        Storage::fake('local');

        $user = $this->createUserWithRole('tenant');
        $this->actingAs($user);

        $validPhoto = UploadedFile::fake()->image('to_let_board.jpg', 600, 400);

        $response = $this->post(route('dashboard.referrals.store'), [
            'submission_method' => 'property_board_photo',
            'owner_name' => 'Owner Board Valid',
            'owner_phone' => '9845669977',
            'city' => 'Bengaluru',
            'locality' => 'Lavelle Road',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
            'photo' => $validPhoto,
            'consent_confirmed' => '1',
        ]);

        $referral = Referral::where('normalized_owner_phone', '9845669977')->first();
        $this->assertNotNull($referral);
        $this->assertNotNull($referral->photo_path);

        // Downloadable by owner
        $downloadResponse = $this->get(route('referrals.photo.download', $referral->id));
        $downloadResponse->assertStatus(200);

        // Unauthorized user cannot download
        $otherUser = $this->createUserWithRole('owner');
        $this->actingAs($otherUser);
        $unauthorizedResponse = $this->get(route('referrals.photo.download', $referral->id));
        $unauthorizedResponse->assertStatus(403);
    }

    // 29. Payout cannot exceed approved rewards
    public function test_29_payout_cannot_be_requested_without_approved_rewards(): void
    {
        $user = $this->createUserWithRole('tenant', ['upi_id' => 'nouser@upi']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No approved rewards are currently available for payout.');

        $this->referralService->requestPayout($user);
    }

    // 30. Rate limiting prevents spam submissions
    public function test_30_rate_limiting_protects_referral_submission(): void
    {
        $user = $this->createUserWithRole('tenant');

        // Submit 25 referrals successfully
        for ($i = 1; $i <= 25; $i++) {
            $this->referralService->createReferral($user, [
                'owner_name' => "Owner Limit {$i}",
                'owner_phone' => '9845'.str_pad($i, 6, '0', STR_PAD_LEFT),
                'city' => 'Bengaluru',
                'locality' => 'Koramangala',
                'property_type' => 'Flat / Apartment',
                'listing_intent' => 'rent',
            ]);
        }

        // 26th submission hits daily limit
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('You have reached the daily referral limit.');

        $this->referralService->createReferral($user, [
            'owner_name' => 'Owner Limit 26',
            'owner_phone' => '9845999999',
            'city' => 'Bengaluru',
            'locality' => 'Koramangala',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);
    }

    // 31. Public page contains no private referral data
    public function test_31_public_landing_page_does_not_leak_private_data(): void
    {
        $owner = $this->createUserWithRole('owner', [
            'name' => 'Confidential Landlord',
            'phone' => '9876549999',
        ]);

        $referrer = $this->createUserWithRole('tenant');
        $this->referralService->createReferral($referrer, [
            'owner_name' => 'Confidential Landlord',
            'owner_phone' => '9876549999',
            'city' => 'Bengaluru',
            'locality' => 'Whitefield',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $response = $this->get(route('refer-and-earn'));
        $response->assertStatus(200);
        $response->assertDontSee('9876549999');
        $response->assertDontSee('Confidential Landlord');
    }

    // 32. Multi-role user retains same referral code across role switches
    public function test_32_multi_role_user_retains_consistent_referral_code(): void
    {
        $user = $this->createUserWithRole('tenant');
        $code1 = $user->getOrCreateReferralCode();

        // Add owner role
        $ownerRole = Role::where('name', 'owner')->first();
        $user->roles()->syncWithoutDetaching([$ownerRole->id => ['is_active' => true]]);

        $code2 = $user->fresh()->getOrCreateReferralCode();
        $this->assertEquals($code1, $code2);
    }

    // 33. Configurable admin reward setting affects new rewards
    public function test_33_configurable_admin_reward_amount_setting(): void
    {
        Setting::set('referral_reward_amount', '150.00');

        $user = $this->createUserWithRole('tenant');
        $referral = $this->referralService->createReferral($user, [
            'owner_name' => 'Custom Reward Owner',
            'owner_phone' => '9845771122',
            'city' => 'Bengaluru',
            'locality' => 'Basavanagudi',
            'property_type' => 'Flat / Apartment',
            'listing_intent' => 'rent',
        ]);

        $reward = $this->referralService->createReward($referral);
        $this->assertEquals(150.00, $reward->amount);
    }

    // 34. Existing properties and users remain completely untouched
    public function test_34_existing_properties_and_users_remain_intact(): void
    {
        $existingOwner = $this->createUserWithRole('owner', [
            'name' => 'Historic Owner',
            'email' => 'historic@example.com',
            'phone' => '9900990099',
        ]);

        $property = Property::create([
            'user_id' => $existingOwner->id,
            'title' => 'Historic Apartment',
            'slug' => 'historic-apartment',
            'purpose' => 'rent',
            'property_type' => 'Flat / Apartment',
            'city' => 'Bengaluru',
            'locality' => 'Indiranagar',
            'price' => 28000,
            'sqft' => 1100,
            'seller_name' => 'Historic Owner',
            'seller_phone' => '9900990099',
            'seller_email' => 'historic@example.com',
            'status' => 'published',
            'is_managed' => false,
        ]);

        // Run referral queries
        $stats = $this->referralService->getReferralStats($existingOwner);
        $this->assertEquals(0, $stats['total']);

        // Verify property unchanged
        $this->assertEquals('Historic Apartment', $property->fresh()->title);
        $this->assertFalse($property->fresh()->is_managed);
    }
}
