<?php

namespace Tests\Feature;

use App\Models\CityLocation;
use App\Models\Page;
use App\Models\Property;
use App\Models\Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HomepageCmsHeroTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that homepage hero values come from the database Page record.
     */
    public function test_homepage_hero_values_come_from_database(): void
    {
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home Page Title',
                'hero_badge' => 'CUSTOM CMS HERO BADGE',
                'hero_title' => 'Custom CMS Headline <span class="text-brand-cyan">One Platform</span>',
                'hero_subtitle' => 'Custom CMS subtitle describing Indian property ecosystem.',
                'hero_cta_text' => 'Custom List Property',
                'hero_cta_link' => '/custom-list-property',
                'sections_data' => [
                    'secondary_cta_text' => 'Custom Browse Homes',
                    'secondary_cta_link' => '/custom-browse',
                ],
                'is_published' => true,
            ]
        );

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('CUSTOM CMS HERO BADGE');
        $response->assertSee('Custom CMS Headline');
        $response->assertSee('Custom CMS subtitle describing Indian property ecosystem.');
        $response->assertSee('Custom List Property');
        $response->assertSee('/custom-list-property');
        $response->assertSee('Custom Browse Homes');
        $response->assertSee('/custom-browse');
    }

    /**
     * Test fallback text works when database hero values are empty.
     */
    public function test_fallback_text_works_for_missing_values(): void
    {
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home Page',
                'hero_badge' => null,
                'hero_title' => null,
                'hero_subtitle' => null,
                'hero_cta_text' => null,
                'hero_cta_link' => null,
                'sections_data' => null,
                'is_published' => true,
            ]
        );

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('EVERYTHING PROPERTY. ONE PLATFORM.');
        $response->assertSee('Everything Property.');
        $response->assertSee('List my Property — Free');
        $response->assertSee('Find a Property');
    }

    /**
     * Test unsafe CTA URLs with javascript: or data: schemes are rejected.
     */
    public function test_unsafe_cta_urls_fallback_to_safe_routes(): void
    {
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home Page',
                'hero_cta_text' => 'Dangerous Link',
                'hero_cta_link' => 'javascript:alert("hacked")',
                'sections_data' => [
                    'secondary_cta_text' => 'Dangerous Secondary',
                    'secondary_cta_link' => 'data:text/html;base64,PHNjcmlwdD4=',
                ],
                'is_published' => true,
            ]
        );

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('javascript:alert');
        $response->assertDontSee('data:text/html');
        // Falls back to safe default post/index property routes
        $response->assertSee(route('properties.post'));
        $response->assertSee(route('properties.index'));
    }

    /**
     * Test that dynamic collections (properties, cities, services) continue to render.
     */
    public function test_homepage_collections_still_render(): void
    {
        Property::create([
            'title' => 'Featured Test Apartment',
            'slug' => 'featured-test-apartment',
            'purpose' => 'rent',
            'category' => 'residential',
            'property_type' => 'Flat / Apartment',
            'city' => 'Jaipur',
            'locality' => 'C-Scheme',
            'price' => 30000,
            'featured' => true,
            'status' => 'Available',
        ]);

        CityLocation::create([
            'name' => 'TestCity',
            'slug' => 'testcity',
            'province_or_state' => 'Rajasthan',
            'is_featured' => true,
            'is_active' => true,
            'is_indexable' => true,
        ]);

        Service::create([
            'title' => 'Test Move Service',
            'slug' => 'test-move-service',
            'category' => 'all',
            'short_description' => 'Test description',
            'icon' => 'truck',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Featured Test Apartment');
        $response->assertSee('TestCity');
        $response->assertSee('Test Move Service');
    }

    /**
     * Test that missing or unpublished home record does not throw an error.
     */
    public function test_missing_or_unpublished_page_handled_safely(): void
    {
        Page::where('slug', 'home')->delete();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Everything Property.');
    }

    /**
     * Test XSS payloads in hero headline/subtitle are sanitized without breaking safe formatting.
     */
    public function test_xss_payloads_in_hero_are_safely_sanitized(): void
    {
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home Page',
                'hero_title' => '<script>alert("hacked")</script><span class="text-brand-cyan" onclick="malicious()">Safe Heading</span><img src=x onerror=alert(1)>',
                'hero_subtitle' => 'Safe text with <strong>bold</strong> and <svg><circle onload=alert(2)></svg>',
                'is_published' => true,
            ]
        );

        $response = $this->get('/');

        $response->assertStatus(200);
        // Script tags and contents dropped
        $response->assertDontSee('<script>alert("hacked")</script>', false);
        // Event handlers stripped
        $response->assertDontSee('onclick="malicious()"', false);
        $response->assertDontSee('onerror=alert(1)', false);
        $response->assertDontSee('onload=alert(2)', false);
        // Permitted tags and classes preserved
        $response->assertSee('<span class="text-brand-cyan">Safe Heading</span>', false);
        $response->assertSee('<strong>bold</strong>', false);
    }

    /**
     * Test protocol-relative URLs (e.g. //evil.com) in CTA links fall back to safe internal routes.
     */
    public function test_protocol_relative_cta_urls_fallback_to_safe_routes(): void
    {
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home Page',
                'hero_cta_text' => 'Protocol Relative Link',
                'hero_cta_link' => '//evil.com/phish',
                'sections_data' => [
                    'secondary_cta_text' => 'Secondary Protocol Relative',
                    'secondary_cta_link' => '//malicious.org/fake',
                ],
                'is_published' => true,
            ]
        );

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('//evil.com/phish');
        $response->assertDontSee('//malicious.org/fake');
        $response->assertSee(route('properties.post'));
        $response->assertSee(route('properties.index'));
    }
}
