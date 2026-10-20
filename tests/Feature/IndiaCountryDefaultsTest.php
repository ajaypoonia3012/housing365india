<?php

namespace Tests\Feature;

use App\Models\CityLocation;
use App\Models\Property;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class IndiaCountryDefaultsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that a newly created Property defaults to country 'IN'.
     */
    public function test_new_property_defaults_to_india(): void
    {
        $property = Property::create([
            'title' => 'Default Country Apartment Test',
            'slug' => 'default-country-apartment-test',
            'purpose' => 'rent',
            'category' => 'residential',
            'property_type' => 'Flat / Apartment',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'locality' => 'Indiranagar',
            'price' => 35000,
            'status' => 'Available',
        ]);

        $this->assertSame('IN', $property->country);

        // Verify in database row directly
        $fresh = Property::find($property->id);
        $this->assertSame('IN', $fresh->country);
    }

    /**
     * Test that a newly created CityLocation defaults to country 'IN'.
     */
    public function test_new_city_location_defaults_to_india(): void
    {
        $city = CityLocation::create([
            'name' => 'Udaipur',
            'slug' => 'udaipur',
            'province_or_state' => 'Rajasthan',
            'is_active' => true,
        ]);

        $this->assertSame('IN', $city->country);

        $fresh = CityLocation::find($city->id);
        $this->assertSame('IN', $fresh->country);
    }

    /**
     * Test that explicit international records remain intact and are not overwritten.
     */
    public function test_explicit_international_records_remain_unchanged(): void
    {
        $internationalProp = Property::create([
            'title' => 'Dubai Marina Luxury Studio',
            'slug' => 'dubai-marina-luxury-studio',
            'purpose' => 'sale',
            'category' => 'residential',
            'property_type' => 'Studio',
            'city' => 'Dubai',
            'state' => 'Dubai',
            'country' => 'AE',
            'locality' => 'Marina',
            'price' => 1200000,
            'status' => 'Available',
        ]);

        $this->assertSame('AE', $internationalProp->country);

        $fresh = Property::find($internationalProp->id);
        $this->assertSame('AE', $fresh->country);
    }
}
