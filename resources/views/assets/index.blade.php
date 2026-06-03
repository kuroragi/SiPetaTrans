@extends('layouts.app')

@section('title', 'Peta Aset - SIPETA-TRANS')
@section('page-title', 'Peta Aset')

@section('content')
    <!-- Filter and Actions -->
    <div class="mb-6 flex justify-between items-center">
        <div class="flex gap-4 flex-1">
            <input type="text" placeholder="Cari aset..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Jenis</option>
                @foreach ($assetTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="baik">Baik</option>
                <option value="perlu_perbaikan">Perlu Perbaikan</option>
                <option value="rusak">Rusak</option>
                <option value="dalam_pemeliharaan">Dalam Pemeliharaan</option>
            </select>
        </div>
        <a href="{{ route('assets.create') }}"
            class="ml-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Aset
        </a>
    </div>

    <!-- Assets Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Nama Aset</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($assets as $key => $asset)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $key + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $asset->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $asset->type->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $statusClass = match ($asset->status) {
                                    'baik' => 'bg-green-100 text-green-800',
                                    'perlu_perbaikan' => 'bg-yellow-100 text-yellow-800',
                                    'rusak' => 'bg-red-100 text-red-800',
                                    'dalam_pemeliharaan' => 'bg-purple-100 text-purple-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $asset->location ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm flex gap-2">
                            <a href="{{ route('assets.show', $asset) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('assets.edit', $asset) }}" class="text-yellow-600 hover:text-yellow-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus?')"
                                    class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                            <p>Tidak ada aset ditemukan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $assets->links() }}
    </div>

@endsection
