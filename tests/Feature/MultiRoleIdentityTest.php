<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MultiRoleIdentityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed default roles
        $this->artisan('migrate');
    }

    public function test_user_can_have_multiple_concurrent_roles(): void
    {
        $user = User::factory()->create([
            'email' => 'multi@housing365.in',
        ]);

        $user->assignRole('owner');
        $user->assignRole('broker');

        $this->assertTrue($user->hasRole('owner'));
        $this->assertTrue($user->hasRole('broker'));
        $this->assertFalse($user->hasRole('builder'));
        $this->assertEquals(2, $user->activeRoles()->count());
    }

    public function test_has_role_and_has_any_and_has_all_helpers(): void
    {
        $user = User::factory()->create();
        $user->assignRole('tenant');
        $user->assignRole('buyer');

        $this->assertTrue($user->hasRole('tenant'));
        $this->assertTrue($user->hasRole('buyer'));
        $this->assertFalse($user->hasRole('seller'));

        $this->assertTrue($user->hasAnyRole(['tenant', 'seller']));
        $this->assertFalse($user->hasAnyRole(['seller', 'builder']));

        $this->assertTrue($user->hasAllRoles(['tenant', 'buyer']));
        $this->assertFalse($user->hasAllRoles(['tenant', 'seller']));
    }

    public function test_user_can_switch_roles_between_assigned_personas(): void
    {
        $user = User::factory()->create();
        $user->assignRole('owner');
        $user->assignRole('tenant');

        $this->actingAs($user);

        // Switch to owner
        $response = $this->get(route('dashboard.switch', 'owner'));
        $response->assertRedirect(route('dashboard.owner'));
        $this->assertEquals('owner', session('active_role'));

        // Switch to tenant
        $response = $this->get(route('dashboard.switch', 'tenant'));
        $response->assertRedirect(route('dashboard.tenant'));
        $this->assertEquals('tenant', session('active_role'));
    }

    public function test_user_cannot_switch_to_unassigned_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole('tenant'); // only tenant

        $this->actingAs($user);

        // Attempting to switch to broker must return 403 Forbidden
        $response = $this->get(route('dashboard.switch', 'broker'));
        $response->assertStatus(403);
    }

    public function test_server_side_authorization_blocks_unauthorized_workspace_access(): void
    {
        $tenantUser = User::factory()->create();
        $tenantUser->assignRole('tenant');

        $this->actingAs($tenantUser);

        // Allowed
        $this->get(route('dashboard.tenant'))->assertStatus(200);

        // Forbidden workspaces
        $this->get(route('dashboard.owner'))->assertStatus(403);
        $this->get(route('dashboard.broker'))->assertStatus(403);
        $this->get(route('dashboard.builder'))->assertStatus(403);
        $this->get(route('dashboard.vendor'))->assertStatus(403);
    }

    public function test_multi_role_user_landing_on_dashboard_shows_selector(): void
    {
        $user = User::factory()->create();
        $user->assignRole('owner');
        $user->assignRole('broker');

        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Your Housing365 Workspaces');
        $response->assertSee('Property Owner');
        $response->assertSee('Real Estate Broker');
    }

    public function test_single_role_user_landing_on_dashboard_redirects_to_workspace(): void
    {
        $user = User::factory()->create();
        $user->assignRole('owner');

        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('dashboard.owner'));
    }

    public function test_registration_with_multiple_personas(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Kunal Sharma',
            'email' => 'kunal@test.com',
            'phone' => '+91 98290 12345',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'city' => 'Jaipur',
            'roles' => ['owner', 'buyer'],
        ]);

        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', 'kunal@test.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('owner'));
        $this->assertTrue($user->hasRole('buyer'));
        $this->assertFalse($user->hasRole('broker'));
    }

    public function test_existing_users_still_authenticate_and_preserve_credentials(): void
    {
        $user = User::create([
            'name' => 'Existing User',
            'email' => 'existing@housing365.in',
            'password' => Hash::make('secretPassword99'),
            'phone' => '+91 99999 88888',
            'user_type' => 'tenant',
        ]);
        $user->assignRole('tenant');

        $loginResponse = $this->post(route('login'), [
            'email' => 'existing@housing365.in',
            'password' => 'secretPassword99',
        ]);

        $this->assertAuthenticatedAs($user);
        $loginResponse->assertRedirect(route('dashboard'));
    }

    public function test_existing_properties_remain_associated_with_owner(): void
    {
        $owner = User::factory()->create([
            'phone' => '+91 63761 63263',
            'email' => 'owner@housing365.in',
        ]);
        $owner->assignRole('owner');

        $property = Property::create([
            'title' => 'Sample Luxury Villa',
            'slug' => 'sample-luxury-villa-101',
            'property_type' => 'villa',
            'purpose' => 'rent',
            'price' => 50000,
            'city' => 'Jaipur',
            'seller_phone' => '+91 63761 63263',
            'seller_email' => 'owner@housing365.in',
            'status' => 'available',
        ]);

        $this->actingAs($owner);
        $response = $this->get(route('dashboard.owner'));

        $response->assertStatus(200);
        $response->assertSee('Sample Luxury Villa');
    }

    public function test_existing_inquiries_remain_intact_and_accessible(): void
    {
        $inquiry = Inquiry::create([
            'name' => 'Inquiring User',
            'email' => 'inquirer@housing365.in',
            'phone' => '+91 91234 56789',
            'subject' => 'Viewing Request for 3 BHK',
            'message' => 'Interested in visiting this Sunday.',
            'status' => 'new',
        ]);

        $this->assertDatabaseHas('inquiries', [
            'id' => $inquiry->id,
            'email' => 'inquirer@housing365.in',
        ]);
    }

    public function test_contact_access_remains_strictly_protected_for_all_personas(): void
    {
        $brokerUser = User::factory()->create();
        $brokerUser->assignRole('broker');

        $property = Property::create([
            'title' => 'Secret Phone Flat',
            'slug' => 'secret-phone-flat',
            'property_type' => 'apartment',
            'purpose' => 'rent',
            'price' => 25000,
            'city' => 'Mumbai',
            'seller_phone' => '+91 99999 11111',
            'seller_email' => 'private_landlord@test.com',
            'status' => 'available',
        ]);

        // Broker without Contact Access cannot see owner phone
        $this->actingAs($brokerUser);
        $response = $this->get(route('properties.show', $property->slug));

        $response->assertStatus(200);
        $response->assertDontSee('+91 99999 11111');
        $response->assertDontSee('private_landlord@test.com');
    }

    public function test_legacy_prime_fields_do_not_grant_contact_access(): void
    {
        $user = User::factory()->create([
            'is_prime' => true,
            'prime_plan' => 'Prime Pro',
            'prime_contacts_limit' => 50,
            'unlocked_contacts_count' => 0,
        ]);
        $user->assignRole('tenant');

        $property = Property::create([
            'title' => 'Protected Direct Contact',
            'slug' => 'protected-direct-contact-99',
            'property_type' => 'flat',
            'purpose' => 'rent',
            'price' => 30000,
            'city' => 'Delhi',
            'seller_phone' => '+91 88888 22222',
            'seller_email' => 'delhi_owner@test.com',
            'status' => 'available',
        ]);

        $this->actingAs($user);

        // Attempting to unlock via endpoint without contact access package credits must fail (402)
        $response = $this->postJson(route('properties.unlock-contact', $property->id));
        $response->assertStatus(402);
        $response->assertJson([
            'success' => false,
            'require_credits' => true,
        ]);
    }

    public function test_user_cannot_have_duplicate_role_assignments(): void
    {
        $user = User::factory()->create();

        // Assign same role three times
        $user->assignRole('owner');
        $user->assignRole('owner');
        $user->assignRole('owner');

        $roleCount = $user->roles()->where('name', 'owner')->count();
        $this->assertEquals(1, $roleCount);
    }

    public function test_removing_one_role_does_not_delete_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Persistent User',
            'email' => 'persistent@housing365.in',
        ]);

        $user->assignRole('owner');
        $user->assignRole('tenant');

        $this->assertTrue($user->hasRole('owner'));
        $this->assertTrue($user->hasRole('tenant'));

        // Remove owner role
        $user->removeRole('owner');

        $this->assertFalse($user->hasRole('owner'));
        $this->assertTrue($user->hasRole('tenant'));

        // Verify user account itself remains intact
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'persistent@housing365.in',
        ]);
    }

    public function test_multi_role_user_with_three_roles_and_selector_scoping(): void
    {
        $user = User::factory()->create();
        $user->assignRole('owner');
        $user->assignRole('broker');
        $user->assignRole('builder');

        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        // Shows exactly the 3 assigned roles
        $response->assertSee('Property Owner');
        $response->assertSee('Real Estate Broker');
        $response->assertSee('Builder / Developer');

        // Does NOT show unassigned roles in workspace cards
        $response->assertDontSee('Co-living Operator');
        $response->assertDontSee('Coworking Operator');

        // Can access all 3 assigned workspaces
        $this->get(route('dashboard.owner'))->assertStatus(200);
        $this->get(route('dashboard.broker'))->assertStatus(200);
        $this->get(route('dashboard.builder'))->assertStatus(200);

        // Forbidden from unassigned workspaces
        $this->get(route('dashboard.vendor'))->assertStatus(403);
        $this->get(route('dashboard.coworking-operator'))->assertStatus(403);
    }

    public function test_owner_a_cannot_see_owner_b_properties(): void
    {
        $ownerA = User::factory()->create([
            'phone' => '+91 11111 22222',
            'email' => 'ownerA@housing365.in',
        ]);
        $ownerA->assignRole('owner');

        $ownerB = User::factory()->create([
            'phone' => '+91 33333 44444',
            'email' => 'ownerB@housing365.in',
        ]);
        $ownerB->assignRole('owner');

        $propertyA = Property::create([
            'title' => 'Owner A Private Villa',
            'slug' => 'owner-a-private-villa',
            'property_type' => 'villa',
            'purpose' => 'rent',
            'price' => 45000,
            'city' => 'Jaipur',
            'seller_phone' => '+91 11111 22222',
            'seller_email' => 'ownerA@housing365.in',
            'status' => 'available',
        ]);

        $propertyB = Property::create([
            'title' => 'Owner B Secret Apartment',
            'slug' => 'owner-b-secret-apartment',
            'property_type' => 'apartment',
            'purpose' => 'rent',
            'price' => 35000,
            'city' => 'Mumbai',
            'seller_phone' => '+91 33333 44444',
            'seller_email' => 'ownerB@housing365.in',
            'status' => 'available',
        ]);

        // When Owner A visits their dashboard, they must see Property A, but NEVER Property B
        $this->actingAs($ownerA);
        $responseA = $this->get(route('dashboard.owner'));
        $responseA->assertStatus(200);
        $responseA->assertSee('Owner A Private Villa');
        $responseA->assertDontSee('Owner B Secret Apartment');

        // When Owner B visits their dashboard, they must see Property B, but NEVER Property A
        $this->actingAs($ownerB);
        $responseB = $this->get(route('dashboard.owner'));
        $responseB->assertStatus(200);
        $responseB->assertSee('Owner B Secret Apartment');
        $responseB->assertDontSee('Owner A Private Villa');
    }

    public function test_direct_url_manipulation_is_blocked_for_all_unauthorized_workspaces(): void
    {
        $tenantOnlyUser = User::factory()->create();
        $tenantOnlyUser->assignRole('tenant');

        $this->actingAs($tenantOnlyUser);

        $forbiddenRoutes = [
            'dashboard.owner',
            'dashboard.broker',
            'dashboard.builder',
            'dashboard.pg-operator',
            'dashboard.coliving-operator',
            'dashboard.coworking-operator',
            'dashboard.vendor',
            'dashboard.buyer',
            'dashboard.seller',
        ];

        foreach ($forbiddenRoutes as $routeName) {
            $response = $this->get(route($routeName));
            $response->assertStatus(403);
        }
    }

    public function test_organization_membership_does_not_bypass_role_authorization(): void
    {
        $user = User::factory()->create();
        $user->assignRole('tenant');

        $org = Organization::create([
            'name' => 'Apex Brokerage Services',
            'slug' => 'apex-brokerage',
            'type' => 'brokerage',
            'city' => 'Mumbai',
        ]);

        OrganizationMember::create([
            'organization_id' => $org->id,
            'user_id' => $user->id,
            'role' => 'member',
        ]);

        $this->actingAs($user);

        // Even though user is in an organization, they lack the 'broker' role
        $response = $this->get(route('dashboard.broker'));
        $response->assertStatus(403);
    }

    public function test_registration_with_duplicate_roles_deduplicates_safely(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Deduplicate User',
            'email' => 'dedup@housing365.in',
            'phone' => '+91 97777 66666',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'city' => 'Bengaluru',
            'roles' => ['owner', 'owner', 'tenant', 'tenant'],
        ]);

        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', 'dedup@housing365.in')->first();
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->roles()->where('name', 'owner')->count());
        $this->assertEquals(1, $user->roles()->where('name', 'tenant')->count());
        $this->assertEquals(2, $user->roles()->count());
    }

    public function test_registration_without_roles_defaults_to_tenant(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Default Role User',
            'email' => 'defaultrole@housing365.in',
            'phone' => '+91 95555 44444',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'city' => 'Jaipur',
            // No roles supplied
        ]);

        $response->assertRedirect(route('dashboard'));

        $user = User::where('email', 'defaultrole@housing365.in')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('tenant'));
        $this->assertEquals(1, $user->roles()->count());
    }
}
