<?php

namespace App\Http\Controllers;

use App\Mail\AssetReportMail;
use App\Models\Asset;
use App\Models\DamageReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

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
                'ditindak_lanjuti' => DamageReport::where('status', 'ditindak_lanjuti')->count(),
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
            'statusOptions' => ['baru', 'ditindak_lanjuti', 'selesai'],
        ]);
    }

    public function update(Request $request, DamageReport $damageReport)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:baru,ditindak_lanjuti,selesai'],
            'asset_id' => ['nullable', 'integer', 'exists:assets,id'],
        ]);

        try {
            DB::transaction(function () use ($validated, $damageReport, $request) {
                $oldStatus = $damageReport->status;
                $damageReport->update($validated);

                // Insert into AssetMonitoring when status changes to 'ditindak_lanjuti'
                if ($validated['status'] === 'ditindak_lanjuti' && $oldStatus !== 'ditindak_lanjuti' && $damageReport->asset_id) {
                    \App\Models\AssetMonitoring::create([
                        'asset_id' => $damageReport->asset_id,
                        'photo_path' => $damageReport->foto,
                        'condition' => 'rusak',
                        'notes' => 'Dari Laporan Kerusakan: ' . $damageReport->keterangan,
                        'photo_date' => now(),
                        'captured_by' => $damageReport->nama_pelapor,
                    ]);
                }
            });

            return back()->with('success', 'Pengaduan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
