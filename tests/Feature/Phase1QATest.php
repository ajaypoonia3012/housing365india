<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase1QATest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_communicates_everything_property_one_platform(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Everything Property.');
        $response->assertSee('One Platform.');
        $response->assertSee('List my Property');
        $response->assertSee('Find a Property');
        $response->assertSee('₹0 Standard Listing &amp; Placement Fee', false);
        $response->assertSee('Your Property, Taken Care Of.');
        $response->assertDontSee('MagicBricks');
        $response->assertDontSee('NoBroker');
        $response->assertDontSee('MBPrime');
    }

    public function test_header_and_services_mega_menu_groups(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Owners');
        $response->assertSee('Tenants');
        $response->assertSee('Services');
        $response->assertSee('Pricing');
        $response->assertSee('Resources');
        $response->assertSee('Franchising');
        $response->assertSee('About');
        $response->assertSee('Contact');

        // Mega menu 6 groups
        $response->assertSee('1. Rent &amp; Lease', false);
        $response->assertSee('2. Live &amp; Move', false);
        $response->assertSee('3. Property Care', false);
        $response->assertSee('4. Commercial', false);
        $response->assertSee('5. Buy &amp; Sell', false);
        $response->assertSee('6. Professional Network', false);
        $response->assertSee('Optional');
    }

    public function test_new_and_deprecated_routes(): void
    {
        // New pages load successfully
        $this->get('/resources')->assertStatus(200);
        $this->get('/franchising')->assertStatus(200);
        $this->get('/housing365-care')->assertStatus(200);
        $this->get('/services/care')->assertStatus(200);
        $this->get('/locations')->assertStatus(200);

        // Deprecated routes redirect cleanly with 301
        $this->get('/prime')->assertRedirect('/pricing');
        $this->get('/my-prime')->assertRedirect('/properties');
        $this->get('/mbprime')->assertRedirect('/pricing');
        $this->get('/us')->assertRedirect('/india');
    }

    public function test_owner_contact_privacy_enforced_server_side_for_guests(): void
    {
        $property = Property::create([
            'title' => 'Test Luxury Apartment',
            'slug' => 'test-luxury-apartment',
            'city' => 'Jaipur',
            'price' => 35000,
            'purpose' => 'rent',
            'category' => 'residential',
            'seller_name' => 'Rahul Sharma',
            'seller_phone' => '+91 98765 43210',
            'status' => 'Available',
        ]);

        // Unauthenticated guest calling unlock-contact receives 401 Unauthorized
        $response = $this->postJson("/properties/{$property->id}/unlock-contact");
        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
            'require_login' => true,
        ]);
    }

    public function test_owner_contact_requires_contact_access_for_authenticated_users(): void
    {
        $user = User::factory()->create();
        $property = Property::create([
            'title' => 'Test 2 BHK Flat',
            'slug' => 'test-2-bhk-flat',
            'city' => 'Mumbai',
            'price' => 45000,
            'purpose' => 'rent',
            'category' => 'residential',
            'seller_name' => 'Vikram Verma',
            'seller_phone' => '+91 99887 76655',
            'status' => 'Available',
        ]);

        // Authenticated user without contact credits receives 402 with require_credits => true
        $response = $this->actingAs($user)->postJson("/properties/{$property->id}/unlock-contact");
        $response->assertStatus(402);
        $response->assertJson([
            'success' => false,
            'require_credits' => true,
        ]);
    }
}
