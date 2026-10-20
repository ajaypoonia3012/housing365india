<?php

namespace Tests\Feature;

use App\Filament\Resources\Pages\Schemas\PageForm;
use App\Filament\Resources\SeoMetaOverrides\SeoMetaOverrideResource;
use App\Services\HtmlSanitizer;
use Tests\TestCase;

class SecuritySanitizationTest extends TestCase
{
    /**
     * Test that HtmlSanitizer eliminates script tags and event handlers.
     */
    public function test_html_sanitizer_eliminates_xss_payloads(): void
    {
        $dirty = '<script>alert("xss")</script><span class="text-brand-cyan" onclick="evil()" onmouseover="hack()">Safe Heading</span><img src=x onerror=alert(1)>';
        $clean = HtmlSanitizer::clean($dirty);

        $this->assertStringNotContainsString('<script>', $clean);
        $this->assertStringNotContainsString('alert("xss")', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('onmouseover', $clean);
        $this->assertStringNotContainsString('<img', $clean);
        $this->assertStringNotContainsString('onerror', $clean);
        $this->assertStringContainsString('<span class="text-brand-cyan">Safe Heading</span>', $clean);
    }

    /**
     * Test that HtmlSanitizer eliminates SVG, iframes, styles, and arbitrary attributes.
     */
    public function test_html_sanitizer_eliminates_svg_iframe_and_styles(): void
    {
        $dirty = '<svg><script>alert(1)</script></svg><iframe src="https://evil.com"></iframe><span style="color:red" id="injected">Styled</span>';
        $clean = HtmlSanitizer::clean($dirty);

        $this->assertStringNotContainsString('<svg>', $clean);
        $this->assertStringNotContainsString('<iframe>', $clean);
        $this->assertStringNotContainsString('style=', $clean);
        $this->assertStringNotContainsString('id=', $clean);
        $this->assertStringContainsString('<span>Styled</span>', $clean);
    }

    /**
     * Test that HtmlSanitizer preserves permitted formatting tags.
     */
    public function test_html_sanitizer_preserves_permitted_tags(): void
    {
        $input = 'Everything Property. <span class="block font-medium text-brand-cyan">One Platform.</span> <strong>Zero Brokerage</strong> &bull; <em>Verified</em><br>';
        $clean = HtmlSanitizer::clean($input);

        $this->assertStringContainsString('<span class="block font-medium text-brand-cyan">One Platform.</span>', $clean);
        $this->assertStringContainsString('<strong>Zero Brokerage</strong>', $clean);
        $this->assertStringContainsString('<em>Verified</em>', $clean);
        $this->assertStringContainsString('<br>', $clean);
    }

    /**
     * Test PageForm CTA URL validation rule rejects protocol-relative and unsafe schemes.
     */
    public function test_page_form_cta_url_rule_validation(): void
    {
        $rule = PageForm::ctaUrlRule();

        // Protocol-relative URLs must fail
        $failed = false;
        $failCallback = function ($message) use (&$failed) {
            $failed = true;
        };
        $rule('hero_cta_link', '//evil.com/phish', $failCallback);
        $this->assertTrue($failed, 'Protocol-relative URL //evil.com must fail validation.');

        // Javascript scheme must fail
        $failed = false;
        $rule('hero_cta_link', 'javascript:alert(1)', $failCallback);
        $this->assertTrue($failed, 'javascript: scheme must fail validation.');

        // Data scheme must fail
        $failed = false;
        $rule('hero_cta_link', 'data:text/html,<script>alert(1)</script>', $failCallback);
        $this->assertTrue($failed, 'data: scheme must fail validation.');

        // Valid relative path must pass
        $failed = false;
        $rule('hero_cta_link', '/properties/search', $failCallback);
        $this->assertFalse($failed, 'Valid relative path must pass validation.');

        // Valid HTTPS URL must pass
        $failed = false;
        $rule('hero_cta_link', 'https://housing365.in/post-property', $failCallback);
        $this->assertFalse($failed, 'Valid HTTPS URL must pass validation.');

        // Valid tel: and mailto: must pass
        $failed = false;
        $rule('hero_cta_link', 'tel:+919876543210', $failCallback);
        $this->assertFalse($failed, 'Valid tel: must pass validation.');

        $failed = false;
        $rule('hero_cta_link', 'mailto:care@housing365.in', $failCallback);
        $this->assertFalse($failed, 'Valid mailto: must pass validation.');
    }
}
