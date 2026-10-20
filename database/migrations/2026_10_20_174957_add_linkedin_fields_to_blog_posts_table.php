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
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('linkedin_post_id')->nullable()->after('published_at');
            $table->string('linkedin_post_url')->nullable()->after('linkedin_post_id');
            $table->timestamp('linkedin_shared_at')->nullable()->after('linkedin_post_url');
            $table->boolean('auto_post_to_linkedin')->default(false)->after('linkedin_shared_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn([
                'linkedin_post_id',
                'linkedin_post_url',
                'linkedin_shared_at',
                'auto_post_to_linkedin',
            ]);
        });
    }
};
