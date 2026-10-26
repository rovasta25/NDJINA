<?php

namespace App\Services;

use App\Support\GabonCities;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Geocoder
{
    /**
     * Resolve a GPS point into a readable place (neighbourhood, city).
     *
     * @return array{city: string, detail: string, label: string}
     */
    public function reverse(float $lat, float $lng): array
    {
        $key = 'geocode:2:'.round($lat, 4).':'.round($lng, 4);

        $cached = Cache::get($key);
        if (is_array($cached)) {
            return $cached;
        }

        $result = $this->fetch($lat, $lng);

        if ($result['label'] !== '') {
            Cache::put($key, $result, now()->addDays(7));
        }

        return $result;
    }

    /**
     * @return array{city: string, detail: string, label: string}
     */
    private function fetch(float $lat, float $lng): array
    {
        $empty = ['city' => '', 'detail' => '', 'label' => ''];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'NDJINA/1.0 (+https://ndjina.onrender.com)',
                'Accept-Language' => 'fr',
            ])->timeout(6)->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'jsonv2',
                'lat' => $lat,
                'lon' => $lng,
                'addressdetails' => 1,
                'accept-language' => 'fr',
            ]);
        } catch (ConnectionException $exception) {
            Log::warning('Geocoding failed', ['error' => $exception->getMessage()]);

            return $empty;
        }

        if (! $response->successful()) {
            Log::warning('Geocoding HTTP error', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 200),
            ]);

            return $empty;
        }

        $address = $response->json('address', []);

        $city = $this->matchKnownCity($address)
            ?? $address['city']
            ?? $address['town']
            ?? $address['village']
            ?? $address['municipality']
            ?? $address['county']
            ?? '';

        $parts = array_filter([
            $address['house_number'] ?? null,
            $address['road'] ?? null,
            $address['neighbourhood'] ?? $address['suburb'] ?? $address['quarter'] ?? null,
            $address['city_district'] ?? null,
        ]);

        $detail = trim(implode(' ', $parts));

        return [
            'city' => $city,
            'detail' => $detail,
            'label' => trim(implode(', ', array_filter([$detail, $city]))),
        ];
    }

    /**
     * @param  array<string, mixed>  $address
     */
    private function matchKnownCity(array $address): ?string
    {
        foreach ($address as $value) {
            if (! is_string($value)) {
                continue;
            }

            foreach (array_keys(GabonCities::CENTRES) as $city) {
                if (mb_stripos($value, $city) !== false) {
                    return $city;
                }
            }
        }

        return null;
    }
}
