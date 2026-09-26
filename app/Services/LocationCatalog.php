<?php

namespace App\Services;

class LocationCatalog
{
    private static ?array $data = null;

    private static function load(): void
    {
        if (self::$data === null) {
            $path = __DIR__ . '/us_locations_data.json';
            if (file_exists($path)) {
                self::$data = json_decode(file_get_contents($path), true);
            } else {
                self::$data = ['states' => [], 'cities' => []];
            }
        }
    }

    public static function getStates(): array
    {
        self::load();
        return self::$data['states'] ?? [];
    }

    public static function getState(string $slug): ?array
    {
        self::load();
        foreach (self::$data['states'] as $state) {
            if ($state['slug'] === $slug) {
                return $state;
            }
        }
        return null;
    }

    public static function getCities(?string $stateSlug = null): array
    {
        self::load();
        $cities = self::$data['cities'] ?? [];

        try {
            $dbCities = \App\Models\CityLocation::where('country', 'US')->get()->keyBy('slug');
            foreach ($cities as &$c) {
                if (isset($dbCities[$c['slug']])) {
                    $dbCity = $dbCities[$c['slug']];
                    if (!empty($dbCity->name)) {
                        $c['title'] = $dbCity->name;
                        $c['name'] = $dbCity->name;
                    }
                    if (!empty($dbCity->description)) {
                        $c['marketNote'] = $dbCity->description;
                    }
                    if (!empty($dbCity->image)) {
                        $c['heroImageUrl'] = $dbCity->image;
                    }
                    if (!empty($dbCity->avg_rent)) {
                        $rentVal = (int) preg_replace('/[^0-9]/', '', (string)$dbCity->avg_rent);
                        if ($rentVal > 0) {
                            $c['medianRent'] = $rentVal;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
        }

        if ($stateSlug) {
            return array_values(array_filter($cities, fn($c) => ($c['stateSlug'] ?? '') === $stateSlug));
        }
        return $cities;
    }

    public static function getCity(string $citySlug, ?string $stateSlug = null): ?array
    {
        self::load();
        $city = null;
        foreach (self::$data['cities'] as $c) {
            if ($c['slug'] === $citySlug) {
                if ($stateSlug === null || ($c['stateSlug'] ?? '') === $stateSlug) {
                    $city = $c;
                    break;
                }
            }
        }

        try {
            $dbCity = \App\Models\CityLocation::where('slug', $citySlug)->where('country', 'US')->first();
            if ($dbCity) {
                if (!$city) {
                    $stateModel = self::getState($stateSlug ?? \Illuminate\Support\Str::slug($dbCity->province_or_state));
                    $city = [
                        'slug' => $dbCity->slug,
                        'title' => $dbCity->name,
                        'name' => $dbCity->name,
                        'stateSlug' => $stateSlug ?? \Illuminate\Support\Str::slug($dbCity->province_or_state),
                        'state' => $dbCity->province_or_state,
                        'stateAbbr' => $stateModel['abbreviation'] ?? strtoupper(substr($dbCity->province_or_state, 0, 2)),
                        'medianRent' => (int) preg_replace('/[^0-9]/', '', (string)$dbCity->avg_rent) ?: 2200,
                        'population' => 250000,
                        'heroImageUrl' => $dbCity->image ?: 'https://images.unsplash.com/photo-1444723121867-7a241cacace9?auto=format&fit=crop&w=2000&q=80',
                        'marketNote' => $dbCity->description ?: "Full-service leasing and licensed property placement in {$dbCity->name}.",
                        'subMarkets' => ['Downtown', 'Metro Area', 'Uptown', 'Westside'],
                        'seoKeywords' => ["{$dbCity->name} leasing agent", "property management in {$dbCity->name}", "tenant placement {$dbCity->name}"]
                    ];
                } else {
                    if (!empty($dbCity->name)) {
                        $city['title'] = $dbCity->name;
                        $city['name'] = $dbCity->name;
                    }
                    if (!empty($dbCity->description)) {
                        $city['marketNote'] = $dbCity->description;
                    }
                    if (!empty($dbCity->image)) {
                        $city['heroImageUrl'] = $dbCity->image;
                    }
                    if (!empty($dbCity->avg_rent)) {
                        $rentVal = (int) preg_replace('/[^0-9]/', '', (string)$dbCity->avg_rent);
                        if ($rentVal > 0) {
                            $city['medianRent'] = $rentVal;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
        }

        return $city;
    }
}
