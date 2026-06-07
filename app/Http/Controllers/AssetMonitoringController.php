<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetMonitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetMonitoringController extends Controller
{
    /**
     * List all assets for monitoring
     */
    public function index()
    {
        $assets = Asset::with(['type', 'monitorings' => function($query) {
            $query->latest('photo_date')->limit(1);
        }])->get();

        return view('asset-monitoring.index', [
            'assets' => $assets,
        ]);
    }

    /**
     * Show asset monitoring detail with photo history
     */
    public function show(Asset $asset)
    {
        $asset->load(['type', 'monitorings' => function($query) {
            $query->orderBy('photo_date', 'desc');
        }, 'maintenance' => function($query) {
            $query->orderBy('start_date', 'desc');
        }]);

        // Get condition history
        $conditionHistory = $asset->monitorings->groupBy(function($photo) {
            return $photo->photo_date->format('Y-m');
        })->map(function($group) {
            return $group->first();
        });

        return view('asset-monitoring.show', [
            'asset' => $asset,
            'photos' => $asset->monitorings,
            'conditionHistory' => $conditionHistory,
        ]);
    }

    /**
     * Upload new photos for asset (supports multiple files)
     */
    public function uploadPhoto(Request $request, Asset $asset)
    {
        $request->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'condition' => 'required|in:baik,perlu_perbaikan,rusak,dalam_pemeliharaan',
            'notes' => 'nullable|string|max:500',
            'photo_date' => 'required|date',
        ]);

        try {
            $uploadCount = 0;

            // Loop through each uploaded file
            foreach ($request->file('photos') as $photoFile) {
                // Store photo
                $photoPath = $photoFile->store('asset-photos', 'public');

                // Create asset photo record for each file
                AssetMonitoring::create([
                    'asset_id' => $asset->id,
                    'photo_path' => $photoPath,
                    'condition' => $request->condition,
                    'notes' => $request->notes,
                    'photo_date' => $request->photo_date,
                    'captured_by' => auth()->user()->name ?? 'System',
                ]);

                $uploadCount++;
            }

            $message = $uploadCount === 1 
                ? 'Foto aset berhasil diunggah' 
                : $uploadCount . ' foto aset berhasil diunggah';

            return redirect()->route('asset-monitoring.show', $asset)
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunggah foto: ' . $e->getMessage());
        }
    }

    /**
     * Delete photo
     */
    public function deletePhoto(AssetMonitoring $photo)
    {
        $asset = $photo->asset;

        // Delete file from storage
        if (Storage::disk('public')->exists($photo->photo_path)) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        // Delete database record
        $photo->delete();

        return redirect()->route('asset-monitoring.show', $asset)
            ->with('success', 'Foto berhasil dihapus');
    }
}
