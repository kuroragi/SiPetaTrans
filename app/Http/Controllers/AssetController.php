<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use Illuminate\Http\Request;

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
        $assetTypes = AssetType::all();
        return view('assets.create', ['assetTypes' => $assetTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_type_id' => 'required|exists:asset_types,id',
            'status' => 'required|in:baik,perlu_perbaikan,rusak,dalam_pemeliharaan',
            'quantity' => 'nullable|integer|min:1',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'last_maintenance' => 'nullable|date',
        ]);

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
        $assetTypes = AssetType::all();
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
            'name' => 'required|string|max:255',
            'asset_type_id' => 'required|exists:asset_types,id',
            'status' => 'required|in:baik,perlu_perbaikan,rusak,dalam_pemeliharaan',
            'quantity' => 'nullable|integer|min:1',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'last_maintenance' => 'nullable|date',
        ]);

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
