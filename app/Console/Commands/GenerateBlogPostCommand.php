<?php

namespace App\Console\Commands;

use App\Services\BlogGenerationService;
use App\Services\LinkedInPublishingService;
use Illuminate\Console\Command;
use Throwable;

class GenerateBlogPostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:generate
                            {--topic= : Custom topic to write an article for}
                            {--dry-run : Print the generated post without saving to database or sharing to LinkedIn}
                            {--publish : Force publish the post and trigger LinkedIn auto-posting immediately}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a high-quality real estate blog post for Housing365 India using Claude AI (claude-sonnet-5-5), prevent duplicate topics, and optionally publish to LinkedIn.';

    /**
     * Execute the console command.
     */
    public function handle(BlogGenerationService $generator, LinkedInPublishingService $linkedIn): int
    {
        $this->info('🚀 Housing365 India — Weekly Blog Generator');

        $isDryRun = (bool) $this->option('dry-run');
        $forcePublish = (bool) $this->option('publish');

        if ($isDryRun) {
            $this->warn('⚠️  [DRY-RUN MODE] Generation will run but NO post will be saved to database or published to LinkedIn.');
        }

        try {
            // 1. Resolve Next Unused Topic
            $customTopic = $this->option('topic');
            $this->line('🔍 Resolving next blog topic...');
            $topic = $generator->getNextTopic($customTopic);
            $this->info("📌 Selected Topic: \"{$topic->title}\" (Category: {$topic->category}, Audience: {$topic->target_audience})");

            // 2. Generate with Anthropic Claude API (claude-sonnet-5-5)
            $this->line('🧠 Invoking Anthropic Claude API (claude-sonnet-5-5)...');
            $articleData = $generator->generateWithClaude($topic);

            // 3. Handle Dry Run Output
            if ($isDryRun) {
                $this->newLine();
                $this->info('======================================================');
                $this->info('               DRY RUN PREVIEW OUTPUT                 ');
                $this->info('======================================================');
                $this->line("<comment>Title:</comment> {$articleData['title']}");
                $this->line("<comment>Slug:</comment> {$articleData['slug']}");
                $this->line("<comment>Meta Description:</comment> {$articleData['meta_description']}");
                $this->line("<comment>Excerpt:</comment> {$articleData['excerpt']}");
                $this->line('<comment>Tags:</comment> ' . implode(', ', (array) $articleData['tags']));
                $this->newLine();
                $this->line('<comment>LinkedIn Caption:</comment>');
                $this->line($articleData['linkedin_caption']);
                $this->newLine();
                $this->line('<comment>Body Preview (first 400 chars):</comment>');
                $this->line(substr(strip_tags($articleData['body']), 0, 400) . '...');
                $this->info('======================================================');
                $this->info('✅ Dry run completed successfully. No changes made.');

                return Command::SUCCESS;
            }

            // 4. Determine Publish State
            $shouldPublish = $generator->shouldAutoPublish($forcePublish);
            $this->line($shouldPublish
                ? '📢 Publishing Mode: PUBLIC (BLOG_AUTO_PUBLISH / --publish enabled)'
                : '📝 Publishing Mode: DRAFT (Default safety setting)'
            );

            // 5. Save BlogPost and mark topic used
            $post = $generator->savePost($topic, $articleData, $shouldPublish);
            $this->info("✅ Blog post created: [ID #{$post->id}] \"{$post->title}\"");
            $this->line("🔗 URL: {$post->public_url}");
            $this->line('Status: ' . ($post->is_published ? '<info>Published</info>' : '<comment>Draft</comment>'));

            // 6. LinkedIn Auto-Post if Published
            if ($shouldPublish) {
                $this->line('📤 Triggering LinkedIn auto-post flow...');
                if ($linkedIn::isApiConfigured()) {
                    $caption = $articleData['linkedin_caption'] ?: null;
                    $linkedInResult = $linkedIn::publishViaApi($post, $caption);

                    if ($linkedInResult['success']) {
                        $this->info('🎉 Successfully posted to LinkedIn feed!');
                        $this->line("Post URL: {$linkedInResult['post_url']}");
                    } else {
                        $this->warn('⚠️ LinkedIn API notice: ' . $linkedInResult['message']);
                    }
                } else {
                    $this->comment('ℹ️ LinkedIn API credentials not set in .env. Admin can use 1-click share from the Filament admin panel.');
                }
            } else {
                $this->comment('ℹ️ Post saved as draft. When reviewed in admin, toggling publish will allow 1-click or API LinkedIn posting.');
            }

            return Command::SUCCESS;
        } catch (Throwable $e) {
            $this->error('❌ Blog Generation Failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }
}
