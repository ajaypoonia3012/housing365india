<?php

namespace Tests\Feature;

use App\Models\CareInvoice;
use App\Models\CareServiceCategory;
use App\Models\ManagementEnrollment;
use App\Models\ManagementPlan;
use App\Models\Organization;
use App\Models\Property;
use App\Models\Role;
use App\Models\ServiceQuote;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\VendorPayout;
use App\Models\WorkOrder;
use App\Services\Housing365CareService;
use Database\Seeders\CareAndManagementSeeder;
use Database\Seeders\Phase5TenantAndInventorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Tests\TestCase;

class Phase6Housing365CareTest extends TestCase
{
    use RefreshDatabase;

    protected Housing365CareService $careService;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'owner'], ['display_name' => 'Property Owner']);
        Role::firstOrCreate(['name' => 'tenant'], ['display_name' => 'Tenant']);
        Role::firstOrCreate(['name' => 'vendor'], ['display_name' => 'Vendor']);
        $this->seed(CareAndManagementSeeder::class);
        $this->seed(Phase5TenantAndInventorySeeder::class);

        $this->careService = app(Housing365CareService::class);
    }

    protected function createOwner(array $overrides = []): User
    {
        $owner = User::factory()->create(array_merge([
            'email' => 'care_owner_'.uniqid().'@housing365.test',
            'phone' => '98700'.rand(10000, 99999),
        ], $overrides));
        $owner->assignRole('owner');

        return $owner;
    }

    protected function createTenant(array $overrides = []): User
    {
        $tenant = User::factory()->create(array_merge([
            'email' => 'care_tenant_'.uniqid().'@housing365.test',
            'phone' => '98800'.rand(10000, 99999),
        ], $overrides));
        $tenant->assignRole('tenant');

        return $tenant;
    }

    protected function createVendor(array $overrides = []): User
    {
        $vendor = User::factory()->create(array_merge([
            'email' => 'care_vendor_'.uniqid().'@housing365.test',
            'phone' => '98900'.rand(10000, 99999),
        ], $overrides));
        $vendor->assignRole('vendor');

        $vendor->vendorProfile()->create([
            'business_name' => 'FastFix Solutions',
            'service_categories' => ['plumbing', 'electrical'],
            'service_areas' => ['Koramangala', 'Indiranagar'],
            'verification_status' => 'verified',
            'is_available' => true,
        ]);

        return $vendor;
    }

    protected function createProperty(User $owner, array $overrides = []): Property
    {
        return Property::create(array_merge([
            'user_id' => $owner->id,
            'title' => 'Prestige Hermitage #'.rand(100, 999),
            'slug' => 'prestige-hermitage-'.uniqid(),
            'property_type' => 'Apartment',
            'category' => 'residential',
            'purpose' => 'rent',
            'price' => 45000.00,
            'locality' => 'Koramangala',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'seller_name' => $owner->name,
            'seller_phone' => $owner->phone,
            'seller_email' => $owner->email,
            'status' => 'Available',
            'is_managed' => false,
        ], $overrides));
    }

    // 1. Customer can create Care request
    public function test_1_customer_can_create_care_request(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $category = CareServiceCategory::where('slug', 'plumbing')->first();

        $this->actingAs($owner);

        $response = $this->post(route('care.request.store'), [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'title' => 'Kitchen Sink Leak Repair',
            'description' => 'Water is leaking under the kitchen sink and flooding the cabinet.',
            'priority' => 'normal',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('service_requests', [
            'user_id' => $owner->id,
            'property_id' => $property->id,
            'service_category_id' => $category->id,
            'status' => ServiceRequest::STATUS_SUBMITTED,
        ]);
    }

    // 2. Unauthorized user cannot access another customer's request
    public function test_2_unauthorized_user_cannot_access_another_customers_request(): void
    {
        $ownerA = $this->createOwner();
        $ownerB = $this->createOwner();
        $propertyA = $this->createProperty($ownerA);
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($ownerA, [
            'service_category_id' => $category->id,
            'property_id' => $propertyA->id,
            'description' => 'Bathroom drain clogged',
        ]);

        $this->actingAs($ownerB);
        $response = $this->get(route('care.requests.show', $request->id));

        $response->assertStatus(403);
    }

    // 3. Vendor cannot access unassigned request
    public function test_3_vendor_cannot_access_unassigned_request(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendorA = $this->createVendor();
        $vendorB = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'AC not cooling',
        ]);

        $this->careService->assignVendor($request, $vendorA);

        $this->actingAs($vendorB);
        $response = $this->get(route('vendor.jobs.show', $request->id));

        $response->assertStatus(403);
    }

    // 4. Vendor can accept assigned request
    public function test_4_vendor_can_accept_assigned_request(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Water heater replacement',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $this->assertEquals('OFFERED', $request->fresh()->assignment_status);

        $this->actingAs($vendor);
        $response = $this->post(route('vendor.requests.accept', $request->id));

        $response->assertRedirect(route('vendor.jobs.show', $request->id));
        $this->assertEquals('ACCEPTED', $request->fresh()->assignment_status);
        $this->assertEquals(ServiceRequest::STATUS_VENDOR_ACCEPTED, $request->fresh()->status);
    }

    // 5. Vendor cannot accept another vendor's assignment
    public function test_5_vendor_cannot_accept_another_vendors_assignment(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendorA = $this->createVendor();
        $vendorB = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Electrical short circuit',
        ]);

        $this->careService->assignVendor($request, $vendorA);

        $this->actingAs($vendorB);
        $response = $this->post(route('vendor.requests.accept', $request->id));

        $response->assertStatus(403);
    }

    // 6. Vendor can submit quote
    public function test_6_vendor_can_submit_quote(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Carpentry lock replacement',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $this->careService->vendorAcceptAssignment($request, $vendor);

        $this->actingAs($vendor);
        $response = $this->post(route('vendor.jobs.quote', $request->id), [
            'labour_amount' => 800,
            'materials_amount' => 1200,
            'tax_amount' => 360,
            'other_charges' => 100,
            'estimated_duration' => '2 Hours',
            'notes' => 'Includes premium brass cylinder lock',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('service_quotes', [
            'service_request_id' => $request->id,
            'vendor_id' => $vendor->id,
            'total_amount' => 2460.00,
            'status' => ServiceQuote::STATUS_PENDING,
        ]);
        $this->assertEquals(ServiceRequest::STATUS_AWAITING_APPROVAL, $request->fresh()->status);
    }

    // 7. Customer can approve own quote
    public function test_7_customer_can_approve_own_quote(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Painting touchup',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, [
            'labour_amount' => 1500,
            'materials_amount' => 500,
        ]);

        $this->actingAs($owner);
        $response = $this->post(route('care.quotes.approve', $quote->id));

        $response->assertRedirect();
        $this->assertEquals(ServiceQuote::STATUS_APPROVED, $quote->fresh()->status);
        $this->assertNotNull($request->fresh()->workOrder);
    }

    // 8. Customer cannot approve another customer's quote
    public function test_8_customer_cannot_approve_another_customers_quote(): void
    {
        $ownerA = $this->createOwner();
        $ownerB = $this->createOwner();
        $propertyA = $this->createProperty($ownerA);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($ownerA, [
            'service_category_id' => $category->id,
            'property_id' => $propertyA->id,
            'description' => 'Geyser repair',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 900]);

        $this->actingAs($ownerB);
        $response = $this->post(route('care.quotes.approve', $quote->id));

        $response->assertStatus(403);
    }

    // 9. Vendor cannot approve own quote as customer
    public function test_9_vendor_cannot_approve_own_quote_as_customer(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Deep cleaning',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 2500]);

        // Vendor attempts to approve own quote
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Vendors cannot approve their own quotes');

        $this->careService->approveQuote($quote, $vendor);
    }

    // 10. Approved quote creates valid work order
    public function test_10_approved_quote_creates_valid_work_order(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Pest control',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 1200]);

        $workOrder = $this->careService->approveQuote($quote, $owner);

        $this->assertInstanceOf(WorkOrder::class, $workOrder);
        $this->assertEquals(WorkOrder::STATUS_SCHEDULED, $workOrder->status);
        $this->assertEquals($vendor->id, $workOrder->vendor_id);
    }

    // 11. Invalid work-order state transition is blocked
    public function test_11_invalid_work_order_state_transition_is_blocked(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Appliance repair',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 500]);
        $workOrder = $this->careService->approveQuote($quote, $owner);

        // Invalid: Attempting to jump directly from SCHEDULED to QC_APPROVED
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid work order state transition');

        $this->careService->updateWorkOrderStatus($workOrder, $vendor, WorkOrder::STATUS_QC_APPROVED);
    }

    // 12. Unauthorized user cannot modify work order
    public function test_12_unauthorized_user_cannot_modify_work_order(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendorA = $this->createVendor();
        $vendorB = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Wiring fix',
        ]);

        $this->careService->assignVendor($request, $vendorA);
        $quote = $this->careService->submitQuote($request, $vendorA, ['labour_amount' => 600]);
        $workOrder = $this->careService->approveQuote($quote, $owner);

        $this->actingAs($vendorB);
        $response = $this->post(route('vendor.work-orders.status', $workOrder->id), [
            'status' => 'IN_PROGRESS',
        ]);

        $response->assertStatus(403);
    }

    // 13. QC workflow works with approval and generates invoice
    public function test_13_qc_workflow_works_with_approval_and_generates_invoice(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Balcony drain',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, [
            'labour_amount' => 1000,
            'tax_amount' => 180,
        ]);
        $workOrder = $this->careService->approveQuote($quote, $owner);
        $this->careService->startWorkOrder($workOrder, $vendor);
        $this->careService->completeWorkOrder($workOrder, $vendor, ['/storage/after.jpg']);

        // Admin/Customer approves QC
        $this->careService->reviewQualityControl($request, 'approve', 'Excellent work by technician', $owner);

        $this->assertEquals(WorkOrder::STATUS_QC_APPROVED, $workOrder->fresh()->status);
        $this->assertDatabaseHas('care_invoices', [
            'service_request_id' => $request->id,
            'work_order_id' => $workOrder->id,
            'total_amount' => 1180.00,
            'payment_status' => CareInvoice::STATUS_PENDING,
        ]);
    }

    // 14. Customer can request rework
    public function test_14_customer_can_request_rework(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Door hinge repair',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 400]);
        $workOrder = $this->careService->approveQuote($quote, $owner);
        $this->careService->startWorkOrder($workOrder, $vendor);
        $this->careService->completeWorkOrder($workOrder, $vendor);

        $this->actingAs($owner);
        $response = $this->post(route('care.requests.confirm', $request->id), [
            'is_resolved' => false,
            'feedback' => 'Door still squeaks loudly. Please apply lubrication.',
        ]);

        $response->assertRedirect();
        $this->assertEquals(ServiceRequest::STATUS_REWORK_REQUIRED, $request->fresh()->status);
        $this->assertEquals(WorkOrder::STATUS_REWORK_REQUIRED, $workOrder->fresh()->status);
        $this->assertEquals(1, $request->fresh()->rework_count);
    }

    // 15. Invoice is generated correctly
    public function test_15_invoice_is_generated_correctly(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Geyser servicing',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, [
            'labour_amount' => 1500,
            'materials_amount' => 800,
            'tax_amount' => 414,
            'other_charges' => 100,
        ]);
        $workOrder = $this->careService->approveQuote($quote, $owner);

        $invoice = $this->careService->generateInvoice($workOrder);

        $this->assertEquals(2400.00, $invoice->subtotal); // 1500 + 800 + 100
        $this->assertEquals(414.00, $invoice->tax_amount);
        $this->assertEquals(2814.00, $invoice->total_amount);
        $this->assertEquals(CareInvoice::STATUS_PENDING, $invoice->payment_status);
    }

    // 16. Payment confirmation is server-side
    public function test_16_payment_confirmation_is_server_side(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'AC Gas Refill',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 2000]);
        $workOrder = $this->careService->approveQuote($quote, $owner);
        $invoice = $this->careService->generateInvoice($workOrder);

        // Invalid signature must fail
        $this->actingAs($owner);
        $response = $this->post(route('care.invoices.pay', $invoice->id), [
            'gateway_order_id' => 'order_123',
            'gateway_payment_id' => 'pay_123',
            'gateway_signature' => 'invalid_signature_tampered',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(CareInvoice::STATUS_PENDING, $invoice->fresh()->payment_status);

        // Valid signature succeeds
        $validOrderId = 'order_care_sbx_test';
        $validSignature = hash_hmac('sha256', $validOrderId.'|'.$invoice->total_amount, config('app.key', 'secret_key'));

        $successResponse = $this->post(route('care.invoices.pay', $invoice->id), [
            'gateway_order_id' => $validOrderId,
            'gateway_payment_id' => 'pay_confirmed_123',
            'gateway_signature' => $validSignature,
            'idempotency_key' => (string) Str::uuid(),
        ]);

        $successResponse->assertRedirect(route('care.invoices.show', $invoice->id));
        $this->assertEquals(CareInvoice::STATUS_PAID, $invoice->fresh()->payment_status);
    }

    // 17. Duplicate payment callback is idempotent
    public function test_17_duplicate_payment_callback_is_idempotent(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Switchboard replacement',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 800]);
        $workOrder = $this->careService->approveQuote($quote, $owner);
        $invoice = $this->careService->generateInvoice($workOrder);

        $orderId = 'order_sbx_idem_test';
        $signature = hash_hmac('sha256', $orderId.'|'.$invoice->total_amount, config('app.key', 'secret_key'));
        $idempotencyKey = (string) Str::uuid();

        // First payment call
        $this->careService->processInvoicePayment($invoice, $owner, [
            'gateway_order_id' => $orderId,
            'gateway_payment_id' => 'pay_idem_1',
            'gateway_signature' => $signature,
            'idempotency_key' => $idempotencyKey,
        ]);

        $this->assertEquals(CareInvoice::STATUS_PAID, $invoice->fresh()->payment_status);
        $payoutCount = VendorPayout::where('care_invoice_id', $invoice->id)->count();
        $this->assertEquals(1, $payoutCount);

        // Duplicate call with same idempotency key
        $this->careService->processInvoicePayment($invoice, $owner, [
            'gateway_order_id' => $orderId,
            'gateway_payment_id' => 'pay_idem_2',
            'gateway_signature' => $signature,
            'idempotency_key' => $idempotencyKey,
        ]);

        // Payout count must remain 1
        $this->assertEquals(1, VendorPayout::where('care_invoice_id', $invoice->id)->count());
    }

    // 18. Vendor payout is created only under valid conditions
    public function test_18_vendor_payout_is_created_only_under_valid_conditions(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Tile repair',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 2000]);
        $workOrder = $this->careService->approveQuote($quote, $owner);
        $invoice = $this->careService->generateInvoice($workOrder);

        // Before payment, zero payouts exist
        $this->assertEquals(0, VendorPayout::where('care_invoice_id', $invoice->id)->count());

        // Process payment
        $orderId = 'order_sbx_payout_test';
        $signature = hash_hmac('sha256', $orderId.'|'.$invoice->total_amount, config('app.key', 'secret_key'));
        $this->careService->processInvoicePayment($invoice, $owner, [
            'gateway_order_id' => $orderId,
            'gateway_payment_id' => 'pay_payout_1',
            'gateway_signature' => $signature,
        ]);

        $payout = VendorPayout::where('care_invoice_id', $invoice->id)->first();
        $this->assertNotNull($payout);
        $this->assertEquals(2000.00, $payout->gross_amount);
        $this->assertEquals(200.00, $payout->platform_fee); // 10% commission
        $this->assertEquals(1800.00, $payout->net_amount);
        $this->assertEquals(VendorPayout::STATUS_PENDING, $payout->status);
    }

    // 19. Care request does NOT create ManagementEnrollment
    public function test_19_care_request_does_not_create_management_enrollment(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner, ['is_managed' => false]);
        $category = CareServiceCategory::first();

        $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Inspection test',
        ]);

        $this->assertFalse($property->fresh()->is_managed);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    // 20. Quote approval does NOT create ManagementEnrollment
    public function test_20_quote_approval_does_not_create_management_enrollment(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner, ['is_managed' => false]);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Kitchen tap fix',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 500]);
        $this->careService->approveQuote($quote, $owner);

        $this->assertFalse($property->fresh()->is_managed);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    // 21. Work completion does NOT create ManagementEnrollment
    public function test_21_work_completion_does_not_create_management_enrollment(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner, ['is_managed' => false]);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Wall crack repair',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 700]);
        $workOrder = $this->careService->approveQuote($quote, $owner);
        $this->careService->startWorkOrder($workOrder, $vendor);
        $this->careService->completeWorkOrder($workOrder, $vendor);

        $this->assertFalse($property->fresh()->is_managed);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    // 22. Care payment does NOT create ManagementEnrollment
    public function test_22_care_payment_does_not_create_management_enrollment(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner, ['is_managed' => false]);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($owner, [
            'service_category_id' => $category->id,
            'property_id' => $property->id,
            'description' => 'Light fitting installation',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 450]);
        $workOrder = $this->careService->approveQuote($quote, $owner);
        $invoice = $this->careService->generateInvoice($workOrder);

        $orderId = 'order_sbx_pm_inv_test';
        $signature = hash_hmac('sha256', $orderId.'|'.$invoice->total_amount, config('app.key', 'secret_key'));
        $this->careService->processInvoicePayment($invoice, $owner, [
            'gateway_order_id' => $orderId,
            'gateway_payment_id' => 'pay_pm_1',
            'gateway_signature' => $signature,
        ]);

        $this->assertFalse($property->fresh()->is_managed);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    // 23. Self-managed owner remains self-managed
    public function test_23_self_managed_owner_remains_self_managed(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner, ['is_managed' => false]);

        $this->assertFalse($property->is_managed);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    // 24. Explicit management enrollment remains the only way to activate Property Management
    public function test_24_explicit_management_enrollment_remains_the_only_way_to_activate_management(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner, ['is_managed' => false]);

        $this->actingAs($owner);
        $plan = ManagementPlan::first();

        // 1. Request enrollment
        $this->post(route('management.request', $property->id), [
            'management_plan_id' => $plan->id,
        ]);

        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();
        $this->assertNotNull($enrollment);
        $this->assertEquals(ManagementEnrollment::STATUS_REQUESTED, $enrollment->status);
        $this->assertFalse($property->fresh()->is_managed);

        // 2. Accept agreement
        $this->post(route('management.agreement.accept', $enrollment->id), [
            'confirm_terms' => 1,
        ]);

        $this->assertEquals(ManagementEnrollment::STATUS_ACTIVE, $enrollment->fresh()->status);
        $this->assertTrue($property->fresh()->is_managed);
    }

    // 25. Contact Access paywall remains intact
    public function test_25_contact_access_paywall_remains_intact(): void
    {
        $owner = $this->createOwner();
        $property = $this->createProperty($owner);

        // Guest sees masked contact
        $response = $this->get(route('properties.show', $property->slug));
        $response->assertStatus(200);
        $response->assertDontSee($owner->phone);
        $response->assertDontSee($owner->email);
    }

    // 26. seller_phone remains protected
    public function test_26_seller_phone_remains_protected(): void
    {
        $owner = $this->createOwner(['phone' => '9876543210']);
        $property = $this->createProperty($owner);
        $tenant = $this->createTenant();

        $this->actingAs($tenant);
        $response = $this->get(route('properties.show', $property->slug));
        $response->assertStatus(200);
        $response->assertDontSee('9876543210');
    }

    // 27. seller_email remains protected
    public function test_27_seller_email_remains_protected(): void
    {
        $owner = $this->createOwner(['email' => 'supersecretowner@housing365.test']);
        $property = $this->createProperty($owner);
        $tenant = $this->createTenant();

        $this->actingAs($tenant);
        $response = $this->get(route('properties.show', $property->slug));
        $response->assertStatus(200);
        $response->assertDontSee('supersecretowner@housing365.test');
    }

    // 28. Prime does not bypass Contact Access
    public function test_28_prime_does_not_bypass_contact_access(): void
    {
        $owner = $this->createOwner(['phone' => '9870011223']);
        $property = $this->createProperty($owner);
        $primeTenant = $this->createTenant(['is_prime' => true, 'prime_plan' => 'gold']);

        $this->actingAs($primeTenant);
        $response = $this->get(route('properties.show', $property->slug));
        $response->assertStatus(200);
        $response->assertDontSee('9870011223');
    }

    // 29. Multi-role authorization remains intact
    public function test_29_multi_role_authorization_remains_intact(): void
    {
        $user = $this->createOwner();
        $this->actingAs($user);

        // Owner cannot access vendor portal
        $response = $this->get(route('dashboard.vendor'));
        $response->assertStatus(403);
    }

    // 30. Organization membership cannot bypass authorization
    public function test_30_organization_membership_cannot_bypass_authorization(): void
    {
        $user = $this->createOwner(); // User only has owner role
        $org = Organization::create([
            'name' => 'Apex Realty Agency',
            'slug' => 'apex-realty-agency',
            'type' => 'broker',
        ]);
        $org->members()->attach($user->id, [
            'role' => 'member',
            'is_active' => true,
        ]);

        $this->actingAs($user);
        // Organization membership does NOT grant broker role
        $response = $this->get(route('dashboard.broker'));
        $response->assertStatus(403);
    }

    // 31. Cross-property access is blocked
    public function test_31_cross_property_access_is_blocked(): void
    {
        $ownerA = $this->createOwner();
        $ownerB = $this->createOwner();
        $propertyB = $this->createProperty($ownerB);
        $category = CareServiceCategory::first();

        $this->actingAs($ownerA);

        // Owner A cannot request Care on Owner B's property
        $this->expectException(InvalidArgumentException::class);
        $this->careService->createRequest($ownerA, [
            'service_category_id' => $category->id,
            'property_id' => $propertyB->id,
            'description' => 'Unauthorized repair request',
        ]);
    }

    // 32. Direct URL manipulation is blocked
    public function test_32_direct_url_manipulation_is_blocked(): void
    {
        $ownerA = $this->createOwner();
        $ownerB = $this->createOwner();
        $propertyA = $this->createProperty($ownerA);
        $vendor = $this->createVendor();
        $category = CareServiceCategory::first();

        $request = $this->careService->createRequest($ownerA, [
            'service_category_id' => $category->id,
            'property_id' => $propertyA->id,
            'description' => 'Private leak',
        ]);

        $this->careService->assignVendor($request, $vendor);
        $quote = $this->careService->submitQuote($request, $vendor, ['labour_amount' => 500]);

        // Owner B attempts direct GET/POST to Owner A's quote endpoints
        $this->actingAs($ownerB);
        $response = $this->post(route('care.quotes.decline', $quote->id));
        $response->assertStatus(403);
    }
}
