@extends('layouts.app')

@section('title', 'Pemeliharaan Aset - SIPETA-TRANS')
@section('page-title', 'Pemeliharaan Aset')

@section('content')
    <!-- Header with Stats -->
    <div class="mb-8">
        <div class="grid grid-cols-4 gap-6">
            <!-- Total Assets -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <p class="text-gray-600 text-sm font-medium">Total Aset</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">120</p>
            </div>

            <!-- Perlu Pemeliharaan -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                <p class="text-gray-600 text-sm font-medium">Perlu Pemeliharaan</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">15</p>
            </div>

            <!-- Sedang Dipelihara -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <p class="text-gray-600 text-sm font-medium">Sedang Dipelihara</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">5</p>
            </div>

            <!-- Selesai -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <p class="text-gray-600 text-sm font-medium">Selesai Dipelihara (Bulan Ini)</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">32</p>
            </div>
        </div>
    </div>

    <!-- Assets List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-wrench text-blue-500"></i> Daftar Pemeliharaan Aset
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Aset</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kategori</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Lokasi</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Terakhir Pemeliharaan</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Static Dummy Data -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-700">1</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-bus text-blue-600 text-lg"></i>
                                </div>
                                Halte Bus A
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Halte</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Jl. Sudirman</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="text-xs">
                                <div class="font-medium">12 Okt 2023</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">
                                Perlu Perbaikan
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="/asset-maintenance/create" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:bg-blue-700 bg-blue-600 shadow hover:shadow-md">
                                <i class="fas fa-plus"></i>
                                <span>Buat</span>
                            </a>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-700">2</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-road text-blue-600 text-lg"></i>
                                </div>
                                Jalan Protokol B
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">Jalan</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Jl. MH Thamrin</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="text-xs">
                                <div class="font-medium">05 Nov 2023</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">
                                Sedang Dipelihara
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="/asset-maintenance/create" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:bg-blue-700 bg-blue-600 shadow hover:shadow-md">
                                <i class="fas fa-plus"></i>
                                <span>Buat</span>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-6 bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
        <p class="text-sm font-medium text-blue-900 mb-2">💡 Catatan:</p>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Klik tombol <strong>Buat</strong> untuk membuat catatan pemeliharaan baru pada aset terkait.</li>
            <li>• Pastikan jadwal pemeliharaan rutin terdata dengan baik.</li>
        </ul>
    </div>
@endsection
