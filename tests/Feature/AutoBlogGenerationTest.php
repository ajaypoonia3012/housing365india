<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\BlogTopic;
use App\Models\Setting;
use App\Services\BlogGenerationService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AutoBlogGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.anthropic.api_key' => 'mock-anthropic-key-test',
            'services.anthropic.model' => 'claude-sonnet-5-5',
            'services.blog.auto_publish' => false,
            'cache.default' => 'array',
        ]);
    }

    protected function sampleClaudeJsonResponse(string $title = 'Understanding RERA Carpet Area Rules'): array
    {
        return [
            'id' => 'msg_mock_12345',
            'type' => 'message',
            'role' => 'assistant',
            'content' => [
                [
                    'type' => 'text',
                    'text' => json_encode([
                        'title' => $title,
                        'slug' => 'understanding-rera-carpet-area-rules',
                        'meta_description' => 'Comprehensive guide explaining RERA carpet area vs super built-up area for Indian property buyers.',
                        'excerpt' => 'Learn how RERA protects home buyers by mandating sales strictly on carpet area rather than super built-up area.',
                        'body' => '<h3>Introduction to RERA Carpet Area</h3><p>Under Section 2(k) of the Real Estate (Regulation and Development) Act, 2016, carpet area is strictly defined as the net usable floor area of an apartment.</p><ul><li>Excludes external walls and common areas</li><li>Includes internal partition walls</li></ul><div class="mt-8 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-500 italic"><strong>Disclaimer:</strong> This article is for informational purposes only and does not constitute formal legal advice.</div>',
                        'tags' => ['RERA', 'Carpet Area', 'Homebuyers', 'Property Law'],
                        'linkedin_caption' => "📢 Essential Guide: Understanding RERA Carpet Area Rules\n\nAre you buying an apartment in India? Know your rights under RERA.\n\n👉 Read more: http://localhost:8000/blog/understanding-rera-carpet-area-rules\n\n#Housing365 #IndianRealEstate #RERA",
                    ]),
                ],
            ],
        ];
    }

    public function test_blog_generate_calls_anthropic_and_saves_draft_by_default(): void
    {
        $topic = BlogTopic::create([
            'title' => 'Understanding RERA Carpet Area Rules',
            'category' => 'RERA & Legal Guidelines',
            'target_audience' => 'Homebuyers',
            'keywords' => ['carpet area', 'rera calculation'],
            'is_used' => false,
        ]);

        Http::fake([
            'https://api.anthropic.com/v1/messages' => Http::response($this->sampleClaudeJsonResponse(), 200),
        ]);

        $this->artisan('blog:generate')
            ->expectsOutputToContain('Selected Topic: "Understanding RERA Carpet Area Rules"')
            ->expectsOutputToContain('Publishing Mode: DRAFT')
            ->assertExitCode(0);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.anthropic.com/v1/messages'
                && $request->header('x-api-key')[0] === 'mock-anthropic-key-test'
                && $request['model'] === 'claude-sonnet-5-5';
        });

        // Verify blog post saved as draft
        $post = BlogPost::first();
        $this->assertNotNull($post);
        $this->assertEquals('Understanding RERA Carpet Area Rules', $post->title);
        $this->assertEquals('understanding-rera-carpet-area-rules', $post->slug);
        $this->assertFalse($post->is_published);
        $this->assertNull($post->published_at);
        $this->assertFalse($post->auto_post_to_linkedin);
        $this->assertStringContainsString('Disclaimer', $post->content);
        $this->assertContains('RERA', $post->tags);

        // Verify topic marked used
        $topic->refresh();
        $this->assertTrue($topic->is_used);
        $this->assertNotNull($topic->used_at);
        $this->assertEquals($post->id, $topic->blog_post_id);
    }

    public function test_blog_generate_prevents_duplicate_topics(): void
    {
        $topic1 = BlogTopic::create([
            'title' => 'Topic 1: Security Deposit Rules',
            'category' => 'Tenant Rights',
            'is_used' => false,
        ]);

        $topic2 = BlogTopic::create([
            'title' => 'Topic 2: Landlord Police Verification',
            'category' => 'Landlord Guide',
            'is_used' => false,
        ]);

        Http::fake([
            'https://api.anthropic.com/v1/messages' => Http::sequence()
                ->push($this->sampleClaudeJsonResponse('Topic 1: Security Deposit Rules'))
                ->push($this->sampleClaudeJsonResponse('Topic 2: Landlord Police Verification')),
        ]);

        // First run should pick Topic 1
        $this->artisan('blog:generate')->assertExitCode(0);
        $this->assertTrue($topic1->fresh()->is_used);
        $this->assertFalse($topic2->fresh()->is_used);

        // Second run should pick Topic 2 (preventing repetition)
        $this->artisan('blog:generate')->assertExitCode(0);
        $this->assertTrue($topic2->fresh()->is_used);

        $this->assertEquals(2, BlogPost::count());
        $this->assertNotEquals($topic1->fresh()->blog_post_id, $topic2->fresh()->blog_post_id);
    }

    public function test_blog_generate_dry_run_does_not_save_or_post(): void
    {
        $topic = BlogTopic::create([
            'title' => 'Dry Run Topic Example',
            'category' => 'Rental Tips',
            'is_used' => false,
        ]);

        Http::fake([
            'https://api.anthropic.com/v1/messages' => Http::response($this->sampleClaudeJsonResponse('Dry Run Topic Example'), 200),
            'https://api.linkedin.com/*' => Http::response([], 201),
        ]);

        $this->artisan('blog:generate', ['--dry-run' => true])
            ->expectsOutputToContain('DRY-RUN MODE')
            ->expectsOutputToContain('DRY RUN PREVIEW OUTPUT')
            ->assertExitCode(0);

        // Verify no blog posts created and topic remains unused
        $this->assertEquals(0, BlogPost::count());
        $this->assertFalse($topic->fresh()->is_used);

        // Verify LinkedIn was NOT called
        Http::assertNotSent(fn ($req) => str_contains($req->url(), 'linkedin.com'));
    }

    public function test_blog_generate_auto_publishes_and_triggers_linkedin_posts_api_when_flag_enabled(): void
    {
        config([
            'services.blog.auto_publish' => true,
            'services.linkedin.access_token' => 'mock-linkedin-jwt-token',
            'services.linkedin.author_urn' => 'urn:li:organization:554433',
            'services.linkedin.version' => '202401',
        ]);

        $topic = BlogTopic::create([
            'title' => 'NRI Property Management and Tax Deductions',
            'category' => 'NRI Property Management',
            'is_used' => false,
        ]);

        Http::fake([
            'https://api.anthropic.com/v1/messages' => Http::response($this->sampleClaudeJsonResponse('NRI Property Management and Tax Deductions'), 200),
            'https://api.linkedin.com/rest/posts' => Http::response([], 201, [
                'x-restli-id' => 'urn:li:share:mock999999',
            ]),
        ]);

        $this->artisan('blog:generate')
            ->expectsOutputToContain('Publishing Mode: PUBLIC')
            ->expectsOutputToContain('Successfully posted to LinkedIn feed!')
            ->assertExitCode(0);

        $post = BlogPost::first();
        $this->assertNotNull($post);
        $this->assertTrue($post->is_published);
        $this->assertNotNull($post->published_at);
        $this->assertEquals('urn:li:share:mock999999', $post->linkedin_post_id);
        $this->assertTrue($post->isSharedOnLinkedIn());

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.linkedin.com/rest/posts'
                && $request->header('Linkedin-Version')[0] === '202401'
                && $request->header('X-Restli-Protocol-Version')[0] === '2.0.0'
                && $request['author'] === 'urn:li:organization:554433'
                && isset($request['content']['article']);
        });
    }

    public function test_blog_generate_validates_json_structure_and_enforces_disclaimer(): void
    {
        $service = app(BlogGenerationService::class);

        // Test missing key throws RuntimeException
        $invalidJson = json_encode(['title' => 'Incomplete']);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("missing required key: 'slug'");
        $service->parseAndValidateJson($invalidJson);
    }

    public function test_disclaimer_appended_if_omitted_by_ai(): void
    {
        $service = app(BlogGenerationService::class);

        $rawJson = json_encode([
            'title' => 'Tenant Lease Rules',
            'slug' => 'tenant-lease-rules',
            'meta_description' => 'A guide for tenant leases.',
            'excerpt' => 'Short summary.',
            'body' => '<p>Here is standard guide content explaining rent payment cycles and maintenance obligations.</p>',
            'tags' => ['lease', 'rules'],
            'linkedin_caption' => 'Caption with #tags',
        ]);

        $parsed = $service->parseAndValidateJson($rawJson);
        $this->assertStringContainsString('Disclaimer:', $parsed['body']);
        $this->assertStringContainsString('This article is for informational purposes only', $parsed['body']);
    }

    public function test_scheduler_contains_weekly_tuesday_blog_generate_command(): void
    {
        $schedule = app()->make(Schedule::class);
        $events = collect($schedule->events());

        $blogEvent = $events->first(function ($event) {
            return str_contains($event->command ?? '', 'blog:generate');
        });

        $this->assertNotNull($blogEvent, 'Schedule must contain blog:generate command');
        $this->assertEquals('Asia/Kolkata', $blogEvent->timezone);
        $this->assertEquals('0 9 * * 2', $blogEvent->expression); // 09:00 AM on Tuesdays in Asia/Kolkata
    }
}
