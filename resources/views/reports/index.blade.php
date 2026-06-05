@extends('layouts.app')

@section('title', 'Laporan - SIPETA-TRANS')
@section('page-title', 'Cetak Laporan')

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

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Form Cetak Laporan -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-print text-blue-500"></i> Filter Laporan
        </h3>

        <form action="{{ route('reports.print') }}" method="POST" target="_blank" class="space-y-4">
            @csrf

            <!-- Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Per Tanggal</label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Laporan</label>
                <select name="type" id="report_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">-- Pilih Jenis Laporan --</option>
                    <option value="asset">Daftar Asset Transportasi</option>
                </select>
            </div>

            <!-- Asset Filters (Hidden by default) -->
            <div id="asset_filters" class="hidden space-y-4 border-l-4 border-blue-500 pl-4 py-2 mt-4 bg-gray-50 rounded-r-lg">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Aset (Opsional)</label>
                    <select name="assetType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        @isset($assetTypes)
                            @foreach($assetTypes as $aType)
                                <option value="{{ $aType->id }}">{{ $aType->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Kondisi (Opsional)</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="baik">Baik</option>
                        <option value="perlu_perbaikan">Perlu Perbaikan</option>
                        <option value="rusak">Rusak</option>
                        <option value="dalam_pemeliharaan">Dalam Pemeliharaan</option>
                    </select>
                </div>
            </div>

            <!-- Format -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Format Ekspor</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="format" value="pdf" class="text-blue-600 focus:ring-blue-500" checked required>
                        <span><i class="fas fa-file-pdf text-red-500"></i> Cetak PDF</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="format" value="excel" class="text-blue-600 focus:ring-blue-500" required>
                        <span><i class="fas fa-file-excel text-green-500"></i> Ekspor Excel</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full px-4 py-2.5 text-white font-medium rounded-lg transition-all duration-200 hover:bg-blue-700 bg-blue-600 shadow hover:shadow-md border-none cursor-pointer mt-4">
                <i class="fas fa-print mr-2"></i>
                <span>Proses Laporan</span>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('report_type');
        const assetFilters = document.getElementById('asset_filters');

        typeSelect.addEventListener('change', function() {
            if (this.value === 'asset') {
                assetFilters.classList.remove('hidden');
            } else {
                assetFilters.classList.add('hidden');
            }
        });
    });
</script>
@endpush
@endsection
