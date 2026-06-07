<?php

namespace App\Http\Controllers;

use App\Models\AssetMaintenance;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AssetMaintenanceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view maintenances', only: ['index', 'show']),
            new Middleware('permission:create maintenances', only: ['create', 'store']),
            new Middleware('permission:edit maintenances', only: ['edit', 'update']),
            new Middleware('permission:delete maintenances', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assetMaintenances = AssetMaintenance::all();

        return view('asset-maintenance.index', [
            'assetMaintenance' => $assetMaintenances
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('asset-maintenance.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'maintenance_type' => 'required|in:rutin,perbaikan',
            'status' => 'required|in:sedang_berjalan,selesai',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cost' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'condition_after' => 'nullable|required_if:status,selesai|in:baik,perlu_perbaikan,rusak',
            'photos.*' => 'image|max:5120'
        ]);

        // Additional validation if perbaikan and selesai: photo is required
        if ($request->maintenance_type === 'perbaikan' && $request->status === 'selesai') {
            $request->validate([
                'photos' => 'required|array|min:1'
            ]);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request) {
            $maintenance = AssetMaintenance::create([
                'asset_id' => $validated['asset_id'],
                'maintenance_type' => $validated['maintenance_type'],
                'status' => $validated['status'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'cost' => $validated['cost'],
                'description' => $validated['description']
            ]);

            if ($validated['maintenance_type'] === 'perbaikan' && $validated['status'] === 'selesai') {
                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $photo) {
                        $path = $photo->store('asset-photos', 'public');
                        
                        \App\Models\AssetMonitoring::create([
                            'asset_id' => $validated['asset_id'],
                            'photo_path' => $path,
                            'condition' => $validated['condition_after'],
                            'notes' => 'Otomatis: Selesai perbaikan kerusakan. (' . $maintenance->description . ')',
                            'photo_date' => now(),
                            'captured_by' => auth()->user()->name ?? 'System',
                        ]);
                    }
                }
            }
        });

        return back()->with('success', 'Data pemeliharaan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
