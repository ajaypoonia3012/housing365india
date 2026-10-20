<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('blog_topics')) {
            Schema::create('blog_topics', function (Blueprint $table) {
                $table->id();
                $table->string('title')->unique();
                $table->string('category')->default('Rental Guide');
                $table->string('target_audience')->nullable();
                $table->text('keywords')->nullable();
                $table->boolean('is_used')->default(false)->index();
                $table->timestamp('used_at')->nullable();
                $table->unsignedBigInteger('blog_post_id')->nullable()->index();
                $table->timestamps();
            });
        }

        Schema::table('blog_posts', function (Blueprint $table) {
            if (! Schema::hasColumn('blog_posts', 'meta_description')) {
                $table->string('meta_description', 500)->nullable()->after('excerpt');
            }
            if (! Schema::hasColumn('blog_posts', 'tags')) {
                $table->json('tags')->nullable()->after('content');
            }
            if (! Schema::hasColumn('blog_posts', 'linkedin_caption')) {
                $table->text('linkedin_caption')->nullable()->after('auto_post_to_linkedin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_topics');

        Schema::table('blog_posts', function (Blueprint $table) {
            if (Schema::hasColumn('blog_posts', 'meta_description')) {
                $table->dropColumn('meta_description');
            }
            if (Schema::hasColumn('blog_posts', 'tags')) {
                $table->dropColumn('tags');
            }
            if (Schema::hasColumn('blog_posts', 'linkedin_caption')) {
                $table->dropColumn('linkedin_caption');
            }
        });
    }
};
