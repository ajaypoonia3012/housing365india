<?php

namespace Tests\Feature;

use App\Models\ContactAccessPackage;
use App\Models\ContactAccessPurchase;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MarketplaceExpansionTest extends TestCase
{
    use RefreshDatabase;

    protected ContactAccessPackage $package;

    protected Property $rentProp;

    protected Property $saleProp;

    protected Property $commRentProp;

    protected Property $commSaleProp;

    protected Property $landProp;

    protected Property $pgProp;

    protected Property $colivingProp;

    protected Property $coworkingProp;

    protected Property $projectProp;

    protected string $secretPhone = '9876543210';

    protected string $secretEmail = 'private.landlord@example.com';

    protected function setUp(): void
    {
        parent::setUp();

        $this->package = ContactAccessPackage::create([
            'name' => 'Starter Contact Pass',
            'slug' => 'starter-pass',
            'credits' => 3,
            'price' => 299.00,
            'currency' => 'INR',
            'validity_days' => 30,
            'is_active' => true,
        ]);

        // 1. Rent Property
        $this->rentProp = Property::create([
            'title' => 'Luxury 3 BHK Flat in Mansarovar',
            'slug' => 'luxury-3-bhk-flat-mansarovar',
            'purpose' => 'rent',
            'category' => 'residential',
            'property_type' => 'Flat / Apartment',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'locality' => 'Mansarovar',
            'price' => 25000,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'sqft' => 1400,
            'seller_name' => 'Rajesh Sharma',
            'seller_phone' => $this->secretPhone,
            'seller_email' => $this->secretEmail,
            'verification_level' => 'basic_verified',
            'status' => 'Available',
        ]);

        // 2. Sale Property
        $this->saleProp = Property::create([
            'title' => 'Independent Luxury Villa in Vaishali',
            'slug' => 'independent-luxury-villa-vaishali',
            'purpose' => 'sale',
            'category' => 'residential',
            'property_type' => 'House / Villa',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'locality' => 'Vaishali Nagar',
            'price' => 15000000,
            'bedrooms' => 4,
            'bathrooms' => 4,
            'sqft' => 3200,
            'seller_name' => 'Vikram Singh',
            'seller_phone' => '9876543211',
            'seller_email' => 'vikram@example.com',
            'verification_level' => 'document_verified',
            'status' => 'Available',
        ]);

        // 3. Commercial Rent
        $this->commRentProp = Property::create([
            'title' => 'Prime Commercial Office Space in BKC',
            'slug' => 'prime-commercial-office-space-bkc',
            'purpose' => 'commercial_rent',
            'category' => 'commercial',
            'property_type' => 'Commercial Office',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'locality' => 'BKC',
            'price' => 120000,
            'sqft' => 1500,
            'seller_name' => 'BKC Properties',
            'seller_phone' => '9876543212',
            'seller_email' => 'bkc@example.com',
            'verification_level' => 'housing365_verified',
            'status' => 'Available',
        ]);

        // 4. Commercial Sale
        $this->commSaleProp = Property::create([
            'title' => 'Retail High Street Shop in Connaught Place',
            'slug' => 'retail-high-street-shop-cp',
            'purpose' => 'commercial_sale',
            'category' => 'commercial',
            'property_type' => 'Retail Shop',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'locality' => 'Connaught Place',
            'price' => 35000000,
            'sqft' => 800,
            'seller_name' => 'Delhi Retail Corp',
            'seller_phone' => '9876543213',
            'seller_email' => 'delhi@example.com',
            'status' => 'Available',
        ]);

        // 5. Land Sale
        $this->landProp = Property::create([
            'title' => 'Freehold Residential Plot on Ajmer Road',
            'slug' => 'freehold-residential-plot-ajmer-road',
            'purpose' => 'land_sale',
            'category' => 'plot_land',
            'property_type' => 'Residential Plot',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'locality' => 'Ajmer Road',
            'price' => 4500000,
            'sqft' => 200,
            'area_unit' => 'sq.yards',
            'seller_name' => 'Land Owner',
            'seller_phone' => '9876543214',
            'seller_email' => 'land@example.com',
            'status' => 'Available',
        ]);

        // 6. PG
        $this->pgProp = Property::create([
            'title' => 'Modern Boys PG with Food in Koramangala',
            'slug' => 'modern-boys-pg-koramangala',
            'purpose' => 'pg',
            'category' => 'pg_coliving',
            'property_type' => 'PG for Men',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'locality' => 'Koramangala',
            'price' => 12000,
            'pg_gender' => 'boys',
            'food_included' => true,
            'occupancy_type' => 'double',
            'deposit_amount' => 20000,
            'seller_name' => 'PG Operator',
            'seller_phone' => '9876543215',
            'seller_email' => 'pg@example.com',
            'status' => 'Available',
        ]);

        // 7. Co-Living
        $this->colivingProp = Property::create([
            'title' => 'Premium Co-living Studio in Hitec City',
            'slug' => 'premium-coliving-studio-hitec-city',
            'purpose' => 'coliving',
            'category' => 'pg_coliving',
            'property_type' => 'Co-living Shared Suite',
            'city' => 'Hyderabad',
            'state' => 'Telangana',
            'locality' => 'Hitec City',
            'price' => 18000,
            'pg_gender' => 'unisex',
            'food_included' => true,
            'occupancy_type' => 'single',
            'deposit_amount' => 25000,
            'seller_name' => 'Coliving Co',
            'seller_phone' => '9876543216',
            'seller_email' => 'colive@example.com',
            'status' => 'Available',
        ]);

        // 8. Coworking
        $this->coworkingProp = Property::create([
            'title' => 'Dedicated Desk at Cyber City Coworking Hub',
            'slug' => 'dedicated-desk-cyber-city-coworking',
            'purpose' => 'coworking',
            'category' => 'commercial',
            'property_type' => 'Dedicated Desk',
            'city' => 'Gurugram',
            'state' => 'Haryana',
            'locality' => 'Cyber City',
            'price' => 8500,
            'seller_name' => 'Coworking Hub',
            'seller_phone' => '9876543217',
            'seller_email' => 'cowork@example.com',
            'status' => 'Available',
        ]);

        // 9. Builder Project
        $this->projectProp = Property::create([
            'title' => 'Godrej Green Groves Township',
            'slug' => 'godrej-green-groves-township',
            'purpose' => 'project',
            'category' => 'residential',
            'property_type' => 'New Launch Township',
            'project_name' => 'Godrej Green Groves',
            'rera_number' => 'RAJ/P/2026/9988',
            'possession_date' => 'December 2027',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'locality' => 'Jagatpura',
            'price' => 8500000,
            'seller_name' => 'Godrej Developer',
            'seller_phone' => '9876543218',
            'seller_email' => 'godrej@example.com',
            'posted_by' => 'Builder',
            'listing_source' => 'Builder',
            'verification_level' => 'housing365_verified',
            'status' => 'Available',
        ]);
    }

    /** 1. Rent search */
    public function test_1_rent_search(): void
    {
        $response = $this->get('/rent');
        $response->assertStatus(200);
        $response->assertSee('Luxury 3 BHK Flat in Mansarovar');
    }

    /** 2. Sale search */
    public function test_2_sale_search(): void
    {
        $response = $this->get('/sale');
        $response->assertStatus(200);
        $response->assertSee('Independent Luxury Villa in Vaishali');
    }

    /** 3. Commercial search */
    public function test_3_commercial_search(): void
    {
        $response = $this->get('/commercial');
        $response->assertStatus(200);
        $response->assertSee('Prime Commercial Office Space in BKC');
    }

    /** 4. Land search */
    public function test_4_land_search(): void
    {
        $response = $this->get('/land');
        $response->assertStatus(200);
        $response->assertSee('Freehold Residential Plot on Ajmer Road');
    }

    /** 5. PG search */
    public function test_5_pg_search(): void
    {
        $response = $this->get('/pg');
        $response->assertStatus(200);
        $response->assertSee('Modern Boys PG with Food in Koramangala');
    }

    /** 6. Co-living search */
    public function test_6_coliving_search(): void
    {
        $response = $this->get('/co-living');
        $response->assertStatus(200);
        $response->assertSee('Premium Co-living Studio in Hitec City');
    }

    /** 7. Coworking search */
    public function test_7_coworking_search(): void
    {
        $response = $this->get('/coworking');
        $response->assertStatus(200);
        $response->assertSee('Dedicated Desk at Cyber City Coworking Hub');
    }

    /** 8. Builder project search */
    public function test_8_builder_project_search(): void
    {
        $response = $this->get('/projects');
        $response->assertStatus(200);
        $response->assertSee('Godrej Green Groves Township');
    }

    /** 9. Purpose filtering via query param */
    public function test_9_purpose_filtering(): void
    {
        $response = $this->get('/properties?purpose=pg');
        $response->assertStatus(200);
        $response->assertSee('Modern Boys PG with Food in Koramangala');
        $response->assertDontSee('Independent Luxury Villa in Vaishali');
    }

    /** 10. Location filtering (State / City / Locality) */
    public function test_10_location_filtering(): void
    {
        $response = $this->get('/rent/rajasthan/jaipur/mansarovar');
        $response->assertStatus(200);
        $response->assertSee('Luxury 3 BHK Flat in Mansarovar');
    }

    /** 11. Price filtering */
    public function test_11_price_filtering(): void
    {
        $response = $this->get('/properties?min_price=10000000');
        $response->assertStatus(200);
        $response->assertSee('Independent Luxury Villa in Vaishali');
        $response->assertDontSee('Luxury 3 BHK Flat in Mansarovar');
    }

    /** 12. Property category filtering */
    public function test_12_property_category_filtering(): void
    {
        $response = $this->get('/properties?category=plot_land');
        $response->assertStatus(200);
        $response->assertSee('Freehold Residential Plot on Ajmer Road');
        $response->assertDontSee('Prime Commercial Office Space in BKC');
    }

    /** 13. Saved property authorization & isolation */
    public function test_13_saved_property_authorization(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // Guest cannot save
        $guestRes = $this->post("/properties/{$this->rentProp->id}/save");
        $guestRes->assertRedirect(route('login'));

        // User A saves rentProp
        $this->actingAs($userA)->post("/properties/{$this->rentProp->id}/save");
        $this->assertTrue($this->rentProp->isSavedBy($userA));
        $this->assertFalse($this->rentProp->isSavedBy($userB));

        // User B visits saved properties - cannot see User A's bookmark
        $resB = $this->actingAs($userB)->get('/saved-properties');
        $resB->assertStatus(200);
        $resB->assertDontSee('Luxury 3 BHK Flat in Mansarovar');

        // User A visits saved properties - sees rentProp and contact is protected
        $resA = $this->actingAs($userA)->get('/saved-properties');
        $resA->assertStatus(200);
        $resA->assertSee('Luxury 3 BHK Flat in Mansarovar');
        $resA->assertDontSee($this->secretPhone);
        $resA->assertDontSee($this->secretEmail);

        // Toggle again to unsave
        $this->actingAs($userA)->post("/properties/{$this->rentProp->id}/save");
        $this->assertFalse($this->rentProp->isSavedBy($userA));
        $resAAfter = $this->actingAs($userA)->get('/saved-properties');
        $resAAfter->assertDontSee('Luxury 3 BHK Flat in Mansarovar');
    }

    /** 14. Free Enquiry workflow */
    public function test_14_enquiry_workflow(): void
    {
        $response = $this->post('/inquiry', [
            'name' => 'Rahul Verma',
            'phone' => '9988776655',
            'email' => 'rahul@example.com',
            'message' => 'Interested in 3 BHK flat',
            'service_type' => 'Residential Rent Enquiry',
        ]);
        $response->assertSessionHas('success');
        $response->assertDontSee($this->secretPhone);
        $response->assertDontSee($this->secretEmail);
        $this->assertDatabaseHas('inquiries', [
            'name' => 'Rahul Verma',
            'phone' => '9988776655',
        ]);
    }

    /** 15. Free Viewing workflow */
    public function test_15_viewing_workflow(): void
    {
        $response = $this->post("/properties/{$this->rentProp->id}/viewing", [
            'name' => 'Pooja Sharma',
            'phone' => '9988776644',
            'email' => 'pooja@example.com',
            'preferred_date' => 'Tomorrow 4 PM',
        ]);
        $response->assertSessionHas('success');
        $response->assertDontSee($this->secretPhone);
        $response->assertDontSee($this->secretEmail);
        $this->assertDatabaseHas('inquiries', [
            'name' => 'Pooja Sharma',
            'property_id' => $this->rentProp->id,
            'service_type' => 'Property Viewing Request',
        ]);
    }

    /** 16. Guest contact protection */
    public function test_16_guest_contact_protection(): void
    {
        $response = $this->get("/properties/{$this->rentProp->slug}");
        $response->assertStatus(200);
        $response->assertDontSee($this->secretPhone);
        $response->assertDontSee($this->secretEmail);
        $response->assertSee('Unlock Owner Contact');
    }

    /** 17. Registered-user contact protection (without credits) */
    public function test_17_registered_user_contact_protection(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get("/properties/{$this->rentProp->slug}");
        $response->assertStatus(200);
        $response->assertDontSee($this->secretPhone);
        $response->assertDontSee($this->secretEmail);
        $response->assertSee('Unlock Owner Contact');
    }

    /** 18. Contact Access unlock with credits */
    public function test_18_contact_access_unlock(): void
    {
        $user = User::factory()->create();
        ContactAccessPurchase::create([
            'user_id' => $user->id,
            'package_id' => $this->package->id,
            'order_reference' => 'ORD-TEST-'.uniqid(),
            'credits_allocated' => 3,
            'credits_remaining' => 3,
            'amount_paid' => 299,
            'payment_status' => 'paid',
            'expires_at' => now()->addDays(30),
        ]);

        $this->assertEquals(3, $user->availableContactCredits());

        $unlockRes = $this->actingAs($user)->post("/properties/{$this->rentProp->id}/unlock-contact");
        $unlockRes->assertJson(['success' => true]);

        $this->assertEquals(2, $user->fresh()->availableContactCredits());
        $this->assertTrue($this->rentProp->isUnlockedBy($user));

        // Now viewing property displays direct contact
        $detailRes = $this->actingAs($user)->get("/properties/{$this->rentProp->slug}");
        $detailRes->assertSee($this->secretPhone);
    }

    /** 19. Similar-property contact protection */
    public function test_19_similar_property_contact_protection(): void
    {
        $response = $this->get("/properties/{$this->saleProp->slug}");
        $response->assertStatus(200);
        // Ensure similar properties do not leak secret owner contacts
        $response->assertDontSee($this->secretPhone);
        $response->assertDontSee($this->secretEmail);
    }

    /** 20. Owner listing flow with ₹0 Free leasing */
    public function test_20_owner_listing_flow(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $photos = [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.jpg'),
            UploadedFile::fake()->image('photo3.jpg'),
            UploadedFile::fake()->image('photo4.jpg'),
            UploadedFile::fake()->image('photo5.jpg'),
        ];

        $res = $this->actingAs($user)->post('/post-property', [
            'purpose' => 'rent',
            'category' => 'residential',
            'property_type' => 'Flat / Apartment',
            'title' => 'Modern 2 BHK in C-Scheme',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'locality' => 'C-Scheme',
            'price' => 30000,
            'deposit_amount' => 60000,
            'sqft' => 1100,
            'bedrooms' => 2,
            'seller_name' => 'Amitabh Sen',
            'seller_phone' => '9876543299',
            'seller_email' => 'amitabh@example.com',
            'opt_in_management' => 0, // Clearly ₹0 leasing only
            'photos' => $photos,
        ]);

        $res->assertSessionHas('success');
        $this->assertDatabaseHas('properties', [
            'title' => 'Modern 2 BHK in C-Scheme',
            'is_managed' => false,
            'user_id' => $user->id,
            'price' => 30000,
        ]);
    }

    /** 21. Broker listing attribution */
    public function test_21_broker_listing_attribution(): void
    {
        Storage::fake('public');
        $broker = User::factory()->create();

        $res = $this->actingAs($broker)->post('/post-property', [
            'purpose' => 'commercial_rent',
            'category' => 'commercial',
            'property_type' => 'Commercial Office',
            'city' => 'Jaipur',
            'locality' => 'Tonk Road',
            'price' => 45000,
            'sqft' => 1200,
            'posted_by' => 'Agent',
            'listing_source' => 'Broker',
            'seller_name' => 'Prime Realty Consultants',
            'seller_phone' => '9876543288',
            'photos' => [UploadedFile::fake()->image('p1.jpg')],
        ]);

        $res->assertSessionHas('success');
        $this->assertDatabaseHas('properties', [
            'listing_source' => 'Broker',
            'seller_name' => 'Prime Realty Consultants',
        ]);
    }

    /** 22. Existing property compatibility */
    public function test_22_existing_property_compatibility(): void
    {
        // Existing property without user_id or verification_level still loads cleanly
        $legacy = Property::create([
            'title' => 'Legacy Apartment From Phase 1',
            'slug' => 'legacy-apartment-phase-1',
            'price' => 18000,
            'city' => 'Jaipur',
            'locality' => 'Malviya Nagar',
            'purpose' => 'rent',
            'seller_name' => 'Old Owner',
            'seller_phone' => '9123456789',
            'status' => 'Available',
        ]);

        $res = $this->get("/properties/{$legacy->slug}");
        $res->assertStatus(200);
        $res->assertSee('Legacy Apartment From Phase 1');
        $res->assertDontSee('9123456789'); // Contact remains protected
    }

    /** 23. Existing user compatibility */
    public function test_23_existing_user_compatibility(): void
    {
        // User created before Phase 3 can log in and browse seamlessly
        $user = User::factory()->create([
            'email' => 'phase1user@housing365.in',
        ]);

        $res = $this->actingAs($user)->get('/properties');
        $res->assertStatus(200);
        $res->assertSee('Explore Listings');
    }

    /** 24. Prime does not bypass Contact Access */
    public function test_24_prime_does_not_bypass_contact_access(): void
    {
        $primeUser = User::factory()->create([
            'is_prime' => true,
            'prime_plan' => 'gold',
            'prime_contacts_limit' => 10,
            'prime_expires_at' => now()->addDays(30),
        ]);

        // Prime user without Contact Access Credits CANNOT view direct owner phone
        $res = $this->actingAs($primeUser)->get("/properties/{$this->rentProp->slug}");
        $res->assertStatus(200);
        $res->assertDontSee($this->secretPhone);
        $res->assertDontSee($this->secretEmail);
        $res->assertSee('Unlock Owner Contact');
    }

    /** 25. PG listing creation with specific fields */
    public function test_25_pg_listing_creation(): void
    {
        Storage::fake('public');
        $operator = User::factory()->create();

        $res = $this->actingAs($operator)->post('/post-property', [
            'purpose' => 'pg',
            'property_type' => 'Paying Guest / PG',
            'city' => 'Jaipur',
            'locality' => 'Mansarovar',
            'price' => 8500,
            'deposit_amount' => 17000,
            'sqft' => 250,
            'pg_gender' => 'girls',
            'food_included' => 1,
            'occupancy_type' => 'Twin Sharing',
            'house_rules' => 'Entry before 10 PM. No smoking.',
            'seller_name' => 'Kaveri Girls Hostel',
            'seller_phone' => '9876543277',
            'photos' => [UploadedFile::fake()->image('pg.jpg')],
        ]);

        $res->assertSessionHas('success');
        $this->assertDatabaseHas('properties', [
            'purpose' => 'pg',
            'category' => 'pg_coliving',
            'pg_gender' => 'girls',
            'food_included' => true,
            'occupancy_type' => 'Twin Sharing',
            'deposit_amount' => 17000,
        ]);
    }

    /** 26. Land listing creation with plot fields */
    public function test_26_land_listing_creation(): void
    {
        Storage::fake('public');
        $owner = User::factory()->create();

        $res = $this->actingAs($owner)->post('/post-property', [
            'purpose' => 'land_sale',
            'property_type' => 'Residential Plot',
            'city' => 'Jaipur',
            'locality' => 'Ajmer Road',
            'price' => 5500000,
            'sqft' => 1800,
            'area_unit' => 'sq.yards',
            'seller_name' => 'Rajesh Sharma',
            'seller_phone' => '9876543266',
            'photos' => [UploadedFile::fake()->image('plot.jpg')],
        ]);

        $res->assertSessionHas('success');
        $this->assertDatabaseHas('properties', [
            'purpose' => 'land_sale',
            'category' => 'plot_land',
            'price' => 5500000,
            'sqft' => 1800,
            'area_unit' => 'sq.yards',
        ]);
    }

    /** 27. Hierarchical SEO URL and canonical verification */
    public function test_27_hierarchical_seo_urls_and_canonicals(): void
    {
        // /rent/rajasthan/jaipur/mansarovar
        $res1 = $this->get('/rent/rajasthan/jaipur/mansarovar');
        $res1->assertStatus(200);
        $res1->assertSee('Mansarovar');
        $res1->assertSee('rel="canonical"', false);

        // /sale/rajasthan/jaipur
        $res2 = $this->get('/sale/rajasthan/jaipur');
        $res2->assertStatus(200);
        $res2->assertSee('Jaipur');

        // /commercial/maharashtra/mumbai
        $res3 = $this->get('/commercial/maharashtra/mumbai');
        $res3->assertStatus(200);
        $res3->assertSee('Mumbai');

        // Unknown location handles gracefully without crashing
        $res4 = $this->get('/rent/nonexistent/nowhere');
        $res4->assertStatus(200);
        $res4->assertSee('noindex', false);
    }
}
