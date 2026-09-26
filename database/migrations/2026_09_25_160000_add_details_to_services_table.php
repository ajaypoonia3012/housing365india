<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('hero_eyebrow')->nullable()->after('icon');
            $table->string('hero_headline')->nullable()->after('hero_eyebrow');
            $table->text('hero_lede')->nullable()->after('hero_headline');
            $table->string('hero_backdrop')->nullable()->after('hero_lede');
            $table->json('scope')->nullable()->after('features');
            $table->json('how_it_works')->nullable()->after('scope');
            $table->json('who_its_for')->nullable()->after('how_it_works');
            $table->string('pricing_note')->nullable()->after('pricing_info');
            $table->json('faq_items')->nullable()->after('pricing_note');
            $table->text('meta_description')->nullable()->after('faq_items');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'hero_eyebrow',
                'hero_headline',
                'hero_lede',
                'hero_backdrop',
                'scope',
                'how_it_works',
                'who_its_for',
                'pricing_note',
                'faq_items',
                'meta_description',
            ]);
        });
    }
};
