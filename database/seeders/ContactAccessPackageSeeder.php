<?php

namespace Database\Seeders;

use App\Models\ContactAccessPackage;
use Illuminate\Database\Seeder;

class ContactAccessPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Starter Contact Pass',
                'slug' => 'starter-pass',
                'description' => 'Unlock direct verified owner contact details for up to 3 properties.',
                'credits' => 3,
                'price' => 299.00,
                'currency' => 'INR',
                'validity_days' => 30,
                'badge_text' => 'Essential',
                'features' => ['3 Direct Owner Contacts', 'Full Mobile & WhatsApp Access', '30 Days Validity', 'Zero Brokerage Guarantee'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Popular Contact Pass',
                'slug' => 'popular-pass',
                'description' => 'Unlock direct verified owner contact details for up to 10 properties.',
                'credits' => 10,
                'price' => 699.00,
                'currency' => 'INR',
                'validity_days' => 60,
                'badge_text' => 'Most Popular',
                'features' => ['10 Direct Owner Contacts', 'Full Mobile & WhatsApp Access', '60 Days Validity', 'Priority Verification Badge', 'Zero Brokerage Guarantee'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium Contact Pass',
                'slug' => 'premium-pass',
                'description' => 'Unlock direct verified owner contact details for up to 25 properties.',
                'credits' => 25,
                'price' => 1299.00,
                'currency' => 'INR',
                'validity_days' => 90,
                'badge_text' => 'Best Value',
                'features' => ['25 Direct Owner Contacts', 'Full Mobile & WhatsApp Access', '90 Days Validity', 'VIP Assistance Access', 'Zero Brokerage Guarantee'],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($packages as $pkg) {
            ContactAccessPackage::updateOrCreate(
                ['slug' => $pkg['slug']],
                $pkg
            );
        }
    }
}
