<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\DamageReport;
use Illuminate\Http\Request;

class DamageReportController extends Controller
{
    public function storePublic(Request $request)
    {
        $validated = $request->validate([
            'nama_pelapor' => ['required', 'string', 'max:255'],
            'kontak' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string'],
            'asset_id' => ['nullable', 'integer', 'exists:assets,id'],
            'keterangan' => ['required', 'string'],
            'foto' => ['required', 'image', 'max:5120'],
        ]);

        $validated['foto'] = $request->file('foto')->store('damage-reports', 'public');
        $validated['status'] = 'baru';

        DamageReport::create($validated);

        return back()->with('success', 'Pengaduan berhasil dikirim. Terima kasih!');
    }

    public function index()
    {
        $reports = DamageReport::with(['asset.type'])->latest()->paginate(15);

        return view('damage-reports.index', [
            'reports' => $reports,
            'counts' => [
                'baru' => DamageReport::where('status', 'baru')->count(),
                'dalam_proses' => DamageReport::where('status', 'dalam_proses')->count(),
                'selesai' => DamageReport::where('status', 'selesai')->count(),
            ],
        ]);
    }

    public function show(DamageReport $damageReport)
    {
        Mail::to($damageReport->kontak)
            ->send(new AssetReportMail(
                'Pengaduan Kerusakan Aset',
                'Telah diterima pengaduan kerusakan'
            ));
        $damageReport->load(['asset.type']);

        return view('damage-reports.show', [
            'report' => $damageReport,
            'assets' => Asset::orderBy('name')->get(['id', 'name']),
            'statusOptions' => ['baru', 'dalam_proses', 'selesai'],
        ]);
    }

    public function update(Request $request, DamageReport $damageReport)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:baru,dalam_proses,selesai'],
            'asset_id' => ['nullable', 'integer', 'exists:assets,id'],
        ]);

        $damageReport->update($validated);

        return back()->with('success', 'Pengaduan berhasil diperbarui.');
    }
}
