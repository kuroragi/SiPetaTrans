<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\AssetType;
use App\Mail\AssetReportMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view proposals', only: ['index', 'show']),
            new Middleware('permission:create proposals', only: ['create', 'store']),
            new Middleware('permission:edit proposals', only: ['edit', 'update', 'updateStatus', 'markCompleted']),
            new Middleware('permission:delete proposals', only: ['destroy']),
        ];
    }

    public function storePublic(Request $request)
    {
        $validated = $request->validate([
            'pengusul' => 'required|string|max:255',
            'email_pengusul' => 'required|email|max:255',
            'tanggal' => 'required|date',
            'jenis_permintaan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'lokasi' => 'required|string',
            'coordinates' => 'nullable|array',
            'perkiraan_anggaran' => 'nullable|integer',
            'foto' => 'required|image|max:5120', // Max 5MB
            'arsip_surat' => 'required|mimes:pdf|max:2048', // Max 2MB
            'tindak_lanjut' => 'nullable|string',
        ]);

        $validated['foto'] = $request->file('foto')->store('proposals/foto', 'public');
        $validated['arsip_surat'] = $request->file('arsip_surat')->store('proposals/surat', 'public');
        $validated['status'] = 'pending';

        Proposal::create($validated);

        return back()->with('success', 'Usulan berhasil dikirim. Terima kasih!');
    }

    public function create()
    {
        $assetTypes = AssetType::with('subtypes')->get();
        return view('proposals.create', compact('assetTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengusul' => 'required|string|max:255',
            'email_pengusul' => 'required|email|max:255',
            'tanggal' => 'required|date',
            'jenis_permintaan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'lokasi' => 'required|string',
            'coordinates' => 'nullable|json',
            'perkiraan_anggaran' => 'nullable|integer',
            'foto' => 'required|image|max:5120',
            'arsip_surat' => 'required|mimes:pdf|max:2048',
            'tindak_lanjut' => 'nullable|string',
            'kelayakan' => 'required|in:layak,tidak layak',
            'status' => 'required|in:ditindak lanjuti,selesai,ditolak',
        ]);

        if ($validated['kelayakan'] === 'tidak layak') {
            $validated['status'] = 'ditolak';
        }

        if (isset($validated['coordinates'])) {
            $validated['coordinates'] = json_decode($validated['coordinates'], true);
        }

        $validated['foto'] = $request->file('foto')->store('proposals/foto', 'public');
        $validated['arsip_surat'] = $request->file('arsip_surat')->store('proposals/surat', 'public');
        
        Proposal::create($validated);

        return redirect()->route('proposals.index')->with('success', 'Data usulan lama berhasil ditambahkan.');
    }

    public function index()
    {
        $proposals = Proposal::latest()->paginate(15);

        return view('proposals.index', [
            'proposals' => $proposals,
            'counts' => [
                'pending' => Proposal::where('status', 'pending')->count(),
                'ditindak_lanjuti' => Proposal::where('status', 'ditindak lanjuti')->count(),
                'ditolak' => Proposal::where('status', 'ditolak')->count(),
                'selesai' => Proposal::where('status', 'selesai')->count(),
            ],
        ]);
    }

    public function edit(Proposal $proposal)
    {
        $assetTypes = AssetType::with('subtypes')->get();
        return view('proposals.edit', compact('proposal', 'assetTypes'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'pengusul' => 'required|string|max:255',
            'email_pengusul' => 'required|email|max:255',
            'tanggal' => 'required|date',
            'jenis_permintaan' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'lokasi' => 'required|string',
            'coordinates' => 'nullable|json',
            'perkiraan_anggaran' => 'nullable|integer',
            'foto' => 'nullable|image|max:5120',
            'arsip_surat' => 'nullable|mimes:pdf|max:2048',
            'tindak_lanjut' => 'nullable|string',
            'kelayakan' => 'required|in:layak,tidak layak',
            'status' => 'required|in:ditindak lanjuti,selesai,ditolak',
        ]);

        if ($validated['kelayakan'] === 'tidak layak') {
            $validated['status'] = 'ditolak';
        }

        if (isset($validated['coordinates'])) {
            $validated['coordinates'] = json_decode($validated['coordinates'], true);
        }

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('proposals/foto', 'public');
        } else {
            unset($validated['foto']);
        }

        if ($request->hasFile('arsip_surat')) {
            $validated['arsip_surat'] = $request->file('arsip_surat')->store('proposals/surat', 'public');
        } else {
            unset($validated['arsip_surat']);
        }
        
        $proposal->update($validated);

        return redirect()->route('proposals.index')->with('success', 'Data usulan berhasil diperbarui.');
    }

    public function show(Proposal $proposal)
    {
        return view('proposals.show', [
            'proposal' => $proposal,
            'statusOptions' => ['pending', 'ditindak lanjuti', 'ditolak', 'selesai'],
        ]);
    }

    public function updateStatus(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,ditindak lanjuti,ditolak,selesai',
            'keterangan_admin' => 'nullable|string',
        ]);

        $oldStatus = $proposal->status;
        $proposal->update($validated);

        if ($oldStatus !== $validated['status']) {
            $statusString = ucfirst($validated['status']);
            Mail::to($proposal->email_pengusul)
                ->send(new AssetReportMail(
                    'Perubahan Status Usulan Anda',
                    "Status usulan Anda terkait '{$proposal->jenis_permintaan}' telah diperbarui menjadi: {$statusString}. Keterangan Admin: " . ($validated['keterangan_admin'] ?? '-')
                ));
        }

        return back()->with('success', 'Status usulan berhasil diperbarui.');
    }

    public function markCompleted(Request $request, Proposal $proposal)
    {
        if ($proposal->status !== 'ditindak lanjuti') {
            return back()->withErrors('Hanya usulan yang sedang ditindak lanjuti yang dapat diselesaikan.');
        }

        $categoryName = $proposal->jenis_permintaan;
        $assetType = AssetType::where('name', 'like', "%{$categoryName}%")->first();
        $subType = null;

        if (!$assetType) {
            $subType = \App\Models\AssetSubType::where('name', 'like', "%{$categoryName}%")->first();
            if ($subType) {
                $assetType = $subType->assetType;
            }
        }

        if (!$assetType) {
            return back()->withErrors('Kategori aset tidak ditemukan. Kategori harus ditambahkan dulu di menu Tipe Aset.');
        }

        // Fill data for assets.create redirection
        $data = [
            'name' => 'Dari Usulan: ' . $proposal->pengusul,
            'asset_type_id' => $assetType->id,
            'quantity' => $proposal->jumlah,
            'location' => $proposal->lokasi,
        ];

        if ($subType) {
            $data['asset_sub_type_id'] = $subType->id;
        }

        // For coordinates, we can extract lat/lng if available
        if ($proposal->coordinates && is_array($proposal->coordinates) && count($proposal->coordinates) >= 2) {
            $lat = (float) $proposal->coordinates[0];
            $lng = (float) $proposal->coordinates[1];
            $data['latitude'] = $lat;
            $data['longitude'] = $lng;
            $data['coordinates'] = json_encode([$lat, $lng]);
        }

        // Mark as completed
        $proposal->update(['status' => 'selesai']);

        return redirect()->route('assets.create')->withInput($data)->with('success', 'Usulan selesai. Silakan lengkapi data aset baru.');
    }
}
