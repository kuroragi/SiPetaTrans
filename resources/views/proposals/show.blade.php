@extends('layouts.app')

@section('title', 'Detail Usulan Masyarakat - SIPETA-TRANS')
@section('page-title', 'Detail Usulan Masyarakat')

@section('content')
    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow border overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Usulan</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Pengusul</p>
                        <p class="font-semibold text-gray-800">{{ $proposal->pengusul }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-800">{{ $proposal->email_pengusul }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Jenis Permintaan</p>
                        <p class="font-semibold text-gray-800">{{ $proposal->jenis_permintaan }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jumlah</p>
                        <p class="font-semibold text-gray-800">{{ $proposal->jumlah }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Perkiraan Anggaran</p>
                    <p class="font-semibold text-gray-800">Rp
                        {{ number_format($proposal->perkiraan_anggaran, 0, ',', '.') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Lokasi</p>
                    <p class="font-semibold text-gray-800">{{ $proposal->lokasi }}</p>
                    @if ($proposal->coordinates)
                        <p class="text-xs text-gray-500">Koordinat: {{ json_encode($proposal->coordinates) }}</p>
                    @endif
                </div>

                <div>
                    <p class="text-sm text-gray-600">Tindak Lanjut (Usulan)</p>
                    <p class="text-gray-800 whitespace-pre-line">{{ $proposal->tindak_lanjut ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-2">Arsip Surat Permintaan (PDF)</p>
                    <a href="{{ asset('storage/' . $proposal->arsip_surat) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition">
                        <i class="fas fa-file-pdf text-red-500"></i> Lihat / Unduh Surat
                    </a>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-2">Foto Dokumentasi</p>
                    <div class="bg-gray-50 border rounded-lg p-3 inline-block">
                        <img src="{{ asset('storage/' . $proposal->foto) }}" alt="Foto Usulan"
                            class="max-h-[400px] w-auto rounded shadow-sm" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow border overflow-hidden h-fit">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Manajemen Tindakan</h3>
            </div>
            <div class="p-6">
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($proposal->status === 'selesai')
                    <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fas fa-check-circle text-2xl text-green-500"></i>
                            <h4 class="font-bold text-lg">Usulan Telah Diselesaikan</h4>
                        </div>
                        <p class="text-sm">Usulan ini telah selesai ditindaklanjuti. Silakan cek data aset yang dimaksud
                            pada menu Master Aset.</p>
                        <div class="mt-4">
                            <a href="{{ route('assets.index') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium text-sm">
                                <i class="fas fa-boxes"></i> Lihat Master Aset
                            </a>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('proposals.update-status', $proposal) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status }}" @selected(old('status', $proposal->status) === $status)>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Mengubah status akan mengirim email notifikasi ke
                                pengusul.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan Admin</label>
                            <textarea name="keterangan_admin" rows="3"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Keterangan tambahan untuk email...">{{ old('keterangan_admin', $proposal->keterangan_admin) }}</textarea>
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                            Simpan Tindakan
                        </button>
                    </form>

                    <hr class="my-6">

                    @if ($proposal->status === 'ditindak lanjuti')
                        <div class="mb-4 p-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-800">
                            <p class="text-sm mb-2"><i class="fas fa-info-circle mr-1"></i> Jika usulan ini telah
                                diselesaikan (aset telah dibangun), tandai sebagai selesai untuk menambahkannya ke Master
                                Aset.</p>
                            <form method="POST" action="{{ route('proposals.mark-completed', $proposal) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition flex items-center justify-center gap-2"
                                    onclick="return confirm('Tandai sebagai selesai dan lanjutkan ke form tambah aset?')">
                                    <i class="fas fa-check-circle"></i> Selesaikan Usulan
                                </button>
                            </form>
                        </div>
                    @endif
                @endif

                <div class="mt-4 flex justify-between items-center">
                    <a href="{{ route('proposals.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
                        &larr; Kembali ke daftar
                    </a>
                    @can('edit proposals')
                    <a href="{{ route('proposals.edit', $proposal->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit Usulan
                    </a>
                    @endcan
                </div>
            </div>

        </div>
    </div>
@endsection
