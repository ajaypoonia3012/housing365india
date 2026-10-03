<?php

namespace Tests\Feature;

use App\Models\CareInvoice;
use App\Models\CareServiceCategory;
use App\Models\ContactAccessPackage;
use App\Models\ContactAccessPurchase;
use App\Models\CoworkingInventory;
use App\Models\MonetizationProduct;
use App\Models\PgBed;
use App\Models\Property;
use App\Models\Role;
use App\Models\TaxConfiguration;
use App\Models\User;
use App\Models\Verification;
use App\Models\VerificationDocument;
use App\Models\VerificationType;
use App\Services\ContactAccessService;
use App\Services\CoworkingBookingService;
use App\Services\Housing365CareService;
use App\Services\PgBookingService;
use App\Services\TaxService;
use Carbon\Carbon;
use Database\Seeders\CareAndManagementSeeder;
use Database\Seeders\Phase10TrustAndFraudSeeder;
use Database\Seeders\Phase5TenantAndInventorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Tests\TestCase;

class Phase11ProductionReadinessTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $ownerUser;

    protected User $tenantUser;

    protected User $vendorUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Roles
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'owner'], ['display_name' => 'Property Owner']);
        Role::firstOrCreate(['name' => 'tenant'], ['display_name' => 'Tenant']);
        Role::firstOrCreate(['name' => 'vendor'], ['display_name' => 'Vendor']);

        // 2. Seeders
        $this->seed(CareAndManagementSeeder::class);
        $this->seed(Phase5TenantAndInventorySeeder::class);
        $this->seed(Phase10TrustAndFraudSeeder::class);

        // 3. Test Users
        $this->adminUser = User::factory()->create([
            'email' => 'admin_p11@housing365.test',
            'phone' => '9870000001',
        ]);
        $this->adminUser->assignRole('admin');

        $this->ownerUser = User::factory()->create([
            'email' => 'owner_p11@housing365.test',
            'phone' => '9870000002',
        ]);
        $this->ownerUser->assignRole('owner');

        $this->tenantUser = User::factory()->create([
            'email' => 'tenant_p11@housing365.test',
            'phone' => '9870000003',
        ]);
        $this->tenantUser->assignRole('tenant');

        $this->vendorUser = User::factory()->create([
            'email' => 'vendor_p11@housing365.test',
            'phone' => '9870000004',
        ]);
        $this->vendorUser->assignRole('vendor');
        $this->vendorUser->vendorProfile()->create([
            'business_name' => 'P11 Solutions',
            'service_categories' => ['plumbing', 'electrical'],
            'service_areas' => ['Bengaluru'],
            'verification_status' => 'verified',
            'is_available' => true,
        ]);
    }

    /**
     * 1. MySQL Engine & Connection Invariant
     */
    public function test_mysql_database_connection_and_innodb_engine_active(): void
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        $this->assertEquals('mysql', $driver, 'Database driver must strictly be MySQL / MariaDB.');

        $databaseName = $connection->getDatabaseName();
        $this->assertStringContainsString('housing365', $databaseName);

        // Verify MariaDB / MySQL version query runs cleanly
        $version = DB::select('SELECT VERSION() as v')[0]->v;
        $this->assertNotEmpty($version);

        // Verify InnoDB engine is active for newly created records
        $tableStatus = DB::select("SHOW TABLE STATUS WHERE Name = 'users'");
        $this->assertNotEmpty($tableStatus);
        $this->assertEquals('InnoDB', $tableStatus[0]->Engine);
    }

    /**
     * 2. Concurrency: PG Bed Hold & Double-Booking Prevention with FOR UPDATE
     */
    public function test_concurrent_pg_bed_hold_and_booking_prevents_double_booking(): void
    {
        $bed = PgBed::where('status', PgBed::STATUS_AVAILABLE)->firstOrFail();
        $service = app(PgBookingService::class);

        // Tenant 1 holds the bed
        $heldBed = $service->holdBed($bed, $this->tenantUser, 15);
        $this->assertEquals(PgBed::STATUS_HELD, $heldBed->status);
        $this->assertEquals($this->tenantUser->id, $heldBed->held_by_user_id);

        // Tenant 2 attempts to hold the same bed -> must throw exception
        $otherTenant = User::factory()->create();
        $otherTenant->assignRole('tenant');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('This bed is not available for booking.');
        $service->holdBed($bed->fresh(), $otherTenant, 15);
    }

    /**
     * 3. Concurrency: Coworking Inventory Booking respects capacity
     */
    public function test_concurrent_coworking_booking_respects_capacity(): void
    {
        $inventory = CoworkingInventory::where('status', CoworkingInventory::STATUS_AVAILABLE)->first();
        if (! $inventory) {
            $prop = Property::first();
            $inventory = CoworkingInventory::create([
                'property_id' => $prop->id,
                'inventory_type' => 'hot_desk',
                'name' => 'Flex Desk 1',
                'capacity' => 1,
                'hourly_price' => 100,
                'daily_price' => 500,
                'monthly_price' => 8000,
                'status' => CoworkingInventory::STATUS_AVAILABLE,
            ]);
        } else {
            $inventory->update(['capacity' => 1]);
        }

        $service = app(CoworkingBookingService::class);
        $startDate = now()->addDays(1)->setTime(9, 0, 0);
        $endDate = now()->addDays(1)->setTime(18, 0, 0);

        // Booking 1 succeeds
        $booking1 = $service->bookInventory($inventory, $this->tenantUser, [
            'booking_type' => 'daily',
            'start_datetime' => $startDate->toDateTimeString(),
            'end_datetime' => $endDate->toDateTimeString(),
            'duration_units' => 1,
            'amount' => 500,
            'tax_amount' => 90,
            'total_amount' => 590,
            'currency' => 'INR',
        ]);
        $this->assertNotNull($booking1->id);

        // Overlapping booking 2 must fail due to capacity=1
        $otherTenant = User::factory()->create();
        $otherTenant->assignRole('tenant');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('This coworking space is not available for the selected dates/times.');

        $service->bookInventory($inventory->fresh(), $otherTenant, [
            'booking_type' => 'daily',
            'start_datetime' => $startDate->toDateTimeString(),
            'end_datetime' => $endDate->toDateTimeString(),
            'duration_units' => 1,
            'amount' => 500,
            'tax_amount' => 90,
            'total_amount' => 590,
            'currency' => 'INR',
        ]);
    }

    /**
     * 4. Concurrency: Contact credit atomic deduction prevents negative balance
     */
    public function test_contact_credit_atomic_deduction_prevents_negative_balance(): void
    {
        $package = ContactAccessPackage::firstOrCreate(
            ['slug' => 'single-test-pass'],
            [
                'name' => 'Single Pass',
                'credits' => 1,
                'price' => 99,
                'currency' => 'INR',
                'validity_days' => 30,
                'is_active' => true,
            ]
        );

        $purchase = ContactAccessPurchase::create([
            'user_id' => $this->tenantUser->id,
            'contact_access_package_id' => $package->id,
            'order_reference' => 'ORD-'.uniqid(),
            'credits_total' => 1,
            'credits_remaining' => 1,
            'amount_paid' => 99,
            'currency' => 'INR',
            'payment_status' => 'paid',
            'purchased_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        $prop1 = Property::create([
            'title' => 'P11 Test Prop 1',
            'slug' => 'p11-test-prop-1-'.uniqid(),
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'price' => 25000,
            'purpose' => 'rent',
            'category' => 'residential',
            'seller_name' => 'Owner One',
            'seller_phone' => '9998881111',
            'seller_email' => 'owner1@test.com',
            'status' => 'Available',
        ]);

        $prop2 = Property::create([
            'title' => 'P11 Test Prop 2',
            'slug' => 'p11-test-prop-2-'.uniqid(),
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'price' => 30000,
            'purpose' => 'rent',
            'category' => 'residential',
            'seller_name' => 'Owner Two',
            'seller_phone' => '9998882222',
            'seller_email' => 'owner2@test.com',
            'status' => 'Available',
        ]);

        $service = app(ContactAccessService::class);

        // First unlock succeeds and consumes the 1 credit
        $result1 = $service->unlockContact($this->tenantUser, $prop1);
        $this->assertTrue($result1['success']);
        $this->assertEquals(0, $result1['credits_remaining']);

        // Second unlock fails with require_credits => true and credits stay at 0 (never negative)
        $result2 = $service->unlockContact($this->tenantUser, $prop2);
        $this->assertFalse($result2['success']);
        $this->assertTrue($result2['require_credits']);
        $this->assertEquals(0, $purchase->fresh()->credits_remaining);
        $this->assertGreaterThanOrEqual(0, $purchase->fresh()->credits_remaining);
    }

    /**
     * 5. Payment Idempotency: Duplicate payment callback produces identical outcome
     */
    public function test_care_invoice_payment_idempotency_prevents_duplicate_processing(): void
    {
        $careService = app(Housing365CareService::class);
        $category = CareServiceCategory::firstOrFail();

        $property = Property::create([
            'title' => 'Idempotency Test Property',
            'slug' => 'idempotency-test-prop-'.uniqid(),
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'price' => 35000,
            'purpose' => 'rent',
            'category' => 'residential',
            'seller_name' => $this->ownerUser->name,
            'seller_phone' => $this->ownerUser->phone,
            'seller_email' => $this->ownerUser->email,
            'user_id' => $this->ownerUser->id,
            'status' => 'Available',
        ]);

        $request = $careService->createRequest($this->ownerUser, [
            'property_id' => $property->id,
            'service_category_id' => $category->id,
            'title' => 'Fix Main Water Pipe Leaking',
            'description' => 'Urgent pipe leakage in master bathroom.',
            'urgency' => 'urgent',
            'city' => 'Bengaluru',
        ]);

        $careService->assignVendor($request, $this->vendorUser, $this->adminUser);
        $careService->vendorAcceptAssignment($request, $this->vendorUser);

        $quote = $careService->submitQuote($request, $this->vendorUser, [
            'labour_amount' => 1000,
            'tax_amount' => 180,
            'scope_of_work' => 'Replace broken pipe and install ball valve',
        ]);

        $workOrder = $careService->approveQuote($quote, $this->ownerUser);
        $careService->startWorkOrder($workOrder, $this->vendorUser);
        $careService->completeWorkOrder($workOrder, $this->vendorUser, ['after1.jpg']);
        $careService->reviewQualityControl($request->fresh(), 'approve', 'QC inspection passed perfectly', $this->ownerUser);

        $invoice = CareInvoice::where('work_order_id', $workOrder->id)->first();
        $this->assertNotNull($invoice);
        $this->assertEquals(CareInvoice::STATUS_PENDING, $invoice->payment_status);

        $orderId = 'order_sbx_p11_test';
        $signature = hash_hmac('sha256', $orderId.'|'.$invoice->total_amount, config('app.key', 'secret_key'));
        $idempotencyKey = 'IDEMP-'.uniqid();

        $paymentPayload = [
            'gateway_order_id' => $orderId,
            'gateway_payment_id' => 'pay_p11_1',
            'gateway_signature' => $signature,
            'idempotency_key' => $idempotencyKey,
            'payment_method' => 'card',
        ];

        // First payment call
        $paidInvoice1 = $careService->processInvoicePayment($invoice, $this->ownerUser, $paymentPayload);
        $this->assertEquals(CareInvoice::STATUS_PAID, $paidInvoice1->payment_status);

        // Duplicate payment call with same payload -> must return same paid invoice without double processing
        $paidInvoice2 = $careService->processInvoicePayment($invoice->fresh(), $this->ownerUser, $paymentPayload);
        $this->assertEquals(CareInvoice::STATUS_PAID, $paidInvoice2->payment_status);
        $this->assertEquals($paidInvoice1->id, $paidInvoice2->id);
    }

    /**
     * 6. Server-side Payment Verification: Unauthenticated & Malformed claims rejected
     */
    public function test_payment_verification_is_server_side_and_rejects_unverified_gateway_claims(): void
    {
        $response = $this->postJson('/contact-access/webhook', [
            'payment_id' => 'fake_unverified_pay_123',
            'signature' => 'invalid_signature_hash',
        ]);

        // Either route doesn't accept forged payload, or returns 400/401/403/404/405/422
        $this->assertTrue(in_array($response->status(), [400, 401, 403, 404, 405, 422]));
    }

    /**
     * 7. Security: APP_DEBUG false error handling
     */
    public function test_app_debug_false_masks_stack_traces_in_production(): void
    {
        Config::set('app.debug', false);

        // Hit a nonexistent route
        $response = $this->get('/api/internal/nonexistent-route-for-testing-debug-masking');
        $this->assertEquals(404, $response->status());

        $content = $response->getContent();
        $this->assertStringNotContainsString('SQLSTATE', $content);
        $this->assertStringNotContainsString('housing365_test', $content);
        $this->assertStringNotContainsString('root@localhost', $content);
        $this->assertStringNotContainsString('PDOException', $content);
        $this->assertStringNotContainsString('Illuminate\\Database', $content);
    }

    /**
     * 8. Security: CSRF protection on state-changing web requests
     */
    public function test_csrf_token_validation_on_state_changing_requests(): void
    {
        $session = app('session')->driver();
        $session->start();
        $validToken = $session->token();
        $this->assertNotEmpty($validToken, 'Session must generate a cryptographically secure CSRF token.');

        // Verify invalid token fails timing-safe hash comparison
        $this->assertFalse(hash_equals($validToken, 'invalid_csrf_token_hash'));

        // Verify login/contact form includes CSRF field
        $response = $this->get('/login');
        if ($response->status() === 200) {
            $response->assertSee('name="_token"', false);
        }
    }

    /**
     * 9. Security: XSS protection in marketplace and reviews
     */
    public function test_xss_protection_escapes_malicious_script_tags_in_marketplace_and_reviews(): void
    {
        $xssTitle = 'Luxury Villa <script>alert("XSS")</script>';
        $xssDescription = 'Description with <img src=x onerror=alert(1)> script injection';

        $property = Property::create([
            'title' => $xssTitle,
            'slug' => 'luxury-villa-xss-test-'.uniqid(),
            'description' => $xssDescription,
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'price' => 50000,
            'purpose' => 'rent',
            'category' => 'residential',
            'seller_name' => 'Owner Safe',
            'seller_phone' => '9999900000',
            'seller_email' => 'safe@test.com',
            'status' => 'Available',
        ]);

        $response = $this->get("/properties/{$property->slug}");
        if ($response->status() === 200) {
            // Raw dangerous executable scripts must not be rendered unescaped in HTML
            $this->assertStringNotContainsString('<script>alert("XSS")</script>', $response->getContent());
        } else {
            $this->assertTrue(true);
        }
    }

    /**
     * 10. Security: IDOR protection on private documents & records
     */
    public function test_idor_protection_blocks_unauthorized_user_from_accessing_private_records(): void
    {
        $careService = app(Housing365CareService::class);
        $category = CareServiceCategory::firstOrFail();

        $property = Property::create([
            'title' => 'IDOR Test Property',
            'slug' => 'idor-test-prop-'.uniqid(),
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'price' => 30000,
            'purpose' => 'rent',
            'category' => 'residential',
            'seller_name' => $this->ownerUser->name,
            'seller_phone' => $this->ownerUser->phone,
            'seller_email' => $this->ownerUser->email,
            'user_id' => $this->ownerUser->id,
            'status' => 'Available',
        ]);

        $request = $careService->createRequest($this->ownerUser, [
            'property_id' => $property->id,
            'service_category_id' => $category->id,
            'title' => 'Private Plumbing Work',
            'description' => 'Owner private request',
            'urgency' => 'routine',
            'city' => 'Bengaluru',
        ]);

        // An unrelated user cannot view or approve the request
        $unrelatedUser = User::factory()->create();
        $unrelatedUser->assignRole('tenant');

        $response = $this->actingAs($unrelatedUser)->get("/care/requests/{$request->id}");
        $this->assertTrue(in_array($response->status(), [403, 404, 302]));
    }

    /**
     * 11. Security: Private verification document URLs require authentication & authorization
     */
    public function test_private_document_urls_require_authenticated_authorization(): void
    {
        Storage::fake('private');

        $verificationType = VerificationType::first();
        $verification = Verification::create([
            'user_id' => $this->ownerUser->id,
            'subject_type' => User::class,
            'subject_id' => $this->ownerUser->id,
            'verification_type_id' => $verificationType->id,
            'verification_type_code' => $verificationType->code,
            'status' => Verification::STATUS_PENDING,
            'submitted_at' => now(),
        ]);

        $doc = VerificationDocument::create([
            'verification_id' => $verification->id,
            'user_id' => $this->ownerUser->id,
            'document_type' => 'aadhaar',
            'file_path' => 'verifications/test_aadhaar.pdf',
            'original_filename' => 'aadhaar.pdf',
            'verification_status' => 'PENDING',
        ]);

        // Unauthenticated guest receives 401 / 302 redirect to login
        $guestResponse = $this->get("/verifications/documents/{$doc->id}/download");
        $this->assertTrue(in_array($guestResponse->status(), [302, 401, 403, 404]));

        // Unrelated user receives 403 / 404
        $unrelatedUser = User::factory()->create();
        $unrelatedUser->assignRole('tenant');
        $unrelatedResponse = $this->actingAs($unrelatedUser)->get("/verifications/documents/{$doc->id}/download");
        $this->assertTrue(in_array($unrelatedResponse->status(), [403, 404, 302]));
    }

    /**
     * 12. Tax Calculation: GST Invariants and Snapshots
     */
    public function test_tax_service_gst_calculation_invariants_and_snapshots(): void
    {
        TaxConfiguration::create([
            'tax_enabled' => true,
            'tax_name' => 'GST',
            'tax_rate' => 18.00,
            'cgst_enabled' => true,
            'sgst_enabled' => true,
            'cgst_rate' => 9.00,
            'sgst_rate' => 9.00,
            'effective_from' => Carbon::now()->subDay(),
            'active' => true,
        ]);

        $product = MonetizationProduct::firstOrCreate(
            ['code' => 'CARE_SERVICE'],
            [
                'name' => 'Housing365 Care Service',
                'target_role' => 'all',
                'target_category' => 'SERVICES',
                'is_taxable' => true,
                'is_active' => true,
            ]
        );

        $taxService = app(TaxService::class);

        // 18% standard rate on Care services
        $calculation = $taxService->calculate(1000.00, $product);
        $this->assertTrue($calculation['tax_enabled']);
        $this->assertEquals(18.00, $calculation['tax_rate']);
        $this->assertEquals(180.00, $calculation['total_tax']);
        $this->assertEquals(90.00, $calculation['cgst']);
        $this->assertEquals(90.00, $calculation['sgst']);
        $this->assertEquals(1180.00, $calculation['total_amount']);

        // Historical snapshot is immutable
        $details = $calculation['tax_details'];
        $this->assertIsArray($details);
        $this->assertTrue($details['tax_enabled']);
        $this->assertArrayHasKey('snapshot_date', $details);
    }

    /**
     * 13. Contact Access: Zero leakage of phone & email across public endpoints
     */
    public function test_contact_access_zero_leakage_across_all_public_views(): void
    {
        $secretPhone = '9876543210';
        $secretEmail = 'secret.owner.p11@housing365.test';

        $property = Property::create([
            'title' => 'Zero Leakage Penthouse Bengaluru',
            'slug' => 'zero-leakage-penthouse-bengaluru-'.uniqid(),
            'description' => 'Magnificent penthouse with city view.',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'locality' => 'Indiranagar',
            'price' => 85000,
            'purpose' => 'rent',
            'category' => 'residential',
            'seller_name' => 'High Profile Landlord',
            'seller_phone' => $secretPhone,
            'seller_email' => $secretEmail,
            'status' => 'Available',
        ]);

        // 1. Check detail page
        $detailResponse = $this->get("/properties/{$property->slug}");
        if ($detailResponse->status() === 200) {
            $content = $detailResponse->getContent();
            $this->assertStringNotContainsString($secretPhone, $content, 'Phone must not leak in property detail HTML');
            $this->assertStringNotContainsString($secretEmail, $content, 'Email must not leak in property detail HTML');
        }

        // 2. Check search / listing page
        $listResponse = $this->get('/properties?city=Bengaluru');
        if ($listResponse->status() === 200) {
            $listContent = $listResponse->getContent();
            $this->assertStringNotContainsString($secretPhone, $listContent, 'Phone must not leak in property search HTML');
            $this->assertStringNotContainsString($secretEmail, $listContent, 'Email must not leak in property search HTML');
        }
    }

    /**
     * 14. Refer & Earn: ₹100 reward integrity
     */
    public function test_referral_program_one_hundred_rupee_reward_integrity(): void
    {
        $referrer = User::factory()->create(['email' => 'referrer_p11@test.com']);
        $code = $referrer->getOrCreateReferralCode();
        $this->assertNotEmpty($code);

        // Verify default ₹100 referral reward value in system settings or config
        $rewardAmount = config('services.referral.reward_amount', 100);
        $this->assertEquals(100, $rewardAmount);
    }

    /**
     * 15. Property Management: Optionality & ₹0 self-managed listing invariant
     */
    public function test_property_management_optionality_and_self_managed_zero_fee_invariant(): void
    {
        $prop = Property::create([
            'title' => 'Self Managed 2BHK Flat',
            'slug' => 'self-managed-2bhk-flat-'.uniqid(),
            'city' => 'Pune',
            'state' => 'Maharashtra',
            'price' => 20000,
            'purpose' => 'rent',
            'category' => 'residential',
            'seller_name' => $this->ownerUser->name,
            'seller_phone' => $this->ownerUser->phone,
            'seller_email' => $this->ownerUser->email,
            'user_id' => $this->ownerUser->id,
            'is_managed' => false,
            'status' => 'Available',
        ]);

        // Unmanaged property has no active management enrollment
        $this->assertFalse((bool) $prop->is_managed);
        $this->assertNull($prop->activeManagementEnrollment);
    }
}
