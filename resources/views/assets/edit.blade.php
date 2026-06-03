@extends('layouts.app')

@section('title', 'Edit Aset - SIPETA-TRANS')
@section('page-title', 'Edit Aset')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-6">
                <h2 class="text-3xl font-bold">Edit Aset</h2>
                <p class="text-blue-100 mt-2">Update informasi aset yang sudah terdaftar dalam sistem</p>
            </div>

            <!-- Form -->
            <form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')

                <!-- Section 1: Informasi Dasar -->
                <div class="mb-8 pb-8 border-b">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-info-circle text-blue-600"></i> Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="registration_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Registrasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="registration_number" name="registration_number"
                                value="{{ old('', $asset->registration_number) }}" placeholder="Contoh: Rambu-001"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('registration_number') border-red-500 @enderror"
                                required>
                            @error('registration_number')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Aset <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $asset->name) }}"
                                placeholder="Contoh: Rambu Stop di Jl. Sudirman"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="asset_type_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kategori/Jenis Aset <span class="text-red-500">*</span>
                            </label>
                            <select id="asset_type_id" name="asset_type_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('asset_type_id') border-red-500 @enderror"
                                required onchange="updateCategoryPreview()">
                                @foreach ($assetTypes as $type)
                                    <option value="{{ $type->id }}" data-icon="{{ $type->icon }}"
                                        data-color="{{ $type->color }}"
                                        {{ $asset->asset_type_id == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_type_id')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        @if ($asset->type?->subtypes->count())
                            <div id="subtype-wrapper">
                                <label for="asset_sub_type_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Sub Kategori/Jenis Aset <span class="text-red-500">*</span>
                                </label>
                                <select id="asset_sub_type_id" name="asset_sub_type_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('asset_sub_type_id') border-red-500 @enderror">
                                    <option value="">-- Pilih Sub Kategori Aset --</option>
                                    @foreach ($asset->type->subtypes as $subtype)
                                        <option value="{{ $subtype->id }}" @selected($asset->asset_sub_type_id == $subtype->id)>
                                            {{ $subtype->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div>
                            <label for="acquired_at" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Perolehan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="acquired_at" name="acquired_at"
                                value="{{ old('acquired_at', $asset->acquired_at) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('acquired_at') border-red-500 @enderror"
                                required>
                            @error('acquired_at')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="acquisition_value" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nilai Perolehan <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="acquisition_value" name="acquisition_value"
                                value="{{ old('acquisition_value', $asset->acquisition_value) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('acquisition_value') border-red-500 @enderror"
                                required>
                            @error('acquisition_value')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="acquisition_source" class="block text-sm font-semibold text-gray-700 mb-2">
                                Cara Perolehan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="acquisition_source" name="acquisition_source"
                                value="{{ old('acquisition_source', $asset->acquisition_source) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('acquisition_source') border-red-500 @enderror"
                                required>
                            @error('acquisition_source')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="current_value" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nilai Aset <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="current_value" name="current_value"
                                value="{{ old('current_value', $asset->current_value) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_value') border-red-500 @enderror"
                                required>
                            @error('current_value')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kategori Preview -->
                    <div id="categoryPreview" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
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
                                <option value="baik" {{ $asset->status == 'baik' ? 'selected' : '' }}>✅ Baik</option>
                                <option value="perlu_perbaikan"
                                    {{ $asset->status == 'perlu_perbaikan' ? 'selected' : '' }}>⚠️ Perlu Perbaikan</option>
                                <option value="rusak" {{ $asset->status == 'rusak' ? 'selected' : '' }}>❌ Rusak</option>
                                <option value="dalam_pemeliharaan"
                                    {{ $asset->status == 'dalam_pemeliharaan' ? 'selected' : '' }}>🔧 Dalam Pemeliharaan
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="quantity" name="quantity" value="{{ $asset->quantity ?? 1 }}"
                                min="1" placeholder="Jumlah aset"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror"
                                required>
                            @error('quantity')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_maintenance" class="block text-sm font-semibold text-gray-700 mb-2">
                                Pemeliharaan Terakhir
                            </label>
                            <input type="date" id="last_maintenance" name="last_maintenance"
                                value="{{ $asset->last_maintenance }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('last_maintenance') border-red-500 @enderror">
                            @error('last_maintenance')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_maintenance_photo" class="block text-sm font-semibold text-gray-700 mb-2">
                                Foto Pemeliharaan Terakhir
                            </label>
                            <div id="dropzone"
                                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer transition hover:border-blue-500 hover:bg-blue-50 @error('last_maintenance_photo') border-red-500 @enderror"">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.9A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>

                                <p class="mt-2 text-gray-600">
                                    Drag & Drop file di sini
                                </p>

                                <p class="text-sm text-gray-500">
                                    atau klik untuk memilih file
                                </p>

                                <input type="file" id="fileInput" name="last_maintenance_photo" class="hidden"
                                    accept=".jpg,.jpeg,.png">
                            </div>

                            <div id="fileInfo" class="hidden mt-4 p-3 bg-gray-50 rounded border">
                            </div>

                            <p class="mt-2 text-xs text-gray-500">
                                Format: JPG, JPEG, PNG
                            </p>
                            @error('last_maintenance_photo')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror

                            {{-- Informasi file --}}
                            <div id="fileInfo" class="hidden mt-4 p-3 bg-gray-50 rounded border"></div>

                            {{-- Preview gambar --}}
                            @if ($asset->last_maintenance_photo)
                                <div id="existingImage" class="mt-4">
                                    <img src="{{ asset('storage/' . $asset->last_maintenance_photo) }}"
                                        class="max-h-64 rounded-lg border">
                                </div>
                            @endif
                            <div id="imagePreviewWrapper" class="hidden mt-4">
                                <img id="imagePreview" class="max-h-64 rounded-lg border shadow-sm" alt="Preview">
                            </div>
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
                        <input type="text" id="location" name="location" value="{{ $asset->location }}"
                            placeholder="Contoh: Jalan Jend. Sudirman No. 123, Bukittinggi"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('location') border-red-500 @enderror"
                            required>
                        @error('location')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-2">
                                Latitude <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="latitude" name="latitude" value="{{ $asset->latitude }}"
                                step="0.000001" placeholder="-6.2088"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('latitude') border-red-500 @enderror"
                                required>
                            @error('latitude')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-2">
                                Longitude <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="longitude" name="longitude" value="{{ $asset->longitude }}"
                                step="0.000001" placeholder="106.8456"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('longitude') border-red-500 @enderror"
                                required>
                            @error('longitude')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Map Preview -->
                    <div class="mt-6 bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                        <p class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-map text-blue-600"></i> Preview Lokasi Aset
                        </p>
                        <p class="text-xs text-blue-700 mb-3">
                            <i class="fas fa-info-circle"></i> Klik pada peta untuk mengubah lokasi aset. Latitude dan
                            Longitude akan terupdate otomatis.
                        </p>
                        <div id="mapPreview"
                            style="height: 300px; border-radius: 8px; background-color: #e5e7eb; cursor: pointer;"
                            title="Klik untuk mengubah lokasi"></div>
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
                            placeholder="Contoh: Rambu dalam kondisi baik, perlu pembersihan setiap 3 bulan..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ $asset->description }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Audit Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-8">
                    <p class="text-xs text-gray-600">
                        <i class="fas fa-info-circle"></i>
                        Dibuat: {{ $asset->created_at->format('d M Y H:i') }} |
                        Diperbarui: {{ $asset->updated_at->format('d M Y H:i') }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('assets.index') }}"
                        class="px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-semibold flex items-center gap-2">
                        <i class="fas fa-times"></i> Batal
                        </button>
                        <a href="{{ route('assets.show', $asset) }}"
                            class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-semibold flex items-center gap-2">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold flex items-center gap-2">
                            <i class="fas fa-save"></i> Perbarui Aset
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

            const assetType = assetTypes.find(t => t.id == selectedOption.value);
            if (assetType) {
                document.getElementById('categoryIcon').innerHTML = `<i class="fas ${assetType.icon}"></i>`;
                document.getElementById('categoryIcon').style.color = assetType.color;
                document.getElementById('categoryName').textContent = assetType.name;
                document.getElementById('categoryDesc').textContent = assetType.description;
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
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&limit=1`
                );
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
                    marker.setPopupContent(
                        `<div class="text-sm"><strong>📍 Hasil Pencarian:</strong><br><small>${results[0].display_name}</small><br><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}<br><br><small class="text-gray-500">Klik peta untuk fine-tune lokasi</small></div>`
                    ).openPopup();
                }
            } catch (error) {
                console.error('Geocoding error:', error);
            }
        }

        // Debounced geocoding with delay
        const debouncedGeocode = debounce(geocodeAddress, 1000);

        function initializeMap() {
            if (map) return;

            const lat = parseFloat(document.getElementById('latitude').value) || -6.2088;
            const lng = parseFloat(document.getElementById('longitude').value) || 106.8456;

            map = L.map('mapPreview').setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            marker = L.marker([lat, lng]).addTo(map).bindPopup(
                `<div class="text-sm"><strong>Lokasi Saat Ini:</strong><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}</div>`
            );

            // Map click event - menambahkan marker baru dan update coordinates
            map.on('click', function(e) {
                const newLat = e.latlng.lat;
                const newLng = e.latlng.lng;

                // Update input fields
                document.getElementById('latitude').value = newLat.toFixed(6);
                document.getElementById('longitude').value = newLng.toFixed(6);

                // Update marker position
                updateMarkerPosition(newLat, newLng);

                // Show popup
                marker.setPopupContent(
                    `<div class="text-sm"><strong>✓ Lokasi Diubah:</strong><br>Lat: ${newLat.toFixed(6)}<br>Lng: ${newLng.toFixed(6)}</div>`
                ).openPopup();
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

    <script>
        const typeSelect = document.getElementById('asset_type');
        const subtypeWrapper = document.getElementById('subtype-wrapper');
        const subtypeSelect = document.getElementById('asset_subtype');

        function loadSubtypes() {

            const option = typeSelect.options[typeSelect.selectedIndex];

            const subtypes = JSON.parse(
                option.dataset.subtypes || '[]'
            );

            subtypeSelect.innerHTML =
                '<option value="">Pilih Subtype</option>';

            if (subtypes.length > 0) {

                subtypeWrapper.style.display = 'block';

                subtypes.forEach(subtype => {

                    subtypeSelect.innerHTML += `
                    <option value="${subtype.id}">
                        ${subtype.name}
                    </option>
                `;
                });

            } else {

                subtypeWrapper.style.display = 'none';

            }
        }

        typeSelect.addEventListener('change', loadSubtypes);

        loadSubtypes(); // dipanggil saat page pertama dibuka
    </script>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');

        const imagePreviewWrapper =
            document.getElementById('imagePreviewWrapper');

        const imagePreview =
            document.getElementById('imagePreview');

        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'image/jpg',
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        dropzone.addEventListener('click', () => {
            fileInput.click();
        });

        dropzone.addEventListener('dragover', e => {
            e.preventDefault();

            dropzone.classList.add(
                'border-blue-500',
                'bg-blue-50'
            );
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove(
                'border-blue-500',
                'bg-blue-50'
            );
        });

        dropzone.addEventListener('drop', e => {
            e.preventDefault();

            dropzone.classList.remove(
                'border-blue-500',
                'bg-blue-50'
            );

            fileInput.files = e.dataTransfer.files;

            validateFile(fileInput.files[0]);
        });

        fileInput.addEventListener('change', () => {
            validateFile(fileInput.files[0]);
        });

        function validateFile(file) {
            if (!file) return;

            if (!allowedTypes.includes(file.type)) {

                alert('Format file tidak diizinkan.');

                fileInput.value = '';

                fileInfo.classList.add('hidden');
                imagePreviewWrapper.classList.add('hidden');

                return;
            }

            const sizeMB =
                (file.size / 1024 / 1024).toFixed(2);

            fileInfo.classList.remove('hidden');

            fileInfo.innerHTML = `
                <div class="flex justify-between">
                    <span class="font-medium">${file.name}</span>
                    <span>${sizeMB} MB</span>
                </div>
            `;

            // Preview gambar
            if (file.type.startsWith('image/')) {

                const reader = new FileReader();

                reader.onload = function(e) {

                    imagePreview.src = e.target.result;

                    imagePreviewWrapper.classList.remove('hidden');
                };

                reader.readAsDataURL(file);

            } else {

                imagePreview.src = '';

                imagePreviewWrapper.classList.add('hidden');
            }
        }
    </script>
@endpush
