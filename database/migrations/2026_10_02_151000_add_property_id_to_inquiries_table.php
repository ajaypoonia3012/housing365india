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
        if (Schema::hasTable('inquiries') && ! Schema::hasColumn('inquiries', 'property_id')) {
            Schema::table('inquiries', function (Blueprint $table) {
                $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('inquiries') && Schema::hasColumn('inquiries', 'property_id')) {
            Schema::table('inquiries', function (Blueprint $table) {
                $table->dropForeign(['property_id']);
                $table->dropColumn('property_id');
            });
        }
    }
};
