<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetTypeController extends Controller
{
    /**
     * Display list of asset types with icon management
     */
    public function index()
    {
        $assetTypes = AssetType::withCount('assets')->get();
        return view('asset-types.index', ['assetTypes' => $assetTypes]);
    }

    /**
     * Update icon and color for asset type
     */
    public function updateIcon(Request $request)
    {
        $validated = $request->validate([
            'asset_type_id' => 'required|exists:asset_types,id',
            'icon' => 'required|string|max:50',
            'color' => 'required|regex:/^#[0-9A-F]{6}$/i',
        ]);

        $assetType = AssetType::find($validated['asset_type_id']);
        $assetType->update([
            'icon' => $validated['icon'],
            'color' => $validated['color'],
        ]);

        return redirect()->route('asset-types.index')
            ->with('success', "Icon dan warna untuk '{$assetType->name}' berhasil diperbarui!");
    }

    /**
     * Show the form for creating a new asset type
     */
    public function create()
    {
        return view('asset-types.create');
    }

    /**
     * Store a newly created asset type
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_types',
            'icon' => 'required|string|max:50',
            'color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string|max:1000',
            'subtypes' => 'nullable|array'
        ]);

        DB::transaction(function () use ($validated) {
            $assetType = AssetType::create($validated);

            if (isset($validated['subtypes'])) {

                foreach ($validated['subtypes'] as $subtypeData) {
                    $assetType->subtypes()->create([
                        'name' => $subtypeData['name'],
                        'color' => $subtypeData['color'],
                    ]);
                }
            }
        });

        return redirect()->route('asset-types.index')
            ->with('success', 'Kategori aset baru berhasil ditambahkan!');
    }

    /**
     * Display the specified asset type
     */
    public function show(AssetType $assetType)
    {
        return redirect()->route('asset-types.index');
    }

    /**
     * Show the form for editing the specified asset type
     */
    public function edit(AssetType $assetType)
    {
        $assetType->loadCount(['assets', 'subtypes'])->load('subtypes');
        return view('asset-types.edit', ['assetType' => $assetType]);
    }

    /**
     * Update the specified asset type in storage
     */
    public function update(Request $request, AssetType $assetType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_types,name,' . $assetType->id,
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'description' => 'nullable|string|max:1000',
            'subtypes' => 'nullable|array'
        ]);

        DB::transaction(function () use ($assetType, $validated) {
            $assetType->update($validated);

            if (isset($validated['subtypes'])) {

                $ids = collect($validated['subtypes'])
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                $assetType->subtypes()
                    ->whereNotIn('id', $ids)
                    ->delete();

                foreach ($validated['subtypes'] as $subtypeData) {
                    $assetType->subtypes()->updateOrCreate(
                        ['id' => $subtypeData['id']],
                        [
                            'name' => $subtypeData['name'],
                            'color' => $subtypeData['color'],
                        ]
                    );
                }
            }
        });

        return redirect()->route('asset-types.index')
            ->with('success', 'Kategori aset berhasil diperbarui!');
    }

    /**
     * Remove the specified asset type from storage
     */
    public function destroy(AssetType $assetType)
    {
        // Check if there are assets using this type
        if ($assetType->assets()->exists()) {
            return redirect()->route('asset-types.index')
                ->with('error', 'Tidak dapat menghapus kategori yang masih memiliki aset terdaftar.');
        }

        DB::transaction(function () use ($assetType){
            // Delete Subtypes
            $assetType->subtypes()->delete();
            
            $assetType->delete();
        });


        return redirect()->route('asset-types.index')
            ->with('success', 'Kategori aset berhasil dihapus!');
    }
}
