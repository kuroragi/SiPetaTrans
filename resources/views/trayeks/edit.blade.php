@extends('layouts.app')

@section('title', 'Edit Trayek - SIPETA-TRANS')
@section('page-title', 'Edit Trayek')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-6 rounded-t-lg">
                <h2 class="text-3xl font-bold">Edit Data Trayek</h2>
                <p class="text-blue-100 mt-2">Perbarui informasi dan rute trayek</p>
            </div>

            <!-- Form -->
            <form action="{{ route('trayeks.update', $trayek->id) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <!-- Section 1: Informasi Dasar -->
                <div class="mb-8 pb-8 border-b">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-info-circle text-blue-600"></i> Informasi Trayek
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kode Trayek <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="code" name="code" value="{{ old('code', $trayek->code) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                                required>
                            @error('code')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Rute <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $trayek->name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="classification" class="block text-sm font-semibold text-gray-700 mb-2">
                                Klasifikasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="classification" name="classification" value="{{ old('classification', $trayek->classification) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('classification') border-red-500 @enderror"
                                required>
                            @error('classification')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="color" class="block text-sm font-semibold text-gray-700 mb-2">
                                Warna Trayek <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="color" name="color" value="{{ old('color', $trayek->color) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('color') border-red-500 @enderror"
                                required>
                            @error('color')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="distance" class="block text-sm font-semibold text-gray-700 mb-2">
                                Jarak Tempuh (km)
                            </label>
                            <input type="number" id="distance" name="distance" value="{{ old('distance', $trayek->distance) }}" step="0.01"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('distance') border-red-500 @enderror">
                            @error('distance')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="route_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tipe Rute <span class="text-red-500">*</span>
                            </label>
                            <select id="route_type" name="route_type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('route_type') border-red-500 @enderror"
                                required>
                                <option value="loop" {{ old('route_type', $trayek->route_type) == 'loop' ? 'selected' : '' }}>Loop (Awal & Akhir Bertemu)</option>
                                <option value="one_way" {{ old('route_type', $trayek->route_type) == 'one_way' ? 'selected' : '' }}>1 Way (Titik awal & akhir tidak bertemu)</option>
                            </select>
                            @error('route_type')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Peta Rute -->
                <div class="mb-8 pb-8 border-b">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-map-marked-alt text-red-600"></i> Peta Rute Trayek
                    </h3>
                    
                    <input type="hidden" name="coordinate" id="coordinate" value="{{ old('coordinate', json_encode($trayek->coordinate ?? [])) }}">
                    
                    <div class="mb-4 flex gap-2">
                        <button type="button" id="undo-btn" class="px-4 py-2 rounded transition text-sm font-semibold shadow-sm" style="background-color: #f59e0b; color: #ffffff;">
                            <i class="fas fa-undo"></i> Hapus Titik Terakhir
                        </button>
                        <button type="button" id="clear-btn" class="px-4 py-2 rounded transition text-sm font-semibold shadow-sm" style="background-color: #ef4444; color: #ffffff;">
                            <i class="fas fa-trash"></i> Reset Rute
                        </button>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500 mb-4">
                        <h4 class="font-bold text-blue-800 mb-2"><i class="fas fa-info-circle"></i> Panduan Menggambar Rute:</h4>
                        <ul class="list-disc list-inside text-sm text-blue-700 space-y-1">
                            <li><strong>Tambah Titik:</strong> Klik pada peta secara berurutan untuk meneruskan rute trayek.</li>
                            <li><strong>Hapus Titik Terakhir:</strong> Klik tombol <span class="font-semibold text-yellow-700">Hapus Titik Terakhir</span> di atas peta untuk menghapus titik pada ujung akhir rute.</li>
                            <li><strong>Reset Rute:</strong> Klik tombol <span class="font-semibold text-red-600">Reset Rute</span> jika Anda ingin menghapus seluruh rute dan menggambar ulang dari awal.</li>
                        </ul>
                    </div>

                    <div id="mapPreview" style="height: 400px; border-radius: 8px; z-index: 1;"></div>
                    @error('coordinate')
                        <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                            {{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('trayeks.index') }}"
                        class="px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold flex items-center gap-2">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let map = null;
        let polyline = null;
        let markers = [];
        let coordinates = [];

        function initializeMap() {
            const defaultLat = -0.305218;
            const defaultLng = 100.369574;

            map = L.map('mapPreview').setView([defaultLat, defaultLng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            polyline = L.polyline([], {color: '#3b82f6', weight: 4}).addTo(map);

            // Load existing coordinates
            const existingCoordsStr = document.getElementById('coordinate').value;
            if (existingCoordsStr && existingCoordsStr !== '[]') {
                try {
                    const parsed = JSON.parse(existingCoordsStr);
                    if (Array.isArray(parsed)) {
                        parsed.forEach(coord => {
                            if(coord.length >= 2) {
                                addPoint(parseFloat(coord[0]), parseFloat(coord[1]), false);
                            }
                        });
                        updatePolyline();
                        if(coordinates.length > 0) {
                            map.fitBounds(polyline.getBounds());
                        }
                    }
                } catch (e) {
                    console.error("Failed to parse existing coordinates", e);
                }
            }

            map.on('click', function(e) {
                addPoint(e.latlng.lat, e.latlng.lng, true);
            });
        }

        function addPoint(lat, lng, update = true) {
            coordinates.push([lat, lng]);
            
            // Add marker
            const markerOptions = {
                radius: 4,
                fillColor: "#ef4444",
                color: "#fff",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            };
            const marker = L.circleMarker([lat, lng], markerOptions).addTo(map);
            markers.push(marker);

            if (update) {
                updatePolyline();
                updateInput();
            }
        }

        function updatePolyline() {
            polyline.setLatLngs(coordinates);
        }

        function updateInput() {
            document.getElementById('coordinate').value = JSON.stringify(coordinates);
        }

        document.getElementById('undo-btn').addEventListener('click', function() {
            if (coordinates.length > 0) {
                coordinates.pop();
                const lastMarker = markers.pop();
                map.removeLayer(lastMarker);
                updatePolyline();
                updateInput();
            }
        });

        document.getElementById('clear-btn').addEventListener('click', function() {
            coordinates = [];
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];
            updatePolyline();
            updateInput();
        });

        document.addEventListener('DOMContentLoaded', () => {
            initializeMap();
        });
    </script>
@endpush
