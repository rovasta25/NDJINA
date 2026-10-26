<?php

namespace App\Http\Controllers;

use App\Services\Geocoder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeocodeController extends Controller
{
    public function reverse(Request $request, Geocoder $geocoder): JsonResponse
    {
        $data = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        return response()->json(
            $geocoder->reverse((float) $data['lat'], (float) $data['lng'])
        );
    }
}
