<?php

namespace Tests\Feature;

use App\Models\CityLocation;
use App\Models\Coupon;
use App\Models\Lead;
use App\Models\MonetizationPlan;
use App\Models\MonetizationProduct;
use App\Models\Property;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Services\AnalyticsService;
use App\Services\LeadManagementService;
use App\Services\MonetizationService;
use App\Services\NotificationService;
use Database\Seeders\Phase9MonetizationAndLeadsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase9MarketplaceGrowthTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $ownerUser;

    protected User $brokerUser;

    protected User $tenantUser;

    protected Property $testProperty;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Ensure Roles
        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'owner'], ['display_name' => 'Property Owner']);
        Role::firstOrCreate(['name' => 'broker'], ['display_name' => 'Broker']);
        Role::firstOrCreate(['name' => 'tenant'], ['display_name' => 'Tenant']);

        // 2. Users
        $this->adminUser = User::factory()->create(['name' => 'Admin User', 'email' => 'admin_p9@housing365.in']);
        $this->adminUser->assignRole('admin');

        $this->ownerUser = User::factory()->create(['name' => 'Owner Rahul', 'email' => 'rahul_p9@example.com']);
        $this->ownerUser->assignRole('owner');

        $this->brokerUser = User::factory()->create(['name' => 'Broker Sharma', 'email' => 'sharma_p9@example.com']);
        $this->brokerUser->assignRole('broker');

        $this->tenantUser = User::factory()->create(['name' => 'Tenant Priya', 'email' => 'priya_p9@example.com']);
        $this->tenantUser->assignRole('tenant');

        // 3. State & City
        $state = State::firstOrCreate(['name' => 'Karnataka', 'slug' => 'karnataka'], ['code' => 'KA']);
        $city = CityLocation::firstOrCreate(
            ['slug' => 'bangalore'],
            [
                'name' => 'Bangalore',
                'state_id' => $state->id,
                'province_or_state' => 'Karnataka',
                'country' => 'IN',
                'tier' => 1,
                'is_active' => true,
            ]
        );

        // 4. Property with Private Owner Contact
        $this->testProperty = Property::create([
            'user_id' => $this->ownerUser->id,
            'title' => 'Luxury 3 BHK Indiranagar',
            'slug' => 'luxury-3-bhk-indiranagar-p9',
            'property_type' => 'Apartment',
            'purpose' => 'rent',
            'state' => 'Karnataka',
            'city' => 'Bangalore',
            'locality' => 'Indiranagar',
            'price' => 55000,
            'status' => 'Available',
            'seller_name' => 'Rahul Owner',
            'seller_phone' => '9876543210',
            'seller_email' => 'secret_owner_p9@example.com',
        ]);

        // Seed monetization products and plans
        $this->seed(Phase9MonetizationAndLeadsSeeder::class);
    }

    /** 1. Lead creation from viewing request */
    public function test_viewing_request_creates_inquiry_and_lead()
    {
        $response = $this->post(route('properties.viewing', $this->testProperty->id), [
            'name' => 'Priya Viewer',
            'phone' => '9988776655',
            'email' => 'priya@example.com',
            'preferred_date' => '2026-10-15',
            'preferred_time' => '10:00 AM',
            'notes' => 'Looking for immediate move-in.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('inquiries', ['phone' => '9988776655']);
        $this->assertDatabaseHas('leads', [
            'contact_phone' => '9988776655',
            'property_id' => $this->testProperty->id,
            'lead_type' => 'residential_rental',
            'status' => Lead::STATUS_ASSIGNED, // Auto-routed to property owner
        ]);
    }

    /** 2. Lead assignment and reassignment audit trail */
    public function test_lead_assignment_creates_immutable_history()
    {
        $leadService = app(LeadManagementService::class);
        $lead = $leadService->createLead([
            'name' => 'Amit Lead',
            'phone' => '9123456789',
            'lead_type' => 'residential_rental',
            'property_id' => $this->testProperty->id,
        ], $this->tenantUser);

        $this->assertEquals($this->ownerUser->id, $lead->assigned_user_id);

        // Reassign to broker
        $leadService->assignLead($lead, $this->brokerUser, $this->adminUser, 'Reassigned to specialist broker');

        $this->assertDatabaseHas('lead_assignments', [
            'lead_id' => $lead->id,
            'assigned_user_id' => $this->brokerUser->id,
            'assigned_by_id' => $this->adminUser->id,
        ]);

        $this->assertDatabaseHas('lead_activities', [
            'lead_id' => $lead->id,
            'activity_type' => 'assignment',
        ]);
    }

    /** 3. Lead status transition history */
    public function test_lead_status_transition_creates_audit_history()
    {
        $leadService = app(LeadManagementService::class);
        $lead = $leadService->createLead([
            'name' => 'Vikas Buyer',
            'phone' => '9111222333',
            'lead_type' => 'property_sale',
        ], $this->tenantUser);

        $leadService->updateStatus($lead, Lead::STATUS_QUALIFIED, $this->brokerUser, 'Client verified pre-approved loan');

        $this->assertDatabaseHas('lead_status_histories', [
            'lead_id' => $lead->id,
            'from_status' => Lead::STATUS_NEW,
            'to_status' => Lead::STATUS_QUALIFIED,
            'changed_by_id' => $this->brokerUser->id,
        ]);
    }

    /** 4. SLA calculation and response tracking */
    public function test_sla_calculation_and_breach_tracking()
    {
        $leadService = app(LeadManagementService::class);

        // 30 min SLA for urgent rental
        $lead = $leadService->createLead([
            'name' => 'Urgent Tenant',
            'phone' => '9998887776',
            'lead_type' => 'residential_rental',
            'priority' => 'urgent',
        ]);

        $this->assertNotNull($lead->sla_deadline_at);
        $this->assertEquals('pending', $lead->sla_status);

        // Update status to contacted
        $leadService->updateStatus($lead, Lead::STATUS_CONTACTED, $this->ownerUser);

        $fresh = $lead->fresh();
        $this->assertNotNull($fresh->first_response_at);
        $this->assertEquals('achieved', $fresh->sla_status);
    }

    /** 5. Lead authorization & isolation */
    public function test_unauthorized_user_cannot_access_or_update_lead()
    {
        $lead = Lead::create([
            'contact_name' => 'Confidential Lead',
            'contact_phone' => '9000000001',
            'contact_email' => 'confidential@example.com',
            'lead_type' => 'residential_rental',
            'property_id' => $this->testProperty->id,
            'assigned_user_id' => $this->ownerUser->id,
            'status' => 'NEW',
        ]);

        $stranger = User::factory()->create();
        $stranger->assignRole('broker');

        $response = $this->actingAs($stranger)->get(route('crm.leads.show', $lead->id));
        $response->assertStatus(403);

        $postRes = $this->actingAs($stranger)->post(route('crm.leads.status', $lead->id), [
            'status' => 'QUALIFIED',
        ]);
        $postRes->assertStatus(403);
    }

    /** 6. Monetization Product & Plan catalog */
    public function test_monetization_catalog_retrieval()
    {
        $response = $this->get(route('monetization.plans'));
        $response->assertStatus(200);
        $response->assertSee('Featured Listing');
        $response->assertSee('Boosted Listing');
    }

    /** 7. Coupon validation */
    public function test_coupon_validation_and_discounts()
    {
        $coupon = Coupon::create([
            'code' => 'FESTIVE50',
            'discount_type' => 'percentage',
            'discount_value' => 50.00,
            'min_purchase_amount' => 500.00,
            'max_discount_amount' => 1000.00,
            'is_active' => true,
        ]);

        $monetizationService = app(MonetizationService::class);
        $result = $monetizationService->validateCoupon('FESTIVE50', 1499.00, $this->ownerUser);

        // 50% of 1499 is 749.50
        $this->assertEquals(749.50, $result['discount']);
        $this->assertEquals(749.50, $result['final_amount']);
    }

    /** 8. Purchase checkout & server-side payment verification */
    public function test_monetization_checkout_and_server_side_verification()
    {
        $plan = MonetizationPlan::firstOrCreate(
            ['slug' => 'featured-test-30d'],
            [
                'name' => 'Featured 30 Days Test',
                'monetization_product_id' => MonetizationProduct::where('code', 'FEATURED_LISTING')->value('id') ?? 1,
                'price' => 1000.00,
                'currency' => 'INR',
                'tax_percentage' => 18.00,
                'validity_days' => 30,
                'is_active' => true,
            ]
        );

        $checkoutRes = $this->actingAs($this->ownerUser)->postJson(route('monetization.checkout'), [
            'plan_id' => $plan->id,
            'property_id' => $this->testProperty->id,
        ]);

        $checkoutRes->assertStatus(200);
        $checkoutData = $checkoutRes->json();
        $this->assertTrue($checkoutData['success']);
        $this->assertNotEmpty($checkoutData['idempotency_key']);

        // Server-side verify payment
        $verifyRes = $this->actingAs($this->ownerUser)->postJson(route('monetization.verify-payment'), [
            'idempotency_key' => $checkoutData['idempotency_key'],
            'gateway_order_id' => $checkoutData['gateway_order_id'],
            'gateway_payment_id' => 'pay_test_123',
            'gateway_signature' => 'sandbox_valid_signature',
        ]);

        $verifyRes->assertStatus(200);
        $verifyData = $verifyRes->json();
        $this->assertEquals('successful', $verifyData['status']);

        // Assert Promotion was created and property marked featured
        $this->assertDatabaseHas('promotions', [
            'property_id' => $this->testProperty->id,
            'is_active' => 1,
        ]);
        $this->assertTrue((bool) $this->testProperty->fresh()->is_featured);

        // Assert Tax Invoice was generated
        $this->assertDatabaseHas('invoices', [
            'id' => $verifyData['invoice_id'],
            'payment_status' => 'paid',
        ]);
    }

    /** 9. Payment verification rejects invalid signatures */
    public function test_payment_verification_rejects_invalid_signatures()
    {
        $plan = MonetizationPlan::first();
        $monetizationService = app(MonetizationService::class);
        $init = $monetizationService->initiatePurchase($this->ownerUser, $plan, $this->testProperty);

        $verifyRes = $this->actingAs($this->ownerUser)->postJson(route('monetization.verify-payment'), [
            'idempotency_key' => $init['transaction']->idempotency_key,
            'gateway_order_id' => $init['order']['order_id'],
            'gateway_payment_id' => 'pay_fake_999',
            'gateway_signature' => 'fake_invalid_signature',
        ]);

        $verifyRes->assertStatus(422);
        $this->assertDatabaseHas('monetization_transactions', [
            'idempotency_key' => $init['transaction']->idempotency_key,
            'status' => 'failed',
        ]);
    }

    /** 10. Contact Access Paywall Privacy Invariant (Zero Leakage) */
    public function test_contact_access_zero_leakage_invariant()
    {
        // Unauthenticated guest requests property page
        $response = $this->get(route('properties.show', $this->testProperty->slug));
        $response->assertStatus(200);

        // Assert phone and email NEVER leak in HTML source
        $content = $response->getContent();
        $this->assertStringNotContainsString('9876543210', $content);
        $this->assertStringNotContainsString('secret_owner_p9@example.com', $content);
    }

    /** 11. Property Management is Strictly Optional Invariant */
    public function test_property_management_remains_strictly_optional()
    {
        // Owner property is created without mandatory management agreement
        $this->assertDatabaseMissing('management_enrollments', [
            'property_id' => $this->testProperty->id,
        ]);
        $this->assertTrue(true);
    }

    /** 12. Refer & Earn ₹100 Invariant intact */
    public function test_refer_and_earn_remains_intact()
    {
        $response = $this->get(route('refer-and-earn'));
        $response->assertStatus(200);
        $response->assertSee('100');
    }

    /** 13. Notification Channel & Preferences */
    public function test_notification_delivery_respects_user_preferences()
    {
        $notifService = app(NotificationService::class);
        $notification = $notifService->send(
            $this->ownerUser,
            'lead_alert',
            'New Qualified Lead',
            'You have a new inquiry for Indiranagar'
        );

        $this->assertDatabaseHas('crm_notifications', [
            'user_id' => $this->ownerUser->id,
            'type' => 'lead_alert',
        ]);
        $this->assertStringContainsString('in_app', $notification->channel);
    }

    /** 14. Analytics privacy (no sensitive phone/email logged) */
    public function test_analytics_logging_prevents_pii_leakage()
    {
        $analytics = app(AnalyticsService::class);
        $event = $analytics->logEvent('listing_view', $this->testProperty, $this->tenantUser, [
            'phone' => '9988776655',
            'email' => 'leak@example.com',
            'source' => 'search_results',
        ]);

        $this->assertDatabaseHas('analytics_events', ['id' => $event->id]);
        $this->assertArrayNotHasKey('phone', $event->metadata);
        $this->assertArrayNotHasKey('email', $event->metadata);
        $this->assertEquals('search_results', $event->metadata['source']);
    }

    /** 15. Role-aware CRM Dashboard access */
    public function test_role_workspaces_load_with_lead_metrics()
    {
        // Owner workspace
        $resOwner = $this->actingAs($this->ownerUser)->get(route('dashboard.owner'));
        $resOwner->assertStatus(200);
        $resOwner->assertSee('Leads');

        // Broker workspace
        $resBroker = $this->actingAs($this->brokerUser)->get(route('dashboard.broker'));
        $resBroker->assertStatus(200);
        $resBroker->assertSee('Leads');
    }
}
