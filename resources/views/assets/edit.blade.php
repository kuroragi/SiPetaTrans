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
                                        data-color="{{ $type->color }}" data-category="{{ $type->asset_category }}"
                                        data-geometry="{{ $type->geometry }}"
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

                        <div class="general-asset-field">
                            <label for="acquired_at" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Perolehan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="acquired_at" name="acquired_at"
                                value="{{ old('acquired_at', $asset->acquired_at) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('acquired_at') border-red-500 @enderror">
                            @error('acquired_at')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="general-asset-field">
                            <label for="acquisition_value" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nilai Perolehan <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="acquisition_value" name="acquisition_value"
                                value="{{ old('acquisition_value', $asset->acquisition_value) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('acquisition_value') border-red-500 @enderror">
                            @error('acquisition_value')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="general-asset-field">
                            <label for="acquisition_source" class="block text-sm font-semibold text-gray-700 mb-2">
                                Cara Perolehan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="acquisition_source" name="acquisition_source"
                                value="{{ old('acquisition_source', $asset->acquisition_source) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('acquisition_source') border-red-500 @enderror">
                            @error('acquisition_source')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="general-asset-field">
                            <label for="current_value" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nilai Aset <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="current_value" name="current_value"
                                value="{{ old('current_value', $asset->current_value) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_value') border-red-500 @enderror">
                            @error('current_value')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Parking Asset Fields -->
                    <div id="parking_asset_fields" style="display: none;">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                            <i class="fas fa-parking text-blue-600"></i> Informasi Parkir
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="vehicle_type" class="block text-sm font-semibold text-gray-700 mb-2">Jenis
                                    Kendaraan</label>
                                <select id="vehicle_type" name="vehicle_type"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    onchange="updateVehicleType()">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="R2"
                                        {{ old('vehicle_type', $asset->vehicle_type) == 'R2' ? 'selected' : '' }}>R2
                                    </option>
                                    <option value="R4"
                                        {{ old('vehicle_type', $asset->vehicle_type) == 'R4' ? 'selected' : '' }}>R4
                                    </option>
                                    <option value="R2/R4"
                                        {{ old('vehicle_type', $asset->vehicle_type) == 'R2/R4' ? 'selected' : '' }}>R2/R4
                                    </option>
                                </select>
                            </div>
                            <div id="r2_field" style="display: none;">
                                <label for="r2" class="block text-sm font-semibold text-gray-700 mb-2">Kapasitas
                                    R2</label>
                                <input type="number" id="r2" name="r2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ old('r2', $asset->r2) }}">
                            </div>
                            <div id="r4_field" style="display: none;">
                                <label for="r4" class="block text-sm font-semibold text-gray-700 mb-2">Kapasitas
                                    R4</label>
                                <input type="number" id="r4" name="r4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ old('r4', $asset->r4) }}">
                            </div>
                            <div>
                                <label for="tariff_type" class="block text-sm font-semibold text-gray-700 mb-2">Jenis
                                    Tarif</label>
                                <select id="tariff_type" name="tariff_type"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="flat"
                                        {{ old('tariff_type', $asset->tariff_type) == 'flat' ? 'selected' : '' }}>Flat
                                    </option>
                                    <option value="progresive"
                                        {{ old('tariff_type', $asset->tariff_type) == 'progresive' ? 'selected' : '' }}>
                                        Progresive</option>
                                </select>
                            </div>
                            <div>
                                <label for="area"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Ukuran/Luas(m<sup>2</sup>)</label>
                                <input type="decimal" id="area" name="area"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ old('area', $asset->area) }}">
                            </div>
                            <div>
                                <label for="manager"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Pengelola</label>
                                <input type="text" id="manager" name="manager"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ old('manager', $asset->manager) }}">
                            </div>
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
                        <div class="general-asset-field">
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                Kondisi Aset
                            </label>
                            <select id="status" name="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                                onchange="updateStatusPreview()">
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

                        <div class="general-asset-field">
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

                        <div class="general-asset-field">
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

                <!-- Section Sub Asset (Dynamic) -->
                <div id="sub_assets_section" class="mb-8 pb-8 border-b" style="display: none;">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-boxes text-orange-600"></i> Detail Sub-Aset
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Karena jumlah aset lebih dari 1, mohon lengkapi detail untuk
                        masing-masing sub-aset di bawah ini.</p>
                    <div id="sub_assets_container" class="space-y-6">
                        <!-- Dynamic sub assets will be appended here -->
                    </div>
                </div>

                <!-- Section 3: Lokasi & Koordinat -->
                <div class="mb-8 pb-8 border-b">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-map-marker-alt text-red-600"></i> Lokasi & Koordinat GPS
                    </h3>

                    <div class="mb-6">
                        <input type="hidden" name="coordinates" id="coordinates"
                            value="{{ is_array($asset->coordinates) ? json_encode($asset->coordinates) : $asset->coordinates }}">
                        <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat/Deskripsi Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="location" name="location" value="{{ $asset->location }}"
                            placeholder="Contoh: Jalan Jend. Sudirman No. 123, Bukittinggi"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('location') border-red-500 @enderror">
                        @error('location')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                {{ $message }}</p>
                        @enderror

                        <div>
                            <label for="is_active" class="block text-sm font-semibold text-gray-700 mb-2">
                                Status Aktif <span class="text-red-500">*</span>
                            </label>
                            <select id="is_active" name="is_active"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('is_active') border-red-500 @enderror"
                                required>
                                <option value="1"
                                    {{ old('is_active', $asset->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0"
                                    {{ old('is_active', $asset->is_active ?? 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('is_active')
                                <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="latlng_wrapper">
                            <div>
                                <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Latitude <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="latitude" name="latitude"
                                    value="{{ old('latitude', $asset->latitude) }}" step="0.000001"
                                    placeholder="-6.2088"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('latitude') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1">Default: Bukittinggi</p>
                                @error('latitude')
                                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Longitude <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="longitude" name="longitude"
                                    value="{{ old('longitude', $asset->longitude) }}" step="0.000001"
                                    placeholder="106.8456"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('longitude') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1">Default: Bukittinggi</p>
                                @error('longitude')
                                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}</p>
                                @enderror
                            </div>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script>
        // Asset types data
        const assetTypes = @json($assetTypes);

        // Set initial latlng visibility
        window.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('asset_type_id');
            if (select && select.selectedIndex >= 0) {
                const geom = select.options[select.selectedIndex]?.dataset.geometry || 'point';
                document.getElementById('latlng_wrapper').style.display = (geom === 'point') ? 'grid' : 'none';
            }
        });

        // Update category preview
        function updateVehicleType() {
            const vType = document.getElementById('vehicle_type').value;
            const r2 = document.getElementById('r2_field');
            const r4 = document.getElementById('r4_field');
            if (vType === 'R2') {
                r2.style.display = 'block';
                r4.style.display = 'none';
            } else if (vType === 'R4') {
                r2.style.display = 'none';
                r4.style.display = 'block';
            } else if (vType === 'R2/R4') {
                r2.style.display = 'block';
                r4.style.display = 'block';
            } else {
                r2.style.display = 'none';
                r4.style.display = 'none';
            }
        }

        function updateCategoryPreview() {
            const select = document.getElementById('asset_type_id');
            if (select.selectedIndex === -1) return;
            const selectedOption = select.options[select.selectedIndex];

            const assetType = assetTypes.find(t => t.id == selectedOption.value);
            if (assetType) {
                document.getElementById('categoryIcon').innerHTML = `<i class="fas ${assetType.icon}"></i>`;
                document.getElementById('categoryIcon').style.color = assetType.color;
                document.getElementById('categoryName').textContent = assetType.name;
                document.getElementById('categoryDesc').textContent = assetType.description;

                // Show/hide fields based on category
                const isParking = assetType.asset_category === 'parking_asset';
                document.querySelectorAll('.general-asset-field').forEach(el => {
                    el.style.display = isParking ? 'none' : 'block';
                });
                document.getElementById('parking_asset_fields').style.display = isParking ? 'block' : 'none';
                if (isParking) updateVehicleType();

                // Show/hide lat/lng based on geometry
                const isPolygon = assetType.geometry === 'polygon';
                const isPolyline = assetType.geometry === 'polyline';
                const isPoint = !isPolygon && !isPolyline;
                document.getElementById('latlng_wrapper').style.display = isPoint ? 'grid' : 'none';

                // Update Leaflet Draw tool based on geometry
                if (drawControl && map) {
                    map.removeControl(drawControl);
                    const isPolygon = assetType.geometry === 'polygon';
                    const isPolyline = assetType.geometry === 'polyline';
                    const isPoint = !isPolygon && !isPolyline;

                    drawControl = new L.Control.Draw({
                        edit: {
                            featureGroup: drawnItems
                        },
                        draw: {
                            polygon: isPolygon,
                            polyline: isPolyline,
                            rectangle: false,
                            circle: false,
                            circlemarker: false,
                            marker: isPoint
                        }
                    });
                    map.addControl(drawControl);
                }
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

        let drawControl = null;
        let drawnItems = new L.FeatureGroup();

        function initializeMap() {
            if (map) return;

            const lat = parseFloat(document.getElementById('latitude').value) || -6.2088;
            const lng = parseFloat(document.getElementById('longitude').value) || 106.8456;

            map = L.map('mapPreview').setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            map.addLayer(drawnItems);

            // parse existing coordinates
            const coordsStr = document.getElementById('coordinates').value;
            let coords = [];
            try {
                if (coordsStr) coords = JSON.parse(coordsStr);
            } catch (e) {}

            const select = document.getElementById('asset_type_id');
            const geom = select.options[select.selectedIndex]?.dataset.geometry || 'point';

            if (coords && coords.length > 0) {
                if (geom === 'polygon' && coords.length >= 3) {
                    const polygon = L.polygon(coords).addTo(drawnItems);
                    map.fitBounds(polygon.getBounds());
                } else if (geom === 'polyline' && coords.length >= 2) {
                    const polyline = L.polyline(coords).addTo(drawnItems);
                    map.fitBounds(polyline.getBounds());
                } else {
                    marker = L.marker([lat, lng]).bindPopup('Lokasi Saat Ini');
                    drawnItems.addLayer(marker);
                }
            } else {
                marker = L.marker([lat, lng]).bindPopup('Lokasi Saat Ini');
                drawnItems.addLayer(marker);
            }

            // Configure draw control
            drawControl = new L.Control.Draw({
                edit: {
                    featureGroup: drawnItems
                },
                draw: {
                    polygon: geom === 'polygon',
                    polyline: geom === 'polyline',
                    rectangle: false,
                    circle: false,
                    circlemarker: false,
                    marker: geom === 'point'
                }
            });
            map.addControl(drawControl);

            map.on(L.Draw.Event.CREATED, function(event) {
                const layer = event.layer;
                const type = event.layerType;

                drawnItems.clearLayers();
                drawnItems.addLayer(layer);

                let newCoords = [];
                if (type === 'marker') {
                    const latlng = layer.getLatLng();
                    document.getElementById('latitude').value = latlng.lat.toFixed(6);
                    document.getElementById('longitude').value = latlng.lng.toFixed(6);
                    newCoords = [latlng.lat, latlng.lng];
                } else if (type === 'polygon' || type === 'polyline') {
                    const latlngs = layer.getLatLngs();
                    const flatLatLngs = type === 'polygon' ? latlngs[0] : latlngs;
                    newCoords = flatLatLngs.map(ll => [ll.lat, ll.lng]);

                    if (newCoords.length > 0) {
                        document.getElementById('latitude').value = newCoords[0][0].toFixed(6);
                        document.getElementById('longitude').value = newCoords[0][1].toFixed(6);
                    }
                }

                document.getElementById('coordinates').value = JSON.stringify(newCoords);
            });

            // Map click event - fallback for point
            map.on('click', function(e) {
                const geomCurrent = document.getElementById('asset_type_id').options[document.getElementById(
                    'asset_type_id').selectedIndex]?.dataset.geometry || 'point';
                if (geomCurrent !== 'point') return;

                const newLat = e.latlng.lat;
                const newLng = e.latlng.lng;

                // Update input fields
                document.getElementById('latitude').value = newLat.toFixed(6);
                document.getElementById('longitude').value = newLng.toFixed(6);
                document.getElementById('coordinates').value = JSON.stringify([parseFloat(newLat.toFixed(6)),
                    parseFloat(newLng.toFixed(6))
                ]);

                // Update marker position
                updateMarkerPosition(newLat, newLng);
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
            if (!map) return;

            map.setView([lat, lng], 13);

            // Only update marker if it's a point geometry
            const select = document.getElementById('asset_type_id');
            const geom = select.options[select.selectedIndex]?.dataset.geometry || 'point';

            if (geom === 'point') {
                drawnItems.clearLayers();
                marker = L.marker([lat, lng]).bindPopup('Lokasi Saat Ini');
                drawnItems.addLayer(marker);
            }
        }

        const existingSubAssets = @json($asset->subAssets ?? []);

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateCategoryPreview();
            initializeMap();

            // Initialize sub assets based on quantity
            const qtyInput = document.getElementById('quantity');
            if (qtyInput) {
                // Initial check
                handleQuantityChange(qtyInput.value, true);

                // Add event listener
                qtyInput.addEventListener('input', function() {
                    handleQuantityChange(this.value, false);
                });
            }
        });

        function handleQuantityChange(value, isInitial = false) {
            let qty = parseInt(value);
            const section = document.getElementById('sub_assets_section');
            const container = document.getElementById('sub_assets_container');

            if (isNaN(qty) || qty <= 1) {
                section.style.display = 'none';
                container.innerHTML = '';
            } else {
                section.style.display = 'block';
                renderSubAssets(qty, container, isInitial);
            }
        }

        function renderSubAssets(qty, container, isInitial) {
            const currentCount = container.children.length;
            if (qty > currentCount) {
                for (let i = currentCount; i < qty; i++) {
                    const existingData = existingSubAssets[i] ? existingSubAssets[i] : null;
                    const idInput = existingData ?
                        `<input type="hidden" name="sub_assets[${i}][id]" value="${existingData.id}">` : '';

                    const statusVal = existingData ? existingData.status : 'baik';
                    const descVal = existingData && existingData.description ? existingData.description : '';

                    let photoHtml = '';
                    if (existingData && existingData.photo_path) {
                        photoHtml =
                            `<div class="mb-2" id="preview_wrapper_${i}"><img id="preview_img_${i}" src="/storage/${existingData.photo_path}" class="h-24 rounded border object-cover"></div>`;
                    } else {
                        photoHtml =
                            `<div class="mb-2 hidden" id="preview_wrapper_${i}"><img id="preview_img_${i}" class="h-24 rounded border object-cover"></div>`;
                    }

                    const html = `
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 relative sub-asset-item">
                            ${idInput}
                            <h4 class="font-bold text-gray-700 mb-4">Sub Aset #${i + 1}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Sub-Aset</label>
                                    ${photoHtml}
                                    <input type="file" name="sub_assets[${i}][photo]" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white" onchange="previewSubAssetImage(this, ${i})">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                    <select name="sub_assets[${i}][status]" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white" required>
                                        <option value="baik" ${statusVal === 'baik' ? 'selected' : ''}>Baik</option>
                                        <option value="perlu_perbaikan" ${statusVal === 'perlu_perbaikan' ? 'selected' : ''}>Perlu Perbaikan</option>
                                        <option value="rusak" ${statusVal === 'rusak' ? 'selected' : ''}>Rusak</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi/Catatan</label>
                                    <textarea name="sub_assets[${i}][description]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white" placeholder="Contoh: Nomor seri, letak persis, dsb.">${descVal}</textarea>
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', html);
                }
            } else if (qty < currentCount) {
                for (let i = currentCount - 1; i >= qty; i--) {
                    container.removeChild(container.lastElementChild);
                }
            }
        }

        function previewSubAssetImage(input, index) {
            const previewWrapper = document.getElementById(`preview_wrapper_${index}`);
            const previewImg = document.getElementById(`preview_img_${index}`);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewWrapper.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                // If they cancel file selection, we might want to revert to existing photo.
                // For simplicity, we just clear the preview if there was no existing photo.
                // We'll leave it as is if they just cancel the dialog.
            }
        }
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
