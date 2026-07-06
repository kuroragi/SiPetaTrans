@extends('layouts.app')

@section('title', 'Edit Usulan - SIPETA-TRANS')
@section('page-title', 'Edit Usulan')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-6 rounded-t-lg relative">
                <a href="{{ route('proposals.index') }}" class="absolute top-6 right-8 text-blue-100 hover:text-white transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <h2 class="text-3xl font-bold">Edit Data Usulan</h2>
                <p class="text-blue-100 mt-2">Ubah data usulan masyarakat.</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('proposals.update', $proposal->id) }}" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-8 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Section 1: Informasi Pengusul -->
                <div class="mb-8 pb-8 border-b">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-user text-blue-600"></i> Informasi Pengusul
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Pengusul <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pengusul" value="{{ old('pengusul', $proposal->pengusul) }}" placeholder="Instansi / perorangan" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Pengusul <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email_pengusul" value="{{ old('email_pengusul', $proposal->email_pengusul) }}" placeholder="Alamat email" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>
                </div>

                <!-- Section 2: Detail Usulan -->
                <div class="mb-8 pb-8 border-b">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-clipboard-list text-purple-600"></i> Detail Usulan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Pengajuan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::parse($proposal->tanggal)->format('Y-m-d')) }}" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jenis Permintaan <span class="text-red-500">*</span>
                            </label>
                            @php
                                $currentAssetType = explode(' - ', $proposal->jenis_permintaan)[0] ?? '';
                                $currentAssetSubType = explode(' - ', $proposal->jenis_permintaan)[1] ?? '';
                            @endphp
                            <select id="asset_type_id" name="asset_type_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="updateSubtypes()">
                                <option value="">-- Pilih Jenis Aset --</option>
                                @foreach ($assetTypes as $type)
                                    <option value="{{ $type->name }}" data-subtypes='@json($type->subtypes)' @selected(old('asset_type_id', $currentAssetType) === $type->name)>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="display: none;" id="subtype-wrapper">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Sub Jenis Aset <span class="text-red-500">*</span>
                            </label>
                            <select id="asset_sub_type_id" name="asset_sub_type_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="updateJenisPermintaan()" data-old="{{ old('asset_sub_type_id', $currentAssetSubType) }}">
                                <option value="">-- Pilih Sub Jenis Aset --</option>
                            </select>
                        </div>
                        <input type="hidden" id="jenis_permintaan" name="jenis_permintaan" value="{{ old('jenis_permintaan', $proposal->jenis_permintaan) }}">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jumlah <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="jumlah" value="{{ old('jumlah', $proposal->jumlah) }}" min="1" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Perkiraan Anggaran (Opsional)
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-500 font-semibold">Rp</span>
                                <input type="number" name="perkiraan_anggaran" value="{{ old('perkiraan_anggaran', $proposal->perkiraan_anggaran) }}" 
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <input type="hidden" name="coordinates" id="coordinates" value="{{ old('coordinates', json_encode($proposal->coordinates)) }}">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Lokasi Usulan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="lokasi" id="location" value="{{ old('lokasi', $proposal->lokasi) }}" placeholder="Nama jalan / area / titik lokasi..." required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="latlng_wrapper">
                        <div>
                            <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-2">
                                Latitude <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="latitude" name="latitude" value="{{ old('latitude', is_array($proposal->coordinates) && isset($proposal->coordinates[0]) ? $proposal->coordinates[0] : -0.305218) }}"
                                step="0.000001" placeholder="-0.305218"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Default: Bukittinggi</p>
                        </div>

                        <div>
                            <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-2">
                                Longitude <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="longitude" name="longitude"
                                value="{{ old('longitude', is_array($proposal->coordinates) && isset($proposal->coordinates[1]) ? $proposal->coordinates[1] : 100.369574) }}" step="0.000001" placeholder="100.369574"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Default: Bukittinggi</p>
                        </div>
                    </div>

                    <!-- Map Preview -->
                    <div class="mt-6 bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                        <p class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-map text-blue-600"></i> Preview Lokasi Usulan
                        </p>
                        <p class="text-xs text-blue-700 mb-3">
                            <i class="fas fa-info-circle"></i> Klik pada peta untuk menentukan lokasi usulan secara akurat.
                            Latitude dan Longitude akan terupdate otomatis.
                        </p>
                        <div id="mapPreview"
                            style="height: 300px; border-radius: 8px; background-color: #e5e7eb; cursor: pointer;"
                            title="Klik untuk menentukan lokasi"></div>
                    </div>
                </div>

                <!-- Section 3: Status & Kelayakan -->
                <div class="mb-8 pb-8 border-b">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-check-double text-green-600"></i> Status & Kelayakan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kelayakan <span class="text-red-500">*</span>
                            </label>
                            <select name="kelayakan" id="kelayakan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @php
                                    $kelayakan = $proposal->status === 'ditolak' ? 'tidak layak' : 'layak';
                                @endphp
                                <option value="layak" @selected(old('kelayakan', $kelayakan) === 'layak')>✅ Layak</option>
                                <option value="tidak layak" @selected(old('kelayakan', $kelayakan) === 'tidak layak')>❌ Tidak Layak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending" @selected(old('status', $proposal->status) === 'pending')>Pending</option>
                                <option value="ditindak lanjuti" @selected(old('status', $proposal->status) === 'ditindak lanjuti')>Ditindak lanjuti</option>
                                <option value="selesai" @selected(old('status', $proposal->status) === 'selesai')>Selesai</option>
                                <option value="ditolak" @selected(old('status', $proposal->status) === 'ditolak')>Ditolak</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Lampiran -->
                <div class="mb-8 pb-8 border-b">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-paperclip text-orange-600"></i> Lampiran Dokumen
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Arsip Surat (PDF) 
                            </label>
                            @if($proposal->arsip_surat)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $proposal->arsip_surat) }}" target="_blank" class="text-sm text-blue-600 hover:underline"><i class="fas fa-file-pdf"></i> Lihat File Saat Ini</a>
                                </div>
                            @endif
                            <input type="file" name="arsip_surat" accept=".pdf" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Foto Dokumentasi 
                            </label>
                            @if($proposal->foto)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/' . $proposal->foto) }}" target="_blank" class="text-sm text-blue-600 hover:underline"><i class="fas fa-image"></i> Lihat Foto Saat Ini</a>
                                </div>
                            @endif
                            <input type="file" name="foto" accept="image/*" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('proposals.index') }}"
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

    @push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script>
        // Map related variables
        let map = null;
        let marker = null;
        let drawnItems = new L.FeatureGroup();

        // Debounce function
        function debounce(func, delay) {
            let timeoutId;
            return function() {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(func, delay);
            };
        }

        // Geocode address
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

                    document.getElementById('latitude').value = lat.toFixed(6);
                    document.getElementById('longitude').value = lng.toFixed(6);
                    document.getElementById('coordinates').value = JSON.stringify([lat, lng]);

                    updateMarkerPosition(lat, lng);

                    if (marker) {
                        marker.setPopupContent(
                            `<div class="text-sm"><strong>📍 Hasil Pencarian:</strong><br><small>${results[0].display_name}</small><br><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}<br><br><small class="text-gray-500">Klik peta untuk fine-tune lokasi</small></div>`
                        ).openPopup();
                    }
                }
            } catch (error) {
                console.error('Geocoding error:', error);
            }
        }

        const debouncedGeocode = debounce(geocodeAddress, 1000);

        function initializeMap() {
            if (map) return;

            const defaultLat = parseFloat(document.getElementById('latitude').value) || -0.305218;
            const defaultLng = parseFloat(document.getElementById('longitude').value) || 100.369574;

            map = L.map('mapPreview').setView([defaultLat, defaultLng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            map.addLayer(drawnItems);

            marker = L.marker([defaultLat, defaultLng]).bindPopup('Klik pada peta untuk menentukan lokasi usulan');
            drawnItems.addLayer(marker);

            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
                document.getElementById('coordinates').value = JSON.stringify([parseFloat(lat.toFixed(6)), parseFloat(lng.toFixed(6))]);

                updateMarkerPosition(lat, lng);
            });

            document.getElementById('latitude').addEventListener('change', updateMarkerFromInput);
            document.getElementById('longitude').addEventListener('change', updateMarkerFromInput);
            document.getElementById('location').addEventListener('input', debouncedGeocode);
        }

        function updateMarkerFromInput() {
            if (!map) return;

            const lat = parseFloat(document.getElementById('latitude').value) || -0.305218;
            const lng = parseFloat(document.getElementById('longitude').value) || 100.369574;

            updateMarkerPosition(lat, lng);
            document.getElementById('coordinates').value = JSON.stringify([lat, lng]);
        }

        function updateMarkerPosition(lat, lng) {
            if (!map) return;
            map.setView([lat, lng], 16);
            drawnItems.clearLayers();
            marker = L.marker([lat, lng]).bindPopup('Lokasi Usulan');
            drawnItems.addLayer(marker);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const kelayakanSelect = document.getElementById('kelayakan');
            const statusSelect = document.getElementById('status');

            function updateStatusBasedOnKelayakan() {
                if (kelayakanSelect.value === 'tidak layak') {
                    statusSelect.value = 'ditolak';
                    Array.from(statusSelect.options).forEach(opt => {
                        if (opt.value !== 'ditolak') {
                            opt.disabled = true;
                        }
                    });
                } else {
                    Array.from(statusSelect.options).forEach(opt => {
                        opt.disabled = false;
                    });
                }
            }

            kelayakanSelect.addEventListener('change', updateStatusBasedOnKelayakan);
            updateStatusBasedOnKelayakan();

            initializeMap();
            if (!document.getElementById('coordinates').value) {
                updateMarkerFromInput();
            }

            // Initialization for subtypes if there is old value
            if (document.getElementById('asset_type_id').value) {
                updateSubtypes();
            }
        });

        function updateSubtypes() {
            const select = document.getElementById('asset_type_id');
            const wrapper = document.getElementById('subtype-wrapper');
            const subSelect = document.getElementById('asset_sub_type_id');
            
            const selectedOption = select.options[select.selectedIndex];
            
            if (!selectedOption || !selectedOption.value) {
                wrapper.style.display = 'none';
                subSelect.innerHTML = '<option value="">-- Pilih Sub Jenis Aset --</option>';
                subSelect.removeAttribute('required');
                updateJenisPermintaan();
                return;
            }

            const subtypes = JSON.parse(selectedOption.dataset.subtypes || '[]');
            
            if (subtypes.length > 0) {
                wrapper.style.display = 'block';
                subSelect.setAttribute('required', 'required');
                
                let html = '<option value="">-- Pilih Sub Jenis Aset --</option>';
                const oldSubType = subSelect.dataset.old;
                subtypes.forEach(subtype => {
                    const selected = oldSubType === subtype.name ? 'selected' : '';
                    html += `<option value="${subtype.name}" ${selected}>${subtype.name}</option>`;
                });
                subSelect.innerHTML = html;
            } else {
                wrapper.style.display = 'none';
                subSelect.innerHTML = '<option value="">-- Pilih Sub Jenis Aset --</option>';
                subSelect.removeAttribute('required');
            }
            updateJenisPermintaan();
        }

        function updateJenisPermintaan() {
            const typeValue = document.getElementById('asset_type_id').value;
            const subTypeValue = document.getElementById('asset_sub_type_id').value;
            let result = typeValue;
            if (subTypeValue) {
                result += ' - ' + subTypeValue;
            }
            document.getElementById('jenis_permintaan').value = result;
        }
    </script>
    @endpush
@endsection
