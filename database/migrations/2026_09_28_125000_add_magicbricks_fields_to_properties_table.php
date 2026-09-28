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
            $table->integer('balconies')->nullable()->default(1);
            $table->string('floor_no')->nullable()->default('Ground');
            $table->string('total_floors')->nullable()->default('4');
            $table->string('posting_as')->nullable()->default('Full House'); // Full House, On sharing basis
            $table->string('total_flats_in_society')->nullable(); // <50, 50-100, >100
            $table->string('maintenance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'balconies',
                'floor_no',
                'total_floors',
                'posting_as',
                'total_flats_in_society',
                'maintenance',
            ]);
        });
    }
};
