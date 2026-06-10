<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Trayek;

class LandingController extends Controller
{
    public function index()
    {
        $assets = Asset::with('type')->get();
        $trayeks = Trayek::all();

        $assetData = $assets
            ->map(function (Asset $asset) {
                return [
                    'id' => $asset->id,
                    'name' => $asset->name,
                    'type' => $asset->type?->name,
                    'icon' => $asset->type?->icon ?? 'fa-cube',
                    'status' => $asset->status,
                    'location' => $asset->location,
                    'geometry' => $asset->type?->geometry ?? 'point',
                    'color' => $asset->type?->color ?? '#3b82f6',
                    'coordinates' => is_string($asset->coordinates) ? json_decode($asset->coordinates, true) : $asset->coordinates,
                ];
            })
            ->values();

        return view('public.landing', [
            'assets' => $assets,
            'assetData' => $assetData,
            'trayeks' => $trayeks,
        ]);
    }
}
