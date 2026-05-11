@extends('layouts.app')

@section('title', 'Kelola Kategori Aset - SIPETA-TRANS')
@section('page-title', 'Kelola Kategori Aset')

@section('content')
<!-- Alert Messages -->
@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.style.display='none';" class="ml-auto text-green-700 hover:text-green-900">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-3">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.style.display='none';" class="ml-auto text-red-700 hover:text-red-900">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<!-- Header Section -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3 mb-2">
            <i class="fas fa-tags text-blue-600"></i> Manajemen Kategori Aset
        </h1>
        <p class="text-gray-600">Kelola jenis-jenis aset dan icon untuk setiap kategori</p>
    </div>
    <a href="{{ route('asset-types.create') }}" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:shadow-lg transition font-bold flex items-center gap-2">
        <i class="fas fa-plus-circle"></i> Tambah Kategori Baru
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Daftar Kategori (Full Width) -->
    <div class="lg:col-span-3">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <i class="fas fa-list"></i> Daftar Kategori Aset ({{ $assetTypes->count() }})
                </h3>
            </div>

            @forelse($assetTypes as $type)
                <div class="border-b last:border-b-0 hover:bg-gray-50 transition">
                    <div class="p-6 flex items-center gap-6">
                        <!-- Icon Display -->
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 rounded-lg flex items-center justify-center text-4xl" 
                                style="background-color: {{ $type->color }}20; color: {{ $type->color }};">
                                <i class="fas {{ $type->icon }}"></i>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="flex-1">
                            <h4 class="font-bold text-lg text-gray-800 mb-1">{{ $type->name }}</h4>
                            <p class="text-sm text-gray-600 mb-3">{{ $type->description }}</p>
                            <div class="flex items-center gap-6 text-xs text-gray-500">
                                <span><i class="fas fa-icons text-blue-600"></i> {{ $type->icon }}</span>
                                <span class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded" style="background-color: {{ $type->color }};"></div>
                                    {{ $type->color }}
                                </span>
                                <span><i class="fas fa-folder text-orange-600"></i> {{ $type->assets_count }} aset terdaftar</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex-shrink-0 flex gap-2">
                            <a href="{{ route('asset-types.edit', $type) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-sm flex items-center gap-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('asset-types.destroy', $type) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    onclick="return confirm('{{ $type->assets_count > 0 ? 'Kategori ini memiliki ' . $type->assets_count . ' aset. Yakin ingin menghapus?' : 'Yakin ingin menghapus kategori ini?' }}')"
                                    {{ $type->assets_count > 0 ? 'disabled' : '' }}
                                    class="px-4 py-2 @if($type->assets_count > 0) bg-gray-300 text-gray-500 cursor-not-allowed @else bg-red-600 text-white hover:bg-red-700 @endif rounded-lg transition font-semibold text-sm flex items-center gap-1">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4 block"></i>
                    <p class="text-gray-500 text-lg">Tidak ada kategori aset</p>
                    <p class="text-gray-400 text-sm mt-2">Buat kategori pertama dengan klik tombol "Tambah Kategori Baru"</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Info Box -->
<div class="mt-8 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6">
    <h4 class="font-bold text-blue-900 mb-3 flex items-center gap-2 text-lg">
        <i class="fas fa-lightbulb"></i> Tips & Informasi
    </h4>
    <ul class="text-sm text-blue-800 space-y-2">
        <li class="flex items-start gap-2">
            <i class="fas fa-check text-blue-600 mt-1"></i>
            <span><strong>Tambah Kategori:</strong> Klik tombol "Tambah Kategori Baru" untuk membuat kategori baru dengan icon dan warna</span>
        </li>
        <li class="flex items-start gap-2">
            <i class="fas fa-check text-blue-600 mt-1"></i>
            <span><strong>Edit Kategori:</strong> Klik tombol "Edit" untuk mengubah nama, deskripsi, icon, atau warna kategori</span>
        </li>
        <li class="flex items-start gap-2">
            <i class="fas fa-check text-blue-600 mt-1"></i>
            <span><strong>Hapus Kategori:</strong> Tombol "Hapus" hanya tersedia untuk kategori yang tidak memiliki aset terdaftar</span>
        </li>
        <li class="flex items-start gap-2">
            <i class="fas fa-check text-blue-600 mt-1"></i>
            <span><strong>Warna Kategori:</strong> Warna akan ditampilkan di peta dan seluruh dashboard untuk identifikasi visual</span>
        </li>
        <li class="flex items-start gap-2">
            <i class="fas fa-check text-blue-600 mt-1"></i>
            <span><strong>Icon Pilihan:</strong> Gunakan icon Font Awesome yang sesuai dengan jenis aset untuk kemudahan identifikasi</span>
        </li>
    </ul>
</div>

@endsection
