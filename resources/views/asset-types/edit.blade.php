@extends('layouts.app')

@section('title', 'Edit Kategori Aset - SIPETA-TRANS')
@section('page-title', 'Edit Kategori: ' . $assetType->name)

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
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-6">
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <i class="fas fa-edit"></i> Edit Kategori Aset
                </h1>
                <p class="text-blue-100 mt-2">Perbarui informasi kategori aset: <strong>{{ $assetType->name }}</strong></p>
            </div>

            <form action="{{ route('asset-types.update', $assetType) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <!-- Nama Kategori -->
                <div class="mb-8">
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fas fa-tag text-blue-600 mr-2"></i> Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        class="w-full px-4 py-3 border-2 @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-blue-500 transition"
                        placeholder="Contoh: Rambu Lalu Lintas" value="{{ old('name', $assetType->name) }}" required>
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
                        placeholder="Jelaskan kategori aset ini...">{{ old('description', $assetType->description) }}</textarea>
                </div>

                <!-- Info Box: Aset Terdaftar -->
                <div class="mb-8 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                    <p class="text-sm text-blue-900 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        <strong>Aset terdaftar dalam kategori ini:</strong> {{ $assetType->assets_count ?? 0 }} aset
                    </p>
                </div>

                <!-- Current Icon and Color Info -->
                <div class="mb-8 p-6 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border-2 border-purple-200">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-circle-info"></i> Informasi Saat Ini
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-3 bg-white rounded">
                            <div class="w-12 h-12 rounded flex items-center justify-center text-2xl"
                                style="background-color: {{ $assetType->color }}20; color: {{ $assetType->color }};">
                                <i class="fas {{ $assetType->icon }}"></i>
                            </div>
                            <div class="text-sm">
                                <p class="text-gray-600">Icon Saat Ini</p>
                                <p class="font-mono font-bold text-gray-800">{{ $assetType->icon }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-white rounded">
                            <div class="w-12 h-12 rounded border-4"
                                style="border-color: {{ $assetType->color }}; background-color: {{ $assetType->color }}20;">
                            </div>
                            <div class="text-sm">
                                <p class="text-gray-600">Warna Saat Ini</p>
                                <p class="font-mono font-bold text-gray-800">{{ $assetType->color }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid Layout untuk Icon dan Warna -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Icon Selection -->
                    <div>
                        <label for="icon" class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-icons text-blue-600 mr-2"></i> Ubah Icon (Opsional)
                        </label>

                        <!-- Icon Preview -->
                        <div id="iconPreview" class="mb-4 p-4 bg-blue-50 rounded-lg text-center border-2 border-blue-200">
                            <p class="text-xs text-gray-600 mb-2">Preview Icon:</p>
                            <div id="previewIconLarge" class="text-6xl mb-2"></div>
                            <p id="previewIconName" class="text-sm font-mono text-gray-700"></p>
                        </div>

                        <!-- Icon Select Dropdown -->
                        <select id="icon" name="icon"
                            class="w-full px-4 py-3 border-2 @error('icon') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-blue-500 transition"
                            onchange="updateIconPreview()">
                            <option value="">-- Gunakan Icon Saat Ini --</option>
                            <optgroup label="Informasi & Status">
                                <option value="fa-circle-info"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-circle-info' ? 'selected' : '' }}>
                                    fa-circle-info - Informasi</option>
                                <option value="fa-exclamation"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-exclamation' ? 'selected' : '' }}>
                                    fa-exclamation - Perhatian</option>
                                <option value="fa-warning"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-warning' ? 'selected' : '' }}>fa-warning -
                                    Peringatan</option>
                                <option value="fa-shield"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-shield' ? 'selected' : '' }}>fa-shield -
                                    Keamanan</option>
                                <option value="fa-flag"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-flag' ? 'selected' : '' }}>fa-flag -
                                    Bendera</option>
                            </optgroup>
                            <optgroup label="Transportasi">
                                <option value="fa-bus"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-bus' ? 'selected' : '' }}>fa-bus - Bus
                                </option>
                                <option value="fa-car"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-car' ? 'selected' : '' }}>fa-car - Mobil
                                </option>
                                <option value="fa-truck"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-truck' ? 'selected' : '' }}>fa-truck -
                                    Truk</option>
                                <option value="fa-road"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-road' ? 'selected' : '' }}>fa-road - Jalan
                                </option>
                                <option value="fa-routes"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-routes' ? 'selected' : '' }}>fa-routes -
                                    Rute</option>
                                <option value="fa-traffic-light"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-traffic-light' ? 'selected' : '' }}>
                                    fa-traffic-light - Lampu Lalu Lintas</option>
                            </optgroup>
                            <optgroup label="Lokasi & Navigasi">
                                <option value="fa-map-marker-alt"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-map-marker-alt' ? 'selected' : '' }}>
                                    fa-map-marker-alt - Marker Peta</option>
                                <option value="fa-map"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-map' ? 'selected' : '' }}>fa-map - Peta
                                </option>
                                <option value="fa-map-location-dot"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-map-location-dot' ? 'selected' : '' }}>
                                    fa-map-location-dot - Lokasi</option>
                                <option value="fa-location-dot"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-location-dot' ? 'selected' : '' }}>
                                    fa-location-dot - Titik Lokasi</option>
                                <option value="fa-compass"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-compass' ? 'selected' : '' }}>fa-compass -
                                    Kompas</option>
                            </optgroup>
                            <optgroup label="Orang & Kelompok">
                                <option value="fa-walking"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-walking' ? 'selected' : '' }}>fa-walking -
                                    Berjalan</option>
                                <option value="fa-person"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-person' ? 'selected' : '' }}>fa-person -
                                    Orang</option>
                                <option value="fa-users"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-users' ? 'selected' : '' }}>fa-users -
                                    Kelompok</option>
                                <option value="fa-user-tie"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-user-tie' ? 'selected' : '' }}>fa-user-tie
                                    - Profesional</option>
                            </optgroup>
                            <optgroup label="Media & Dokumentasi">
                                <option value="fa-camera"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-camera' ? 'selected' : '' }}>fa-camera -
                                    Kamera</option>
                                <option value="fa-video"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-video' ? 'selected' : '' }}>fa-video -
                                    Video</option>
                                <option value="fa-eye"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-eye' ? 'selected' : '' }}>fa-eye -
                                    Mata/Pengawasan</option>
                                <option value="fa-binoculars"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-binoculars' ? 'selected' : '' }}>
                                    fa-binoculars - Binokular</option>
                                <option value="fa-radar"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-radar' ? 'selected' : '' }}>fa-radar -
                                    Radar</option>
                            </optgroup>
                            <optgroup label="Perbaikan & Perawatan">
                                <option value="fa-hammer"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-hammer' ? 'selected' : '' }}>fa-hammer -
                                    Palu</option>
                                <option value="fa-wrench"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-wrench' ? 'selected' : '' }}>fa-wrench -
                                    Kunci Inggris</option>
                                <option value="fa-tools"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-tools' ? 'selected' : '' }}>fa-tools -
                                    Alat</option>
                                <option value="fa-toolbox"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-toolbox' ? 'selected' : '' }}>fa-toolbox -
                                    Kotak Alat</option>
                                <option value="fa-gears"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-gears' ? 'selected' : '' }}>fa-gears -
                                    Gigi</option>
                            </optgroup>
                            <optgroup label="Data & Informasi">
                                <option value="fa-chart-bar"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-chart-bar' ? 'selected' : '' }}>
                                    fa-chart-bar - Grafik Batang</option>
                                <option value="fa-chart-line"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-chart-line' ? 'selected' : '' }}>
                                    fa-chart-line - Grafik Garis</option>
                                <option value="fa-chart-pie"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-chart-pie' ? 'selected' : '' }}>
                                    fa-chart-pie - Grafik Pie</option>
                                <option value="fa-chart-area"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-chart-area' ? 'selected' : '' }}>
                                    fa-chart-area - Grafik Area</option>
                            </optgroup>
                            <optgroup label="Lainnya">
                                <option value="fa-cube"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-cube' ? 'selected' : '' }}>fa-cube - Kubus
                                </option>
                                <option value="fa-box"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-box' ? 'selected' : '' }}>fa-box - Kotak
                                </option>
                                <option value="fa-package"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-package' ? 'selected' : '' }}>fa-package -
                                    Paket</option>
                                <option value="fa-star"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-star' ? 'selected' : '' }}>fa-star -
                                    Bintang</option>
                                <option value="fa-heart"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-heart' ? 'selected' : '' }}>fa-heart -
                                    Hati</option>
                                <option value="fa-lightbulb"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-lightbulb' ? 'selected' : '' }}>
                                    fa-lightbulb - Ide</option>
                                <option value="fa-sun"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-sun' ? 'selected' : '' }}>fa-sun -
                                    Matahari</option>
                                <option value="fa-moon"
                                    {{ (old('icon') ?? $assetType->icon) === 'fa-moon' ? 'selected' : '' }}>fa-moon - Bulan
                                </option>
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
                            <i class="fas fa-palette text-blue-600 mr-2"></i> Ubah Warna (Opsional)
                        </label>

                        <!-- Color Preview -->
                        <div id="colorPreview" class="mb-4 p-4 bg-gray-50 rounded-lg border-2 border-gray-200">
                            <p class="text-xs text-gray-600 mb-2">Preview Warna:</p>
                            <div class="flex items-center gap-4">
                                <div id="colorCircle" class="w-16 h-16 rounded-lg border-4 border-gray-300 transition"
                                    style="background-color: {{ old('color', $assetType->color) }};"></div>
                                <div>
                                    <p id="colorHexDisplay" class="font-mono text-lg font-bold text-gray-800">
                                        {{ old('color', $assetType->color) }}</p>
                                    <p class="text-xs text-gray-600">Warna terpilih</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label for="colorPicker" class="text-xs text-gray-600 block mb-2">Picker</label>
                                <input type="color" id="colorPicker"
                                    class="w-full h-12 rounded-lg cursor-pointer border-2 border-gray-300"
                                    value="{{ old('color', $assetType->color) }}" onchange="updateColorPreview()">
                            </div>
                            <div class="flex-1">
                                <label for="colorHex" class="text-xs text-gray-600 block mb-2">Hex</label>
                                <input type="text" id="colorHex" name="color"
                                    class="w-full px-3 py-2 border-2 @error('color') border-red-500 @else border-gray-300 @enderror rounded-lg font-mono text-sm focus:outline-none focus:border-blue-500"
                                    value="{{ old('color', $assetType->color) }}" placeholder="#000000"
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
                    <h3 class="font-bold text-gray-800 mb-4">Preview Perubahan:</h3>
                    <div class="flex items-center gap-6">
                        <div id="previewCard" class="flex items-center gap-4 p-4 bg-white rounded-lg flex-1">
                            <div id="previewIconContainer"
                                class="w-16 h-16 rounded-lg flex items-center justify-center text-3xl"
                                style="background-color: {{ old('color', $assetType->color) }}20; color: {{ old('color', $assetType->color) }};">
                                <i class="fas {{ old('icon', $assetType->icon) }}"></i>
                            </div>
                            <div class="flex-1">
                                <p id="previewName" class="font-bold text-gray-800">{{ old('name', $assetType->name) }}
                                </p>
                                <p id="previewDesc" class="text-xs text-gray-600">
                                    {{ old('description', $assetType->description) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8 p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border-2 border-gray-200">

                    <div class="flex items-center px-3 py-2 bg-gray-200 rounded-md">
                        <input type="checkbox" id="is_sub_type_needed" name="is_sub_type_needed"
                            class="mr-3 w-5 h-5 accent-green-600">

                        <h3 class="font-bold text-gray-800">
                            Apakah Kategori Ini Memiliki Sub-Kategori?
                        </h3>
                    </div>

                    <!-- Container -->
                    <div id="subTypeSection" class="hidden mt-5">

                        <div class="p-5 bg-white rounded-lg border border-gray-300 shadow-sm">

                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-semibold text-gray-700">
                                    Daftar Sub-Kategori
                                </h4>

                                <button type="button" id="addSubType"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">

                                    + Tambah Sub-Kategori
                                </button>
                            </div>

                            <div id="subTypeContainer"></div>

                        </div>

                    </div>

                </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-6 border-t-2 border-gray-200">
            <button type="submit"
                class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Simpan Perubahan
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
            const currentIcon = '{{ $assetType->icon }}';
            const icon = iconValue || currentIcon;

            if (!icon) {
                document.getElementById('iconPreview').classList.add('hidden');
                return;
            }

            const iconClass = icon.split(' - ')[0];
            document.getElementById('previewIconLarge').innerHTML = `<i class="fas ${iconClass}"></i>`;
            document.getElementById('previewIconName').textContent = iconClass;
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

    <!-- sub-type script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const checkbox = document.getElementById('is_sub_type_needed');
            const section = document.getElementById('subTypeSection');
            const container = document.getElementById('subTypeContainer');
            const addButton = document.getElementById('addSubType');

            let index = 0;

            checkbox.addEventListener('change', function() {
                section.classList.toggle('hidden', !this.checked);
            });

            addButton.addEventListener('click', function() {
                addSubType();
            });

            function addSubType() {

                const html = `
            <div class="sub-type-item p-4 mb-4 bg-gray-50 border rounded-lg">

                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-medium text-gray-700">
                        Sub-Kategori #${index + 1}
                    </h5>

                    <button
                        type="button"
                        class="remove-sub-type text-red-600 hover:text-red-800">
                        Hapus
                    </button>
                </div>

                <div class="grid md:grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-1 text-sm font-medium">
                            Nama Sub-Kategori
                        </label>

                        <input
                            type="text"
                            name="sub_types[${index}][name]"
                            class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium">
                            Warna
                        </label>

                        <input
                            type="color"
                            name="sub_types[${index}][color]"
                            value="#22c55e"
                            class="w-full h-10 rounded-lg">
                    </div>

                </div>

            </div>
        `;

                container.insertAdjacentHTML('beforeend', html);

                index++;
            }

            container.addEventListener('click', function(e) {

                if (e.target.classList.contains('remove-sub-type')) {
                    e.target.closest('.sub-type-item').remove();
                }

            });

        });
    </script>
@endpush
