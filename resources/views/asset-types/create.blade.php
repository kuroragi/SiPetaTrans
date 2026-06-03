@extends('layouts.app')

@section('title', 'Tambah Kategori Aset - SIPETA-TRANS')
@section('page-title', 'Tambah Kategori Aset Baru')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('asset-types.index') }}"
                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-6">
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <i class="fas fa-plus-circle"></i> Tambah Kategori Aset Baru
                </h1>
                <p class="text-green-100 mt-2">Buat kategori aset baru dengan icon dan warna yang sesuai</p>
            </div>

            <form action="{{ route('asset-types.store') }}" method="POST" class="p-8">
                @csrf

                <!-- Nama Kategori -->
                <div class="mb-8">
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fas fa-tag text-blue-600 mr-2"></i> Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        class="w-full px-4 py-3 border-2 @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-blue-500 transition"
                        placeholder="Contoh: Rambu Lalu Lintas" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-8">
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fas fa-align-left text-blue-600 mr-2"></i> Deskripsi (Opsional)
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition"
                        placeholder="Jelaskan kategori aset ini...">{{ old('description') }}</textarea>
                </div>

                <!-- Grid Layout untuk Icon dan Warna -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Icon Selection -->
                    <div>
                        <label for="icon" class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-icons text-blue-600 mr-2"></i> Pilih Icon <span class="text-red-500">*</span>
                        </label>

                        <!-- Icon Preview -->
                        <div id="iconPreview"
                            class="mb-4 p-4 bg-blue-50 rounded-lg text-center border-2 border-blue-200 hidden">
                            <p class="text-xs text-gray-600 mb-2">Preview Icon:</p>
                            <div id="previewIconLarge" class="text-6xl mb-2"></div>
                            <p id="previewIconName" class="text-sm font-mono text-gray-700"></p>
                        </div>

                        <!-- Icon Select Dropdown -->
                        <select id="icon" name="icon"
                            class="w-full px-4 py-3 border-2 @error('icon') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-blue-500 transition"
                            onchange="updateIconPreview()" required>
                            <option value="">-- Pilih Icon --</option>
                            <optgroup label="Informasi & Status">
                                <option value="fa-circle-info">fa-circle-info - Informasi</option>
                                <option value="fa-exclamation">fa-exclamation - Perhatian</option>
                                <option value="fa-warning">fa-warning - Peringatan</option>
                                <option value="fa-shield">fa-shield - Keamanan</option>
                                <option value="fa-flag">fa-flag - Bendera</option>
                            </optgroup>
                            <optgroup label="Transportasi">
                                <option value="fa-bus">fa-bus - Bus</option>
                                <option value="fa-car">fa-car - Mobil</option>
                                <option value="fa-truck">fa-truck - Truk</option>
                                <option value="fa-road">fa-road - Jalan</option>
                                <option value="fa-routes">fa-routes - Rute</option>
                                <option value="fa-traffic-light">fa-traffic-light - Lampu Lalu Lintas</option>
                            </optgroup>
                            <optgroup label="Lokasi & Navigasi">
                                <option value="fa-map-marker-alt">fa-map-marker-alt - Marker Peta</option>
                                <option value="fa-map">fa-map - Peta</option>
                                <option value="fa-map-location-dot">fa-map-location-dot - Lokasi</option>
                                <option value="fa-location-dot">fa-location-dot - Titik Lokasi</option>
                                <option value="fa-compass">fa-compass - Kompas</option>
                            </optgroup>
                            <optgroup label="Orang & Kelompok">
                                <option value="fa-walking">fa-walking - Berjalan</option>
                                <option value="fa-person">fa-person - Orang</option>
                                <option value="fa-users">fa-users - Kelompok</option>
                                <option value="fa-user-tie">fa-user-tie - Profesional</option>
                            </optgroup>
                            <optgroup label="Media & Dokumentasi">
                                <option value="fa-camera">fa-camera - Kamera</option>
                                <option value="fa-video">fa-video - Video</option>
                                <option value="fa-eye">fa-eye - Mata/Pengawasan</option>
                                <option value="fa-binoculars">fa-binoculars - Binokular</option>
                                <option value="fa-radar">fa-radar - Radar</option>
                            </optgroup>
                            <optgroup label="Perbaikan & Perawatan">
                                <option value="fa-hammer">fa-hammer - Palu</option>
                                <option value="fa-wrench">fa-wrench - Kunci Inggris</option>
                                <option value="fa-tools">fa-tools - Alat</option>
                                <option value="fa-toolbox">fa-toolbox - Kotak Alat</option>
                                <option value="fa-gears">fa-gears - Gigi</option>
                            </optgroup>
                            <optgroup label="Data & Informasi">
                                <option value="fa-chart-bar">fa-chart-bar - Grafik Batang</option>
                                <option value="fa-chart-line">fa-chart-line - Grafik Garis</option>
                                <option value="fa-chart-pie">fa-chart-pie - Grafik Pie</option>
                                <option value="fa-chart-area">fa-chart-area - Grafik Area</option>
                            </optgroup>
                            <optgroup label="Lainnya">
                                <option value="fa-cube">fa-cube - Kubus</option>
                                <option value="fa-box">fa-box - Kotak</option>
                                <option value="fa-package">fa-package - Paket</option>
                                <option value="fa-star">fa-star - Bintang</option>
                                <option value="fa-heart">fa-heart - Hati</option>
                                <option value="fa-lightbulb">fa-lightbulb - Ide</option>
                                <option value="fa-sun">fa-sun - Matahari</option>
                                <option value="fa-moon">fa-moon - Bulan</option>
                            </optgroup>
                        </select>

                        @error('icon')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Warna Selection -->
                    <div>
                        <label for="color" class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-palette text-blue-600 mr-2"></i> Warna Kategori <span
                                class="text-red-500">*</span>
                        </label>

                        <!-- Color Preview -->
                        <div id="colorPreview" class="mb-4 p-4 bg-gray-50 rounded-lg border-2 border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Preview Warna:</p>
                            <div class="flex items-center gap-4">
                                <div id="colorCircle" class="w-16 h-16 rounded-lg border-4 border-gray-300 transition"
                                    style="background-color: #3B82F6;"></div>
                                <div>
                                    <p id="colorHexDisplay" class="font-mono text-lg font-bold text-gray-800">#3B82F6</p>
                                    <p class="text-xs text-gray-600">Warna terpilih</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label for="colorPicker" class="text-xs text-gray-600 block mb-2">Picker</label>
                                <input type="color" id="colorPicker"
                                    class="w-full h-12 rounded-lg cursor-pointer border-2 border-gray-300" value="#3B82F6"
                                    onchange="updateColorPreview()">
                            </div>
                            <div class="flex-1">
                                <label for="colorHex" class="text-xs text-gray-600 block mb-2">Hex</label>
                                <input type="text" id="colorHex" name="color"
                                    class="w-full px-3 py-2 border-2 @error('color') border-red-500 @else border-gray-300 @enderror rounded-lg font-mono text-sm focus:outline-none focus:border-blue-500"
                                    value="{{ old('color', '#3B82F6') }}" placeholder="#000000" required
                                    onchange="updateColorFromHex()">
                            </div>
                        </div>

                        @error('color')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Preview Card -->
                <div class="mb-8 p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border-2 border-gray-200">
                    <h3 class="font-bold text-gray-800 mb-4">Preview Kategori:</h3>
                    <div class="flex items-center gap-6">
                        <div id="previewCard" class="flex items-center gap-4 p-4 bg-white rounded-lg flex-1">
                            <div id="previewIconContainer"
                                class="w-16 h-16 rounded-lg flex items-center justify-center text-3xl"
                                style="background-color: #3B82F620; color: #3B82F6;">
                                <i class="fas fa-cube"></i>
                            </div>
                            <div class="flex-1">
                                <p id="previewName" class="font-bold text-gray-800">Nama Kategori</p>
                                <p id="previewDesc" class="text-xs text-gray-600">Deskripsi kategori</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8 p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border-2 border-gray-200">
                    <button type="button"
                        class="ms-auto bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-3 py-2 rounded-md text-sm font-medium transition-color"
                        id="addSubtypeButton">Tambah
                        Sub
                        Kategori</button>
                    <div id="subtypesContainer" class="mt-4 space-y-4">
                        <!-- Subtype fields will be added here dynamically -->
                        <p class="text-gray-600 text-sm" id="nullsubtypep">Belum ada sub kategori. Klik "Tambah Sub
                            Kategori" untuk
                            menambahkan.</p>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t-2 border-gray-200">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Simpan Kategori
                    </button>
                    <a href="{{ route('asset-types.index') }}"
                        class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-bold flex items-center justify-center gap-2">
                        <i class="fas fa-times-circle"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function updateIconPreview() {
            const select = document.getElementById('icon');
            const iconValue = select.value;

            if (!iconValue) {
                document.getElementById('iconPreview').classList.add('hidden');
                return;
            }

            const iconClass = iconValue.split(' - ')[0];
            document.getElementById('previewIconLarge').innerHTML = `<i class="fas ${iconClass}"></i>`;
            document.getElementById('previewIconName').textContent = iconValue;
            document.getElementById('iconPreview').classList.remove('hidden');

            // Update preview card
            const color = document.getElementById('colorHex').value;
            document.getElementById('previewIconContainer').innerHTML = `<i class="fas ${iconClass}"></i>`;
            document.getElementById('previewIconContainer').style.color = color;
            document.getElementById('previewIconContainer').style.backgroundColor = color + '20';
        }

        function updateColorPreview() {
            const color = document.getElementById('colorPicker').value;
            document.getElementById('colorHex').value = color;
            document.getElementById('colorHexDisplay').textContent = color;
            document.getElementById('colorCircle').style.backgroundColor = color;

            // Update preview card
            document.getElementById('previewIconContainer').style.color = color;
            document.getElementById('previewIconContainer').style.backgroundColor = color + '20';
        }

        function updateColorFromHex() {
            const hex = document.getElementById('colorHex').value;
            if (hex.match(/^#[0-9A-F]{6}$/i)) {
                document.getElementById('colorPicker').value = hex;
                document.getElementById('colorHexDisplay').textContent = hex;
                document.getElementById('colorCircle').style.backgroundColor = hex;

                // Update preview card
                document.getElementById('previewIconContainer').style.color = hex;
                document.getElementById('previewIconContainer').style.backgroundColor = hex + '20';
            }
        }

        // Update preview on input change
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('name').addEventListener('input', (e) => {
                document.getElementById('previewName').textContent = e.target.value || 'Nama Kategori';
            });

            document.getElementById('description').addEventListener('input', (e) => {
                document.getElementById('previewDesc').textContent = e.target.value || 'Deskripsi kategori';
            });

            // Initialize preview
            updateIconPreview();
        });
    </script>

    <script>
        let subtypeCount = 0;

        document.getElementById('addSubtypeButton').addEventListener('click', () => {
            subtypeCount++;
            const container = document.getElementById('subtypesContainer');

            // Remove "Belum ada sub kategori" message if it exists
            const nullMessage = document.getElementById('nullsubtypep');
            if (nullMessage) {
                nullMessage.remove();
            }

            const subtypeDiv = document.createElement('div');
            subtypeDiv.classList.add('p-3', 'border-2', 'border-gray-800', 'w-100', 'rounded-lg', 'flex', 'gap-3',
                'items-center');

            subtypeDiv.innerHTML = `
                <input type="text" name="subtypes[${subtypeCount}][name]"
                                class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                                placeholder="Nama Sub Kategori">
                            <input type="color" name="subtypes[${subtypeCount}][color]"
                                class="w-10 h-10 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" value="#3B82F6">
                            <button class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded-md text-sm font-medium transition-color" type="button" id="removeSubtypeButton" number="${subtypeCount}">
                                <i class="fas fa-trash"></i>
                            </button>
            `;

            container.appendChild(subtypeDiv);
        });

        document.getElementById('subtypesContainer').addEventListener('click', (e) => {
            if (e.target.closest('#removeSubtypeButton')) {
                const button = e.target.closest('#removeSubtypeButton');
                const number = button.getAttribute('number');
                const subtypeDiv = button.parentElement;
                subtypeDiv.remove();

                // If no subtypes left, show the null message
                if (document.querySelectorAll('#subtypesContainer > div').length === 0) {
                    const nullMessage = document.createElement('p');
                    nullMessage.id = 'nullsubtypep';
                    nullMessage.classList.add('text-gray-600', 'text-sm');
                    nullMessage.textContent =
                        'Belum ada sub kategori. Klik "Tambah Sub Kategori" untuk menambahkan.';
                    document.getElementById('subtypesContainer').appendChild(nullMessage);
                }
            }
        });
    </script>
@endpush
