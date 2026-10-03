<?php

namespace Tests\Feature;

use App\Models\ContactAccessPackage;
use App\Models\ContactAccessPurchase;
use App\Models\ContactUnlock;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactAccessPaywallTest extends TestCase
{
    use RefreshDatabase;

    protected ContactAccessPackage $package;

    protected Property $property;

    protected string $secretPhone = '+91 98765 43210';

    protected string $secretEmail = 'direct.owner@example.in';

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
            'sort_order' => 1,
        ]);

        $this->property = Property::create([
            'title' => 'Prestige Sky Villas',
            'slug' => 'prestige-sky-villas',
            'city' => 'Bengaluru',
            'price' => 75000,
            'purpose' => 'rent',
            'category' => 'residential',
            'seller_name' => 'Ananya Sharma',
            'seller_phone' => $this->secretPhone,
            'seller_email' => $this->secretEmail,
            'status' => 'Available',
        ]);
    }

    /**
     * 1. Guest cannot see owner phone number in HTML.
     */
    public function test_guest_cannot_see_phone(): void
    {
        $response = $this->get("/properties/{$this->property->slug}");

        $response->assertStatus(200);
        $response->assertDontSee($this->secretPhone);
        $response->assertDontSee('9876543210');
    }

    /**
     * 2. Guest cannot see owner email in HTML.
     */
    public function test_guest_cannot_see_email(): void
    {
        $response = $this->get("/properties/{$this->property->slug}");

        $response->assertStatus(200);
        $response->assertDontSee($this->secretEmail);
    }

    /**
     * 3. Registered user without Contact Access cannot see owner phone.
     */
    public function test_registered_user_without_contact_access_cannot_see_phone(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("/properties/{$this->property->slug}");

        $response->assertStatus(200);
        $response->assertDontSee($this->secretPhone);
        $response->assertDontSee('9876543210');
    }

    /**
     * 4. Registered user without Contact Access cannot see owner email.
     */
    public function test_registered_user_without_contact_access_cannot_see_email(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("/properties/{$this->property->slug}");

        $response->assertStatus(200);
        $response->assertDontSee($this->secretEmail);
    }

    /**
     * 5. Unauthorized user cannot access contact endpoint.
     */
    public function test_unauthorized_user_cannot_access_contact_endpoint(): void
    {
        // Unauthenticated guest receives 401
        $guestResponse = $this->postJson("/properties/{$this->property->id}/unlock-contact");
        $guestResponse->assertStatus(401);
        $guestResponse->assertJson(['success' => false, 'require_login' => true]);

        // Authenticated user with 0 credits receives 402 with require_credits => true
        $user = User::factory()->create();
        $userResponse = $this->actingAs($user)->postJson("/properties/{$this->property->id}/unlock-contact");
        $userResponse->assertStatus(402);
        $userResponse->assertJson(['success' => false, 'require_credits' => true]);
        $userResponse->assertJsonMissing(['phone' => $this->secretPhone]);
    }

    /**
     * 6. Free enquiry works without leaking owner private phone/email.
     */
    public function test_free_enquiry_works(): void
    {
        $response = $this->post('/inquiry', [
            'name' => 'Pooja Verma',
            'email' => 'pooja@example.in',
            'phone' => '+91 91234 56789',
            'service_type' => 'Property Tour / Application',
            'subject' => "Inquiry for {$this->property->title}",
            'message' => 'I would like to book a physical visit this Saturday.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('inquiries', [
            'email' => 'pooja@example.in',
        ]);

        // Verify enquiry response does not expose owner secret details
        $this->followRedirects($response)->assertDontSee($this->secretPhone);
    }

    /**
     * 7. Contact Access purchase flow works.
     */
    public function test_contact_access_purchase_flow_works(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/contact-access/purchase', [
            'package_id' => $this->package->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'amount' => '299.00',
            'currency' => 'INR',
        ]);
        $this->assertDatabaseHas('contact_access_purchases', [
            'user_id' => $user->id,
            'payment_status' => 'pending',
            'credits_granted' => 3,
        ]);
    }

    /**
     * 8. Failed payment grants zero credits.
     */
    public function test_failed_payment_grants_zero_credits(): void
    {
        $user = User::factory()->create();

        $purchase = ContactAccessPurchase::create([
            'user_id' => $user->id,
            'contact_access_package_id' => $this->package->id,
            'order_reference' => 'CA-FAIL-001',
            'credits_granted' => 3,
            'credits_remaining' => 3,
            'amount_paid' => 299.00,
            'currency' => 'INR',
            'payment_gateway' => 'sandbox',
            'payment_status' => 'pending',
            'gateway_order_id' => 'sandbox_order_fail',
        ]);

        // Verify with invalid signature
        $response = $this->actingAs($user)->postJson('/contact-access/verify', [
            'purchase_id' => $purchase->id,
            'gateway_order_id' => 'sandbox_order_fail',
            'gateway_payment_id' => 'pay_invalid_123',
            'signature' => 'invalid_signature_tampered',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);

        $this->assertEquals('failed', $purchase->fresh()->payment_status);
        $this->assertEquals(0, $user->fresh()->availableContactCredits());
    }

    /**
     * 9. Successful payment grants correct credits.
     */
    public function test_successful_payment_grants_correct_credits(): void
    {
        $user = User::factory()->create();

        $purchase = ContactAccessPurchase::create([
            'user_id' => $user->id,
            'contact_access_package_id' => $this->package->id,
            'order_reference' => 'CA-PASS-001',
            'credits_granted' => 3,
            'credits_remaining' => 3,
            'amount_paid' => 299.00,
            'currency' => 'INR',
            'payment_gateway' => 'sandbox',
            'payment_status' => 'pending',
            'gateway_order_id' => 'sandbox_order_pass',
        ]);

        $response = $this->actingAs($user)->postJson('/contact-access/verify', [
            'purchase_id' => $purchase->id,
            'gateway_order_id' => 'sandbox_order_pass',
            'gateway_payment_id' => 'pay_valid_123',
            'signature' => 'test_signature_valid',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertEquals('paid', $purchase->fresh()->payment_status);
        $this->assertEquals(3, $user->fresh()->availableContactCredits());
    }

    /**
     * 10. Unlock consumes exactly correct credit.
     */
    public function test_unlock_consumes_correct_credits(): void
    {
        $user = User::factory()->create();

        ContactAccessPurchase::create([
            'user_id' => $user->id,
            'contact_access_package_id' => $this->package->id,
            'order_reference' => 'CA-UNLOCK-001',
            'credits_granted' => 3,
            'credits_remaining' => 3,
            'amount_paid' => 299.00,
            'currency' => 'INR',
            'payment_gateway' => 'sandbox',
            'payment_status' => 'paid',
            'paid_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        $this->assertEquals(3, $user->availableContactCredits());

        $response = $this->actingAs($user)->postJson("/properties/{$this->property->id}/unlock-contact");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'contact' => [
                'phone' => $this->secretPhone,
                'email' => $this->secretEmail,
            ],
            'credits_remaining' => 2,
        ]);

        $this->assertEquals(2, $user->fresh()->availableContactCredits());
    }

    /**
     * 11. Duplicate unlock does not incorrectly charge.
     */
    public function test_duplicate_unlock_does_not_incorrectly_charge(): void
    {
        $user = User::factory()->create();

        ContactAccessPurchase::create([
            'user_id' => $user->id,
            'contact_access_package_id' => $this->package->id,
            'order_reference' => 'CA-DUP-001',
            'credits_granted' => 5,
            'credits_remaining' => 5,
            'amount_paid' => 499.00,
            'currency' => 'INR',
            'payment_gateway' => 'sandbox',
            'payment_status' => 'paid',
            'paid_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        // First unlock: deducts 1 credit (5 -> 4)
        $firstUnlock = $this->actingAs($user)->postJson("/properties/{$this->property->id}/unlock-contact");
        $firstUnlock->assertStatus(200);
        $this->assertEquals(4, $user->fresh()->availableContactCredits());

        // Second unlock on same property: 0 credits deducted
        $secondUnlock = $this->actingAs($user)->postJson("/properties/{$this->property->id}/unlock-contact");
        $secondUnlock->assertStatus(200);
        $secondUnlock->assertJson(['success' => true, 'already_unlocked' => true]);
        $this->assertEquals(4, $user->fresh()->availableContactCredits());
        $this->assertEquals(1, ContactUnlock::where('user_id', $user->id)->where('property_id', $this->property->id)->count());
    }

    /**
     * 12. User cannot unlock another user's private data or verify another user's purchase.
     */
    public function test_user_cannot_unlock_another_users_private_data(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $purchaseA = ContactAccessPurchase::create([
            'user_id' => $userA->id,
            'contact_access_package_id' => $this->package->id,
            'order_reference' => 'CA-USERA-001',
            'credits_granted' => 3,
            'credits_remaining' => 3,
            'amount_paid' => 299.00,
            'currency' => 'INR',
            'payment_gateway' => 'sandbox',
            'payment_status' => 'pending',
            'gateway_order_id' => 'order_user_a',
        ]);

        // User B cannot verify User A's purchase
        $verifyResponse = $this->actingAs($userB)->postJson('/contact-access/verify', [
            'purchase_id' => $purchaseA->id,
            'gateway_order_id' => 'order_user_a',
            'gateway_payment_id' => 'pay_b_steal',
            'signature' => 'test_signature_valid',
        ]);
        $verifyResponse->assertStatus(404);

        // User A unlocks property; User B viewing the property still cannot see details
        $purchaseA->update(['payment_status' => 'paid', 'paid_at' => now()]);
        $this->actingAs($userA)->postJson("/properties/{$this->property->id}/unlock-contact");

        $viewResponse = $this->actingAs($userB)->get("/properties/{$this->property->slug}");
        $viewResponse->assertDontSee($this->secretPhone);
        $viewResponse->assertDontSee($this->secretEmail);
    }

    /**
     * 13. Prime fields do not grant Contact Access.
     */
    public function test_prime_fields_do_not_grant_contact_access(): void
    {
        $legacyPrimeUser = User::factory()->create([
            'is_prime' => true,
            'prime_plan' => 'Unlimited',
            'prime_contacts_limit' => 999,
            'prime_expires_at' => now()->addYear(),
        ]);

        $this->assertEquals(0, $legacyPrimeUser->availableContactCredits());

        $response = $this->actingAs($legacyPrimeUser)->postJson("/properties/{$this->property->id}/unlock-contact");
        $response->assertStatus(402);
        $response->assertJson([
            'success' => false,
            'require_credits' => true,
        ]);
    }

    /**
     * 14. Inactive packages cannot be purchased.
     */
    public function test_inactive_package_cannot_be_purchased(): void
    {
        $inactivePkg = ContactAccessPackage::create([
            'name' => 'Deprecated Pass',
            'slug' => 'deprecated-pass',
            'credits' => 1,
            'price' => 99.00,
            'is_active' => false,
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/contact-access/purchase', [
            'package_id' => $inactivePkg->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    /**
     * 15. Duplicate payment callback does not duplicate credits.
     */
    public function test_duplicate_payment_callback_does_not_duplicate_credits(): void
    {
        $user = User::factory()->create();

        $purchase = ContactAccessPurchase::create([
            'user_id' => $user->id,
            'contact_access_package_id' => $this->package->id,
            'order_reference' => 'CA-IDEM-001',
            'credits_granted' => 3,
            'credits_remaining' => 3,
            'amount_paid' => 299.00,
            'currency' => 'INR',
            'payment_gateway' => 'sandbox',
            'payment_status' => 'pending',
            'gateway_order_id' => 'sandbox_order_idem',
        ]);

        // First verification
        $this->actingAs($user)->postJson('/contact-access/verify', [
            'purchase_id' => $purchase->id,
            'gateway_order_id' => 'sandbox_order_idem',
            'gateway_payment_id' => 'pay_idem_123',
            'signature' => 'test_signature_valid',
        ]);
        $this->assertEquals(3, $user->fresh()->availableContactCredits());

        // Duplicate second verification callback
        $this->actingAs($user)->postJson('/contact-access/verify', [
            'purchase_id' => $purchase->id,
            'gateway_order_id' => 'sandbox_order_idem',
            'gateway_payment_id' => 'pay_idem_123',
            'signature' => 'test_signature_valid',
        ]);
        // Credits must still be 3, not 6
        $this->assertEquals(3, $user->fresh()->availableContactCredits());
    }

    /**
     * 16. Existing property functionality and owner viewing own property still works.
     */
    public function test_existing_property_ownership_and_listings_work(): void
    {
        $owner = User::factory()->create();
        $ownedProperty = Property::create([
            'title' => 'Owner Penthouse',
            'slug' => 'owner-penthouse',
            'city' => 'Delhi',
            'price' => 120000,
            'seller_name' => $owner->name,
            'seller_phone' => $owner->phone ?? '+91 99911 22233',
            'seller_email' => $owner->email,
            'status' => 'Available',
        ]);

        $this->assertTrue($ownedProperty->isUnlockedBy($owner));

        $response = $this->actingAs($owner)->postJson("/properties/{$ownedProperty->id}/unlock-contact");
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'already_unlocked' => true,
        ]);
    }
}
