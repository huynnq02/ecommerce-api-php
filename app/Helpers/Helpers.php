<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

function buildQueryStringHelper(array $location)
{
    return implode(',', array_values($location));
}

function getCoordinatesHelper($location)
{
    try {
        $queryString = buildQueryStringHelper($location);
        $nominatimUrl = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($queryString);

        // Make a request to the Nominatim API using Laravel's HTTP client
        $caFilePath = base_path('cacert.pem');
        $response = Http::withOptions(['verify' => $caFilePath])->get($nominatimUrl);

        // Decode the JSON response
        $data = $response->json();

        if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
            $latitude = $data[0]['lat'];
            $longitude = $data[0]['lon'];

            // Return latitude and longitude as separate values
            return [$latitude, $longitude];
        } else {
            // Return null if coordinates are not available
            return null;
        }
    } catch (\Exception $e) {
        // Return something in case of an exception
        return null;
    }
}
