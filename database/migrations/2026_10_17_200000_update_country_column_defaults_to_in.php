<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Updates legacy 'CA' defaults on properties and city_locations to 'IN'.
     * Preserves existing valid records and leaves any intentional international rows untouched.
     */
    public function up(): void
    {
        if (Schema::hasTable('properties') && Schema::hasColumn('properties', 'country')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->string('country')->default('IN')->change();
            });

            // Conservatively update only records with established Indian geography
            $indianStates = [
                'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
                'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka',
                'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram',
                'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu',
                'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
                'Delhi', 'Delhi NCR', 'Chandigarh', 'Puducherry', 'Jammu and Kashmir', 'Ladakh',
            ];

            DB::table('properties')
                ->where('country', 'CA')
                ->where(function ($q) use ($indianStates) {
                    $q->whereIn('state', $indianStates)
                        ->orWhereIn('city', ['Mumbai', 'Bengaluru', 'Delhi', 'New Delhi', 'Hyderabad', 'Chennai', 'Kolkata', 'Pune', 'Ahmedabad', 'Jaipur', 'Gurugram', 'Noida']);
                })
                ->update(['country' => 'IN']);
        }

        if (Schema::hasTable('city_locations') && Schema::hasColumn('city_locations', 'country')) {
            Schema::table('city_locations', function (Blueprint $table) {
                $table->string('country')->default('IN')->change();
            });

            $indianStates = [
                'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
                'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka',
                'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram',
                'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu',
                'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
                'Delhi', 'Delhi NCR', 'Chandigarh', 'Puducherry', 'Jammu and Kashmir', 'Ladakh',
            ];

            DB::table('city_locations')
                ->where('country', 'CA')
                ->whereIn('province_or_state', $indianStates)
                ->update(['country' => 'IN']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('properties') && Schema::hasColumn('properties', 'country')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->string('country')->default('CA')->change();
            });
        }

        if (Schema::hasTable('city_locations') && Schema::hasColumn('city_locations', 'country')) {
            Schema::table('city_locations', function (Blueprint $table) {
                $table->string('country')->default('CA')->change();
            });
        }
    }
};
