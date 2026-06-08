@extends('layouts.app')

@section('title', 'Buat Pemeliharaan Aset - SIPETA-TRANS')
@section('page-title', 'Form Pemeliharaan Aset')

@section('content')
<!-- Asset Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg shadow-lg p-6 mb-6">
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas fa-bus text-3xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold">Halte Bus A</h2>
                <p class="text-blue-100">
                    <i class="fas fa-tag mr-1"></i> Halte
                    <span class="mx-2">•</span>
                    <i class="fas fa-map-marker-alt mr-1"></i> Jl. Sudirman
                </p>
            </div>
        </div>
        <a href="/asset-maintenance" class="inline-flex items-center gap-2 px-4 py-2 text-white font-medium rounded-lg transition-all duration-200 hover:bg-gray-700 bg-gray-600 shadow hover:shadow-md">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-3 gap-6 mb-6">
    <!-- Form Pemeliharaan -->
    <div class="col-span-1 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-wrench text-blue-500"></i> Buat Data Pemeliharaan
        </h3>

        <form action="{{ route('asset-maintenance.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Pilih Aset -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Aset</label>
                <select name="asset_id" id="asset_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">-- Pilih Aset --</option>
                    @if(isset($assets))
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" {{ (isset($selectedAsset) && $selectedAsset->id == $asset->id) ? 'selected' : '' }} data-status="{{ $asset->status }}">
                                {{ $asset->name }} - {{ $asset->registration_number }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Tipe Pemeliharaan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pemeliharaan</label>
                <select name="maintenance_type" id="maintenance_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="rutin">Rutin</option>
                    <option value="perbaikan" id="option_perbaikan" style="display: none;">Perbaikan Kerusakan</option>
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
                <input type="date" name="start_date" value="2023-11-01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
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
            <div class="border-l-4 border-blue-500 pl-4">
                <!-- Date Header -->
                <div class="mb-3 pb-2 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-tools text-blue-600"></i>
                            <span class="font-semibold text-gray-800">15 Jan 2023</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-semibold rounded bg-green-100 text-green-800">
                            Rutin - Selesai
                        </span>
                    </div>
                </div>
                <!-- Content -->
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-sm text-gray-800 font-medium">Pengecatan ulang halte dan perbaikan atap bocor.</p>
                    <p class="text-xs text-gray-500 mt-1">Biaya: Rp 500.000</p>
                </div>
            </div>
            
            <div class="border-l-4 border-blue-500 pl-4">
                <!-- Date Header -->
                <div class="mb-3 pb-2 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-tools text-blue-600"></i>
                            <span class="font-semibold text-gray-800">02 Mar 2022</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-semibold rounded bg-green-100 text-green-800">
                            Perbaikan - Selesai
                        </span>
                    </div>
                </div>
                <!-- Content -->
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-sm text-gray-800 font-medium">Perbaikan kursi tunggu yang patah.</p>
                    <p class="text-xs text-gray-500 mt-1">Biaya: Rp 200.000</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Asset Info (Static) -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h4 class="text-lg font-semibold text-gray-800 mb-4">Informasi Aset</h4>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
            <p class="text-sm text-gray-600">Nama Aset</p>
            <p class="font-medium text-gray-800">Halte Bus A</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Kategori</p>
            <p class="font-medium text-gray-800">Halte</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Lokasi</p>
            <p class="font-medium text-gray-800">Jl. Sudirman</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Kondisi Saat Ini</p>
            <p class="px-3 py-1 rounded inline-block font-medium bg-red-100 text-red-800 text-sm mt-1">
                Perlu Perbaikan
            </p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const assetSelect = document.getElementById('asset_id');
        const perbaikanOption = document.getElementById('option_perbaikan');
        const maintenanceTypeSelect = document.getElementById('maintenance_type');
        
        function updateOptions() {
            const selectedOption = assetSelect.options[assetSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.status === 'perlu_perbaikan') {
                perbaikanOption.style.display = '';
            } else {
                perbaikanOption.style.display = 'none';
                if (maintenanceTypeSelect.value === 'perbaikan') {
                    maintenanceTypeSelect.value = '';
                }
            }
        }
        
        assetSelect.addEventListener('change', updateOptions);
        updateOptions(); // Initial check
    });
</script>
@endpush
