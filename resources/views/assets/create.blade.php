@extends('layouts.app')

@section('title', 'Tambah Aset Baru - SIPETA-TRANS')
@section('page-title', 'Tambah Aset Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-6">
            <h2 class="text-3xl font-bold">Formulir Input Aset Baru</h2>
            <p class="text-blue-100 mt-2">Lengkapi semua data aset dengan informasi yang akurat dan detail</p>
        </div>

        <!-- Form -->
        <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <!-- Section 1: Informasi Dasar -->
            <div class="mb-8 pb-8 border-b">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <i class="fas fa-info-circle text-blue-600"></i> Informasi Dasar
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Aset <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                            placeholder="Contoh: Rambu Stop di Jl. Sudirman"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                            required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="asset_type_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kategori/Jenis Aset <span class="text-red-500">*</span>
                        </label>
                        <select id="asset_type_id" name="asset_type_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('asset_type_id') border-red-500 @enderror" 
                            required onchange="updateCategoryPreview()">
                            <option value="">-- Pilih Kategori Aset --</option>
                            @foreach($assetTypes as $type)
                                <option value="{{ $type->id }}" data-icon="{{ $type->icon }}" data-color="{{ $type->color }}" 
                                    {{ old('asset_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('asset_type_id')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kategori Preview -->
                <div id="categoryPreview" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600 mb-2">Kategori Terpilih:</p>
                    <div class="flex items-center gap-4">
                        <div id="categoryIcon" class="text-5xl text-blue-600"></div>
                        <div>
                            <p id="categoryName" class="text-lg font-bold text-gray-800"></p>
                            <p id="categoryDesc" class="text-sm text-gray-600"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Status & Kondisi -->
            <div class="mb-8 pb-8 border-b">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <i class="fas fa-check-double text-green-600"></i> Status & Kondisi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kondisi Aset <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror" 
                            required onchange="updateStatusPreview()">
                            <option value="">-- Pilih Status --</option>
                            <option value="baik" {{ old('status') == 'baik' ? 'selected' : '' }}>✅ Baik</option>
                            <option value="perlu_perbaikan" {{ old('status') == 'perlu_perbaikan' ? 'selected' : '' }}>⚠️ Perlu Perbaikan</option>
                            <option value="rusak" {{ old('status') == 'rusak' ? 'selected' : '' }}>❌ Rusak</option>
                            <option value="dalam_pemeliharaan" {{ old('status') == 'dalam_pemeliharaan' ? 'selected' : '' }}>🔧 Dalam Pemeliharaan</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" 
                            min="1" placeholder="Jumlah aset"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror" 
                            required>
                        @error('quantity')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_maintenance" class="block text-sm font-semibold text-gray-700 mb-2">
                            Pemeliharaan Terakhir
                        </label>
                        <input type="date" id="last_maintenance" name="last_maintenance" value="{{ old('last_maintenance') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_maintenance') border-red-500 @enderror">
                        @error('last_maintenance')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Lokasi & Koordinat -->
            <div class="mb-8 pb-8 border-b">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <i class="fas fa-map-marker-alt text-red-600"></i> Lokasi & Koordinat GPS
                </h3>

                <div class="mb-6">
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">
                        Alamat/Deskripsi Lokasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" 
                        placeholder="Contoh: Jalan Jend. Sudirman No. 123, Bukittinggi"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('location') border-red-500 @enderror" 
                        required>
                    @error('location')
                        <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-2">
                            Latitude <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="latitude" name="latitude" value="{{ old('latitude', -6.2088) }}" 
                            step="0.000001" placeholder="-6.2088"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('latitude') border-red-500 @enderror" 
                            required>
                        <p class="text-xs text-gray-500 mt-1">Default: Bukittinggi</p>
                        @error('latitude')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-2">
                            Longitude <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="longitude" name="longitude" value="{{ old('longitude', 106.8456) }}" 
                            step="0.000001" placeholder="106.8456"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('longitude') border-red-500 @enderror" 
                            required>
                        <p class="text-xs text-gray-500 mt-1">Default: Bukittinggi</p>
                        @error('longitude')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Map Preview (Optional) -->
                <div class="mt-6 bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                    <p class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-map text-blue-600"></i> Preview Lokasi Aset
                    </p>
                    <p class="text-xs text-blue-700 mb-3">
                        <i class="fas fa-info-circle"></i> Klik pada peta untuk menentukan lokasi aset secara akurat. Latitude dan Longitude akan terupdate otomatis.
                    </p>
                    <div id="mapPreview" style="height: 300px; border-radius: 8px; background-color: #e5e7eb; cursor: pointer;" title="Klik untuk menentukan lokasi"></div>
                </div>
            </div>

            <!-- Section 4: Deskripsi Tambahan -->
            <div class="mb-8 pb-8 border-b">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                    <i class="fas fa-notes-medical text-purple-600"></i> Deskripsi Tambahan
                </h3>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Catatan/Deskripsi Aset
                    </label>
                    <textarea id="description" name="description" rows="5" 
                        placeholder="Contoh: Rambu dalam kondisi baik, perlu pembersihan setiap 3 bulan, sudah ada cat anti karat..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-end">
                <a href="{{ route('assets.index') }}" class="px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold flex items-center gap-2">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="reset" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-semibold flex items-center gap-2">
                    <i class="fas fa-redo"></i> Reset
                </button>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Aset
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Asset types data
    const assetTypes = @json($assetTypes);

    // Update category preview
    function updateCategoryPreview() {
        const select = document.getElementById('asset_type_id');
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption.value === '') {
            document.getElementById('categoryPreview').classList.add('hidden');
            return;
        }

        const assetType = assetTypes.find(t => t.id == selectedOption.value);
        if (assetType) {
            document.getElementById('categoryIcon').innerHTML = `<i class="fas ${assetType.icon}"></i>`;
            document.getElementById('categoryIcon').style.color = assetType.color;
            document.getElementById('categoryName').textContent = assetType.name;
            document.getElementById('categoryDesc').textContent = assetType.description;
            document.getElementById('categoryPreview').classList.remove('hidden');
        }
    }

    // Update status preview
    function updateStatusPreview() {
        const status = document.getElementById('status').value;
        // Status preview bisa ditambahkan di sini jika diperlukan
    }

    // Initialize map
    let map = null;
    let marker = null;
    
    // Debounce function
    function debounce(func, delay) {
        let timeoutId;
        return function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(func, delay);
        };
    }

    // Geocode address using Nominatim API
    async function geocodeAddress() {
        const address = document.getElementById('location').value;
        if (!address || address.trim().length < 3) return;
        
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`);
            const results = await response.json();
            
            if (results.length > 0) {
                const lat = parseFloat(results[0].lat);
                const lng = parseFloat(results[0].lon);
                
                // Update input fields
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
                
                // Update marker
                updateMarkerPosition(lat, lng);
                
                // Update popup
                marker.setPopupContent(`<div class="text-sm"><strong>📍 Hasil Pencarian:</strong><br><small>${results[0].display_name}</small><br><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}<br><br><small class="text-gray-500">Klik peta untuk fine-tune lokasi</small></div>`).openPopup();
            }
        } catch (error) {
            console.error('Geocoding error:', error);
        }
    }

    // Debounced geocoding with delay
    const debouncedGeocode = debounce(geocodeAddress, 1000);
    
    function initializeMap() {
        if (map) return;
        
        const defaultLat = -6.2088;
        const defaultLng = 106.8456;
        
        map = L.map('mapPreview').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([defaultLat, defaultLng]).addTo(map).bindPopup('Klik pada peta untuk menentukan lokasi aset');
        
        // Map click event - menambahkan marker baru dan update coordinates
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            // Update input fields
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            
            // Update marker position
            updateMarkerPosition(lat, lng);
            
            // Show popup
            marker.setPopupContent(`<div class="text-sm"><strong>✓ Lokasi Dipilih:</strong><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</div>`).openPopup();
        });
        
        // Update marker when coordinates change via input
        document.getElementById('latitude').addEventListener('change', updateMarkerFromInput);
        document.getElementById('longitude').addEventListener('change', updateMarkerFromInput);
        
        // Geocode when address changes
        document.getElementById('location').addEventListener('input', debouncedGeocode);
    }

    function updateMarkerFromInput() {
        if (!map) return;
        
        const lat = parseFloat(document.getElementById('latitude').value) || -6.2088;
        const lng = parseFloat(document.getElementById('longitude').value) || 106.8456;
        
        updateMarkerPosition(lat, lng);
    }

    function updateMarkerPosition(lat, lng) {
        if (!map || !marker) return;
        
        map.setView([lat, lng], 13);
        marker.setLatLng([lat, lng]);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        updateCategoryPreview();
        initializeMap();
    });
</script>
@endpush

