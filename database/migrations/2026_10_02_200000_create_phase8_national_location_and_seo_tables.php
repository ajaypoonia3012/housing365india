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
        // 1. Indian States & Union Territories table
        if (! Schema::hasTable('states')) {
            Schema::create('states', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100)->unique();
                $table->string('code', 10)->nullable()->index(); // RJ, MH, DL, KA, etc.
                $table->string('type', 30)->default('state'); // state, union_territory
                $table->string('region', 50)->nullable(); // Northern, Western, Southern, Eastern, Central, North-Eastern
                $table->string('capital', 100)->nullable();
                $table->string('tenancy_act_title', 255)->nullable();
                $table->string('police_verification_portal', 255)->nullable();
                $table->text('stamp_duty_summary')->nullable();
                $table->string('meta_title', 255)->nullable();
                $table->text('meta_description')->nullable();
                $table->string('seo_h1', 255)->nullable();
                $table->text('seo_content')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_indexable')->default(true);
                $table->integer('display_order')->default(0);
                $table->timestamps();
            });
        }

        // 2. Extend city_locations table safely
        Schema::table('city_locations', function (Blueprint $table) {
            if (! Schema::hasColumn('city_locations', 'state_id')) {
                $table->foreignId('state_id')->nullable()->after('id')->constrained('states')->nullOnDelete();
            }
            if (! Schema::hasColumn('city_locations', 'state_slug')) {
                $table->string('state_slug', 100)->nullable()->after('state_id')->index();
            }
            if (! Schema::hasColumn('city_locations', 'seo_h1')) {
                $table->string('seo_h1', 255)->nullable()->after('description');
            }
            if (! Schema::hasColumn('city_locations', 'seo_intro')) {
                $table->text('seo_intro')->nullable()->after('seo_h1');
            }
            if (! Schema::hasColumn('city_locations', 'meta_title')) {
                $table->string('meta_title', 255)->nullable()->after('seo_intro');
            }
            if (! Schema::hasColumn('city_locations', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (! Schema::hasColumn('city_locations', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('meta_description');
            }
            if (! Schema::hasColumn('city_locations', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (! Schema::hasColumn('city_locations', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_featured');
            }
            if (! Schema::hasColumn('city_locations', 'is_indexable')) {
                $table->boolean('is_indexable')->default(true)->after('is_active');
            }
            if (! Schema::hasColumn('city_locations', 'display_order')) {
                $table->integer('display_order')->default(0)->after('is_indexable');
            }
        });

        // 3. Localities table
        if (! Schema::hasTable('localities')) {
            Schema::create('localities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('city_location_id')->constrained('city_locations')->cascadeOnDelete();
                $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
                $table->string('name', 150);
                $table->string('slug', 150);
                $table->string('pincode', 20)->nullable()->index();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->text('landmark_summary')->nullable();
                $table->text('metro_connectivity')->nullable();
                $table->json('society_clusters')->nullable();
                $table->string('meta_title', 255)->nullable();
                $table->text('meta_description')->nullable();
                $table->text('seo_intro')->nullable();
                $table->text('seo_content')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_indexable')->default(false); // Indexable only if inventory exists or explicitly reviewed
                $table->integer('display_order')->default(0);
                $table->timestamps();

                $table->unique(['city_location_id', 'slug']);
                $table->index(['city_location_id', 'is_active']);
            });
        }

        // 4. Centralized SEO Meta Overrides (CMS for URLs)
        if (! Schema::hasTable('seo_meta_overrides')) {
            Schema::create('seo_meta_overrides', function (Blueprint $table) {
                $table->id();
                $table->string('path', 255)->unique(); // e.g. /rent/rajasthan/jaipur/
                $table->string('route_name', 100)->nullable()->index();
                $table->string('meta_title', 255)->nullable();
                $table->text('meta_description')->nullable();
                $table->string('canonical_url', 255)->nullable();
                $table->string('h1', 255)->nullable();
                $table->text('intro_text')->nullable();
                $table->longText('body_html')->nullable();
                $table->boolean('is_indexable')->default(true);
                $table->string('schema_type', 50)->nullable();
                $table->json('schema_json')->nullable();
                $table->json('faqs')->nullable();
                $table->timestamp('last_reviewed_at')->nullable();
                $table->timestamps();
            });
        }

        // 5. Additional Performance Indexes on properties table
        Schema::table('properties', function (Blueprint $table) {
            // Index for high-volume location discovery
            $table->index(['status', 'city'], 'idx_properties_status_city');
            $table->index(['status', 'purpose'], 'idx_properties_status_purpose');
            $table->index(['city', 'locality'], 'idx_properties_city_locality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex('idx_properties_status_city');
            $table->dropIndex('idx_properties_status_purpose');
            $table->dropIndex('idx_properties_city_locality');
        });

        Schema::dropIfExists('seo_meta_overrides');
        Schema::dropIfExists('localities');

        Schema::table('city_locations', function (Blueprint $table) {
            $cols = [
                'state_id', 'state_slug', 'seo_h1', 'seo_intro', 'meta_title',
                'meta_description', 'latitude', 'longitude', 'is_active',
                'is_indexable', 'display_order',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('city_locations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::dropIfExists('states');
    }
};
