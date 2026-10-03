<?php

namespace Tests\Feature;

use App\Models\CareServiceCategory;
use App\Models\ManagementEnrollment;
use App\Models\ManagementPlan;
use App\Models\Property;
use App\Models\Role;
use App\Models\ServiceQuote;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\VendorProfile;
use App\Models\WorkOrder;
use App\Services\Housing365CareService;
use Database\Seeders\CareAndManagementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareAndPropertyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles & categories
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'owner'], ['display_name' => 'Property Owner']);
        Role::firstOrCreate(['name' => 'tenant'], ['display_name' => 'Tenant']);
        Role::firstOrCreate(['name' => 'vendor'], ['display_name' => 'Vendor']);
        $this->seed(CareAndManagementSeeder::class);
    }

    protected function createTestProperty(array $overrides = []): Property
    {
        static $counter = 1;
        $counter++;

        return Property::create(array_merge([
            'title' => 'Test Property '.$counter,
            'slug' => 'test-property-'.$counter.'-'.uniqid(),
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'price' => 45000,
            'purpose' => 'rent',
            'category' => 'residential',
            'status' => 'Available',
            'seller_name' => 'Owner Test',
            'seller_email' => 'owner'.$counter.'@test.com',
            'seller_phone' => '987654321'.($counter % 10),
            'is_managed' => false,
        ], $overrides));
    }

    /**
     * Test 1: Public can view Care showcase and category landings.
     */
    public function test_public_can_view_care_showcase_and_category_landing(): void
    {
        $response = $this->get('/care');
        $response->assertStatus(200);
        $response->assertSee('Housing365 Care');
        $response->assertSee('Plumbing');
        $response->assertSee('Electrical');

        $responseCategory = $this->get('/care/plumbing');
        $responseCategory->assertStatus(200);
        $responseCategory->assertSee('Plumbing');
        $responseCategory->assertSee('Book Plumbing Service');
    }

    /**
     * Test 2: Unauthenticated guest redirected to login for Care request form.
     */
    public function test_unauthenticated_user_redirected_to_login_when_requesting_care_service(): void
    {
        $response = $this->get('/care/request');
        $response->assertRedirect('/login');

        $postResponse = $this->post('/care/request', [
            'service_category_id' => 1,
            'description' => 'Fix leaking water tap in kitchen',
        ]);
        $postResponse->assertRedirect('/login');
    }

    /**
     * Test 3: Owner can create service request for own property.
     */
    public function test_owner_can_create_service_request_for_own_property(): void
    {
        $owner = User::factory()->create(['email' => 'landlord@test.com', 'phone' => '9876543210']);
        $owner->assignRole('owner');

        $property = $this->createTestProperty([
            'seller_email' => $owner->email,
            'seller_phone' => $owner->phone,
            'is_managed' => false,
        ]);

        $category = CareServiceCategory::where('slug', 'plumbing')->first();

        $response = $this->actingAs($owner)->post('/care/request', [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'title' => 'Kitchen tap leak',
            'description' => 'Continuous leaking from kitchen mixer tap.',
            'priority' => 'normal',
        ]);

        $this->assertDatabaseHas('service_requests', [
            'user_id' => $owner->id,
            'property_id' => $property->id,
            'service_category_id' => $category->id,
            'title' => 'Kitchen tap leak',
            'status' => 'SUBMITTED',
        ]);

        $serviceRequest = ServiceRequest::where('user_id', $owner->id)->first();
        $this->assertNotNull($serviceRequest);
        $this->assertStringStartsWith('CARE-', $serviceRequest->request_number);
        $response->assertRedirect(route('care.requests.show', $serviceRequest->id));
    }

    /**
     * Test 4: Tenant can create maintenance request without property.
     */
    public function test_tenant_can_create_maintenance_request(): void
    {
        $tenant = User::factory()->create(['email' => 'renter@test.com']);
        $tenant->assignRole('tenant');

        $category = CareServiceCategory::where('slug', 'electrical')->first();

        $response = $this->actingAs($tenant)->post('/care/request', [
            'service_category_id' => $category->id,
            'title' => 'Switchboard sparked in bedroom',
            'description' => 'Main AC switchboard sparked and tripped MCB.',
            'priority' => 'high',
        ]);

        $serviceRequest = ServiceRequest::where('user_id', $tenant->id)->first();
        $this->assertNotNull($serviceRequest);
        $this->assertEquals('SUBMITTED', $serviceRequest->status);
        $response->assertRedirect(route('care.requests.show', $serviceRequest->id));
    }

    /**
     * Test 5: Unauthorized user cannot view or manage another user's request.
     */
    public function test_unauthorized_user_cannot_view_another_users_service_request(): void
    {
        $userA = User::factory()->create(['email' => 'usera@test.com']);
        $userB = User::factory()->create(['email' => 'userb@test.com']);

        $category = CareServiceCategory::first();
        $request = ServiceRequest::create([
            'request_number' => 'SR-TEST-001',
            'user_id' => $userA->id,
            'service_category_id' => $category->id,
            'title' => 'Private Issue',
            'description' => 'Confidential repair issue',
            'status' => 'SUBMITTED',
        ]);

        $response = $this->actingAs($userB)->get(route('care.requests.show', $request->id));
        $response->assertStatus(403);
    }

    /**
     * Test 6: Vendor assignment by operations.
     */
    public function test_operations_triage_assigns_vendor(): void
    {
        $careService = app(Housing365CareService::class);

        $customer = User::factory()->create();
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');
        VendorProfile::create([
            'user_id' => $vendor->id,
            'business_name' => 'Apex Repairs & Plumbing',
            'verification_status' => 'verified',
        ]);

        $category = CareServiceCategory::first();
        $serviceRequest = $careService->createRequest($customer, [
            'service_category_id' => $category->id,
            'description' => 'Needs fixing',
        ]);

        $careService->assignVendor($serviceRequest, $vendor);

        $this->assertEquals($vendor->id, $serviceRequest->fresh()->assigned_vendor_id);
        $this->assertEquals(ServiceRequest::STATUS_VENDOR_ASSIGNED, $serviceRequest->fresh()->status);
    }

    /**
     * Test 7: Vendor cannot view jobs assigned to another vendor.
     */
    public function test_vendor_cannot_view_jobs_assigned_to_another_vendor(): void
    {
        $vendorA = User::factory()->create();
        $vendorA->assignRole('vendor');

        $vendorB = User::factory()->create();
        $vendorB->assignRole('vendor');

        $customer = User::factory()->create();
        $category = CareServiceCategory::first();

        $serviceRequest = ServiceRequest::create([
            'request_number' => 'SR-VENDOR-001',
            'user_id' => $customer->id,
            'service_category_id' => $category->id,
            'assigned_vendor_id' => $vendorA->id,
            'title' => 'Plumbing Repair',
            'description' => 'Fix tap',
            'status' => ServiceRequest::STATUS_VENDOR_ASSIGNED,
        ]);

        $response = $this->actingAs($vendorB)->get(route('vendor.jobs.show', $serviceRequest->id));
        $response->assertStatus(403);
    }

    /**
     * Test 8: Vendor submits quote -> quote status PENDING, request AWAITING_APPROVAL.
     */
    public function test_vendor_submits_quote_and_status_becomes_awaiting_approval(): void
    {
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');
        $customer = User::factory()->create();
        $category = CareServiceCategory::first();

        $serviceRequest = ServiceRequest::create([
            'request_number' => 'SR-VENDOR-002',
            'user_id' => $customer->id,
            'service_category_id' => $category->id,
            'assigned_vendor_id' => $vendor->id,
            'title' => 'AC Servicing',
            'description' => 'Gas refill and coil cleaning',
            'status' => ServiceRequest::STATUS_VENDOR_ASSIGNED,
        ]);

        $response = $this->actingAs($vendor)->post(route('vendor.jobs.quote', $serviceRequest->id), [
            'labour_amount' => 500,
            'materials_amount' => 1200,
            'tax_amount' => 306,
            'other_charges' => 0,
            'estimated_duration' => '3 Hours',
            'notes' => 'Includes 3-month gas leakage warranty',
        ]);

        $response->assertRedirect(route('vendor.jobs.show', $serviceRequest->id));

        $this->assertDatabaseHas('service_quotes', [
            'service_request_id' => $serviceRequest->id,
            'vendor_id' => $vendor->id,
            'labour_amount' => 500,
            'materials_amount' => 1200,
            'total_amount' => 2006,
            'status' => ServiceQuote::STATUS_PENDING,
        ]);

        $this->assertEquals(ServiceRequest::STATUS_AWAITING_APPROVAL, $serviceRequest->fresh()->status);
    }

    /**
     * Test 9: Customer approves quote -> creates work order, status becomes SCHEDULED.
     */
    public function test_customer_approves_quote_generates_work_order(): void
    {
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');
        $customer = User::factory()->create();
        $category = CareServiceCategory::first();

        $serviceRequest = ServiceRequest::create([
            'request_number' => 'SR-QUOTE-001',
            'user_id' => $customer->id,
            'service_category_id' => $category->id,
            'assigned_vendor_id' => $vendor->id,
            'title' => 'Geyser repair',
            'description' => 'Heating coil broken',
            'status' => ServiceRequest::STATUS_AWAITING_APPROVAL,
        ]);

        $quote = ServiceQuote::create([
            'service_request_id' => $serviceRequest->id,
            'vendor_id' => $vendor->id,
            'quote_number' => 'SQ-TEST-001',
            'labour_amount' => 300,
            'materials_amount' => 600,
            'total_amount' => 900,
            'status' => ServiceQuote::STATUS_PENDING,
        ]);

        // Stranger cannot approve (must return 403)
        $stranger = User::factory()->create();
        $forbiddenResponse = $this->actingAs($stranger)->post(route('care.quotes.approve', $quote->id));
        $forbiddenResponse->assertStatus(403);

        // Real customer approves
        $approveResponse = $this->actingAs($customer)->post(route('care.quotes.approve', $quote->id));
        $approveResponse->assertRedirect(route('care.requests.show', $serviceRequest->id));

        $this->assertEquals(ServiceQuote::STATUS_APPROVED, $quote->fresh()->status);
        $this->assertEquals(ServiceRequest::STATUS_SCHEDULED, $serviceRequest->fresh()->status);

        $workOrder = WorkOrder::where('service_request_id', $serviceRequest->id)->first();
        $this->assertNotNull($workOrder);
        $this->assertEquals(WorkOrder::STATUS_SCHEDULED, $workOrder->status);
    }

    /**
     * Test 10: Vendor starts and completes work order -> submits for QC.
     */
    public function test_vendor_starts_and_completes_work_order(): void
    {
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');
        $customer = User::factory()->create();
        $category = CareServiceCategory::first();

        $serviceRequest = ServiceRequest::create([
            'request_number' => 'SR-WO-001',
            'user_id' => $customer->id,
            'service_category_id' => $category->id,
            'assigned_vendor_id' => $vendor->id,
            'title' => 'Painting Job',
            'description' => 'Hall touchup painting',
            'status' => ServiceRequest::STATUS_SCHEDULED,
        ]);

        $quote = ServiceQuote::create([
            'service_request_id' => $serviceRequest->id,
            'vendor_id' => $vendor->id,
            'quote_number' => 'SQ-WO-001',
            'total_amount' => 1500,
            'status' => ServiceQuote::STATUS_APPROVED,
        ]);

        $workOrder = WorkOrder::create([
            'work_order_number' => 'WO-TEST-001',
            'service_request_id' => $serviceRequest->id,
            'service_quote_id' => $quote->id,
            'vendor_id' => $vendor->id,
            'status' => WorkOrder::STATUS_SCHEDULED,
        ]);

        // Vendor starts work
        $this->actingAs($vendor)->post(route('vendor.work-orders.start', $workOrder->id));
        $this->assertEquals(WorkOrder::STATUS_IN_PROGRESS, $workOrder->fresh()->status);
        $this->assertEquals(ServiceRequest::STATUS_IN_PROGRESS, $serviceRequest->fresh()->status);

        // Vendor completes work
        $this->actingAs($vendor)->post(route('vendor.work-orders.complete', $workOrder->id), [
            'work_notes' => 'Hall touch-up completed with Asian Paints Royale.',
        ]);
        $this->assertEquals(WorkOrder::STATUS_QC_PENDING, $workOrder->fresh()->status);
        $this->assertEquals(ServiceRequest::STATUS_QC_PENDING, $serviceRequest->fresh()->status);
    }

    /**
     * Test 11: QC review and closure by customer.
     */
    public function test_customer_qc_review_closes_service_request(): void
    {
        $customer = User::factory()->create();
        $category = CareServiceCategory::first();

        $serviceRequest = ServiceRequest::create([
            'request_number' => 'SR-QC-001',
            'user_id' => $customer->id,
            'service_category_id' => $category->id,
            'title' => 'RO Filter Replacement',
            'description' => 'New sediment and carbon filters',
            'status' => ServiceRequest::STATUS_QC_PENDING,
        ]);

        $workOrder = WorkOrder::create([
            'work_order_number' => 'WO-QC-001',
            'service_request_id' => $serviceRequest->id,
            'vendor_id' => $customer->id,
            'status' => WorkOrder::STATUS_QC_PENDING,
        ]);

        $response = $this->actingAs($customer)->post(route('care.requests.qc', $serviceRequest->id), [
            'decision' => 'approve',
            'rating' => 5,
            'review' => 'Excellent service and genuine parts used!',
        ]);

        $response->assertRedirect(route('care.requests.show', $serviceRequest->id));

        $this->assertEquals(ServiceRequest::STATUS_QC_COMPLETED, $serviceRequest->fresh()->status);
        $this->assertNotNull($serviceRequest->fresh()->closed_at);
        $this->assertEquals(5, $serviceRequest->fresh()->rating);
        $this->assertEquals('Excellent service and genuine parts used!', $serviceRequest->fresh()->review);
        $this->assertEquals(WorkOrder::STATUS_QC_COMPLETED, $workOrder->fresh()->status);
    }

    /**
     * Test 12: Path A (Leasing Only) is ₹0 free and unmanaged by default.
     * Owner can list free, receive inquiries, and use Care without Property Management.
     */
    public function test_owner_path_a_leasing_only_is_free_and_unmanaged_by_default(): void
    {
        $owner = User::factory()->create(['email' => 'freeowner@test.com', 'phone' => '9988776655']);
        $owner->assignRole('owner');

        $property = $this->createTestProperty([
            'seller_email' => $owner->email,
            'seller_phone' => $owner->phone,
            'is_managed' => false,
        ]);

        // Default state is unmanaged
        $this->assertFalse($property->is_managed);
        $this->assertNull($property->activeManagementEnrollment);

        // Can use Housing365 Care without subscribing to Property Management
        $category = CareServiceCategory::where('slug', 'deep-cleaning')->first();
        $response = $this->actingAs($owner)->post('/care/request', [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Pre-tenant move-in deep cleaning',
        ]);

        $this->assertDatabaseHas('service_requests', [
            'property_id' => $property->id,
            'user_id' => $owner->id,
        ]);

        // Property remains unmanaged
        $this->assertFalse($property->fresh()->is_managed);
    }

    /**
     * Test 13: Path B (Leasing + Optional Property Management) explicit enrollment workflow.
     */
    public function test_owner_path_b_property_management_enrollment_workflow(): void
    {
        $owner = User::factory()->create(['email' => 'managedowner@test.com', 'phone' => '9123456789']);
        $owner->assignRole('owner');

        $property = $this->createTestProperty([
            'seller_email' => $owner->email,
            'seller_phone' => $owner->phone,
            'is_managed' => false,
        ]);

        $plan = ManagementPlan::where('slug', 'standard-property-management')->first();

        // 1. Owner visits explore page
        $exploreResponse = $this->actingAs($owner)->get(route('management.explore', $property->id));
        $exploreResponse->assertStatus(200);
        $exploreResponse->assertSee('Leasing Only');
        $exploreResponse->assertSee('Full Property Management');

        // 2. Owner requests enrollment
        $requestResponse = $this->actingAs($owner)->post(route('management.request', $property->id), [
            'management_plan_id' => $plan->id,
            'notes' => 'Please manage my apartment while I am away.',
        ]);

        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();
        $this->assertNotNull($enrollment);
        $this->assertEquals(ManagementEnrollment::STATUS_REQUESTED, $enrollment->status);
        $this->assertStringStartsWith('PMA-', $enrollment->agreement_reference);

        // Property is NOT yet active/managed
        $this->assertFalse($property->fresh()->is_managed);

        // 3. Owner views agreement
        $agreementResponse = $this->actingAs($owner)->get(route('management.agreement', $enrollment->id));
        $agreementResponse->assertStatus(200);
        $agreementResponse->assertSee($enrollment->agreement_reference);
        $agreementResponse->assertSee($plan->name);

        // 4. Owner accepts agreement
        $acceptResponse = $this->actingAs($owner)->post(route('management.agreement.accept', $enrollment->id), [
            'confirm_terms' => '1',
        ]);
        $acceptResponse->assertRedirect(route('dashboard.owner'));

        $this->assertEquals(ManagementEnrollment::STATUS_ACTIVE, $enrollment->fresh()->status);
        $this->assertNotNull($enrollment->fresh()->agreement_accepted_at);
        $this->assertTrue($property->fresh()->is_managed);

        // 5. Owner can cancel anytime (30-day exit clause) reverting to Leasing Only
        $cancelResponse = $this->actingAs($owner)->post(route('management.agreement.cancel', $enrollment->id), [
            'reason' => 'Relocated back to town; self-managing.',
        ]);
        $cancelResponse->assertRedirect(route('dashboard.owner'));

        $this->assertEquals(ManagementEnrollment::STATUS_CANCELLED, $enrollment->fresh()->status);
        $this->assertFalse($property->fresh()->is_managed);
    }

    /**
     * Test 14: Contact Access paywall regression: No phone or email leaked in care/vendor views.
     */
    public function test_contact_access_paywall_zero_leakage_in_care_and_vendor_views(): void
    {
        $owner = User::factory()->create([
            'name' => 'Secret Landlord',
            'email' => 'secret_owner_99@private.in',
            'phone' => '+91 99999 88888',
        ]);
        $owner->assignRole('owner');

        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $property = $this->createTestProperty([
            'seller_email' => $owner->email,
            'seller_phone' => $owner->phone,
        ]);

        $category = CareServiceCategory::first();

        $serviceRequest = ServiceRequest::create([
            'request_number' => 'SR-LEAK-TEST',
            'user_id' => $owner->id,
            'property_id' => $property->id,
            'service_category_id' => $category->id,
            'assigned_vendor_id' => $vendor->id,
            'title' => 'Bathroom repair',
            'description' => 'Fix leaking shower head',
            'status' => ServiceRequest::STATUS_VENDOR_ASSIGNED,
        ]);

        // Vendor views job detail
        $vendorResponse = $this->actingAs($vendor)->get(route('vendor.jobs.show', $serviceRequest->id));
        $vendorResponse->assertStatus(200);

        // Verify owner's personal phone and email are never rendered or leaked to the vendor
        $vendorResponse->assertDontSee('secret_owner_99@private.in');
        $vendorResponse->assertDontSee('+91 99999 88888');
        $vendorResponse->assertDontSee('9999988888');
    }
}
