<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\BlogTopic;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class BlogGenerationService
{
    public const DEFAULT_MODEL = 'claude-sonnet-5-5';

    /**
     * Standardized legal disclaimer for real estate advice.
     */
    public const LEGAL_DISCLAIMER_HTML = '<div class="mt-8 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-500 italic"><strong>Disclaimer:</strong> This article is for informational purposes only and does not constitute formal legal, financial, or tax advice. Real estate laws, municipal tenancy regulations, and RERA adjudications vary across Indian states. Always consult with a registered legal counsel or local RERA authority before executing binding agreements.</div>';

    /**
     * Resolve the next unused topic or seed from configuration if empty.
     */
    public function getNextTopic(?string $customTopic = null): BlogTopic
    {
        if (! empty($customTopic)) {
            return BlogTopic::create([
                'title' => trim($customTopic),
                'category' => 'Rental Guide',
                'target_audience' => 'General',
                'is_used' => false,
            ]);
        }

        // Check if there are any unused topics in database
        $topic = BlogTopic::where('is_used', false)->orderBy('id')->first();
        if ($topic) {
            return $topic;
        }

        // If no topics exist at all, populate from config/blog_topics.php
        $seededCount = $this->seedDefaultTopics();
        if ($seededCount > 0) {
            return BlogTopic::where('is_used', false)->orderBy('id')->first();
        }

        throw new RuntimeException('All blog topics in blog_topics have already been used. Please add new topics or reset existing ones.');
    }

    /**
     * Seed default topics into blog_topics table if not already present.
     */
    public function seedDefaultTopics(): int
    {
        $defaults = config('blog_topics.default_topics', []);
        $count = 0;

        foreach ($defaults as $item) {
            $existing = BlogTopic::where('title', $item['title'])->first();
            if (! $existing) {
                BlogTopic::create([
                    'title' => $item['title'],
                    'category' => $item['category'] ?? 'Rental Guide',
                    'target_audience' => $item['target_audience'] ?? 'General',
                    'keywords' => $item['keywords'] ?? [],
                    'is_used' => false,
                ]);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Call Anthropic Messages API with model claude-sonnet-5-5 to write the blog post.
     *
     * @return array{title: string, slug: string, meta_description: string, excerpt: string, body: string, tags: array, linkedin_caption: string}
     */
    public function generateWithClaude(BlogTopic $topic): array
    {
        $apiKey = config('services.anthropic.api_key');
        if (empty($apiKey)) {
            throw new RuntimeException('Anthropic API key is not configured. Please set ANTHROPIC_API_KEY in your .env file.');
        }

        $model = config('services.anthropic.model', self::DEFAULT_MODEL);

        $systemPrompt = $this->buildSystemPrompt();
        $userPrompt = $this->buildUserPrompt($topic);

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])
        ->timeout(60)
        ->post('https://api.anthropic.com/v1/messages', [
            'model' => $model,
            'max_tokens' => 4096,
            'system' => $systemPrompt,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $userPrompt,
                ],
            ],
        ]);

        if (! $response->successful()) {
            $error = $response->body();
            Log::error('Anthropic API error: ' . $error);
            throw new RuntimeException('Anthropic Claude API returned an error (' . $response->status() . '): ' . ($response->json('error.message') ?: $error));
        }

        $rawText = $response->json('content.0.text');
        if (empty($rawText)) {
            throw new RuntimeException('Anthropic Claude API returned an empty text content.');
        }

        return $this->parseAndValidateJson($rawText, $topic);
    }

    /**
     * Parse raw Claude response and validate JSON structure.
     */
    public function parseAndValidateJson(string $rawText, ?BlogTopic $topic = null): array
    {
        $clean = trim($rawText);

        // Strip markdown ```json ... ``` wrapper if present
        if (str_starts_with($clean, '```')) {
            $clean = preg_replace('/^```(?:json)?\s*/i', '', $clean);
            $clean = preg_replace('/\s*```$/', '', $clean);
            $clean = trim($clean);
        }

        $data = json_decode($clean, true);
        if (! is_array($data)) {
            throw new RuntimeException('Claude response could not be parsed as valid JSON: ' . json_last_error_msg() . "\nRaw text snippet: " . substr($rawText, 0, 300));
        }

        $requiredKeys = ['title', 'slug', 'meta_description', 'excerpt', 'body', 'tags', 'linkedin_caption'];
        foreach ($requiredKeys as $key) {
            if (! array_key_exists($key, $data) || empty($data[$key])) {
                throw new RuntimeException("Claude JSON response is missing required key: '{$key}'");
            }
        }

        if (! is_array($data['tags'])) {
            $data['tags'] = array_filter(array_map('trim', explode(',', (string) $data['tags'])));
        }

        // Clean slug
        $data['slug'] = Str::slug($data['slug'] ?: $data['title']);

        // Ensure disclaimer is present in body
        $body = (string) $data['body'];
        if (! str_contains(strtolower($body), 'disclaimer')) {
            $body .= "\n\n" . self::LEGAL_DISCLAIMER_HTML;
        }
        $data['body'] = $body;

        return $data;
    }

    /**
     * Save generated article as a BlogPost and mark topic as used.
     */
    public function savePost(BlogTopic $topic, array $articleData, bool $publish = false): BlogPost
    {
        $slug = $articleData['slug'];
        if (BlogPost::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $post = BlogPost::create([
            'title' => $articleData['title'],
            'slug' => $slug,
            'category' => $topic->category ?: 'Rental Guide',
            'excerpt' => $articleData['excerpt'],
            'content' => $articleData['body'],
            'meta_description' => $articleData['meta_description'],
            'tags' => $articleData['tags'],
            'linkedin_caption' => $articleData['linkedin_caption'],
            'author_name' => 'Housing365 Advisory Desk',
            'read_time' => '5 min read',
            'is_published' => $publish,
            'published_at' => $publish ? now() : null,
            'auto_post_to_linkedin' => $publish,
        ]);

        $topic->markAsUsed($post->id);

        return $post;
    }

    /**
     * Determine whether the post should be auto-published based on settings or env.
     */
    public function shouldAutoPublish(bool $cliPublishFlag = false): bool
    {
        if ($cliPublishFlag) {
            return true;
        }

        $dbSetting = Setting::get('blog_auto_publish');
        if ($dbSetting !== null) {
            return filter_var($dbSetting, FILTER_VALIDATE_BOOLEAN);
        }

        return (bool) config('services.blog.auto_publish', env('BLOG_AUTO_PUBLISH', false));
    }

    /**
     * Build system prompt for Anthropic Claude.
     */
    protected function buildSystemPrompt(): string
    {
        return <<<PROMPT
You are a senior real estate analyst, legal researcher, and editorial writer for Housing365 India (housing365.in) — a premier Indian property management and rental advisory platform.
You specialize in Indian real estate legislation, including RERA (Real Estate Regulatory Authority), Model Tenancy Act, State Rent Control Acts, property taxation (TDS, capital gains, Section 24), and NRI property management (FEMA rules, repatriation of rental income).

CRITICAL CONSTRAINTS:
1. Do NOT invent statistics, laws, judicial rulings, or legal advice. If citing laws, refer strictly to genuine statutory provisions (e.g. Model Tenancy Act 2021, RERA Act 2016 Section 18/70, Indian Contract Act 1872, FEMA regulations, Income Tax Act Section 194-IB/195).
2. Always write for an Indian audience using Indian terminology (e.g. Lakhs, Crores, BHK, society maintenance, police intimation, leave and licence, stamp duty).
3. Always include a concise, professional legal disclaimer at the end of the body.
4. Output MUST be ONLY a single valid JSON object without markdown fences, commentary, or extra text.

The JSON object MUST have these exact keys:
- "title": string (engaging, authoritative, SEO-rich title)
- "slug": string (url-safe kebab-case slug)
- "meta_description": string (concise meta description, 140-160 characters)
- "excerpt": string (2-3 sentences summarizing the key takeaways)
- "body": string (comprehensive, structured HTML article with <h3> subheadings, <p> paragraphs, <ul>/<li> lists, <strong> highlights, and an end disclaimer)
- "tags": array of 4 to 7 strings
- "linkedin_caption": string (high-engagement LinkedIn post copy with emojis, bullet points, call to action, and hashtags like #Housing365 #IndianRealEstate #RERA)
PROMPT;
    }

    /**
     * Build user prompt for a specific topic.
     */
    protected function buildUserPrompt(BlogTopic $topic): string
    {
        $keywords = is_array($topic->keywords) ? implode(', ', $topic->keywords) : (string) $topic->keywords;
        $category = $topic->category ?: 'Rental Guide';
        $audience = $topic->target_audience ?: 'Indian Tenants and Landlords';

        return <<<PROMPT
Write a comprehensive, authoritative blog guide for Housing365 India on the following topic:

Topic Title: "{$topic->title}"
Category: "{$category}"
Target Audience: "{$audience}"
Focus Keywords: "{$keywords}"

Write with practical, actionable advice, clear steps, and accurate legal context for India. Return ONLY the JSON object.
PROMPT;
    }
}
