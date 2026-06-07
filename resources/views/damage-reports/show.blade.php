@extends('layouts.app')

@section('title', 'Detail Pengaduan - SIPETA-TRANS')
@section('page-title', 'Detail Pengaduan Kerusakan')

@section('content')
    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow border overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Pengaduan</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama pelapor</p>
                        <p class="font-semibold text-gray-800">{{ $report->nama_pelapor }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kontak</p>
                        <p class="font-semibold text-gray-800">{{ $report->kontak }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Lokasi kejadian</p>
                    <p class="font-semibold text-gray-800">{{ $report->lokasi }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Keterangan</p>
                    <p class="text-gray-800 whitespace-pre-line">{{ $report->keterangan }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Aset terkait</p>
                        <p class="font-semibold text-gray-800">{{ $report->asset?->name ?? '-' }}</p>
                        @if ($report->asset?->type)
                            <p class="text-sm text-gray-600">Jenis: {{ $report->asset->type->name }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal pengaduan</p>
                        <p class="font-semibold text-gray-800">{{ $report->created_at?->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-2">Foto</p>
                    <div class="bg-gray-50 border rounded-lg p-3">
                        <img src="{{ asset('storage/' . $report->foto) }}" alt="Foto kerusakan"
                            class="max-h-[520px] w-auto rounded" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow border overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Manajemen</h3>
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

                @if(empty($report->forwarded_at))

                    <form method="POST" action="{{ route('damage-reports.update', $report) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                @foreach ($statusOptions as $status)
                                    <option value="{{ $status }}" @selected(old('status', $report->status) === $status)>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Set Aset (opsional)</label>
                            <select name="asset_id"
                                class="mt-1 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">- Tidak memilih -</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}" @selected((string) old('asset_id', $report->asset_id) === (string) $asset->id)>
                                        {{ $asset->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                            Simpan
                        </button>
                    </form>

                @else

                    <div class="mb-4 p-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-800">
                        <i class="fas fa-info-circle mr-1"></i> pengaduan telah ditindak lanjuti.
                    </div>
                
                @endif

                <div class="mt-6">
                    <a href="{{ route('damage-reports.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
                        &larr; Kembali ke daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
