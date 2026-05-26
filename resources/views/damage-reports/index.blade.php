@extends('layouts.app')

@section('title', 'Pengaduan Kerusakan - SIPETA-TRANS')
@section('page-title', 'Pengaduan Kerusakan')

@section('content')
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4 border">
            <p class="text-sm text-gray-600">Baru</p>
            <p class="text-2xl font-bold text-gray-800">{{ $counts['baru'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border">
            <p class="text-sm text-gray-600">Dalam Proses</p>
            <p class="text-2xl font-bold text-gray-800">{{ $counts['dalam_proses'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border">
            <p class="text-sm text-gray-600">Selesai</p>
            <p class="text-2xl font-bold text-gray-800">{{ $counts['selesai'] ?? 0 }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Pelapor</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Kontak</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Aset</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reports as $key => $report)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $reports->firstItem() + $key }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $report->nama_pelapor }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $report->kontak }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $report->asset?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $report->lokasi }}</td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $statusClass = match ($report->status) {
                                    'baru' => 'bg-blue-100 text-blue-800',
                                    'dalam_proses' => 'bg-yellow-100 text-yellow-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $report->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('damage-reports.show', $report) }}"
                                class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-6 text-sm text-gray-600" colspan="8">Belum ada pengaduan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $reports->links() }}
        </div>
    </div>
@endsection
