<?php

namespace Tests\Feature;

use App\Models\CareServiceCategory;
use App\Models\Inquiry;
use App\Models\ManagementEnrollment;
use App\Models\ManagementPlan;
use App\Models\Property;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\CareAndManagementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Strict QA Invariant Tests for Property Management Optionality and Care Decoupling.
 *
 * Verifies all 14 mandatory business rules from Phase 4 specification.
 */
class PropertyManagementActivationInvariantsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'owner'], ['display_name' => 'Property Owner']);
        Role::firstOrCreate(['name' => 'tenant'], ['display_name' => 'Tenant']);
        $this->seed(CareAndManagementSeeder::class);
    }

    protected function createOwnerWithProperty(array $propertyOverrides = []): array
    {
        static $counter = 100;
        $counter++;

        $owner = User::factory()->create([
            'name' => 'Owner '.$counter,
            'email' => 'owner_'.$counter.'@housing365.test',
            'phone' => '98700'.str_pad((string) $counter, 5, '0', STR_PAD_LEFT),
        ]);
        $owner->assignRole('owner');

        $property = Property::create(array_merge([
            'title' => 'Prestige Lakeside Villa '.$counter,
            'slug' => 'prestige-lakeside-villa-'.$counter.'-'.uniqid(),
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'locality' => 'Whitefield',
            'price' => 55000,
            'purpose' => 'rent',
            'category' => 'residential',
            'status' => 'Available',
            'user_id' => $owner->id,
            'seller_name' => $owner->name,
            'seller_email' => $owner->email,
            'seller_phone' => $owner->phone,
            'is_managed' => false,
        ], $propertyOverrides));

        return [$owner, $property];
    }

    /**
     * Invariant 1: New owner creates property -> NO management enrollment
     */
    public function test_invariant_1_new_owner_creates_property_has_no_management_enrollment(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();

        $this->assertFalse($property->is_managed);
        $this->assertDatabaseMissing('management_enrollments', [
            'property_id' => $property->id,
        ]);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    /**
     * Invariant 2: Owner lists property -> NO management enrollment
     */
    public function test_invariant_2_owner_lists_property_has_no_management_enrollment(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty(['status' => 'Available']);

        $response = $this->get('/properties/'.$property->slug);
        $response->assertStatus(200);

        $this->assertFalse($property->fresh()->is_managed);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    /**
     * Invariant 3: Owner submits Care request -> NO management enrollment
     */
    public function test_invariant_3_owner_submits_care_request_has_no_management_enrollment(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();
        $category = CareServiceCategory::where('slug', 'plumbing')->first();

        $response = $this->actingAs($owner)->post('/care/request', [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'title' => 'Bathroom pipeline leak',
            'description' => 'Minor pipeline leak under master bathroom sink.',
            'priority' => 'normal',
        ]);

        $this->assertDatabaseHas('service_requests', [
            'property_id' => $property->id,
            'user_id' => $owner->id,
        ]);

        // Decoupling assertion: Care does not imply or trigger property management
        $this->assertFalse($property->fresh()->is_managed);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    /**
     * Invariant 4: Owner submits maintenance request -> NO management enrollment
     */
    public function test_invariant_4_owner_submits_maintenance_request_has_no_management_enrollment(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();
        $category = CareServiceCategory::where('slug', 'electrical')->first();

        $response = $this->actingAs($owner)->post('/care/request', [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'title' => 'Tripping MCB breaker',
            'description' => 'Main electrical breaker trips when geyser is switched on.',
            'priority' => 'high',
        ]);

        $this->assertFalse($property->fresh()->is_managed);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    /**
     * Invariant 5: Owner selects Leasing Only -> NO management enrollment
     */
    public function test_invariant_5_owner_selects_leasing_only_has_no_management_enrollment(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();

        // Owner views the management explore page which outlines Path A (Leasing Only) vs Path B (Property Management)
        $response = $this->actingAs($owner)->get(route('management.explore', $property->id));
        $response->assertStatus(200);
        $response->assertSee('PATH A');
        $response->assertSee('Leasing Only');
        $response->assertSee('₹0 FREE FOREVER');

        // Without submitting enrollment, property remains purely on Leasing Only
        $this->assertFalse($property->fresh()->is_managed);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    /**
     * Invariant 6: Owner explicitly selects Property Management -> enrollment created with REQUESTED status
     */
    public function test_invariant_6_owner_explicitly_selects_property_management_creates_enrollment(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();
        $plan = ManagementPlan::where('slug', 'standard-property-management')->first();

        $response = $this->actingAs($owner)->post(route('management.request', $property->id), [
            'management_plan_id' => $plan->id,
            'notes' => 'Looking for end-to-end tenant management.',
        ]);

        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();
        $this->assertNotNull($enrollment);
        $this->assertEquals(ManagementEnrollment::STATUS_REQUESTED, $enrollment->status);
        $this->assertStringStartsWith('PMA-', $enrollment->agreement_reference);

        // Before agreement acceptance, property is NOT active managed
        $this->assertFalse($property->fresh()->is_managed);
        $this->assertNull($property->fresh()->activeManagementEnrollment);
    }

    /**
     * Invariant 7: Owner accepts Management Agreement -> activation succeeds
     */
    public function test_invariant_7_owner_accepts_management_agreement_activation_succeeds(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();
        $plan = ManagementPlan::where('slug', 'standard-property-management')->first();

        $this->actingAs($owner)->post(route('management.request', $property->id), [
            'management_plan_id' => $plan->id,
        ]);
        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();

        $response = $this->actingAs($owner)->post(route('management.agreement.accept', $enrollment->id), [
            'confirm_terms' => '1',
        ]);
        $response->assertRedirect(route('dashboard.owner'));

        $freshEnrollment = $enrollment->fresh();
        $this->assertEquals(ManagementEnrollment::STATUS_ACTIVE, $freshEnrollment->status);
        $this->assertNotNull($freshEnrollment->agreement_accepted_at);
        $this->assertNotNull($freshEnrollment->agreement_accepted_ip);
        $this->assertTrue($property->fresh()->is_managed);
        $this->assertNotNull($property->fresh()->activeManagementEnrollment);
    }

    /**
     * Invariant 8: Owner rejects or fails to accept agreement -> activation does not occur
     */
    public function test_invariant_8_owner_rejects_agreement_activation_does_not_occur(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();
        $plan = ManagementPlan::where('slug', 'standard-property-management')->first();

        $this->actingAs($owner)->post(route('management.request', $property->id), [
            'management_plan_id' => $plan->id,
        ]);
        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();

        // Owner sends POST without accepting terms
        $response = $this->actingAs($owner)->post(route('management.agreement.accept', $enrollment->id), [
            'confirm_terms' => '0',
        ]);

        $this->assertEquals(ManagementEnrollment::STATUS_REQUESTED, $enrollment->fresh()->status);
        $this->assertFalse($property->fresh()->is_managed);
        $this->assertNull($property->fresh()->activeManagementEnrollment);
    }

    /**
     * Invariant 9: Owner cannot accidentally activate management through a GET request
     */
    public function test_invariant_9_owner_cannot_accidentally_activate_management_through_get_request(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();
        $plan = ManagementPlan::where('slug', 'standard-property-management')->first();

        $this->actingAs($owner)->post(route('management.request', $property->id), [
            'management_plan_id' => $plan->id,
        ]);
        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();

        // Attempting to send GET to the accept endpoint must result in 405 Method Not Allowed
        $response = $this->actingAs($owner)->get('/management/agreement/'.$enrollment->id.'/accept');
        $this->assertContains($response->getStatusCode(), [404, 405]);

        $this->assertEquals(ManagementEnrollment::STATUS_REQUESTED, $enrollment->fresh()->status);
        $this->assertFalse($property->fresh()->is_managed);
    }

    /**
     * Invariant 10: Owner can activate management later from Owner Portal
     */
    public function test_invariant_10_owner_can_activate_management_later_from_owner_portal(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();

        // Owner visits dashboard later, clicks on Manage button linking to management.explore
        $dashboardResponse = $this->actingAs($owner)->get(route('dashboard.owner'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee(route('management.explore', $property->id));

        // Owner initiates activation from that link
        $plan = ManagementPlan::first();
        $this->actingAs($owner)->post(route('management.request', $property->id), [
            'management_plan_id' => $plan->id,
        ]);
        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();

        $this->actingAs($owner)->post(route('management.agreement.accept', $enrollment->id), [
            'confirm_terms' => '1',
        ]);

        $this->assertTrue($property->fresh()->is_managed);
        $this->assertEquals(ManagementEnrollment::STATUS_ACTIVE, $enrollment->fresh()->status);
    }

    /**
     * Invariant 11: Owner can cancel management according to the configured agreement terms
     */
    public function test_invariant_11_owner_can_cancel_management_according_to_configured_terms(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();
        $plan = ManagementPlan::first();

        $this->actingAs($owner)->post(route('management.request', $property->id), ['management_plan_id' => $plan->id]);
        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();
        $this->actingAs($owner)->post(route('management.agreement.accept', $enrollment->id), ['confirm_terms' => '1']);

        $this->assertTrue($property->fresh()->is_managed);

        // Owner requests cancellation
        $cancelResponse = $this->actingAs($owner)->post(route('management.agreement.cancel', $enrollment->id), [
            'reason' => 'Opting for self-management as I moved closer to the property.',
        ]);
        $cancelResponse->assertRedirect(route('dashboard.owner'));

        $this->assertEquals(ManagementEnrollment::STATUS_CANCELLED, $enrollment->fresh()->status);
        $this->assertFalse($property->fresh()->is_managed);
    }

    /**
     * Invariant 12: Cancelling management does not delete the property
     */
    public function test_invariant_12_cancelling_management_does_not_delete_the_property(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();
        $plan = ManagementPlan::first();

        $this->actingAs($owner)->post(route('management.request', $property->id), ['management_plan_id' => $plan->id]);
        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();
        $this->actingAs($owner)->post(route('management.agreement.accept', $enrollment->id), ['confirm_terms' => '1']);

        // Cancel
        $this->actingAs($owner)->post(route('management.agreement.cancel', $enrollment->id));

        // Property record MUST exist intact in database
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'title' => $property->title,
            'is_managed' => false,
        ]);
        $this->assertNotNull(Property::find($property->id));
    }

    /**
     * Invariant 13: Cancelling management does not delete tenant/inquiry history
     */
    public function test_invariant_13_cancelling_management_does_not_delete_tenant_inquiry_history(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty();
        $plan = ManagementPlan::first();

        // Create inquiry history for this property
        $inquiry1 = Inquiry::create([
            'property_id' => $property->id,
            'name' => 'Prospective Tenant 1',
            'email' => 'tenant1@test.com',
            'phone' => '9111122222',
            'message' => 'Interested in 2-year lease.',
        ]);

        $inquiry2 = Inquiry::create([
            'property_id' => $property->id,
            'name' => 'Prospective Tenant 2',
            'email' => 'tenant2@test.com',
            'phone' => '9333344444',
            'message' => 'Can we schedule a viewing this Saturday?',
        ]);

        $this->actingAs($owner)->post(route('management.request', $property->id), ['management_plan_id' => $plan->id]);
        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();
        $this->actingAs($owner)->post(route('management.agreement.accept', $enrollment->id), ['confirm_terms' => '1']);

        // Cancel management
        $this->actingAs($owner)->post(route('management.agreement.cancel', $enrollment->id));

        // Inquiries MUST still exist completely intact
        $this->assertDatabaseHas('inquiries', ['id' => $inquiry1->id]);
        $this->assertDatabaseHas('inquiries', ['id' => $inquiry2->id]);
        $this->assertEquals(2, $property->fresh()->inquiries()->count());
    }

    /**
     * Invariant 14: Property remains available for Leasing Only after management cancellation
     */
    public function test_invariant_14_property_remains_available_for_leasing_only_after_cancellation(): void
    {
        [$owner, $property] = $this->createOwnerWithProperty(['status' => 'Available', 'purpose' => 'rent']);
        $plan = ManagementPlan::first();

        $this->actingAs($owner)->post(route('management.request', $property->id), ['management_plan_id' => $plan->id]);
        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();
        $this->actingAs($owner)->post(route('management.agreement.accept', $enrollment->id), ['confirm_terms' => '1']);

        // Cancel management
        $this->actingAs($owner)->post(route('management.agreement.cancel', $enrollment->id));

        $freshProperty = $property->fresh();
        $this->assertEquals('Available', $freshProperty->status);
        $this->assertEquals('rent', $freshProperty->purpose);
        $this->assertFalse($freshProperty->is_managed);

        // Marketplace public listing remains accessible for free leasing
        $listingResponse = $this->get('/properties/'.$freshProperty->slug);
        $listingResponse->assertStatus(200);
        $listingResponse->assertSee($freshProperty->title);
    }

    /**
     * Cross-User Security: Owner A cannot view or tamper with Owner B's management enrollment.
     */
    public function test_owner_a_cannot_view_or_cancel_owner_b_management_enrollment(): void
    {
        [$ownerA, $propertyA] = $this->createOwnerWithProperty();
        [$ownerB, $propertyB] = $this->createOwnerWithProperty();

        $plan = ManagementPlan::first();
        $this->actingAs($ownerA)->post(route('management.request', $propertyA->id), ['management_plan_id' => $plan->id]);
        $enrollmentA = ManagementEnrollment::where('property_id', $propertyA->id)->first();

        // Owner B tries to access Owner A's agreement view -> 403 Forbidden
        $response = $this->actingAs($ownerB)->get(route('management.agreement', $enrollmentA->id));
        $response->assertStatus(403);

        // Owner B tries to cancel Owner A's agreement -> 403 Forbidden
        $cancelResponse = $this->actingAs($ownerB)->post(route('management.agreement.cancel', $enrollmentA->id));
        $cancelResponse->assertStatus(403);
    }
}
