@extends('layouts.app')

@section('title', $asset->name . ' - SIPETA-TRANS')
@section('page-title', 'Detail Aset: ' . $asset->name)

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('assets.index') }}"
                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Aset
            </a>
        </div>

        <!-- Header Card -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg shadow-lg p-8 mb-6">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center gap-6">
                    @php
                        $iconColor = $asset->type?->color ?? '#3b82f6';
                        $iconClass = $asset->type?->icon ?? 'fa-cube';
                    @endphp
                    <div class="w-24 h-24 bg-white bg-opacity-20 rounded-lg flex items-center justify-center text-5xl">
                        <i class="fas {{ $iconClass }}"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">{{ $asset->name }}</h1>
                        <p class="text-blue-100 text-lg">{{ $asset->type?->name ?? 'Uncategorized' }}</p>
                    </div>
                </div>

                <!-- Status Badge -->
                @php
                    $statusClass = match ($asset->status) {
                        'baik' => 'bg-green-100 text-green-800',
                        'perlu_perbaikan' => 'bg-yellow-100 text-yellow-800',
                        'rusak' => 'bg-red-100 text-red-800',
                        'dalam_pemeliharaan' => 'bg-purple-100 text-purple-800',
                        default => 'bg-gray-100 text-gray-800',
                    };
                    $statusIcon = match ($asset->status) {
                        'baik' => 'fa-check-circle',
                        'perlu_perbaikan' => 'fa-exclamation-circle',
                        'rusak' => 'fa-times-circle',
                        'dalam_pemeliharaan' => 'fa-hammer',
                        default => 'fa-question-circle',
                    };
                @endphp
                <span class="px-6 py-3 bg-white bg-opacity-20 rounded-lg text-lg font-bold flex items-center gap-2">
                    <i class="fas {{ $statusIcon }}"></i> {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                </span>
            </div>

            <!-- Quick Info -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <p class="text-blue-600 text-sm">No. Registrasi</p>
                    <p class="text-gray-500 text-2md font-bold">#{{ $asset->registration_number ?? '-' }}</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <p class="text-blue-600 text-sm">Jumlah Unit</p>
                    <p class="text-gray-500 text-2md font-bold">{{ $asset->quantity ?? 1 }}</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <p class="text-blue-600 text-sm">Tanggal Perolehan</p>
                    <p class="text-gray-500 text-sm font-mono">{{ date('d-m-Y', strtotime($asset->acquired_at)) ?? '' }}</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <p class="text-blue-600 text-sm">Nilai Perolehan</p>
                    <p class="text-gray-500 text-sm font-mono">Rp
                        {{ number_format($asset->acquisition_value, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <p class="text-blue-600 text-sm">Cara perolehan</p>
                    <p class="text-gray-500 text-sm font-mono">{{ $asset->acquisition_source ?? '' }}</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <p class="text-blue-600 text-sm">Nilai Aset</p>
                    <p class="text-gray-500 text-sm font-mono">Rp {{ number_format($asset->current_value, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Left Column - Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Location Information -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <i class="fas fa-map-marker-alt"></i> Informasi Lokasi
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-1">Alamat</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $asset->location ?? '-' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Latitude</p>
                                <p class="font-mono text-sm bg-gray-50 p-3 rounded">{{ $asset->latitude ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Longitude</p>
                                <p class="font-mono text-sm bg-gray-50 p-3 rounded">{{ $asset->longitude ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Map -->
                        @if ($asset->latitude && $asset->longitude)
                            <div id="map" style="height: 300px; border-radius: 8px; border: 1px solid #e5e7eb;"></div>
                        @else
                            <div class="bg-gray-100 h-48 rounded-lg flex items-center justify-center text-gray-500">
                                <i class="fas fa-map text-4xl opacity-50 mr-4"></i>
                                <span>Koordinat GPS tidak tersedia</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <i class="fas fa-notes-medical"></i> Informasi Tambahan
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Deskripsi</p>
                            <p class="text-gray-800 bg-gray-50 p-4 rounded-lg min-h-24">
                                {{ $asset->description ?? 'Tidak ada deskripsi' }}
                            </p>
                        </div>

                        @if ($asset->last_maintenance)
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Pemeliharaan Terakhir</p>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar text-blue-600"></i>
                                    <span class="font-semibold">{{ $asset->last_maintenance->format('d F Y') }}</span>
                                    <span
                                        class="text-sm text-gray-600">({{ $asset->last_maintenance->diffForHumans() }})</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Status & Actions -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <i class="fas fa-check-double"></i> Status
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl"
                                style="background-color: {{ $asset->type?->color ?? '#3b82f6' }}20; color: {{ $asset->type?->color ?? '#3b82f6' }};">
                                <i class="fas {{ $statusIcon }}"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Kondisi Saat Ini</p>
                                <p class="text-2xl font-bold text-gray-800">
                                    {{ ucfirst(str_replace('_', ' ', $asset->status)) }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <span class="text-sm text-gray-600">Total Aset</span>
                                <span class="font-bold text-gray-800">{{ $asset->quantity ?? 1 }} unit</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <span class="text-sm text-gray-600">Kategori</span>
                                <span class="font-bold text-gray-800">{{ $asset->type?->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <i class="fas fa-cog"></i> Aksi
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('assets.edit', $asset) }}"
                            class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i> Edit Aset
                        </a>

                        <form action="{{ route('assets.destroy', $asset) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus aset ini?')"
                                class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold flex items-center justify-center gap-2">
                                <i class="fas fa-trash"></i> Hapus Aset
                            </button>
                        </form>

                        <a href="{{ route('assets.index') }}"
                            class="w-full px-4 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-bold text-gray-800 mb-3 text-sm">Metadata</h4>
                    <div class="text-xs text-gray-600 space-y-2">
                        <div><strong>Dibuat:</strong></div>
                        <div class="ml-2">{{ $asset->created_at->format('d M Y H:i:s') }}</div>
                        <div><strong>Diperbarui:</strong></div>
                        <div class="ml-2">{{ $asset->updated_at->format('d M Y H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        @if ($asset->latitude && $asset->longitude)
            const map = L.map('map').setView([{{ $asset->latitude }}, {{ $asset->longitude }}], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const color = '{{ $asset->type?->color ?? '#3b82f6' }}';
            L.circleMarker([{{ $asset->latitude }}, {{ $asset->longitude }}], {
                radius: 12,
                fillColor: color,
                color: '#fff',
                weight: 3,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map).bindPopup(`
            <strong>{{ $asset->name }}</strong><br>
            {{ $asset->location }}<br>
            <small>{{ $asset->type?->name }}</small>
        `).openPopup();
        @endif
    </script>
@endpush
