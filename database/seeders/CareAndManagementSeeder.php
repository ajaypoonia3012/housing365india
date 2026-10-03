<?php

namespace Database\Seeders;

use App\Models\CareServiceCategory;
use App\Models\ManagementPlan;
use Illuminate\Database\Seeder;

class CareAndManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Plumbing',
                'slug' => 'plumbing',
                'description' => 'Fix leaking taps, pipes, bathroom fittings, blockages, and water pump issues.',
                'icon' => 'fa-solid fa-faucet-drip',
                'is_emergency_available' => true,
                'sort_order' => 1,
                'base_price' => 299,
                'pricing_type' => 'fixed',
            ],
            [
                'name' => 'Electrical',
                'slug' => 'electrical',
                'description' => 'Wiring inspection, switchboard repair, MCB replacement, and short circuit troubleshooting.',
                'icon' => 'fa-solid fa-bolt',
                'is_emergency_available' => true,
                'sort_order' => 2,
                'base_price' => 299,
                'pricing_type' => 'fixed',
            ],
            [
                'name' => 'AC / HVAC',
                'slug' => 'ac-hvac',
                'description' => 'AC servicing, gas refilling, cooling diagnostics, and installation/uninstallation.',
                'icon' => 'fa-solid fa-snowflake',
                'is_emergency_available' => false,
                'sort_order' => 3,
                'base_price' => 499,
                'pricing_type' => 'quote_based',
            ],
            [
                'name' => 'RO / Water Purifier',
                'slug' => 'ro-water-purifier',
                'description' => 'Filter replacement, membrane service, TDS tuning, and periodic RO health check.',
                'icon' => 'fa-solid fa-droplet',
                'is_emergency_available' => false,
                'sort_order' => 4,
                'base_price' => 399,
                'pricing_type' => 'fixed',
            ],
            [
                'name' => 'Geyser',
                'slug' => 'geyser',
                'description' => 'Thermostat replacement, coil repair, element descaling, and water heater service.',
                'icon' => 'fa-solid fa-temperature-arrow-up',
                'is_emergency_available' => false,
                'sort_order' => 5,
                'base_price' => 399,
                'pricing_type' => 'fixed',
            ],
            [
                'name' => 'Appliance Repair',
                'slug' => 'appliance-repair',
                'description' => 'Refrigerator, washing machine, microwave, and kitchen chimney repairs.',
                'icon' => 'fa-solid fa-tv',
                'is_emergency_available' => false,
                'sort_order' => 6,
                'base_price' => 349,
                'pricing_type' => 'quote_based',
            ],
            [
                'name' => 'Carpentry',
                'slug' => 'carpentry',
                'description' => 'Door lock repair, modular kitchen alignment, furniture assembly, and wooden polish.',
                'icon' => 'fa-solid fa-hammer',
                'is_emergency_available' => false,
                'sort_order' => 7,
                'base_price' => 299,
                'pricing_type' => 'fixed',
            ],
            [
                'name' => 'Painting',
                'slug' => 'painting',
                'description' => 'Touch-up painting, rental repaint, waterproof coating, and full home whitewashing.',
                'icon' => 'fa-solid fa-paint-roller',
                'is_emergency_available' => false,
                'sort_order' => 8,
                'base_price' => 999,
                'pricing_type' => 'quote_based',
            ],
            [
                'name' => 'Pest Control',
                'slug' => 'pest-control',
                'description' => 'Eco-safe treatment for termites, cockroaches, bed bugs, and general pest prevention.',
                'icon' => 'fa-solid fa-bug',
                'is_emergency_available' => false,
                'sort_order' => 9,
                'base_price' => 799,
                'pricing_type' => 'fixed',
            ],
            [
                'name' => 'Deep Cleaning',
                'slug' => 'deep-cleaning',
                'description' => 'Comprehensive kitchen, bathroom, floor scrubbing, and balcony sanitization.',
                'icon' => 'fa-solid fa-broom',
                'is_emergency_available' => false,
                'sort_order' => 10,
                'base_price' => 1499,
                'pricing_type' => 'quote_based',
            ],
            [
                'name' => 'Move-in Cleaning',
                'slug' => 'move-in-cleaning',
                'description' => 'Sparkling turnaround for vacant flats before tenant arrival.',
                'icon' => 'fa-solid fa-truck-ramp-box',
                'is_emergency_available' => false,
                'sort_order' => 11,
                'base_price' => 1999,
                'pricing_type' => 'quote_based',
            ],
            [
                'name' => 'Move-out Cleaning',
                'slug' => 'move-out-cleaning',
                'description' => 'Deep restoration and deposit handover cleaning after tenant checkout.',
                'icon' => 'fa-solid fa-box-open',
                'is_emergency_available' => false,
                'sort_order' => 12,
                'base_price' => 1999,
                'pricing_type' => 'quote_based',
            ],
            [
                'name' => 'Property Inspection',
                'slug' => 'property-inspection',
                'description' => '50-point physical visual audit with photolog, electrical, civil, and plumbing reports.',
                'icon' => 'fa-solid fa-clipboard-check',
                'is_emergency_available' => false,
                'sort_order' => 13,
                'base_price' => 1299,
                'pricing_type' => 'fixed',
            ],
            [
                'name' => 'General Maintenance',
                'slug' => 'general-maintenance',
                'description' => 'Odd jobs, curtain rod installation, silicon sealing, and minor fixtures.',
                'icon' => 'fa-solid fa-screwdriver-wrench',
                'is_emergency_available' => false,
                'sort_order' => 14,
                'base_price' => 499,
                'pricing_type' => 'fixed',
            ],
            [
                'name' => 'Emergency Maintenance',
                'slug' => 'emergency-maintenance',
                'description' => 'Urgent response for bursting pipes, major power outages, and immediate safety issues.',
                'icon' => 'fa-solid fa-triangle-exclamation',
                'is_emergency_available' => true,
                'sort_order' => 15,
                'base_price' => 799,
                'pricing_type' => 'quote_based',
            ],
        ];

        foreach ($categories as $cat) {
            CareServiceCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // Management Plans (Strictly Optional)
        $plans = [
            [
                'name' => 'Standard Property Management',
                'slug' => 'standard-property-management',
                'description' => 'Essential property oversight, rental monitoring, and vendor coordination for residential owners.',
                'pricing_model' => 'percentage',
                'fee_percentage' => 8.00,
                'fixed_fee' => null,
                'billing_frequency' => 'monthly',
                'included_services' => [
                    'Tenant Placement Coordination',
                    'Quarterly Physical Property Inspection with Photo Reports',
                    'Dedicated Housing365 Care Maintenance Coordinator',
                    'Vendor Work Order Triage and Quotes',
                    '24/7 Emergency Triage Assistance',
                ],
                'sla_details' => [
                    'Emergency Response' => 'Within 4 Hours',
                    'Routine Triage' => 'Within 24 Hours',
                    'Inspection Reporting' => 'Same Day Digital Report',
                ],
                'terms_content' => 'Optional agreement between Property Owner and Housing365 India for residential management oversight. Terminable with 30 days notice.',
                'is_active' => true,
            ],
            [
                'name' => 'Comprehensive Care & Management',
                'slug' => 'comprehensive-care-management',
                'description' => 'Full-service hands-off asset protection including pre-inspections, tenant vetting, and deep maintenance oversight.',
                'pricing_model' => 'percentage',
                'fee_percentage' => 10.00,
                'fixed_fee' => null,
                'billing_frequency' => 'monthly',
                'included_services' => [
                    'Everything in Standard Plan',
                    'Bi-Monthly Detailed Inspections',
                    'Semi-Annual Move-in/Move-out Deep Cleaning Oversight',
                    'Priority Vendor Dispatch & Zero Convenience Markups',
                    'Annual Civil & Paint Assessment',
                    'Digital Expense & Rent Ledger Statements',
                ],
                'sla_details' => [
                    'Emergency Response' => 'Within 2 Hours',
                    'Routine Triage' => 'Same Day',
                    'Inspection Reporting' => 'Comprehensive High-Res Photo Dossier',
                ],
                'terms_content' => 'Comprehensive management package covering full lifecycle asset stewardship. Terminable with 30 days notice.',
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            ManagementPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
