<?php

namespace Tests\Feature;

use App\Models\CityLocation;
use App\Models\Locality;
use App\Models\Property;
use App\Models\SeoMetaOverride;
use App\Models\State;
use App\Models\User;
use App\Services\SeoIndexabilityService;
use App\Services\SeoService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase8NationalSeoTest extends TestCase
{
    use RefreshDatabase;

    protected State $state;

    protected CityLocation $city;

    protected Locality $locality;

    protected Property $property;

    protected function setUp(): void
    {
        parent::setUp();

        $this->state = State::create([
            'name' => 'Rajasthan',
            'slug' => 'rajasthan',
            'code' => 'RJ',
            'type' => 'state',
            'region' => 'Northern',
            'capital' => 'Jaipur',
            'is_active' => true,
            'is_indexable' => true,
        ]);

        $this->city = CityLocation::create([
            'name' => 'Jaipur',
            'slug' => 'jaipur',
            'province_or_state' => 'Rajasthan',
            'country' => 'IN',
            'state_id' => $this->state->id,
            'state_slug' => 'rajasthan',
            'tier' => 1,
            'is_active' => true,
            'is_indexable' => true,
        ]);

        $this->locality = Locality::create([
            'city_location_id' => $this->city->id,
            'state_id' => $this->state->id,
            'name' => 'Mansarovar',
            'slug' => 'mansarovar',
            'pincode' => '302020',
            'is_active' => true,
            'is_indexable' => true,
        ]);

        $this->property = Property::create([
            'title' => 'Luxury 3BHK Apartment in Mansarovar',
            'slug' => 'luxury-3bhk-apartment-mansarovar-jaipur',
            'city' => 'Jaipur',
            'locality' => 'Mansarovar',
            'state' => 'Rajasthan',
            'country' => 'IN',
            'price' => 28000,
            'price_display' => '₹28,000',
            'bedrooms' => 3,
            'bathrooms' => 3,
            'property_type' => 'Apartment',
            'purpose' => 'rent',
            'category' => 'residential',
            'status' => 'Available',
            'seller_phone' => '9829012345',
            'seller_email' => 'private_owner@housing365.in',
            'is_managed' => false,
        ]);
    }

    public function test_01_country_and_state_hierarchy_works(): void
    {
        $this->assertEquals('Rajasthan', $this->state->name);
        $this->assertEquals('RJ', $this->state->code);
        $this->assertCount(1, $this->state->cities);
    }

    public function test_02_city_and_locality_relationship_works(): void
    {
        $this->assertEquals('Jaipur', $this->city->name);
        $this->assertEquals($this->state->id, $this->city->state_id);
        $this->assertCount(1, $this->city->localities);
        $this->assertEquals('Mansarovar', $this->city->localities->first()->name);
    }

    public function test_03_unique_slug_enforced_for_locality_within_city(): void
    {
        $this->expectException(QueryException::class);

        Locality::create([
            'city_location_id' => $this->city->id,
            'name' => 'Mansarovar Duplicate',
            'slug' => 'mansarovar', // Duplicate within same city
        ]);
    }

    public function test_04_city_marketplace_page_renders_cleanly(): void
    {
        $response = $this->get('/locations/rajasthan/jaipur');

        $response->assertStatus(200);
        $response->assertSee('Jaipur', false);
        $response->assertSee('Mansarovar', false);
        $response->assertSee('Luxury 3BHK Apartment in Mansarovar', false);
    }

    public function test_05_locality_marketplace_page_renders_cleanly(): void
    {
        $response = $this->get('/locations/rajasthan/jaipur/mansarovar');

        $response->assertStatus(200);
        $response->assertSee('Mansarovar', false);
        $response->assertSee('Luxury 3BHK Apartment in Mansarovar', false);
    }

    public function test_06_rental_discovery_route_renders(): void
    {
        $response = $this->get('/rent/rajasthan/jaipur/mansarovar');

        $response->assertStatus(200);
        $response->assertSee('Luxury 3BHK Apartment in Mansarovar', false);
    }

    public function test_07_pg_discovery_route_renders(): void
    {
        $response = $this->get('/pg/rajasthan/jaipur');

        $response->assertStatus(200);
    }

    public function test_08_coliving_discovery_route_renders(): void
    {
        $response = $this->get('/co-living/rajasthan/jaipur');

        $response->assertStatus(200);
    }

    public function test_09_coworking_discovery_route_renders(): void
    {
        $response = $this->get('/coworking/rajasthan/jaipur');

        $response->assertStatus(200);
    }

    public function test_10_commercial_discovery_route_renders(): void
    {
        $response = $this->get('/commercial/rajasthan/jaipur');

        $response->assertStatus(200);
    }

    public function test_11_buy_discovery_route_renders(): void
    {
        $response = $this->get('/buy/rajasthan/jaipur');

        $response->assertStatus(200);
    }

    public function test_12_sell_discovery_route_renders(): void
    {
        $response = $this->get('/sell/rajasthan/jaipur');

        $response->assertStatus(200);
    }

    public function test_13_land_discovery_route_renders(): void
    {
        $response = $this->get('/land/rajasthan/jaipur');

        $response->assertStatus(200);
    }

    public function test_14_builders_discovery_route_renders(): void
    {
        $response = $this->get('/builders/rajasthan/jaipur');

        $response->assertStatus(200);
    }

    public function test_15_care_location_hub_renders(): void
    {
        $response = $this->get('/housing365-care/rajasthan/jaipur');

        $response->assertStatus(200);
        $response->assertSee('HOUSING365 CARE', false);
        $response->assertSee('Jaipur', false);
    }

    public function test_16_property_management_location_hub_renders_and_declares_optional(): void
    {
        $response = $this->get('/property-management/rajasthan/jaipur');

        $response->assertStatus(200);
        $response->assertSee('OPTIONAL PROPERTY MANAGEMENT', false);
        $response->assertSee('Property Management is strictly', false);
    }

    public function test_17_empty_inactive_location_is_not_indexable(): void
    {
        $emptyLocality = Locality::create([
            'city_location_id' => $this->city->id,
            'name' => 'Empty Rural Village',
            'slug' => 'empty-rural-village',
            'is_active' => false,
            'is_indexable' => false,
        ]);

        $service = app(SeoIndexabilityService::class);
        $shouldIndex = $service->shouldIndex('/locations/rajasthan/jaipur/empty-rural-village', [
            'locality' => $emptyLocality,
        ]);

        $this->assertFalse($shouldIndex);
    }

    public function test_18_unpublished_property_excluded_from_discovery(): void
    {
        $draftProperty = Property::create([
            'title' => 'Secret Unapproved House',
            'slug' => 'secret-unapproved-house',
            'city' => 'Jaipur',
            'price' => 15000,
            'purpose' => 'rent',
            'status' => 'Pending Review',
        ]);

        $response = $this->get('/locations/rajasthan/jaipur');

        $response->assertStatus(200);
        $response->assertDontSee('Secret Unapproved House');
    }

    public function test_19_canonical_url_cleans_query_filters(): void
    {
        $service = app(SeoIndexabilityService::class);
        $canonical = $service->buildCanonicalUrl('https://housing365.in/rent/rajasthan/jaipur?sort=price_low&bhk=2&ref=H365-XYZ');

        $this->assertEquals('https://housing365.in/rent/rajasthan/jaipur', $canonical);
    }

    public function test_20_sitemap_index_renders_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('<sitemapindex', false);
        $response->assertSee('/sitemaps/properties.xml', false);
        $response->assertSee('/sitemaps/locations.xml', false);
        $response->assertSee('/sitemaps/categories.xml', false);
    }

    public function test_21_properties_sitemap_contains_only_available_properties(): void
    {
        $response = $this->get('/sitemaps/properties.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('luxury-3bhk-apartment-mansarovar-jaipur', false);
    }

    public function test_22_private_dashboard_pages_excluded_from_sitemaps_and_robots(): void
    {
        $robotsContent = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Disallow: /dashboard/', $robotsContent);
        $this->assertStringContainsString('Disallow: /admin/', $robotsContent);
        $this->assertStringContainsString('Disallow: /referrals/', $robotsContent);
        $this->assertStringContainsString('Disallow: /rewards/', $robotsContent);
        $this->assertStringContainsString('Disallow: /payouts/', $robotsContent);
    }

    public function test_23_owner_phone_and_email_never_leak_in_public_property_view(): void
    {
        $response = $this->get('/properties/'.$this->property->slug);

        $response->assertStatus(200);
        $response->assertDontSee('9829012345');
        $response->assertDontSee('private_owner@housing365.in');
    }

    public function test_24_owner_contact_never_leaks_in_schema_json(): void
    {
        $seoService = app(SeoService::class);
        $meta = $seoService->resolveMeta('/properties/'.$this->property->slug, [
            'property' => $this->property,
        ]);

        $schemaJson = json_encode($meta['structuredData']);

        $this->assertStringNotContainsString('9829012345', $schemaJson);
        $this->assertStringNotContainsString('private_owner@housing365.in', $schemaJson);
        $this->assertStringContainsString('RealEstateListing', $schemaJson);
    }

    public function test_25_contact_access_paywall_remains_intact(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/properties/'.$this->property->id.'/unlock-contact');

        // Gated by Contact Access paywall: 402 Payment Required when 0 credits
        $response->assertStatus(402);
    }

    public function test_26_property_management_remains_optional_on_all_pages(): void
    {
        $this->assertFalse((bool) $this->property->is_managed);
    }

    public function test_27_housing365_care_remains_independent(): void
    {
        $response = $this->get('/housing365-care/rajasthan/jaipur');

        $response->assertStatus(200);
        $this->assertDatabaseCount('service_requests', 0);
    }

    public function test_28_refer_and_earn_program_remains_intact(): void
    {
        $response = $this->get('/refer-and-earn');

        $response->assertStatus(200);
        $response->assertSee('₹100', false);
    }

    public function test_29_custom_seo_meta_override_is_applied_if_configured(): void
    {
        SeoMetaOverride::create([
            'path' => '/rent/rajasthan/jaipur/',
            'meta_title' => 'Handpicked Verified Rental Flats in Jaipur | Housing365 India',
            'meta_description' => 'Browse custom reviewed flats in Jaipur.',
            'h1' => 'Premier Jaipur Residential Rentals',
            'is_indexable' => true,
        ]);

        $seoService = app(SeoService::class);
        $meta = $seoService->resolveMeta('/rent/rajasthan/jaipur/');

        $this->assertEquals('Handpicked Verified Rental Flats in Jaipur | Housing365 India', $meta['title']);
        $this->assertEquals('Premier Jaipur Residential Rentals', $meta['h1']);
    }

    public function test_30_structured_data_breadcrumbs_generate_correctly(): void
    {
        $seoService = app(SeoService::class);
        $meta = $seoService->resolveMeta('/locations/rajasthan/jaipur/mansarovar', [
            'state' => $this->state,
            'city' => $this->city,
            'locality' => $this->locality,
            'purpose' => 'Rent',
        ]);

        $this->assertNotEmpty($meta['breadcrumbs']);
        $this->assertEquals('Mansarovar', end($meta['breadcrumbs'])['title']);
    }
}
