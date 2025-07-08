<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    public function geocode(string $address): ?array
    {
        $response = Http::withHeaders([
            'User-Agent' => 'MonProjetLaravel/1.0 (monemail@example.com)', // Modifie avec ton projet et email
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $address,
            'format' => 'json',
            'limit' => 1,
        ]);

        if ($response->successful() && !empty($response->json())) {
            $result = $response->json()[0];
            return [
                'lat' => $result['lat'],
                'lng' => $result['lon'],
            ];
        }

        return null;
    }
}
