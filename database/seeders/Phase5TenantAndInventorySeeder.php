<?php

namespace Database\Seeders;

use App\Models\CoworkingInventory;
use App\Models\PgBed;
use App\Models\PgRoom;
use App\Models\Property;
use App\Models\RentAccount;
use App\Models\RentLedger;
use App\Models\User;
use Illuminate\Database\Seeder;

class Phase5TenantAndInventorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure test owner & tenant exist
        $owner = User::firstOrCreate(
            ['email' => 'landlord@test.com'],
            ['name' => 'Rajesh Sharma', 'password' => bcrypt('password'), 'phone' => '9876543210']
        );
        $owner->assignRole('owner');

        $tenant = User::firstOrCreate(
            ['email' => 'renter@test.com'],
            ['name' => 'Amit Verma', 'password' => bcrypt('password'), 'phone' => '9876543211']
        );
        $tenant->assignRole('tenant');

        // 2. PG Property with Rooms and Beds
        $pgProperty = Property::firstOrCreate(
            ['slug' => 'housing365-colive-koramangala'],
            [
                'title' => 'Housing365 Colive & PG Hub Koramangala',
                'description' => 'Premium fully-furnished PG with AC, 3-time meals, high-speed WiFi and daily housekeeping.',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'locality' => 'Koramangala 4th Block',
                'price' => 12000,
                'deposit_amount' => 12000,
                'purpose' => 'pg',
                'category' => 'residential',
                'status' => 'Available',
                'user_id' => $owner->id,
                'seller_name' => $owner->name,
                'seller_email' => $owner->email,
                'seller_phone' => $owner->phone,
                'is_managed' => false,
                'is_verified' => true,
            ]
        );

        $room101 = PgRoom::firstOrCreate(
            ['property_id' => $pgProperty->id, 'room_number' => '101'],
            [
                'floor' => '1st Floor',
                'room_type' => 'double',
                'gender_preference' => 'male',
                'monthly_rent_per_bed' => 12000,
                'deposit_per_bed' => 12000,
                'amenities' => ['AC', 'Attached Washroom', 'Geyser', 'Balcony'],
                'status' => 'active',
            ]
        );

        PgBed::firstOrCreate(
            ['pg_room_id' => $room101->id, 'bed_code' => 'R101-A'],
            ['property_id' => $pgProperty->id, 'status' => PgBed::STATUS_AVAILABLE]
        );

        PgBed::firstOrCreate(
            ['pg_room_id' => $room101->id, 'bed_code' => 'R101-B'],
            ['property_id' => $pgProperty->id, 'status' => PgBed::STATUS_AVAILABLE]
        );

        // 3. Coworking Property with Desks and Cabins
        $coworkingProperty = Property::firstOrCreate(
            ['slug' => 'housing365-innovate-cowork-indiranagar'],
            [
                'title' => 'Housing365 Innovate Coworking Hub Indiranagar',
                'description' => 'Modern ergonomic workspace with 1Gbps fiber internet, private meeting pods and artisanal cafe.',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'locality' => 'Indiranagar 100ft Road',
                'price' => 6500,
                'purpose' => 'coworking',
                'category' => 'commercial',
                'status' => 'Available',
                'user_id' => $owner->id,
                'seller_name' => $owner->name,
                'seller_email' => $owner->email,
                'seller_phone' => $owner->phone,
                'is_managed' => false,
                'is_verified' => true,
            ]
        );

        CoworkingInventory::firstOrCreate(
            ['property_id' => $coworkingProperty->id, 'inventory_code' => 'HOT-DESK-01'],
            [
                'inventory_type' => CoworkingInventory::TYPE_HOT_DESK,
                'capacity' => 1,
                'hourly_rate' => 150,
                'daily_rate' => 600,
                'monthly_rate' => 6500,
                'amenities' => ['Fiber WiFi', 'Unlimited Coffee', 'Power Backup'],
                'status' => CoworkingInventory::STATUS_AVAILABLE,
            ]
        );

        CoworkingInventory::firstOrCreate(
            ['property_id' => $coworkingProperty->id, 'inventory_code' => 'DEDICATED-DESK-01'],
            [
                'inventory_type' => CoworkingInventory::TYPE_DEDICATED_DESK,
                'capacity' => 1,
                'hourly_rate' => 200,
                'daily_rate' => 850,
                'monthly_rate' => 9500,
                'amenities' => ['Dual Monitors', 'Ergonomic Chair', 'Filing Cabinet'],
                'status' => CoworkingInventory::STATUS_AVAILABLE,
            ]
        );

        CoworkingInventory::firstOrCreate(
            ['property_id' => $coworkingProperty->id, 'inventory_code' => 'CABIN-A'],
            [
                'inventory_type' => CoworkingInventory::TYPE_PRIVATE_CABIN,
                'capacity' => 4,
                'hourly_rate' => 800,
                'daily_rate' => 3500,
                'monthly_rate' => 38000,
                'amenities' => ['Private Access', 'Whiteboard', 'Conference Phone'],
                'status' => CoworkingInventory::STATUS_AVAILABLE,
            ]
        );

        // 4. Sample Rent Account and Ledger
        $rentProperty = Property::firstOrCreate(
            ['slug' => 'prestige-residences-harlur'],
            [
                'title' => 'Prestige 3BHK Residences Harlur Road',
                'description' => 'Semi-furnished 3BHK high-rise apartment.',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'locality' => 'Harlur Road',
                'price' => 45000,
                'deposit_amount' => 150000,
                'purpose' => 'rent',
                'category' => 'residential',
                'status' => 'Available',
                'user_id' => $owner->id,
                'seller_name' => $owner->name,
                'seller_email' => $owner->email,
                'seller_phone' => $owner->phone,
                'is_managed' => false,
            ]
        );

        $rentAccount = RentAccount::firstOrCreate(
            ['property_id' => $rentProperty->id, 'tenant_id' => $tenant->id],
            [
                'owner_id' => $owner->id,
                'lease_start_date' => now()->startOfMonth(),
                'lease_end_date' => now()->addMonths(11),
                'monthly_rent' => 45000,
                'security_deposit' => 150000,
                'due_day_of_month' => 5,
                'status' => RentAccount::STATUS_ACTIVE,
            ]
        );

        RentLedger::firstOrCreate(
            ['rent_account_id' => $rentAccount->id, 'billing_period' => date('Y-m')],
            [
                'property_id' => $rentProperty->id,
                'tenant_id' => $tenant->id,
                'owner_id' => $owner->id,
                'due_date' => date('Y-m-05'),
                'amount_due' => 45000,
                'amount_paid' => 0.00,
                'outstanding_balance' => 45000,
                'status' => RentLedger::STATUS_DUE,
            ]
        );
    }
}
