<?php

namespace Database\Seeders;

use App\Models\CityLocation;
use App\Models\Locality;
use App\Models\Property;
use App\Models\State;
use App\Services\IndiaLocationCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NationalLocationsSeeder extends Seeder
{
    /**
     * Seed Indian States, Cities and prime Localities based on verified inventory and catalog data.
     */
    public function run(): void
    {
        $catalog = IndiaLocationCatalog::getStates();

        $stateCodes = [
            'maharashtra' => 'MH',
            'karnataka' => 'KA',
            'delhi-ncr' => 'DL',
            'telangana' => 'TG',
            'tamil-nadu' => 'TN',
            'gujarat' => 'GJ',
            'west-bengal' => 'WB',
            'rajasthan' => 'RJ',
            'uttar-pradesh' => 'UP',
            'punjab-haryana' => 'PB',
            'kerala' => 'KL',
            'madhya-pradesh' => 'MP',
            'andhra-pradesh' => 'AP',
            'goa' => 'GA',
        ];

        foreach ($catalog as $stateSlug => $data) {
            $state = State::firstOrCreate(
                ['slug' => $stateSlug],
                [
                    'name' => $data['name'],
                    'code' => $stateCodes[$stateSlug] ?? strtoupper(substr($stateSlug, 0, 2)),
                    'type' => str_contains(strtolower($data['name']), 'delhi') ? 'union_territory' : 'state',
                    'region' => $data['region'] ?? 'India',
                    'capital' => $data['capital'] ?? null,
                    'tenancy_act_title' => $data['tenancy_act'] ?? null,
                    'police_verification_portal' => $data['police_verification'] ?? null,
                    'stamp_duty_summary' => $data['stamp_duty_note'] ?? null,
                    'meta_title' => "Property for Rent & Sale in {$data['name']} | Housing365 India",
                    'meta_description' => "Verified flats, apartments, and commercial properties across {$data['name']}. Legal tenancy agreements, zero broker spam, and verified owners on Housing365.",
                    'seo_h1' => "Property Marketplace in {$data['name']}",
                    'seo_content' => "Browse verified properties, residential rentals, commercial office spaces, and student PGs across {$data['name']}. Housing365 coordinates online e-stamping and police tenant verification.",
                    'is_active' => true,
                    'is_indexable' => true,
                ]
            );

            // Connect or create cities under this state
            if (! empty($data['cities'])) {
                foreach ($data['cities'] as $cSlug => $cData) {
                    $city = CityLocation::where('slug', $cSlug)->first();

                    if (! $city) {
                        $city = CityLocation::create([
                            'name' => $cData['name'],
                            'slug' => $cSlug,
                            'province_or_state' => $data['name'],
                            'country' => 'IN',
                            'tier' => 1,
                            'state_id' => $state->id,
                            'state_slug' => $stateSlug,
                            'description' => $cData['meta_desc'] ?? null,
                            'meta_title' => "Flats, Apartments & Commercial Property for Rent in {$cData['name']} | Housing365",
                            'meta_description' => $cData['meta_desc'] ?? "Find 100% verified flats and commercial properties for rent in {$cData['name']}. Zero broker spam, 11-month e-stamp deed on Housing365.",
                            'seo_h1' => "Property in {$cData['name']}",
                            'seo_intro' => "Explore verified residential apartments, independent houses, coworking hubs, and student PGs across prime areas of {$cData['name']}.",
                            'is_active' => true,
                            'is_indexable' => true,
                        ]);
                    } else {
                        $city->update([
                            'state_id' => $state->id,
                            'state_slug' => $stateSlug,
                            'is_active' => true,
                            'is_indexable' => true,
                            'seo_h1' => $city->seo_h1 ?: "Property in {$city->name}",
                            'seo_intro' => $city->seo_intro ?: "Explore verified residential rentals, commercial office spaces, and student PGs in {$city->name}.",
                            'meta_title' => $city->meta_title ?: "Flats, Apartments & Commercial Property for Rent in {$city->name} | Housing365",
                            'meta_description' => $city->meta_description ?: ($cData['meta_desc'] ?? "Verified properties in {$city->name}."),
                        ]);
                    }

                    // Seed localities for this city
                    if (! empty($cData['localities'])) {
                        foreach ($cData['localities'] as $locName) {
                            $locSlug = Str::slug($locName);
                            Locality::firstOrCreate(
                                [
                                    'city_location_id' => $city->id,
                                    'slug' => $locSlug,
                                ],
                                [
                                    'state_id' => $state->id,
                                    'name' => $locName,
                                    'meta_title' => "Property for Rent in {$locName}, {$city->name} | Housing365",
                                    'meta_description' => "Verified flats and residential houses for rent in {$locName}, {$city->name}. 100% direct owner listings with legal agreements.",
                                    'seo_intro' => "{$locName} is one of the premier residential enclaves in {$city->name} featuring gated societies, commercial corridors, and excellent transit connectivity.",
                                    'is_active' => true,
                                    // Indexable if properties exist in this locality
                                    'is_indexable' => Property::where('city', $city->name)->where('locality', 'LIKE', "%{$locName}%")->exists(),
                                ]
                            );
                        }
                    }
                }
            }
        }

        // Connect any localities already present in existing Property records
        $existingLocalities = Property::whereNotNull('locality')
            ->distinct()
            ->get(['city', 'locality', 'state']);

        foreach ($existingLocalities as $p) {
            $city = CityLocation::where('name', 'LIKE', $p->city)->first();
            if ($city) {
                $locName = trim($p->locality);
                $locSlug = Str::slug($locName);
                if (! empty($locSlug)) {
                    Locality::firstOrCreate(
                        [
                            'city_location_id' => $city->id,
                            'slug' => $locSlug,
                        ],
                        [
                            'state_id' => $city->state_id,
                            'name' => $locName,
                            'meta_title' => "Properties in {$locName}, {$city->name} | Housing365 India",
                            'meta_description' => "Explore verified properties in {$locName}, {$city->name}. Verified listings with direct owner contact access on Housing365.",
                            'seo_intro' => "Prime residential and commercial opportunities available in {$locName}, {$city->name}.",
                            'is_active' => true,
                            'is_indexable' => true, // Genuine properties exist here!
                        ]
                    );
                }
            }
        }
    }
}
