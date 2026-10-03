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
        // 1. Saved / Bookmarked Properties table (User favorites)
        if (! Schema::hasTable('saved_properties')) {
            Schema::create('saved_properties', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('property_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['user_id', 'property_id']);
                $table->index('user_id');
                $table->index('property_id');
            });
        }

        // 2. Additive columns to properties table for unified marketplace expansion
        Schema::table('properties', function (Blueprint $table) {
            // Future ownership normalization (nullable foreignId to users)
            if (! Schema::hasColumn('properties', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }

            // Listing source attribution: Owner, Broker, Builder, Operator, Housing365
            if (! Schema::hasColumn('properties', 'listing_source')) {
                $table->string('listing_source', 50)->default('Owner')->after('posted_by');
            }

            // Verification hierarchy: unverified, submitted, basic_verified, document_verified, housing365_verified
            if (! Schema::hasColumn('properties', 'verification_level')) {
                $table->string('verification_level', 50)->default('unverified')->after('is_verified');
            }

            // PG / Co-living fields
            if (! Schema::hasColumn('properties', 'pg_gender')) {
                $table->string('pg_gender', 30)->nullable()->after('furnishing'); // boys, girls, unisex
            }
            if (! Schema::hasColumn('properties', 'food_included')) {
                $table->boolean('food_included')->default(false)->after('pg_gender');
            }
            if (! Schema::hasColumn('properties', 'occupancy_type')) {
                $table->string('occupancy_type', 50)->nullable()->after('food_included'); // single, double, triple, shared
            }
            if (! Schema::hasColumn('properties', 'deposit_amount')) {
                $table->decimal('deposit_amount', 12, 2)->nullable()->after('price');
            }
            if (! Schema::hasColumn('properties', 'house_rules')) {
                $table->text('house_rules')->nullable()->after('description');
            }

            // Coworking fields
            if (! Schema::hasColumn('properties', 'coworking_details')) {
                $table->json('coworking_details')->nullable()->after('house_rules'); // desk_types, meeting_rooms, pass_types
            }

            // Builder Project & Commercial fields
            if (! Schema::hasColumn('properties', 'rera_number')) {
                $table->string('rera_number', 100)->nullable()->after('total_flats_in_society');
            }
            if (! Schema::hasColumn('properties', 'project_name')) {
                $table->string('project_name', 150)->nullable()->after('rera_number');
            }
            if (! Schema::hasColumn('properties', 'possession_date')) {
                $table->string('possession_date', 50)->nullable()->after('project_name');
            }
            if (! Schema::hasColumn('properties', 'is_managed')) {
                $table->boolean('is_managed')->default(false)->after('featured'); // Opt-in Property Management
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_properties');

        Schema::table('properties', function (Blueprint $table) {
            $columnsToDrop = [
                'user_id',
                'listing_source',
                'verification_level',
                'pg_gender',
                'food_included',
                'occupancy_type',
                'deposit_amount',
                'house_rules',
                'coworking_details',
                'rera_number',
                'project_name',
                'possession_date',
                'is_managed',
            ];

            foreach ($columnsToDrop as $col) {
                if (Schema::hasColumn('properties', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
