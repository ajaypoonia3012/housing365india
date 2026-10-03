<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\MonetizationPlan;
use App\Models\MonetizationProduct;
use App\Models\Property;
use App\Models\Role;
use App\Models\TaxConfiguration;
use App\Models\User;
use App\Services\MonetizationService;
use App\Services\TaxService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase9TaxConfigurationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $ownerUser;

    protected User $tenantUser;

    protected MonetizationProduct $taxableProduct;

    protected MonetizationProduct $exemptProduct;

    protected MonetizationPlan $taxablePlan;

    protected MonetizationPlan $exemptPlan;

    protected Property $testProperty;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        $ownerRole = Role::firstOrCreate(['name' => 'owner'], ['display_name' => 'Property Owner']);
        $tenantRole = Role::firstOrCreate(['name' => 'tenant'], ['display_name' => 'Tenant']);

        $this->adminUser = User::factory()->create(['email' => 'admin_tax@example.com']);
        $this->adminUser->roles()->attach($adminRole->id);

        $this->ownerUser = User::factory()->create(['email' => 'owner_tax@example.com']);
        $this->ownerUser->roles()->attach($ownerRole->id);

        $this->tenantUser = User::factory()->create(['email' => 'tenant_tax@example.com']);
        $this->tenantUser->roles()->attach($tenantRole->id);

        $this->taxableProduct = MonetizationProduct::create([
            'name' => 'Featured Listing',
            'code' => 'FEATURED_LISTING',
            'target_role' => 'owner',
            'target_category' => 'LISTING_PROMOTION',
            'is_taxable' => true,
            'is_active' => true,
        ]);

        $this->taxablePlan = MonetizationPlan::create([
            'name' => 'Featured 30 Days',
            'slug' => 'featured-30d-test',
            'monetization_product_id' => $this->taxableProduct->id,
            'price' => 1000.00,
            'currency' => 'INR',
            'tax_percentage' => 18.00,
            'validity_days' => 30,
            'is_active' => true,
        ]);

        $this->exemptProduct = MonetizationProduct::create([
            'name' => 'Exempt Service',
            'code' => 'EXEMPT_SERVICE',
            'target_role' => 'tenant',
            'target_category' => 'EXEMPT',
            'is_taxable' => false,
            'is_active' => true,
        ]);

        $this->exemptPlan = MonetizationPlan::create([
            'name' => 'Exempt Tier',
            'slug' => 'exempt-plan-test',
            'monetization_product_id' => $this->exemptProduct->id,
            'price' => 500.00,
            'currency' => 'INR',
            'tax_percentage' => 0.00,
            'validity_days' => 30,
            'is_active' => true,
        ]);

        $this->testProperty = Property::create([
            'user_id' => $this->ownerUser->id,
            'title' => 'Tax Test Apartment',
            'slug' => 'tax-test-apartment',
            'property_type' => 'Apartment',
            'purpose' => 'rent',
            'city' => 'Bangalore',
            'price' => 45000,
            'status' => 'Available',
            'featured' => false,
        ]);
    }

    /** 1. GST enabled at 18% */
    public function test_gst_enabled_at_18_percent()
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

        $taxService = app(TaxService::class);
        $result = $taxService->calculate(1000.00, $this->taxableProduct);

        $this->assertTrue($result['tax_enabled']);
        $this->assertEquals(18.00, $result['tax_rate']);
        $this->assertEquals(180.00, $result['total_tax']);
        $this->assertEquals(90.00, $result['cgst']);
        $this->assertEquals(90.00, $result['sgst']);
        $this->assertEquals(1180.00, $result['total_amount']);
    }

    /** 2. GST enabled at another rate (12%) */
    public function test_gst_enabled_at_12_percent()
    {
        TaxConfiguration::create([
            'tax_enabled' => true,
            'tax_name' => 'GST',
            'tax_rate' => 12.00,
            'cgst_enabled' => true,
            'sgst_enabled' => true,
            'cgst_rate' => 6.00,
            'sgst_rate' => 6.00,
            'effective_from' => Carbon::now()->subDay(),
            'active' => true,
        ]);

        $taxService = app(TaxService::class);
        $result = $taxService->calculate(1000.00, $this->taxableProduct);

        $this->assertTrue($result['tax_enabled']);
        $this->assertEquals(12.00, $result['tax_rate']);
        $this->assertEquals(120.00, $result['total_tax']);
        $this->assertEquals(60.00, $result['cgst']);
        $this->assertEquals(60.00, $result['sgst']);
        $this->assertEquals(1120.00, $result['total_amount']);
    }

    /** 3. GST disabled (GST = 0) */
    public function test_gst_disabled_calculates_zero_tax()
    {
        TaxConfiguration::create([
            'tax_enabled' => false,
            'tax_name' => 'GST',
            'tax_rate' => 18.00,
            'effective_from' => Carbon::now()->subDay(),
            'active' => true,
        ]);

        $taxService = app(TaxService::class);
        $result = $taxService->calculate(1000.00, $this->taxableProduct);

        $this->assertFalse($result['tax_enabled']);
        $this->assertEquals(0.00, $result['tax_rate']);
        $this->assertEquals(0.00, $result['total_tax']);
        $this->assertEquals(1000.00, $result['total_amount']);
    }

    /** 4. Product taxable = false results in zero tax even if GST is ON */
    public function test_exempt_product_calculates_zero_tax()
    {
        TaxConfiguration::create([
            'tax_enabled' => true,
            'tax_rate' => 18.00,
            'effective_from' => Carbon::now()->subDay(),
            'active' => true,
        ]);

        $taxService = app(TaxService::class);
        $result = $taxService->calculate(500.00, $this->exemptProduct);

        $this->assertFalse($result['tax_enabled']);
        $this->assertEquals(0.00, $result['total_tax']);
        $this->assertEquals(500.00, $result['total_amount']);
    }

    /** 5. Global GST OFF overrides product taxable */
    public function test_global_gst_off_overrides_taxable_product()
    {
        TaxConfiguration::create([
            'tax_enabled' => false,
            'tax_rate' => 18.00,
            'effective_from' => Carbon::now()->subDay(),
            'active' => true,
        ]);

        $taxService = app(TaxService::class);
        $result = $taxService->calculate(1000.00, $this->taxableProduct);

        $this->assertFalse($result['tax_enabled']);
        $this->assertEquals(0.00, $result['total_tax']);
        $this->assertEquals(1000.00, $result['total_amount']);
    }

    /** 6. Historical invoice retains old tax rate */
    public function test_historical_invoice_retains_old_tax_rate()
    {
        $config18 = TaxConfiguration::create([
            'tax_enabled' => true,
            'tax_name' => 'GST',
            'tax_rate' => 18.00,
            'cgst_enabled' => true,
            'sgst_enabled' => true,
            'cgst_rate' => 9.00,
            'sgst_rate' => 9.00,
            'effective_from' => Carbon::now()->subDays(10),
            'active' => true,
        ]);

        $monetizationService = app(MonetizationService::class);

        $checkoutA = $monetizationService->initiatePurchase($this->ownerUser, $this->taxablePlan, $this->testProperty);
        $txnA = $monetizationService->verifyAndFulfill($checkoutA['transaction']->idempotency_key, [
            'gateway_payment_id' => 'pay_hist_18',
            'gateway_signature' => 'sandbox_valid_signature',
        ]);

        // Administrator now updates configuration to Tax OFF
        $config18->update(['active' => false]);
        TaxConfiguration::create([
            'tax_enabled' => false,
            'tax_name' => 'GST',
            'tax_rate' => 0.00,
            'effective_from' => Carbon::now()->subMinute(),
            'active' => true,
        ]);

        // Historical Invoice A remains untouched with 18%
        $freshTxnA = $txnA->fresh(['invoice']);
        $this->assertEquals(180.00, (float) $freshTxnA->tax_amount);
        $this->assertEquals(18.00, (float) $freshTxnA->invoice->tax_rate);
        $this->assertEquals(180.00, (float) $freshTxnA->invoice->tax);
        $this->assertEquals(1180.00, (float) $freshTxnA->invoice->total);
    }

    /** 7. New transaction uses new tax rate */
    public function test_new_transaction_uses_new_tax_rate()
    {
        TaxConfiguration::create([
            'tax_enabled' => true,
            'tax_name' => 'GST',
            'tax_rate' => 12.00,
            'cgst_enabled' => true,
            'sgst_enabled' => true,
            'cgst_rate' => 6.00,
            'sgst_rate' => 6.00,
            'effective_from' => Carbon::now()->subDays(1),
            'active' => true,
        ]);

        $monetizationService = app(MonetizationService::class);

        $checkout = $monetizationService->initiatePurchase($this->ownerUser, $this->taxablePlan, $this->testProperty);
        $txn = $monetizationService->verifyAndFulfill($checkout['transaction']->idempotency_key, [
            'gateway_payment_id' => 'pay_new_12',
            'gateway_signature' => 'sandbox_valid_signature',
        ]);

        $this->assertEquals(120.00, (float) $txn->tax_amount);
        $this->assertEquals(12.00, (float) $txn->invoice->tax_rate);
        $this->assertEquals(120.00, (float) $txn->invoice->tax);
        $this->assertEquals(1120.00, (float) $txn->invoice->total);
    }

    /** 8. Admin can change tax configuration */
    public function test_admin_can_change_tax_configuration()
    {
        $this->actingAs($this->adminUser);

        $config = TaxConfiguration::create([
            'tax_enabled' => true,
            'tax_name' => 'GST',
            'tax_rate' => 18.00,
            'effective_from' => Carbon::now()->subDay(),
            'active' => true,
        ]);

        $config->update([
            'tax_rate' => 12.00,
            'cgst_rate' => 6.00,
            'sgst_rate' => 6.00,
            'notes' => 'Updated by authorized administrator',
        ]);

        $this->assertDatabaseHas('tax_configurations', [
            'id' => $config->id,
            'tax_rate' => 12.00,
            'cgst_rate' => 6.00,
            'notes' => 'Updated by authorized administrator',
        ]);
    }

    /** 9. Non-admin cannot access or change tax configuration */
    public function test_non_admin_cannot_change_tax_configuration()
    {
        $this->actingAs($this->ownerUser);

        $res = $this->get('/admin/tax-configurations');
        $res->assertStatus(403);
    }

    /** 10. Client cannot manipulate tax amount */
    public function test_client_cannot_manipulate_tax_amount()
    {
        TaxConfiguration::create([
            'tax_enabled' => true,
            'tax_name' => 'GST',
            'tax_rate' => 18.00,
            'effective_from' => Carbon::now()->subDay(),
            'active' => true,
        ]);

        // Attempting to inject tax_rate = 0 or total = 500 via payload
        $res = $this->actingAs($this->ownerUser)->postJson(route('monetization.checkout'), [
            'plan_id' => $this->taxablePlan->id,
            'property_id' => $this->testProperty->id,
            'tax_rate' => 0.00,
            'tax_amount' => 0.00,
            'amount' => 100.00,
        ]);

        $res->assertStatus(200);
        $data = $res->json();

        // Server authoritative calculation overrides any client payload
        $this->assertEquals(180.00, $data['pricing']['tax']);
        $this->assertEquals(1180.00, $data['amount']);
        $this->assertEquals(18.00, $data['pricing']['tax_rate']);
    }

    /** 11. Coupon calculated before tax */
    public function test_coupon_calculated_before_tax()
    {
        TaxConfiguration::create([
            'tax_enabled' => true,
            'tax_name' => 'GST',
            'tax_rate' => 18.00,
            'effective_from' => Carbon::now()->subDay(),
            'active' => true,
        ]);

        Coupon::create([
            'code' => 'SAVE200',
            'discount_type' => 'fixed',
            'discount_value' => 200.00,
            'min_purchase_amount' => 500.00,
            'is_active' => true,
        ]);

        $monetizationService = app(MonetizationService::class);
        $checkout = $monetizationService->initiatePurchase(
            $this->ownerUser,
            $this->taxablePlan,
            $this->testProperty,
            'SAVE200'
        );

        // Subtotal = 1000, Discount = 200, Taxable = 800
        // GST (18% of 800) = 144
        // Final Total = 800 + 144 = 944
        $pricing = $checkout['pricing'];
        $this->assertEquals(1000.00, $pricing['base_price']);
        $this->assertEquals(200.00, $pricing['discount']);
        $this->assertEquals(800.00, $pricing['taxable_amount']);
        $this->assertEquals(144.00, $pricing['tax']);
        $this->assertEquals(944.00, $pricing['total']);
    }

    /** 12. CGST and SGST calculation split */
    public function test_cgst_and_sgst_calculation_split()
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

        $taxService = app(TaxService::class);
        $calc = $taxService->calculate(2000.00, $this->taxableProduct);

        $this->assertEquals(180.00, $calc['cgst']);
        $this->assertEquals(180.00, $calc['sgst']);
        $this->assertEquals(360.00, $calc['total_tax']);
        $this->assertEquals(2360.00, $calc['total_amount']);
    }

    /** 13. IGST calculation where configured */
    public function test_igst_calculation_where_configured()
    {
        TaxConfiguration::create([
            'tax_enabled' => true,
            'tax_name' => 'GST',
            'tax_rate' => 18.00,
            'igst_enabled' => true,
            'igst_rate' => 18.00,
            'effective_from' => Carbon::now()->subDay(),
            'active' => true,
        ]);

        $taxService = app(TaxService::class);
        $calc = $taxService->calculate(1000.00, $this->taxableProduct, null, ['is_interstate' => true]);

        $this->assertEquals(180.00, $calc['igst']);
        $this->assertEquals(0.00, $calc['cgst']);
        $this->assertEquals(0.00, $calc['sgst']);
        $this->assertEquals(180.00, $calc['total_tax']);
        $this->assertEquals(1180.00, $calc['total_amount']);
    }

    /** 14. Invoice displays correct tax snapshot */
    public function test_invoice_displays_correct_tax_snapshot()
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

        $monetizationService = app(MonetizationService::class);
        $checkout = $monetizationService->initiatePurchase($this->ownerUser, $this->taxablePlan, $this->testProperty);
        $txn = $monetizationService->verifyAndFulfill($checkout['transaction']->idempotency_key, [
            'gateway_payment_id' => 'pay_inv_display',
            'gateway_signature' => 'sandbox_valid_signature',
        ]);

        $response = $this->actingAs($this->ownerUser)->get(route('monetization.invoice.show', $txn->invoice->id));
        $response->assertStatus(200);
        $response->assertSee('CGST (9.0%)');
        $response->assertSee('SGST (9.0%)');
        $response->assertSee('₹90.00');
        $response->assertSee('₹1,180.00');
    }

    /** 15. Payment transaction stores correct tax snapshot */
    public function test_payment_transaction_stores_correct_tax_snapshot()
    {
        TaxConfiguration::create([
            'tax_enabled' => true,
            'tax_name' => 'GST',
            'tax_rate' => 12.00,
            'cgst_enabled' => true,
            'sgst_enabled' => true,
            'cgst_rate' => 6.00,
            'sgst_rate' => 6.00,
            'effective_from' => Carbon::now()->subDay(),
            'active' => true,
        ]);

        $monetizationService = app(MonetizationService::class);
        $checkout = $monetizationService->initiatePurchase($this->ownerUser, $this->taxablePlan, $this->testProperty);
        $txn = $checkout['transaction'];

        $this->assertEquals(12.00, (float) $txn->tax_rate);
        $this->assertTrue((bool) $txn->tax_enabled);
        $this->assertEquals('GST', $txn->tax_name);
        $this->assertEquals(120.00, (float) $txn->tax_amount);
        $this->assertIsArray($txn->tax_details);
        $this->assertEquals(60.00, $txn->tax_details['cgst']);
        $this->assertEquals(60.00, $txn->tax_details['sgst']);
    }
}
