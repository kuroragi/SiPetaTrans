<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = Asset::with('type')->paginate(15);
        $assetTypes = AssetType::all();
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
            'acquired_at' => 'required',
            'acquisition_value' => 'required',
            'acquisition_source' => 'required',
            'current_value' => 'required',
            'status' => 'required|in:baik,perlu_perbaikan,rusak,dalam_pemeliharaan',
            'quantity' => 'nullable|integer|min:1',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'last_maintenance' => 'nullable|date',
            'last_maintenance_photo' => 'nullable|image',
        ]);

        $validated['last_maintenance_photo'] = $request->file('last_maintenance_photo')->store('assets', 'public');

        Asset::create($validated);
        return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan');
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
            'acquired_at' => 'required',
            'acquisition_value' => 'required',
            'acquisition_source' => 'required',
            'current_value' => 'required',
            'status' => 'required|in:baik,perlu_perbaikan,rusak,dalam_pemeliharaan',
            'quantity' => 'nullable|integer|min:1',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'last_maintenance' => 'nullable|date',
            'last_maintenance_photo' => 'nullable|image',
        ]);

        if($request->hasFile('last_maintenance_photo')){
            if(!empty($asset->last_maintenance_photo)){
                Storage::disk('public')->delete($asset->last_maintenance_photo);
            }

            $validated['last_maintenance_photo'] = $request->file('last_maintenance_photo')->store('assets', 'public');
        }

        $asset->update($validated);
        return redirect()->route('assets.index')->with('success', 'Aset berhasil diperbarui');
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
