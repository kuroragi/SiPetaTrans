<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetDepreciation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssetDepreciationController extends Controller
{
    public function show(Asset $asset)
    {
        $depreciations = $asset->depreciations()->get();
        return view('asset-depreciations.show', compact('asset', 'depreciations'));
    }

    public function store(Request $request, Asset $asset)
    {
        $request->validate([
            'depreciation_date' => 'required|date',
            'value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $depreciationDate = Carbon::parse($request->depreciation_date);
        $value = $request->value;
        $acquiredAt = $asset->acquired_at ? Carbon::parse($asset->acquired_at) : null;
        $createdAt = $asset->created_at ? Carbon::parse($asset->created_at)->startOfDay() : null;

        // Validation 1: Jika date penyusutan kurang dari acquired_at
        if ($acquiredAt && $depreciationDate->lt($acquiredAt)) {
            return back()->withErrors(['depreciation_date' => 'Tanggal tidak boleh lebih kecil dari tanggal perolehan.'])->withInput();
        }

        // Validation 2: Jika nilai penyusutan lebih tinggi dari nilai perolehan
        if ($value > $asset->acquisition_value) {
            return back()->withErrors(['value' => 'Nilai penyusutan tidak boleh lebih tinggi dari perolehan.'])->withInput();
        }

        // Validation 3: Jika tanggal penyusutan kurang dari created_at asset dan angka penyusutan lebih rendah dari current_value
        if ($createdAt && $depreciationDate->lt($createdAt) && $value < $asset->current_value) {
            return back()->withErrors(['value' => 'Tanggal penyusutan sebelum pencatatan aset tidak diizinkan memiliki nilai lebih rendah dari nilai saat ini.'])->withInput();
        }

        try {
            DB::beginTransaction();

            $depreciation = $asset->depreciations()->create([
                'depreciation_date' => $depreciationDate->format('Y-m-d'),
                'value' => $value,
                'notes' => $request->notes,
            ]);

            // Validation 4: jika tanggal penyusutan lebih dari created_at asset atau lebih dari tanggal penyusutan terbaru maka jadikan nilai dari penyusutan menjadi current_value asset
            $latestDepreciation = $asset->depreciations()->orderByDesc('depreciation_date')->first();
            
            if ($createdAt && $depreciationDate->gte($createdAt) || ($latestDepreciation && $depreciationDate->gte(Carbon::parse($latestDepreciation->depreciation_date)))) {
                $asset->update(['current_value' => $value]);
            }

            DB::commit();
            return back()->with('success', 'Data penyusutan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, Asset $asset, AssetDepreciation $depreciation)
    {
        $request->validate([
            'depreciation_date' => 'required|date',
            'value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $depreciationDate = Carbon::parse($request->depreciation_date);
        $value = $request->value;
        $acquiredAt = $asset->acquired_at ? Carbon::parse($asset->acquired_at) : null;
        $createdAt = $asset->created_at ? Carbon::parse($asset->created_at)->startOfDay() : null;

        if ($acquiredAt && $depreciationDate->lt($acquiredAt)) {
            return back()->withErrors(['depreciation_date' => 'Tanggal tidak boleh lebih kecil dari tanggal perolehan.'])->withInput();
        }
        if ($value > $asset->acquisition_value) {
            return back()->withErrors(['value' => 'Nilai penyusutan tidak boleh lebih tinggi dari perolehan.'])->withInput();
        }
        if ($createdAt && $depreciationDate->lt($createdAt) && $value < $asset->current_value) {
            return back()->withErrors(['value' => 'Tanggal penyusutan sebelum pencatatan aset tidak diizinkan memiliki nilai lebih rendah dari nilai saat ini.'])->withInput();
        }

        try {
            DB::beginTransaction();

            $depreciation->update([
                'depreciation_date' => $depreciationDate->format('Y-m-d'),
                'value' => $value,
                'notes' => $request->notes,
            ]);

            // Re-calculate current_value based on the newest depreciation date, or fallback to acquisition_value
            $latestDepreciation = $asset->depreciations()->orderByDesc('depreciation_date')->first();
            if ($latestDepreciation) {
                $asset->update(['current_value' => $latestDepreciation->value]);
            } else {
                $asset->update(['current_value' => $asset->acquisition_value]);
            }

            DB::commit();
            return back()->with('success', 'Data penyusutan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Asset $asset, AssetDepreciation $depreciation)
    {
        try {
            DB::beginTransaction();

            $depreciation->delete();

            // Re-calculate current_value
            $latestDepreciation = $asset->depreciations()->orderByDesc('depreciation_date')->first();
            if ($latestDepreciation) {
                $asset->update(['current_value' => $latestDepreciation->value]);
            } else {
                $asset->update(['current_value' => $asset->acquisition_value]);
            }

            DB::commit();
            return back()->with('success', 'Data penyusutan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }
}
