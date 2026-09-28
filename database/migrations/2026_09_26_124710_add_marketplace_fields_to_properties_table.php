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
        Schema::table('properties', function (Blueprint $table) {
            $table->string('purpose')->default('rent'); // rent, sale, lease
            $table->string('category')->default('residential'); // residential, commercial, plot_land
            $table->string('price_display')->nullable(); // e.g. "₹45,000/mo", "₹1.85 Cr", "₹75 Lakhs"
            $table->string('area_unit')->default('sqft'); // sqft, sq.yards, acres, bigha
            $table->string('furnishing')->nullable(); // Fully Furnished, Semi-Furnished, Unfurnished, Bare Shell
            $table->string('posted_by')->default('Owner'); // Owner, Builder, Agent
            $table->string('seller_name')->nullable();
            $table->string('seller_phone')->nullable();
            $table->string('seller_email')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('locality')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'purpose',
                'category',
                'price_display',
                'area_unit',
                'furnishing',
                'posted_by',
                'seller_name',
                'seller_phone',
                'seller_email',
                'is_verified',
                'locality',
            ]);
        });
    }
};
