<?php

namespace Tests\Feature;

use App\Models\CareServiceCategory;
use App\Models\Property;
use App\Models\Role;
use App\Models\ServiceQuote;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\VendorProfile;
use Database\Seeders\CareAndManagementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareAndManagementSecurityIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        Role::firstOrCreate(['name' => 'owner'], ['display_name' => 'Property Owner']);
        Role::firstOrCreate(['name' => 'tenant'], ['display_name' => 'Tenant']);
        Role::firstOrCreate(['name' => 'vendor'], ['display_name' => 'Vendor']);
        $this->seed(CareAndManagementSeeder::class);
    }

    protected function createOwnerUser(int $id = 1): User
    {
        $user = User::factory()->create([
            'email' => "owner{$id}@example.com",
            'phone' => "981110000{$id}",
        ]);
        $user->assignRole('owner');

        return $user;
    }

    protected function createTenantUser(int $id = 1): User
    {
        $user = User::factory()->create([
            'email' => "tenant{$id}@example.com",
            'phone' => "982220000{$id}",
        ]);
        $user->assignRole('tenant');

        return $user;
    }

    protected function createVendorUser(int $id = 1): User
    {
        $user = User::factory()->create([
            'email' => "vendor{$id}@example.com",
            'phone' => "983330000{$id}",
        ]);
        $user->assignRole('vendor');

        VendorProfile::create([
            'user_id' => $user->id,
            'business_name' => "Vendor {$id} Services",
            'verification_status' => 'verified',
        ]);

        return $user;
    }

    /**
     * Owner Isolation: Owner A cannot view Owner B's private service requests.
     */
    public function test_owner_a_cannot_view_owner_b_private_service_requests(): void
    {
        $ownerA = $this->createOwnerUser(1);
        $ownerB = $this->createOwnerUser(2);

        $category = CareServiceCategory::first();
        $requestB = ServiceRequest::create([
            'request_number' => 'CARE-B-001',
            'user_id' => $ownerB->id,
            'service_category_id' => $category->id,
            'title' => 'Owner B Private Issue',
            'description' => 'Confidential plumbing issue for Owner B',
            'status' => ServiceRequest::STATUS_SUBMITTED,
        ]);

        $response = $this->actingAs($ownerA)->get(route('care.requests.show', $requestB->id));
        $response->assertStatus(403);
    }

    /**
     * Owner Isolation: Owner A cannot view or approve Owner B's vendor quotes.
     */
    public function test_owner_a_cannot_approve_owner_b_vendor_quotes(): void
    {
        $ownerA = $this->createOwnerUser(1);
        $ownerB = $this->createOwnerUser(2);
        $vendor = $this->createVendorUser(1);

        $category = CareServiceCategory::first();
        $requestB = ServiceRequest::create([
            'request_number' => 'CARE-B-002',
            'user_id' => $ownerB->id,
            'service_category_id' => $category->id,
            'assigned_vendor_id' => $vendor->id,
            'title' => 'Owner B Electrical Fix',
            'description' => 'Fix power outlet',
            'status' => ServiceRequest::STATUS_AWAITING_APPROVAL,
        ]);

        $quote = ServiceQuote::create([
            'quote_number' => 'SQ-B-001',
            'service_request_id' => $requestB->id,
            'vendor_id' => $vendor->id,
            'labour_amount' => 500,
            'materials_amount' => 500,
            'total_amount' => 1000,
            'status' => ServiceQuote::STATUS_PENDING,
        ]);

        // Owner A attempts to approve Owner B's quote -> 403 Forbidden
        $response = $this->actingAs($ownerA)->post(route('care.quotes.approve', $quote->id));
        $response->assertStatus(403);
        $this->assertEquals(ServiceQuote::STATUS_PENDING, $quote->fresh()->status);
    }

    /**
     * Tenant Isolation: Tenant A cannot view Tenant B's service requests.
     */
    public function test_tenant_a_cannot_view_tenant_b_service_requests(): void
    {
        $tenantA = $this->createTenantUser(1);
        $tenantB = $this->createTenantUser(2);

        $category = CareServiceCategory::first();
        $requestB = ServiceRequest::create([
            'request_number' => 'CARE-TENANT-B',
            'user_id' => $tenantB->id,
            'service_category_id' => $category->id,
            'title' => 'Tenant B Leaking Sink',
            'description' => 'Fix bathroom sink in rented room',
            'status' => ServiceRequest::STATUS_SUBMITTED,
        ]);

        $response = $this->actingAs($tenantA)->get(route('care.requests.show', $requestB->id));
        $response->assertStatus(403);
    }

    /**
     * Tenant Security: Tenant cannot access owner property management explore or agreement routes.
     */
    public function test_tenant_cannot_access_owner_management_enrollment_routes(): void
    {
        $owner = $this->createOwnerUser(1);
        $tenant = $this->createTenantUser(1);

        $property = Property::create([
            'title' => 'Owner Apartment',
            'slug' => 'owner-apartment-'.uniqid(),
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'user_id' => $owner->id,
            'seller_email' => $owner->email,
            'seller_phone' => $owner->phone,
            'price' => 30000,
            'status' => 'Available',
            'is_managed' => false,
        ]);

        // Tenant tries to explore management for owner's property -> 403
        $response = $this->actingAs($tenant)->get(route('management.explore', $property->id));
        $response->assertStatus(403);

        // Tenant tries to request management -> 403
        $postResponse = $this->actingAs($tenant)->post(route('management.request', $property->id), [
            'management_plan_id' => 1,
        ]);
        $postResponse->assertStatus(403);
    }

    /**
     * Vendor Isolation: Vendor A cannot view jobs assigned to Vendor B.
     */
    public function test_vendor_a_cannot_view_jobs_assigned_to_vendor_b(): void
    {
        $vendorA = $this->createVendorUser(1);
        $vendorB = $this->createVendorUser(2);
        $owner = $this->createOwnerUser(1);

        $category = CareServiceCategory::first();
        $requestB = ServiceRequest::create([
            'request_number' => 'CARE-JOB-B',
            'user_id' => $owner->id,
            'service_category_id' => $category->id,
            'assigned_vendor_id' => $vendorB->id,
            'title' => 'Assigned only to Vendor B',
            'description' => 'AC servicing',
            'status' => ServiceRequest::STATUS_VENDOR_ASSIGNED,
        ]);

        $response = $this->actingAs($vendorA)->get(route('vendor.jobs.show', $requestB->id));
        $response->assertStatus(403);
    }

    /**
     * Vendor Security: Vendor cannot see owner personal phone or email in job view.
     */
    public function test_vendor_cannot_see_owner_contact_in_job_view(): void
    {
        $vendor = $this->createVendorUser(1);
        $owner = User::factory()->create([
            'name' => 'Dr. Private Owner',
            'email' => 'dr_private_owner@confidential.in',
            'phone' => '+91 98888 77777',
        ]);
        $owner->assignRole('owner');

        $property = Property::create([
            'title' => 'Private Villa',
            'slug' => 'private-villa-'.uniqid(),
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'user_id' => $owner->id,
            'seller_email' => $owner->email,
            'seller_phone' => $owner->phone,
            'price' => 75000,
            'status' => 'Available',
            'is_managed' => false,
        ]);

        $category = CareServiceCategory::first();
        $request = ServiceRequest::create([
            'request_number' => 'CARE-ASSIGNED-V1',
            'user_id' => $owner->id,
            'property_id' => $property->id,
            'service_category_id' => $category->id,
            'assigned_vendor_id' => $vendor->id,
            'title' => 'Plumbing Inspection',
            'description' => 'Inspect pressure pump',
            'status' => ServiceRequest::STATUS_VENDOR_ASSIGNED,
        ]);

        $response = $this->actingAs($vendor)->get(route('vendor.jobs.show', $request->id));
        $response->assertStatus(200);

        // Assert strictly zero leakage of owner's direct contact
        $response->assertDontSee('dr_private_owner@confidential.in');
        $response->assertDontSee('+91 98888 77777');
        $response->assertDontSee('9888877777');
    }

    /**
     * Route Tampering / Guest Security: Direct URL manipulation for /care/*, /management/*, /vendor/*
     */
    public function test_guest_blocked_from_care_management_vendor_routes(): void
    {
        // Unauthenticated guest cannot view service request details
        $this->get('/care/requests/1')->assertRedirect('/login');

        // Unauthenticated guest cannot explore management
        $this->get('/properties/1/management/explore')->assertRedirect('/login');

        // Unauthenticated guest cannot view vendor jobs
        $this->get(route('vendor.jobs'))->assertRedirect('/login');

        // Non-vendor cannot view vendor portal
        $owner = $this->createOwnerUser(1);
        $this->actingAs($owner)->get(route('vendor.jobs'))->assertStatus(403);
    }
}
