<?php

namespace Database\Seeders;

use App\Models\LeadRoutingRule;
use App\Models\MonetizationPlan;
use App\Models\MonetizationProduct;
use App\Models\SlaRule;
use App\Models\TaxConfiguration;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class Phase9MonetizationAndLeadsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Monetization Products
        $products = [
            [
                'name' => 'Featured Listing',
                'code' => 'FEATURED_LISTING',
                'target_role' => 'owner',
                'target_category' => 'LISTING_PROMOTION',
                'description' => 'Highlight listing at top of search results with verified featured badge.',
                'is_active' => true,
                'plans' => [
                    [
                        'name' => 'Featured Listing 30 Days',
                        'slug' => 'featured-30d',
                        'price' => 1499.00,
                        'currency' => 'INR',
                        'tax_percentage' => 18.00,
                        'validity_days' => 30,
                        'features' => ['Top search tier placement', 'Featured ribbon badge', '3x verified views'],
                        'is_active' => true,
                    ],
                    [
                        'name' => 'Featured Listing 60 Days',
                        'slug' => 'featured-60d',
                        'price' => 2499.00,
                        'currency' => 'INR',
                        'tax_percentage' => 18.00,
                        'validity_days' => 60,
                        'features' => ['Top search tier placement', 'Featured ribbon badge', '5x verified views', 'Dedicated lead manager'],
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Boosted Listing',
                'code' => 'BOOSTED_LISTING',
                'target_role' => 'owner',
                'target_category' => 'LISTING_PROMOTION',
                'description' => 'Fast algorithmic discovery boost for active listings.',
                'is_active' => true,
                'plans' => [
                    [
                        'name' => 'Boosted Listing 14 Days',
                        'slug' => 'boost-14d',
                        'price' => 799.00,
                        'currency' => 'INR',
                        'tax_percentage' => 18.00,
                        'validity_days' => 14,
                        'features' => ['Category page highlight', 'WhatsApp lead alerts'],
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Premium Marketing Pack',
                'code' => 'PREMIUM_MARKETING',
                'target_role' => 'builder',
                'target_category' => 'MARKETING_PACKAGE',
                'description' => 'Comprehensive multi-channel outreach for high-volume projects and builders.',
                'is_active' => true,
                'plans' => [
                    [
                        'name' => 'Project Launch Boost 30 Days',
                        'slug' => 'prem-mktg-30d',
                        'price' => 9999.00,
                        'currency' => 'INR',
                        'tax_percentage' => 18.00,
                        'validity_days' => 30,
                        'features' => ['Hero banner placement', 'Direct buyer matching', 'Dedicated CRM routing', 'Verified site-visit scheduling'],
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Broker Subscription Plan',
                'code' => 'BROKER_SUBSCRIPTION',
                'target_role' => 'broker',
                'target_category' => 'SUBSCRIPTION',
                'description' => 'High-capacity CRM leads and multi-listing management for real estate consultants.',
                'is_active' => true,
                'plans' => [
                    [
                        'name' => 'Broker Pro 30 Days',
                        'slug' => 'broker-pro-30d',
                        'price' => 3999.00,
                        'currency' => 'INR',
                        'tax_percentage' => 18.00,
                        'validity_days' => 30,
                        'features' => ['Unlimited CRM lead routing', 'Priority SLA tracking', 'Client pipeline management'],
                        'is_active' => true,
                    ],
                ],
            ],
            [
                'name' => 'Contact Access Unlock Pack',
                'code' => 'CONTACT_ACCESS',
                'target_role' => 'tenant',
                'target_category' => 'MICROTRANSACTION',
                'description' => 'Direct verified owner contact unlock pack for prospective tenants & buyers.',
                'is_active' => true,
                'plans' => [
                    [
                        'name' => '5 Direct Contact Unlocks',
                        'slug' => 'contact-5-pack',
                        'price' => 499.00,
                        'currency' => 'INR',
                        'tax_percentage' => 18.00,
                        'validity_days' => 90,
                        'features' => ['5 direct phone/email contact reveals', 'Zero brokerage guarantee', 'Direct owner chat'],
                        'is_active' => true,
                    ],
                ],
            ],
        ];

        foreach ($products as $prodData) {
            $plans = $prodData['plans'];
            unset($prodData['plans']);

            $product = MonetizationProduct::firstOrCreate(
                ['code' => $prodData['code']],
                $prodData
            );

            foreach ($plans as $planData) {
                MonetizationPlan::firstOrCreate(
                    ['slug' => $planData['slug']],
                    array_merge($planData, ['monetization_product_id' => $product->id])
                );
            }
        }

        // 2. Default SLA Rules
        $slaRules = [
            ['name' => 'Critical Rental Response', 'lead_type' => 'residential_rental', 'priority' => 'urgent', 'response_time_minutes' => 30, 'is_active' => true],
            ['name' => 'Standard Rental Response', 'lead_type' => 'residential_rental', 'priority' => 'normal', 'response_time_minutes' => 120, 'is_active' => true],
            ['name' => 'Sale Lead Response', 'lead_type' => 'property_sale', 'priority' => 'high', 'response_time_minutes' => 60, 'is_active' => true],
            ['name' => 'Commercial Lead Response', 'lead_type' => 'commercial', 'priority' => 'normal', 'response_time_minutes' => 180, 'is_active' => true],
            ['name' => 'Housing365 Care Response', 'lead_type' => 'care_service', 'priority' => 'urgent', 'response_time_minutes' => 15, 'is_active' => true],
            ['name' => 'Property Management Lead Response', 'lead_type' => 'property_management', 'priority' => 'normal', 'response_time_minutes' => 60, 'is_active' => true],
        ];

        foreach ($slaRules as $sla) {
            SlaRule::firstOrCreate(
                ['lead_type' => $sla['lead_type'], 'priority' => $sla['priority']],
                $sla
            );
        }

        // 3. Default General Lead Routing Rule
        LeadRoutingRule::firstOrCreate(
            ['name' => 'Default Direct Owner Routing'],
            [
                'lead_type' => null,
                'state' => null,
                'city' => null,
                'locality' => null,
                'property_type' => null,
                'category' => null,
                'routing_method' => 'listing_owner',
                'priority' => 1,
                'is_active' => true,
            ]
        );

        // 4. Default Seeded Tax Configuration (18% GST as initial configuration data)
        TaxConfiguration::firstOrCreate(
            ['tax_name' => 'GST', 'active' => true],
            [
                'tax_enabled' => true,
                'tax_name' => 'GST',
                'tax_type' => 'GST',
                'tax_rate' => 18.00,
                'cgst_enabled' => true,
                'sgst_enabled' => true,
                'igst_enabled' => false,
                'cgst_rate' => 9.00,
                'sgst_rate' => 9.00,
                'igst_rate' => 18.00,
                'effective_from' => Carbon::now()->subYear(),
                'effective_until' => null,
                'applicable_to' => null,
                'notes' => 'Default standard GST rate seeded as initial configuration data.',
                'active' => true,
            ]
        );
    }
}
