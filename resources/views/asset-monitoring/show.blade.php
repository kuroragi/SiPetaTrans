@extends('layouts.app')

@section('title', 'Detail Monitoring - ' . $asset->name . ' - SIPETA-TRANS')
@section('page-title', 'Detail Monitoring Kondisi Aset')

@section('content')
<!-- Alert Messages -->
@if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800 font-semibold mb-2">Terjadi Kesalahan:</p>
        <ul class="text-red-700 text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4 text-green-800">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
@endif

<!-- Asset Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg shadow-lg p-6 mb-6">
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas {{ $asset->type->icon }} text-3xl" style="color: {{ $asset->type->color }}"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold">{{ $asset->name }}</h2>
                <p class="text-blue-100">
                    <i class="fas fa-tag mr-1"></i> {{ $asset->type->name }}
                    <span class="mx-2">•</span>
                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $asset->location ?? '-' }}
                </p>
            </div>
        </div>
        <a href="{{ route('asset-monitoring.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-white font-medium rounded-lg transition-all duration-200 hover:bg-gray-700 bg-gray-600 shadow hover:shadow-md">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>
</div>

<div>

    <div class="border-b border-gray-200">
        <nav class="flex">
            <button
                type="button"
                class="tab-btn px-6 py-4 border-b-2 border-blue-500 text-blue-600 font-medium"
                data-tab="monitoring">
                <i class="fas fa-camera mr-2"></i>
                Monitoring Kondisi
            </button>

            <button
                type="button"
                class="tab-btn px-6 py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium"
                data-tab="history">
                <i class="fas fa-history mr-2"></i>
                Histori Pemeliharaan
            </button>
        </nav>
    </div>

    <!-- Monitoring -->
    <div id="tab-monitoring" class="tab-content">

        <div class="grid grid-cols-3 gap-6 mb-6">
            <!-- Upload Foto Form -->
            <div class="col-span-1 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-camera text-blue-500"></i> Unggah Foto Baru
                </h3>

                <form action="{{ route('asset-monitoring.upload', $asset) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Photo Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-images mr-1"></i> Foto (Bisa Multiple)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-blue-500 transition"
                            id="photo-drop">
                            <input type="file" name="photos[]" id="photo-input" accept="image/*" class="hidden" required multiple>
                            <div id="photo-preview" class="hidden space-y-2">
                                <div id="preview-list" class="grid grid-cols-2 gap-2"></div>
                                <button type="button" id="clear-photos" class="mt-2 w-full text-xs text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 py-1 rounded">Ubah Foto</button>
                            </div>
                            <div id="photo-placeholder">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Klik atau drag foto di sini</p>
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF (Max 5MB, Bisa Multiple)</p>
                            </div>
                        </div>
                        <p id="file-count" class="text-xs text-gray-500 mt-2"></p>
                    </div>

                    <!-- Condition -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Aset</label>
                        <select name="condition" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="baik" style="color: #22c55e; font-weight: bold;">✓ Baik</option>
                            <option value="perlu_perbaikan" style="color: #f59e0b; font-weight: bold;">⚠ Perlu Perbaikan</option>
                            <option value="rusak" style="color: #ef4444; font-weight: bold;">✗ Rusak</option>
                            <option value="dalam_pemeliharaan" style="color: #a855f7; font-weight: bold;">⚙ Dalam Pemeliharaan</option>
                        </select>
                    </div>

                    <!-- Photo Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Foto</label>
                        <input type="date" name="photo_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Deskripsi kondisi atau catatan penting..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full px-4 py-2.5 text-white font-medium rounded-lg transition-all duration-200 hover:bg-green-700 bg-green-600 shadow hover:shadow-md border-none cursor-pointer">
                        <i class="fas fa-upload mr-2"></i>
                        <span>Unggah Foto</span>
                    </button>
                </form>
            </div>

            <!-- History Timeline -->
            <div class="col-span-2 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-images text-blue-500"></i> Album Foto Kondisi
                </h3>

                @if($photos->count() > 0)
                    <div class="space-y-6 max-h-96 overflow-y-auto">
                        @php
                            // Group photos by date
                            $photosByDate = $photos->groupBy(function($photo) {
                                return $photo->photo_date->format('Y-m-d');
                            })->sortByDesc(function($group) {
                                return $group->first()->photo_date;
                            });

                            $statusColors = [
                                'baik' => ['badge' => 'bg-green-100 text-green-800', 'icon' => 'fa-check-circle', 'color' => 'text-green-600'],
                                'perlu_perbaikan' => ['badge' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fa-exclamation-circle', 'color' => 'text-yellow-600'],
                                'rusak' => ['badge' => 'bg-red-100 text-red-800', 'icon' => 'fa-times-circle', 'color' => 'text-red-600'],
                                'dalam_pemeliharaan' => ['badge' => 'bg-purple-100 text-purple-800', 'icon' => 'fa-hammer', 'color' => 'text-purple-600'],
                            ];
                        @endphp

                        @foreach($photosByDate as $date => $photosGroup)
                            @php
                                $firstPhoto = $photosGroup->first();
                                $condition = $firstPhoto->condition;
                                $colors = $statusColors[$condition] ?? $statusColors['baik'];
                            @endphp
                            <div class="border-l-4 border-blue-500 pl-4">
                                <!-- Date Header -->
                                <div class="mb-3 pb-2 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <i class="fas {{ $colors['icon'] }} {{ $colors['color'] }}"></i>
                                            <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span>
                                            <span class="text-xs text-gray-500">{{ $photosGroup->count() }} foto</span>
                                        </div>
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded {{ $colors['badge'] }}">
                                            {{ ucfirst(str_replace('_', ' ', $condition)) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Photo Gallery Grid -->
                                <div class="grid grid-cols-4 gap-3">
                                    @forelse($photosGroup as $photo)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                                alt="Photo" 
                                                class="w-full h-24 object-cover rounded-lg border border-gray-300 cursor-pointer hover:opacity-80 transition"
                                                data-gallery="true"
                                                data-gallery-date="{{ $photo->photo_date->format('Y-m-d') }}"
                                                data-photo-id="{{ $photo->id }}"
                                                data-photo-path="{{ asset('storage/' . $photo->photo_path) }}"
                                                data-photo-date="{{ $photo->photo_date->format('d M Y H:i') }}"
                                                data-photo-condition="{{ ucfirst(str_replace('_', ' ', $photo->condition)) }}"
                                                data-photo-notes="{{ $photo->notes ?? '' }}"
                                                data-photo-captured="{{ $photo->captured_by }}"
                                                onclick="openGallery(event)">
                                            
                                            <!-- Hover overlay with actions -->
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                                                <a href="{{ asset('storage/' . $photo->photo_path) }}" target="_blank" 
                                                class="p-2 bg-white rounded-full hover:bg-gray-100 transition"
                                                title="Buka full size">
                                                    <i class="fas fa-eye text-gray-700"></i>
                                                </a>
                                                <form action="{{ route('asset-monitoring.delete-photo', $photo) }}" method="POST" class="inline"
                                                    onsubmit="return confirm('Yakin menghapus foto ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="p-2 bg-white rounded-full hover:bg-red-100 transition"
                                                            title="Hapus foto">
                                                        <i class="fas fa-trash text-red-600"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Photo time indicator -->
                                            <div class="absolute bottom-1 right-1 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded text-center opacity-0 group-hover:opacity-100 transition">
                                                {{ $photo->photo_date->format('H:i') }}
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-500 text-sm col-span-4">Tidak ada foto</p>
                                    @endforelse
                                </div>

                                <!-- Notes if exists -->
                                @if($firstPhoto->notes)
                                    <p class="text-xs text-gray-600 italic mt-2 p-2 bg-gray-50 rounded">
                                        <i class="fas fa-sticky-note mr-1"></i> {{ $firstPhoto->notes }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl opacity-30 mb-2"></i>
                        <p>Belum ada foto untuk aset ini</p>
                        <p class="text-sm">Mulai dokumentasi dengan mengunggah foto di samping</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- History -->
    <div id="tab-history" class="tab-content hidden">

        <div class="grid grid-cols-3 gap-6 mb-6">
            <!-- Form Pemeliharaan -->
            <div class="col-span-1 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-wrench text-blue-500"></i> Buat Data Pemeliharaan
                </h3>

                <form action="{{ route('asset-maintenance.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">

                    <!-- Tipe Pemeliharaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pemeliharaan</label>
                        <select name="maintenance_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="rutin">Rutin</option>
                            <option value="perbaikan">Perbaikan Kerusakan</option>
                        </select>
                    </div>

                    <!-- Status Pemeliharaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pemeliharaan</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="sedang_berjalan">Sedang Berjalan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi / Tanggal Selesai</label>
                        <input type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Biaya -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Biaya (Rp)</label>
                        <input type="number" name="cost" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="0">
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Tindakan</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan tindakan pemeliharaan..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full px-4 py-2.5 text-white font-medium rounded-lg transition-all duration-200 hover:bg-green-700 bg-green-600 shadow hover:shadow-md border-none cursor-pointer">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan Pemeliharaan</span>
                    </button>
                </form>
            </div>

            <!-- Riwayat Timeline -->
            <div class="col-span-2 bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-blue-500"></i> Riwayat Pemeliharaan
                </h3>
                
                <div class="space-y-6 max-h-96 overflow-y-auto">
                    @forelse($asset->maintenance as $m)
                        <div class="border-l-4 border-blue-500 pl-4">
                            <!-- Date Header -->
                            <div class="mb-3 pb-2 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-tools text-blue-600"></i>
                                        <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($m->start_date)->format('d M Y') }}</span>
                                    </div>
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded {{ $m->status === 'selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($m->maintenance_type) }} - {{ ucfirst(str_replace('_', ' ', $m->status)) }}
                                    </span>
                                </div>
                            </div>
                            <!-- Content -->
                            <div class="bg-gray-50 rounded p-3">
                                <p class="text-sm text-gray-800 font-medium">{{ $m->description ?? 'Tidak ada deskripsi' }}</p>
                                @if($m->cost)
                                    <p class="text-xs text-gray-500 mt-1">Biaya: Rp {{ number_format($m->cost, 0, ',', '.') }}</p>
                                @endif
                                @if($m->end_date)
                                    <p class="text-xs text-gray-500 mt-1">Selesai: {{ \Carbon\Carbon::parse($m->end_date)->format('d M Y') }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl opacity-30 mb-2"></i>
                            <p>Belum ada riwayat pemeliharaan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
    
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-blue-500">
        <p class="text-gray-600 text-sm">Total Foto</p>
        <p class="text-2xl font-bold text-blue-600">{{ $photos->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-green-500">
        <p class="text-gray-600 text-sm">Kondisi Terbaik</p>
        <p class="text-2xl font-bold text-green-600">{{ $photos->where('condition', 'baik')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-yellow-500">
        <p class="text-gray-600 text-sm">Perlu Perbaikan</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $photos->where('condition', 'perlu_perbaikan')->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-t-4 border-red-500">
        <p class="text-gray-600 text-sm">Rusak</p>
        <p class="text-2xl font-bold text-red-600">{{ $photos->where('condition', 'rusak')->count() }}</p>
    </div>
</div>

<!-- Asset Info -->
<div class="grid grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Aset</h4>
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-600">Nama Aset</p>
                <p class="font-medium text-gray-800">{{ $asset->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Kategori</p>
                <p class="font-medium text-gray-800">{{ $asset->type->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Lokasi</p>
                <p class="font-medium text-gray-800">{{ $asset->location ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Kondisi Saat Ini</p>
                @php
                    $statusColors = [
                        'baik' => 'bg-green-100 text-green-800',
                        'perlu_perbaikan' => 'bg-yellow-100 text-yellow-800',
                        'rusak' => 'bg-red-100 text-red-800',
                        'dalam_pemeliharaan' => 'bg-purple-100 text-purple-800',
                    ];
                @endphp
                <p class="px-3 py-1 rounded inline-block font-medium {{ $statusColors[$asset->status] ?? 'bg-gray-100 text-gray-800' }} text-sm mt-1">
                    {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Timeline Kondisi</h4>
        @if($conditionHistory->count() > 0)
            <div class="space-y-2">
                @foreach($conditionHistory as $month => $history)
                    @php
                        $statusColors = [
                            'baik' => 'text-green-600',
                            'perlu_perbaikan' => 'text-yellow-600',
                            'rusak' => 'text-red-600',
                            'dalam_pemeliharaan' => 'text-purple-600',
                        ];
                    @endphp
                    <div class="flex items-center justify-between py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">{{ $history->photo_date->format('M Y') }}</span>
                        <span class="text-sm font-semibold {{ $statusColors[$history->condition] ?? 'text-gray-600' }}">
                            {{ ucfirst(str_replace('_', ' ', $history->condition)) }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada history kondisi</p>
        @endif
    </div>
</div>

<!-- Gallery Modal -->
<div id="galleryModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center p-4" style="backdrop-filter: blur(4px);">
    <div class="relative w-full max-w-4xl max-h-screen flex flex-col">
        <!-- Close Button -->
        <button onclick="closeGallery()" class="absolute top-4 right-4 z-10 text-white hover:text-gray-300 transition">
            <i class="fas fa-times text-3xl"></i>
        </button>

        <!-- Main Image -->
        <div class="flex-1 flex items-center justify-center bg-black rounded-lg overflow-hidden">
            <img id="galleryMainImage" src="" alt="Gallery" class="max-w-full max-h-96 object-contain">
        </div>

        <!-- Gallery Info -->
        <div class="bg-white mt-4 rounded-lg p-4">
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Tanggal</p>
                    <p id="galleryPhotoDate" class="text-sm text-gray-800 font-medium"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Kondisi</p>
                    <p id="galleryPhotoCondition" class="text-sm text-gray-800 font-medium"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Diambil Oleh</p>
                    <p id="galleryPhotoCaptured" class="text-sm text-gray-800 font-medium"></p>
                </div>
            </div>
            <div id="galleryNotesContainer" class="mb-4 hidden">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-1">Catatan</p>
                <p id="galleryPhotoNotes" class="text-sm text-gray-700 italic"></p>
            </div>

            <!-- Navigation -->
            <div class="flex items-center justify-between">
                <button onclick="prevGalleryPhoto()" class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 border border-gray-300">
                    <i class="fas fa-chevron-left"></i>
                    <span>Sebelumnya</span>
                </button>

                <div class="text-center">
                    <p class="text-sm text-gray-600"><span id="galleryCurrentIndex">1</span> dari <span id="galleryTotalCount">1</span> foto</p>
                </div>

                <button onclick="nextGalleryPhoto()" class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 font-medium rounded-lg transition-all duration-200 hover:bg-gray-100 border border-gray-300">
                    <span>Berikutnya</span>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photo-input');
        const photoDrop = document.getElementById('photo-drop');
        const photoPreview = document.getElementById('photo-preview');
        const previewList = document.getElementById('preview-list');
        const photoPlaceholder = document.getElementById('photo-placeholder');
        const fileCount = document.getElementById('file-count');
        const clearPhotosBtn = document.getElementById('clear-photos');

        function handleFiles(files) {
            previewList.innerHTML = '';
            fileCount.textContent = '';

            if (files.length === 0) {
                photoPreview.classList.add('hidden');
                photoPlaceholder.classList.remove('hidden');
                return;
            }

            photoPlaceholder.classList.add('hidden');
            photoPreview.classList.remove('hidden');
            fileCount.textContent = files.length + ' foto dipilih';

            // Create preview for each selected file
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.createElement('div');
                        preview.className = 'relative group';
                        preview.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                            <div class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-2 py-1 rounded">Foto ${index + 1}</div>
                        `;
                        previewList.appendChild(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // File input change handler
        photoInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        // Drag and drop handlers
        photoDrop.addEventListener('dragover', (e) => {
            e.preventDefault();
            photoDrop.classList.add('border-blue-500', 'bg-blue-50');
        });

        photoDrop.addEventListener('dragleave', () => {
            photoDrop.classList.remove('border-blue-500', 'bg-blue-50');
        });

        photoDrop.addEventListener('drop', (e) => {
            e.preventDefault();
            photoDrop.classList.remove('border-blue-500', 'bg-blue-50');
            photoInput.files = e.dataTransfer.files;
            handleFiles(photoInput.files);
        });

        // Click to select files
        photoDrop.addEventListener('click', () => {
            photoInput.click();
        });

        // Clear/Change photos button
        clearPhotosBtn.addEventListener('click', () => {
            photoInput.value = '';
            handleFiles([]);
        });
    });

    // Gallery Variables
    let galleryPhotos = [];
    let currentGalleryIndex = 0;
    let currentGalleryDate = null;

    // Initialize gallery when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Collect all photos from gallery images
        const galleryImages = document.querySelectorAll('[data-gallery=\"true\"]');
        
        galleryImages.forEach(img => {
            const photoDate = img.getAttribute('data-gallery-date');
            const photoId = img.getAttribute('data-photo-id');
            
            if (!galleryPhotos[photoDate]) {
                galleryPhotos[photoDate] = [];
            }
            
            galleryPhotos[photoDate].push({
                id: photoId,
                path: img.getAttribute('data-photo-path'),
                date: img.getAttribute('data-photo-date'),
                condition: img.getAttribute('data-photo-condition'),
                notes: img.getAttribute('data-photo-notes'),
                captured: img.getAttribute('data-photo-captured'),
            });
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(event) {
            const modal = document.getElementById('galleryModal');
            if (modal && !modal.classList.contains('hidden')) {
                if (event.key === 'ArrowLeft') prevGalleryPhoto();
                if (event.key === 'ArrowRight') nextGalleryPhoto();
                if (event.key === 'Escape') closeGallery();
            }
        });
    });

    function openGallery(event) {
        event.preventDefault();
        const img = event.target;
        const photoDate = img.getAttribute('data-gallery-date');
        const photoId = img.getAttribute('data-photo-id');
        
        currentGalleryDate = photoDate;
        
        // Find current photo index
        const photosForDate = galleryPhotos[photoDate];
        currentGalleryIndex = photosForDate.findIndex(p => p.id === photoId);
        
        // Update modal with current photo
        updateGalleryDisplay();
        
        // Show modal
        document.getElementById('galleryModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeGallery() {
        document.getElementById('galleryModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function updateGalleryDisplay() {
        const photosForDate = galleryPhotos[currentGalleryDate];
        const currentPhoto = photosForDate[currentGalleryIndex];
        
        // Update main image
        document.getElementById('galleryMainImage').src = currentPhoto.path;
        document.getElementById('galleryMainImage').alt = 'Gallery Photo';
        
        // Update info
        document.getElementById('galleryPhotoDate').textContent = currentPhoto.date;
        document.getElementById('galleryPhotoCondition').textContent = currentPhoto.condition;
        document.getElementById('galleryPhotoCaptured').textContent = currentPhoto.captured;
        
        // Update notes
        const notesContainer = document.getElementById('galleryNotesContainer');
        if (currentPhoto.notes && currentPhoto.notes.trim()) {
            document.getElementById('galleryPhotoNotes').textContent = currentPhoto.notes;
            notesContainer.classList.remove('hidden');
        } else {
            notesContainer.classList.add('hidden');
        }
        
        // Update counter
        document.getElementById('galleryCurrentIndex').textContent = currentGalleryIndex + 1;
        document.getElementById('galleryTotalCount').textContent = photosForDate.length;
        
        // Update button states
        updateGalleryButtons(photosForDate);
    }

    function updateGalleryButtons(photosForDate) {
        const prevBtn = document.querySelector('button[onclick=\"prevGalleryPhoto()\"]');
        const nextBtn = document.querySelector('button[onclick=\"nextGalleryPhoto()\"]');
        
        if (currentGalleryIndex === 0) {
            prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
            prevBtn.disabled = true;
        } else {
            prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            prevBtn.disabled = false;
        }
        
        if (currentGalleryIndex === photosForDate.length - 1) {
            nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
            nextBtn.disabled = true;
        } else {
            nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            nextBtn.disabled = false;
        }
    }

    function prevGalleryPhoto() {
        const photosForDate = galleryPhotos[currentGalleryDate];
        if (currentGalleryIndex > 0) {
            currentGalleryIndex--;
            updateGalleryDisplay();
        }
    }

    function nextGalleryPhoto() {
        const photosForDate = galleryPhotos[currentGalleryDate];
        if (currentGalleryIndex < photosForDate.length - 1) {
            currentGalleryIndex++;
            updateGalleryDisplay();
        }
    }

    // Close gallery when clicking outside the modal content
    document.getElementById('galleryModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeGallery();
        }
    });
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {

    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {

        tab.addEventListener('click', () => {

            tabs.forEach(btn => {
                btn.classList.remove(
                    'border-blue-500',
                    'text-blue-600'
                );

                btn.classList.add(
                    'border-transparent',
                    'text-gray-500'
                );
            });

            contents.forEach(content => {
                content.classList.add('hidden');
            });

            tab.classList.remove(
                'border-transparent',
                'text-gray-500'
            );

            tab.classList.add(
                'border-blue-500',
                'text-blue-600'
            );

            document
                .getElementById(`tab-${tab.dataset.tab}`)
                .classList.remove('hidden');

        });

    });

});
</script>
@endpush

