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

        <form action="{{ route('reports.print') }}" method="POST" class="space-y-4">
            @csrf

            <!-- From Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="from" value="{{ date('Y-m-01') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- To Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ date('Y-m-t') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Laporan</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">-- Pilih Jenis Laporan --</option>
                    <option value="asset">Daftar Asset Transportasi</option>
                    <!-- Anda bisa menambahkan jenis laporan lainnya di sini -->
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full px-4 py-2.5 text-white font-medium rounded-lg transition-all duration-200 hover:bg-blue-700 bg-blue-600 shadow hover:shadow-md border-none cursor-pointer mt-4">
                <i class="fas fa-print mr-2"></i>
                <span>Cetak / Ekspor Laporan</span>
            </button>
        </form>
    </div>
</div>
@endsection
