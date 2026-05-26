<?php

namespace App\Http\Controllers;

use App\Models\Asset;

class LandingController extends Controller
{
    public function index()
    {
        $assets = Asset::with('type')->get();

        $assetData = $assets
            ->map(function (Asset $asset) {
                return [
                    'id' => $asset->id,
                    'name' => $asset->name,
                    'type' => $asset->type?->name,
                    'icon' => $asset->type?->icon ?? 'fa-cube',
                    'status' => $asset->status,
                    'latitude' => $asset->latitude,
                    'longitude' => $asset->longitude,
                    'location' => $asset->location,
                ];
            })
            ->values();

        return view('public.landing', [
            'assets' => $assets,
            'assetData' => $assetData,
        ]);
    }
}
