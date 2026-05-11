@extends('layouts.app')

@section('title', 'Monitoring Kondisi Aset - SIPETA-TRANS')
@section('page-title', 'Monitoring Kondisi Aset')

@section('content')
<!-- Header with Stats -->
<div class="mb-8">
    <div class="grid grid-cols-4 gap-6">
        <!-- Total Assets -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm font-medium">Total Aset</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $assets->count() }}</p>
        </div>

        <!-- With Photos -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm font-medium">Sudah Terdokumentasi</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $assets->filter(fn($a) => $a->photos->count() > 0)->count() }}</p>
        </div>

        <!-- Total Photos -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm font-medium">Total Foto</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $assets->sum(fn($a) => $a->photos->count()) }}</p>
        </div>

        <!-- Not Documented -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-gray-600 text-sm font-medium">Belum Terdokumentasi</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $assets->filter(fn($a) => $a->photos->count() === 0)->count() }}</p>
        </div>
    </div>
</div>

<!-- Assets List -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-list text-blue-500"></i> Daftar Aset Monitoring Kondisi
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Aset</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kategori</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Lokasi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jumlah Foto</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Foto Terakhir</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kondisi</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($assets as $asset)
                    @php
                        $latestPhoto = $asset->photos->first();
                        $statusColors = [
                            'baik' => 'bg-green-100 text-green-800',
                            'perlu_perbaikan' => 'bg-yellow-100 text-yellow-800',
                            'rusak' => 'bg-red-100 text-red-800',
                            'dalam_pemeliharaan' => 'bg-purple-100 text-purple-800',
                        ];
                        $statusClass = $statusColors[$latestPhoto?->condition ?? 'baik'] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas {{ $asset->type->icon }} text-blue-600 text-lg" style="color: {{ $asset->type->color }}"></i>
                                </div>
                                {{ $asset->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $asset->type->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $asset->location ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-medium">
                                {{ $asset->photos->count() }} foto
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @if($latestPhoto)
                                <div class="text-xs">
                                    <div class="font-medium">{{ $latestPhoto->photo_date->format('d M Y') }}</div>
                                    <div class="text-gray-500">{{ $latestPhoto->photo_date->format('H:i') }}</div>
                                </div>
                            @else
                                <span class="text-gray-500 italic">Belum ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($latestPhoto)
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $latestPhoto->condition)) }}
                                </span>
                            @else
                                <span class="text-gray-500 italic text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('asset-monitoring.show', $asset) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:bg-blue-700 bg-blue-600 shadow hover:shadow-md">
                                <i class="fas fa-eye"></i>
                                <span>Detail</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-inbox text-4xl opacity-30"></i>
                                <p>Tidak ada aset untuk dimonitor</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Legend -->
<div class="mt-6 bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
    <p class="text-sm font-medium text-blue-900 mb-2">💡 Catatan:</p>
    <ul class="text-sm text-blue-800 space-y-1">
        <li>• Klik tombol <strong>Detail</strong> untuk melihat history kondisi dan mengunggah foto baru</li>
        <li>• Setiap foto harus didokumentasikan dengan tanggal dan kondisi yang akurat</li>
        <li>• Monitor kondisi aset secara berkala untuk maintenance yang tepat waktu</li>
    </ul>
</div>
@endsection
