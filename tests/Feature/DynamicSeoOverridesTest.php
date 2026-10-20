<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\SeoMetaOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DynamicSeoOverridesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that an existing SEO override replaces title and meta description.
     */
    public function test_existing_seo_override_replaces_title_and_description(): void
    {
        SeoMetaOverride::create([
            'path' => '/about',
            'meta_title' => 'Custom About Housing365 Title',
            'meta_description' => 'Custom About Housing365 Meta Description for SEO testing.',
            'is_indexable' => true,
        ]);

        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('<title>Custom About Housing365 Title</title>', false);
        $response->assertSee('<meta name="description" content="Custom About Housing365 Meta Description for SEO testing.">', false);
        $response->assertSee('<meta property="og:title" content="Custom About Housing365 Title">', false);
        $response->assertSee('<meta name="twitter:title" content="Custom About Housing365 Title">', false);
    }

    /**
     * Test that a missing override falls back to Blade metadata.
     */
    public function test_missing_override_falls_back_to_blade_metadata(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('<title>', false);
        $response->assertSee('Housing365', false);
        $response->assertSee('<meta name="description"', false);
    }

    /**
     * Test that canonical URL override works.
     */
    public function test_canonical_url_override_works(): void
    {
        SeoMetaOverride::create([
            'path' => '/about',
            'canonical_url' => 'https://housing365.in/preferred-about',
            'is_indexable' => true,
        ]);

        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('<link rel="canonical" href="https://housing365.in/preferred-about">', false);
    }

    /**
     * Test that non-indexable override outputs noindex, follow.
     */
    public function test_non_indexable_override_outputs_noindex_follow(): void
    {
        SeoMetaOverride::create([
            'path' => '/about',
            'is_indexable' => false,
        ]);

        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('<meta name="robots" content="noindex, follow">', false);
    }

    /**
     * Test that missing overrides do not break page rendering.
     */
    public function test_missing_overrides_do_not_break_page_rendering(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    /**
     * Test that HTML metadata is properly escaped.
     */
    public function test_html_metadata_is_escaped(): void
    {
        SeoMetaOverride::create([
            'path' => '/about',
            'meta_title' => 'Title with <script>alert("xss")</script> & entities',
            'meta_description' => 'Description with <b>bold</b> and "quotes"',
            'is_indexable' => true,
        ]);

        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertDontSee('<script>alert("xss")</script>', false);
        $response->assertSee('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false);
    }

    /**
     * Test that exactly one canonical tag is rendered on the page.
     */
    public function test_exactly_one_canonical_tag_is_rendered(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $content = $response->getContent();
        $canonicalCount = substr_count($content, 'rel="canonical"');

        $this->assertSame(1, $canonicalCount, "Expected exactly 1 canonical tag, found {$canonicalCount}.");
    }

    /**
     * Test that protected owner contact details are not emitted into public structured data.
     */
    public function test_protected_contact_details_not_emitted_by_structured_data(): void
    {
        $owner = User::create([
            'name' => 'Confidential Owner',
            'email' => 'private_owner_email@example.com',
            'phone' => '+919876543210',
            'password' => bcrypt('secret123'),
        ]);

        $property = Property::create([
            'user_id' => $owner->id,
            'title' => 'Verified Penthouse Test Listing',
            'slug' => 'verified-penthouse-test-listing',
            'purpose' => 'rent',
            'category' => 'residential',
            'property_type' => 'Penthouse',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'locality' => 'Malviya Nagar',
            'price' => 45000,
            'status' => 'Available',
            'description' => 'Luxury penthouse listing description for test.',
        ]);

        $response = $this->get('/properties/'.$property->slug);

        $response->assertStatus(200);
        $response->assertDontSee('private_owner_email@example.com');
        $response->assertDontSee('+919876543210');
    }

    /**
     * Test script-safe JSON-LD encoding with JSON_HEX_* prevents script injection.
     */
    public function test_safe_json_ld_encoding_prevents_script_injection(): void
    {
        SeoMetaOverride::create([
            'path' => '/about',
            'meta_title' => 'Safe JSON-LD Test',
            'is_indexable' => true,
            'schema_json' => [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => '</script><script>alert("xss1")</script>',
            ],
            'faqs' => [
                [
                    'question' => 'Is this safe? </script><script>alert("xss2")</script>',
                    'answer' => 'Yes, JSON_HEX_TAG encodes angle brackets.',
                ],
            ],
        ]);

        $response = $this->get('/about');

        $response->assertStatus(200);
        // Raw script tags must NEVER be present inside the JSON-LD script tag
        $response->assertDontSee('</script><script>alert("xss1")</script>', false);
        $response->assertDontSee('</script><script>alert("xss2")</script>', false);
        // Script-safe HEX encoding must be present
        $response->assertSee('\u003C/script\u003E\u003Cscript\u003Ealert(\u0022xss1\u0022)\u003C/script\u003E', false);
        $response->assertSee('\u003C/script\u003E\u003Cscript\u003Ealert(\u0022xss2\u0022)\u003C/script\u003E', false);
    }

    /**
     * Test sitemaps exclude routes marked non-indexable in SeoMetaOverride.
     */
    public function test_sitemap_excludes_non_indexable_routes(): void
    {
        $property = Property::create([
            'title' => 'Hidden Sitemap Test Property',
            'slug' => 'hidden-sitemap-test-property',
            'purpose' => 'rent',
            'category' => 'residential',
            'property_type' => 'Flat',
            'city' => 'Jaipur',
            'price' => 20000,
            'status' => 'Available',
        ]);

        SeoMetaOverride::create([
            'path' => '/properties/'.$property->slug,
            'is_indexable' => false,
        ]);

        $response = $this->get('/sitemaps/properties.xml');

        $response->assertStatus(200);
        $response->assertDontSee('/properties/'.$property->slug);
    }

    /**
     * Test free viewing requests and general inquiries do not require paywall or contact tokens.
     */
    public function test_free_viewing_request_and_inquiry_flows(): void
    {
        $property = Property::create([
            'title' => 'Free Viewing Apartment',
            'slug' => 'free-viewing-apartment',
            'purpose' => 'rent',
            'category' => 'residential',
            'property_type' => 'Flat',
            'city' => 'Jaipur',
            'price' => 25000,
            'status' => 'Available',
        ]);

        // Viewing request is 100% free
        $viewingResponse = $this->post('/properties/'.$property->id.'/viewing', [
            'name' => 'Prospective Tenant',
            'email' => 'tenant@example.com',
            'phone' => '+919876500000',
            'preferred_date' => now()->addDays(2)->format('Y-m-d'),
            'preferred_time' => '11:00 AM',
            'notes' => 'Looking forward to viewing the flat.',
        ]);

        $viewingResponse->assertSessionHasNoErrors();
        $viewingResponse->assertStatus(302); // redirects back with success message

        // General website inquiry is 100% free
        $inquiryResponse = $this->post('/inquiry', [
            'name' => 'General Inquirer',
            'email' => 'inquirer@example.com',
            'phone' => '+919876511111',
            'message' => 'I would like to inquire about property listing on Housing365.',
        ]);

        $inquiryResponse->assertSessionHasNoErrors();
        $inquiryResponse->assertStatus(302);
    }
}
