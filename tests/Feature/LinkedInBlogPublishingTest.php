<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Setting;
use App\Services\LinkedInPublishingService;
use Filament\Actions\Action;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LinkedInBlogPublishingTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_post_has_linkedin_fields_and_helpers(): void
    {
        $post = BlogPost::create([
            'title' => 'RERA Master Guide for Bengaluru Tenants and Buyers',
            'slug' => 'rera-master-guide-bengaluru',
            'category' => 'RERA & Legal Guidelines',
            'excerpt' => 'Complete walkthrough of RERA provisions, carpet area regulations, and complaint filing.',
            'content' => 'Full article content about RERA Karnataka...',
            'author_name' => 'Housing365 Team',
            'read_time' => '5 min read',
            'is_published' => true,
            'published_at' => now(),
            'auto_post_to_linkedin' => true,
        ]);

        $this->assertFalse($post->isSharedOnLinkedIn());
        $this->assertStringContainsString('blog/rera-master-guide-bengaluru', $post->public_url);

        $post->update([
            'linkedin_post_id' => 'urn:li:share:123456789',
            'linkedin_post_url' => 'https://www.linkedin.com/feed/update/urn:li:share:123456789',
            'linkedin_shared_at' => now(),
        ]);

        $this->assertTrue($post->fresh()->isSharedOnLinkedIn());
        $this->assertEquals('urn:li:share:123456789', $post->fresh()->linkedin_post_id);
    }

    public function test_linkedin_service_formats_post_and_generates_dynamic_hashtags(): void
    {
        $post = BlogPost::create([
            'title' => 'Model Tenancy Act 2026: Landlord and Tenant Rights in India',
            'slug' => 'model-tenancy-act-2026-rights',
            'category' => 'Rental Agreements & Disputes',
            'excerpt' => 'Everything you need to know about security deposit limits and rent hike notices.',
            'author_name' => 'Legal Advisory',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $hashtags = LinkedInPublishingService::generateHashtags($post);
        $this->assertStringContainsString('#Housing365', $hashtags);
        $this->assertStringContainsString('#IndianRealEstate', $hashtags);
        $this->assertStringContainsString('#ModelTenancyAct', $hashtags);
        $this->assertStringContainsString('#TenantRights', $hashtags);

        $postText = LinkedInPublishingService::formatPostText($post);
        $this->assertStringContainsString('Model Tenancy Act 2026', $postText);
        $this->assertStringContainsString($post->public_url, $postText);
        $this->assertStringContainsString('#Housing365', $postText);
    }

    public function test_linkedin_service_generates_valid_share_urls(): void
    {
        $post = BlogPost::create([
            'title' => 'Stamp Duty and Registration Charges in Maharashtra 2026',
            'slug' => 'stamp-duty-maharashtra-2026',
            'category' => 'Property Registration',
            'excerpt' => 'Current stamp duty slabs, concession for women buyers, and metro cess.',
            'author_name' => 'Housing365 Team',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $offsiteUrl = LinkedInPublishingService::getShareOffsiteUrl($post);
        $this->assertStringStartsWith('https://www.linkedin.com/sharing/share-offsite/?url=', $offsiteUrl);
        $this->assertStringContainsString(urlencode($post->public_url), $offsiteUrl);

        $feedUrl = LinkedInPublishingService::getShareFeedIntentUrl($post);
        $this->assertStringStartsWith('https://www.linkedin.com/feed/?shareActive=true&text=', $feedUrl);
    }

    public function test_linkedin_api_publishing_handles_missing_token_gracefully(): void
    {
        Setting::where('key', 'linkedin_access_token')->delete();

        $post = BlogPost::create([
            'title' => 'How NRIs Can Buy Agricultural Land or Commercial Property in India',
            'slug' => 'nri-buy-property-india',
            'category' => 'NRI Investment',
            'excerpt' => 'RBI and FEMA compliance rules for Non-Resident Indians.',
            'author_name' => 'FEMA Desk',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $result = LinkedInPublishingService::publishViaApi($post);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('LinkedIn Access Token is not configured', $result['message']);
    }

    public function test_linkedin_api_publishing_succeeds_with_valid_token(): void
    {
        Setting::set('linkedin_access_token', 'mock_token_123', 'social', 'text', 'Token');
        Setting::set('linkedin_author_urn', 'urn:li:organization:998877', 'social', 'text', 'URN');

        Http::fake([
            'https://api.linkedin.com/rest/posts' => Http::response([], 201, [
                'x-restli-id' => 'urn:li:share:987654321',
            ]),
        ]);

        $post = BlogPost::create([
            'title' => 'Home Loan Interest Rate Tax Deductions Section 24 and 80EEA',
            'slug' => 'home-loan-tax-deductions-2026',
            'category' => 'Home Loans & Tax',
            'excerpt' => 'Maximize your income tax savings under new vs old tax regime.',
            'author_name' => 'Tax Consultant',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $result = LinkedInPublishingService::publishViaApi($post);
        $this->assertTrue($result['success']);
        $this->assertEquals('urn:li:share:987654321', $result['post_id']);

        $post->refresh();
        $this->assertEquals('urn:li:share:987654321', $post->linkedin_post_id);
        $this->assertNotNull($post->linkedin_shared_at);
        $this->assertTrue($post->isSharedOnLinkedIn());
    }

    public function test_filament_action_is_properly_configured(): void
    {
        $action = LinkedInPublishingService::createFilamentAction();

        $this->assertInstanceOf(Action::class, $action);
        $this->assertEquals('post_to_linkedin', $action->getName());
        $this->assertEquals('Post to LinkedIn', $action->getLabel());
        $this->assertEquals('info', $action->getColor());
    }
}
