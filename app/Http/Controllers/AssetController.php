<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view assets', only: ['index', 'show']),
            new Middleware('permission:create assets', only: ['create', 'store']),
            new Middleware('permission:edit assets', only: ['edit', 'update']),
            new Middleware('permission:delete assets', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asset::with(['type', 'subtype']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('registration_number', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('asset_type_id', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assets = $query->paginate(15);
        $assetTypes = AssetType::all();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('assets.table_body', compact('assets'))->render(),
                'pagination' => $assets->links()->toHtml()
            ]);
        }

        return view('assets.index', [
            'assets' => $assets,
            'assetTypes' => $assetTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assetTypes = AssetType::with(['subtypes'])->get();
        return view('assets.create', ['assetTypes' => $assetTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'asset_type_id' => 'required|exists:asset_types,id',
            'asset_sub_type_id' => 'nullable|exists:asset_sub_types,id',
            'acquired_at' => 'nullable',
            'acquisition_value' => 'nullable',
            'acquisition_source' => 'nullable',
            'current_value' => 'nullable',
            'status' => 'nullable|in:baik,perlu_perbaikan,rusak,dalam_pemeliharaan',
            'is_active' => 'required|boolean',
            'quantity' => 'nullable|integer|min:1',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'area' => 'nullable|numeric',
            'coordinates' => 'nullable|string',
            'vehicle_type' => 'nullable|in:R2,R4,R2/R4',
            'r2' => 'nullable|integer',
            'r4' => 'nullable|integer',
            'tariff_type' => 'nullable|in:flat,progresive',
            'manager' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'last_maintenance' => 'nullable|date',
            'last_maintenance_photo' => 'nullable|image',
            'sub_assets' => 'nullable|array',
            'sub_assets.*.description' => 'nullable|string',
            'sub_assets.*.status' => 'required_with:sub_assets|in:baik,perlu_perbaikan,rusak',
            'sub_assets.*.photo' => 'nullable|image',
        ]);
        
        if (!empty($validated['coordinates'])) {
            $validated['coordinates'] = json_decode($validated['coordinates'], true);
        }

        $photoPath = null;
        $uploadedSubPhotos = [];
        
        if($request->hasFile('last_maintenance_photo')){
            $photoPath = $request->file('last_maintenance_photo')->store('assets', 'public');
            $validated['last_maintenance_photo'] = $photoPath;
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request, $photoPath, &$uploadedSubPhotos) {
                $asset = Asset::create($validated);

                if ($photoPath) {
                    \App\Models\AssetMonitoring::create([
                        'asset_id' => $asset->id,
                        'photo_path' => $photoPath,
                        'condition' => $validated['status'],
                        'notes' => 'Inisialisasi aset baru',
                        'photo_date' => now(),
                        'captured_by' => auth()->user()->name ?? 'System',
                    ]);
                }
                
                if ($request->has('sub_assets') && $validated['quantity'] > 1) {
                    foreach ($request->sub_assets as $subData) {
                        $subPhotoPath = null;
                        if (isset($subData['photo']) && $subData['photo']->isValid()) {
                            $subPhotoPath = $subData['photo']->store('sub-assets', 'public');
                            $uploadedSubPhotos[] = $subPhotoPath;
                        }
                        \App\Models\SubAsset::create([
                            'asset_id' => $asset->id,
                            'photo_path' => $subPhotoPath,
                            'description' => $subData['description'] ?? null,
                            'status' => $subData['status'] ?? 'baik',
                        ]);
                    }
                }
            });

            return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            if (!empty($uploadedSubPhotos)) {
                foreach ($uploadedSubPhotos as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            return back()->withInput()->with('error', 'Gagal menambahkan aset: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        return view('assets.show', ['asset' => $asset]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        $assetTypes = AssetType::with(['subtypes'])->get();
        return view('assets.edit', [
            'asset' => $asset,
            'assetTypes' => $assetTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'asset_type_id' => 'required|exists:asset_types,id',
            'asset_sub_type_id' => 'nullable|exists:asset_sub_types,id',
            'acquired_at' => 'nullable',
            'acquisition_value' => 'nullable',
            'acquisition_source' => 'nullable',
            'current_value' => 'nullable',
            'status' => 'nullable|in:baik,perlu_perbaikan,rusak,dalam_pemeliharaan',
            'is_active' => 'required|boolean',
            'quantity' => 'nullable|integer|min:1',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'area' => 'nullable|numeric',
            'coordinates' => 'nullable|string',
            'vehicle_type' => 'nullable|in:R2,R4,R2/R4',
            'r2' => 'nullable|integer',
            'r4' => 'nullable|integer',
            'tariff_type' => 'nullable|in:flat,progresive',
            'manager' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'last_maintenance' => 'nullable|date',
            'last_maintenance_photo' => 'nullable|image',
            'sub_assets' => 'nullable|array',
            'sub_assets.*.id' => 'nullable|exists:sub_assets,id',
            'sub_assets.*.description' => 'nullable|string',
            'sub_assets.*.status' => 'required_with:sub_assets|in:baik,perlu_perbaikan,rusak',
            'sub_assets.*.photo' => 'nullable|image',
        ]);
        
        if (!empty($validated['coordinates'])) {
            $validated['coordinates'] = json_decode($validated['coordinates'], true);
        }

        $photoPath = null;
        $uploadedSubPhotos = [];
        $oldPhotoPath = $asset->last_maintenance_photo;

        if($request->hasFile('last_maintenance_photo')){
            $photoPath = $request->file('last_maintenance_photo')->store('assets', 'public');
            $validated['last_maintenance_photo'] = $photoPath;
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request, $asset, $photoPath, &$uploadedSubPhotos) {
                $asset->update($validated);

                if ($photoPath) {
                    \App\Models\AssetMonitoring::create([
                        'asset_id' => $asset->id,
                        'photo_path' => $photoPath,
                        'condition' => $validated['status'],
                        'notes' => 'Update aset',
                        'photo_date' => now(),
                        'captured_by' => auth()->user()->name ?? 'System',
                    ]);
                }
                
                // Handle sub_assets if quantity > 1
                if ($validated['quantity'] > 1) {
                    $providedSubAssetIds = collect($request->sub_assets ?? [])->pluck('id')->filter()->toArray();
                    
                    // Delete sub_assets that are not in the request
                    $subAssetsToDelete = $asset->subAssets()->whereNotIn('id', $providedSubAssetIds)->get();
                    foreach ($subAssetsToDelete as $subAsset) {
                        if ($subAsset->photo_path) {
                            Storage::disk('public')->delete($subAsset->photo_path);
                        }
                        $subAsset->delete();
                    }

                    if ($request->has('sub_assets')) {
                        foreach ($request->sub_assets as $subData) {
                            $subPhotoPath = null;
                            if (isset($subData['photo']) && $subData['photo']->isValid()) {
                                $subPhotoPath = $subData['photo']->store('sub-assets', 'public');
                                $uploadedSubPhotos[] = $subPhotoPath;
                            }

                            if (!empty($subData['id'])) {
                                // Update existing
                                $subAsset = \App\Models\SubAsset::find($subData['id']);
                                if ($subAsset) {
                                    $oldSubPhoto = $subAsset->photo_path;
                                    $subAsset->update([
                                        'photo_path' => $subPhotoPath ?: $subAsset->photo_path,
                                        'description' => $subData['description'] ?? null,
                                        'status' => $subData['status'] ?? 'baik',
                                    ]);
                                    // if new photo uploaded, delete old
                                    if ($subPhotoPath && $oldSubPhoto) {
                                        Storage::disk('public')->delete($oldSubPhoto);
                                    }
                                }
                            } else {
                                // Create new
                                \App\Models\SubAsset::create([
                                    'asset_id' => $asset->id,
                                    'photo_path' => $subPhotoPath,
                                    'description' => $subData['description'] ?? null,
                                    'status' => $subData['status'] ?? 'baik',
                                ]);
                            }
                        }
                    }
                } else {
                    // if quantity is 1 or less, delete all sub_assets
                    $allSubAssets = $asset->subAssets;
                    foreach ($allSubAssets as $subAsset) {
                        if ($subAsset->photo_path) {
                            Storage::disk('public')->delete($subAsset->photo_path);
                        }
                        $subAsset->delete();
                    }
                }
            });

            // If main photo was updated successfully, delete the old one
            if ($photoPath && $oldPhotoPath) {
                Storage::disk('public')->delete($oldPhotoPath);
            }

            return redirect()->route('assets.index')->with('success', 'Aset berhasil diperbarui');
        } catch (\Exception $e) {
            // Rollback uploaded photos
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            if (!empty($uploadedSubPhotos)) {
                foreach ($uploadedSubPhotos as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            return back()->withInput()->with('error', 'Gagal memperbarui aset: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus');
    }
}
