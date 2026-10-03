<?php

namespace Tests\Feature;

use App\Models\CoworkingBooking;
use App\Models\CoworkingInventory;
use App\Models\ManagementEnrollment;
use App\Models\PgBed;
use App\Models\PgBooking;
use App\Models\PgRoom;
use App\Models\Property;
use App\Models\RentLedger;
use App\Models\RentPayment;
use App\Models\Role;
use App\Models\TenantApplication;
use App\Models\TenantScreening;
use App\Models\User;
use App\Models\VerificationDocument;
use App\Services\CoworkingBookingService;
use App\Services\PgBookingService;
use App\Services\RentPaymentService;
use App\Services\TenantScreeningService;
use Database\Seeders\CareAndManagementSeeder;
use Database\Seeders\Phase5TenantAndInventorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Phase5TenantLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'owner'], ['display_name' => 'Property Owner']);
        Role::firstOrCreate(['name' => 'tenant'], ['display_name' => 'Tenant']);
        $this->seed(CareAndManagementSeeder::class);
        $this->seed(Phase5TenantAndInventorySeeder::class);
    }

    protected function createOwner(array $overrides = []): User
    {
        $owner = User::factory()->create(array_merge([
            'email' => 'test_owner_'.uniqid().'@housing365.test',
            'phone' => '98700'.rand(10000, 99999),
        ], $overrides));
        $owner->assignRole('owner');

        return $owner;
    }

    protected function createTenant(array $overrides = []): User
    {
        $tenant = User::factory()->create(array_merge([
            'email' => 'test_tenant_'.uniqid().'@housing365.test',
            'phone' => '98800'.rand(10000, 99999),
        ], $overrides));
        $tenant->assignRole('tenant');

        return $tenant;
    }

    protected function createProperty(User $owner, array $overrides = []): Property
    {
        return Property::create(array_merge([
            'title' => 'Greenwood Residency Apartment',
            'slug' => 'greenwood-residency-'.uniqid(),
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'locality' => 'Whitefield',
            'price' => 38000,
            'deposit_amount' => 100000,
            'purpose' => 'rent',
            'category' => 'residential',
            'status' => 'Available',
            'user_id' => $owner->id,
            'seller_name' => $owner->name,
            'seller_email' => $owner->email,
            'seller_phone' => $owner->phone,
            'is_managed' => false,
        ], $overrides));
    }

    /**
     * 1. Tenant Screening: Application creation and initial consent requirement.
     */
    public function test_tenant_submits_rental_application_and_initiates_consent_gated_screening(): void
    {
        $owner = $this->createOwner();
        $tenant = $this->createTenant();
        $property = $this->createProperty($owner);

        $response = $this->actingAs($tenant)->post(route('tenant.apply', $property->id), [
            'monthly_income' => 95000,
            'occupation' => 'Software Engineer',
            'employer_name' => 'Tech Corp India',
            'tenancy_months' => 11,
            'proposed_move_in' => date('Y-m-d', strtotime('+10 days')),
            'notes' => 'Family of two, looking for long-term lease.',
        ]);

        $this->assertDatabaseHas('tenant_applications', [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'monthly_income' => 95000,
            'status' => TenantApplication::STATUS_SUBMITTED,
        ]);

        $application = TenantApplication::where('tenant_id', $tenant->id)->first();
        $this->assertNotNull($application);
        $this->assertNotNull($application->screening);
        $this->assertEquals(TenantScreening::STATUS_CONSENT_REQUIRED, $application->screening->status);

        $response->assertRedirect(route('tenant.screenings.show', $application->screening->id));
    }

    /**
     * 2. Consent-First Verification: Explicit consent capture with IP and timestamp.
     */
    public function test_tenant_grants_explicit_consent_for_background_screening(): void
    {
        $owner = $this->createOwner();
        $tenant = $this->createTenant();
        $property = $this->createProperty($owner);

        $screeningService = app(TenantScreeningService::class);
        $application = $screeningService->createApplication($tenant, $property, ['monthly_income' => 80000]);
        $screening = $application->screening;

        $response = $this->actingAs($tenant)->post(route('tenant.screenings.consent', $screening->id), [
            'confirm_consent' => '1',
            'consent_purpose' => 'Tenancy KYC and Criminal Background Verification',
        ]);

        $response->assertSessionHas('success');

        $freshScreening = $screening->fresh();
        $this->assertEquals(TenantScreening::STATUS_CONSENTED, $freshScreening->status);
        $this->assertNotNull($freshScreening->consent_granted_at);
        $this->assertNotNull($freshScreening->consent_ip_address);
    }

    /**
     * 3. Document Verification & OCR: Private file upload, masked identifier, and OCR simulation.
     */
    public function test_tenant_uploads_document_and_ocr_extracts_masked_identity(): void
    {
        Storage::fake('local');

        $owner = $this->createOwner();
        $tenant = $this->createTenant();
        $property = $this->createProperty($owner);

        $screeningService = app(TenantScreeningService::class);
        $application = $screeningService->createApplication($tenant, $property, ['monthly_income' => 80000]);
        $screening = $application->screening;
        $screeningService->grantConsent($screening, $tenant, '127.0.0.1', 'KYC');

        $fakeFile = UploadedFile::fake()->create('aadhaar_card.pdf', 500, 'application/pdf');

        $response = $this->actingAs($tenant)->post(route('tenant.screenings.document', $screening->id), [
            'document_type' => 'aadhaar',
            'document_file' => $fakeFile,
            'document_number' => '987654321098',
        ]);

        $response->assertSessionHas('success');

        $document = VerificationDocument::where('tenant_screening_id', $screening->id)->first();
        $this->assertNotNull($document);
        $this->assertEquals('aadhaar', $document->document_type);
        $this->assertEquals('XXXX XXXX 1098', $document->document_number_masked);
        $this->assertEquals(VerificationDocument::STATUS_VERIFIED, $document->verification_status);

        // Verify document is NOT exposed publicly and can only be downloaded by authorized user
        $downloadResponse = $this->actingAs($tenant)->get(route('tenant.documents.download', $document->id));
        $downloadResponse->assertStatus(200);

        // Stranger receives 403 Forbidden
        $stranger = $this->createTenant();
        $forbiddenResponse = $this->actingAs($stranger)->get(route('tenant.documents.download', $document->id));
        $forbiddenResponse->assertStatus(403);
    }

    /**
     * 4. Rent Ledger & Online Payment Settlement with Idempotency.
     */
    public function test_tenant_initiates_and_confirms_rent_payment_with_idempotency(): void
    {
        $owner = $this->createOwner();
        $tenant = $this->createTenant();
        $property = $this->createProperty($owner);

        $rentService = app(RentPaymentService::class);
        $account = $rentService->createRentAccount($property, $owner, $tenant, [
            'monthly_rent' => 38000,
            'security_deposit' => 100000,
        ]);

        $billingPeriod = '2026-11';
        $ledger = $rentService->generateMonthlyLedger($account, $billingPeriod);

        $this->assertEquals(38000, $ledger->amount_due);
        $this->assertEquals(38000, $ledger->outstanding_balance);
        $this->assertEquals(RentLedger::STATUS_DUE, $ledger->status);

        // 1. Tenant initiates payment
        $idempotencyKey = 'IDEMPOTENT-RENT-TEST-123';
        $initResult = $rentService->initiatePayment($ledger, $tenant, 38000, 'upi', $idempotencyKey);
        $payment = $initResult['payment'];

        $this->assertEquals(RentPayment::STATUS_INITIATED, $payment->status);
        $this->assertEquals($idempotencyKey, $payment->idempotency_key);

        // Duplicate initiation returns existing transaction without double charging
        $duplicateResult = $rentService->initiatePayment($ledger, $tenant, 38000, 'upi', $idempotencyKey);
        $this->assertTrue($duplicateResult['is_duplicate']);
        $this->assertEquals($payment->id, $duplicateResult['payment']->id);

        // 2. Server-side payment confirmation callback
        $confirmResponse = $this->actingAs($tenant)->post(route('rent.payments.confirm', $payment->id), [
            'gateway_payment_id' => 'pay_confirmed_999',
            'gateway_signature' => 'sandbox_valid_test_signature',
        ]);
        $confirmResponse->assertRedirect(route('rent.ledgers.show', $ledger->id));

        $freshPayment = $payment->fresh();
        $this->assertEquals(RentPayment::STATUS_PAID, $freshPayment->status);

        $freshLedger = $ledger->fresh();
        $this->assertEquals(38000, $freshLedger->amount_paid);
        $this->assertEquals(0.00, $freshLedger->outstanding_balance);
        $this->assertEquals(RentLedger::STATUS_PAID, $freshLedger->status);
    }

    /**
     * 5. Property Management Isolation: Rent payment does NOT enroll property into Property Management.
     */
    public function test_using_rent_payment_does_not_activate_property_management(): void
    {
        $owner = $this->createOwner();
        $tenant = $this->createTenant();
        $property = $this->createProperty($owner, ['is_managed' => false]);

        $rentService = app(RentPaymentService::class);
        $account = $rentService->createRentAccount($property, $owner, $tenant, ['monthly_rent' => 25000]);
        $ledger = $rentService->generateMonthlyLedger($account, '2026-12');
        $init = $rentService->initiatePayment($ledger, $tenant, 25000);
        $rentService->confirmPayment($init['payment'], ['gateway_payment_id' => 'pay_check_pm_123']);

        // Assert strictly unmanaged
        $this->assertFalse($property->fresh()->is_managed);
        $this->assertEquals(0, ManagementEnrollment::where('property_id', $property->id)->count());
    }

    /**
     * 6. PG Bed-Level Inventory & Double-Booking Prevention.
     */
    public function test_pg_bed_booking_prevents_double_booking_via_transaction_locks(): void
    {
        $owner = $this->createOwner();
        $tenantA = $this->createTenant();
        $tenantB = $this->createTenant();

        $pgProperty = $this->createProperty($owner, ['purpose' => 'pg']);

        $room = PgRoom::create([
            'property_id' => $pgProperty->id,
            'room_number' => '201',
            'room_type' => 'double',
            'monthly_rent_per_bed' => 11000,
            'status' => 'active',
        ]);

        $bed = PgBed::create([
            'pg_room_id' => $room->id,
            'property_id' => $pgProperty->id,
            'bed_code' => 'R201-A',
            'status' => PgBed::STATUS_AVAILABLE,
        ]);

        $pgService = app(PgBookingService::class);

        // Tenant A books the bed successfully
        $bookingA = $pgService->bookBed($bed, $tenantA, [
            'check_in_date' => date('Y-m-d', strtotime('+3 days')),
            'monthly_rent' => 11000,
        ]);

        $this->assertNotNull($bookingA);
        $this->assertEquals(PgBooking::STATUS_CONFIRMED, $bookingA->status);
        $this->assertEquals(PgBed::STATUS_RESERVED, $bed->fresh()->status);

        // Tenant B attempts to book the same bed -> Throws exception (Double booking prevented)
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Selected PG bed is already reserved or occupied.');

        $pgService->bookBed($bed, $tenantB, [
            'check_in_date' => date('Y-m-d', strtotime('+3 days')),
        ]);
    }

    /**
     * 7. Coworking Desk Inventory & Overlapping Reservation Conflict Prevention.
     */
    public function test_coworking_booking_prevents_capacity_conflicts(): void
    {
        $owner = $this->createOwner();
        $tenantA = $this->createTenant();
        $tenantB = $this->createTenant();

        $coworkingProperty = $this->createProperty($owner, ['purpose' => 'coworking']);

        $inventory = CoworkingInventory::create([
            'property_id' => $coworkingProperty->id,
            'inventory_code' => 'POD-1',
            'inventory_type' => 'private_cabin',
            'capacity' => 1,
            'daily_rate' => 1200,
            'status' => CoworkingInventory::STATUS_AVAILABLE,
        ]);

        $coworkingService = app(CoworkingBookingService::class);

        // Tenant A books the pod for tomorrow 9 AM to 6 PM
        $start = date('Y-m-d 09:00:00', strtotime('+1 day'));
        $end = date('Y-m-d 18:00:00', strtotime('+1 day'));

        $bookingA = $coworkingService->bookInventory($inventory, $tenantA, [
            'start_datetime' => $start,
            'end_datetime' => $end,
            'booking_type' => 'daily',
            'duration_units' => 1,
        ]);

        $this->assertNotNull($bookingA);
        $this->assertEquals(CoworkingBooking::STATUS_CONFIRMED, $bookingA->status);

        // Tenant B attempts to book the same time window -> Throws exception
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('This coworking space is not available for the selected dates/times.');

        $coworkingService->bookInventory($inventory, $tenantB, [
            'start_datetime' => date('Y-m-d 12:00:00', strtotime('+1 day')),
            'end_datetime' => date('Y-m-d 15:00:00', strtotime('+1 day')),
            'booking_type' => 'hourly',
            'duration_units' => 3,
        ]);
    }

    /**
     * 8. Contact Access Paywall & Zero Phone/Email Leakage in Phase 5 Views.
     */
    public function test_zero_owner_contact_leakage_in_phase_5_views(): void
    {
        $owner = $this->createOwner([
            'name' => 'High Privacy Landlord',
            'email' => 'private_landlord_99@secret.in',
            'phone' => '+91 91234 56789',
        ]);
        $tenant = $this->createTenant();
        $property = $this->createProperty($owner);

        // 1. Public PG Inventory page
        $pgResponse = $this->get(route('pg.inventory', $property->id));
        $pgResponse->assertStatus(200);
        $pgResponse->assertDontSee('private_landlord_99@secret.in');
        $pgResponse->assertDontSee('+91 91234 56789');
        $pgResponse->assertDontSee('9123456789');

        // 2. Public Coworking Inventory page
        $cwResponse = $this->get(route('coworking.inventory', $property->id));
        $cwResponse->assertStatus(200);
        $cwResponse->assertDontSee('private_landlord_99@secret.in');
        $cwResponse->assertDontSee('+91 91234 56789');

        // 3. Rent Ledger page for tenant
        $rentService = app(RentPaymentService::class);
        $account = $rentService->createRentAccount($property, $owner, $tenant, ['monthly_rent' => 30000]);
        $ledger = $rentService->generateMonthlyLedger($account, '2026-10');

        $ledgerResponse = $this->actingAs($tenant)->get(route('rent.ledgers.show', $ledger->id));
        $ledgerResponse->assertStatus(200);
        $ledgerResponse->assertDontSee('private_landlord_99@secret.in');
        $ledgerResponse->assertDontSee('+91 91234 56789');
    }

    /**
     * 9. Cross-User Security: Tenant A cannot view Tenant B's rent ledger or screening.
     */
    public function test_tenant_a_cannot_view_tenant_b_rent_ledger_or_screening(): void
    {
        $owner = $this->createOwner();
        $tenantA = $this->createTenant();
        $tenantB = $this->createTenant();
        $property = $this->createProperty($owner);

        // Screening for Tenant B
        $screeningService = app(TenantScreeningService::class);
        $applicationB = $screeningService->createApplication($tenantB, $property, ['monthly_income' => 70000]);
        $screeningB = $applicationB->screening;

        // Tenant A tries to view Tenant B's screening -> 403 Forbidden
        $responseScreening = $this->actingAs($tenantA)->get(route('tenant.screenings.show', $screeningB->id));
        $responseScreening->assertStatus(403);

        // Rent ledger for Tenant B
        $rentService = app(RentPaymentService::class);
        $accountB = $rentService->createRentAccount($property, $owner, $tenantB, ['monthly_rent' => 30000]);
        $ledgerB = $rentService->generateMonthlyLedger($accountB, '2026-10');

        // Tenant A tries to view Tenant B's rent ledger -> 403 Forbidden
        $responseLedger = $this->actingAs($tenantA)->get(route('rent.ledgers.show', $ledgerB->id));
        $responseLedger->assertStatus(403);
    }
}
