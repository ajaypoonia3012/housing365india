<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            IndiaDatabaseSeeder::class,
            ContactAccessPackageSeeder::class,
            MarketplacePropertiesSeeder::class,
            CareAndManagementSeeder::class,
            Phase9MonetizationAndLeadsSeeder::class,
            Phase10TrustAndFraudSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
