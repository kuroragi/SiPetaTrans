@extends('layouts.app')

@section('title', 'Peta Aset - SIPETA-TRANS')
@section('page-title', 'Peta Aset')

@section('content')
    <!-- Filter and Actions -->
    <div class="mb-6 flex justify-between items-center">
        <div class="flex gap-4 flex-1">
            <input type="text" id="search-input" placeholder="Cari aset..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select id="type-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Jenis</option>
                @foreach ($assetTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="baik">Baik</option>
                <option value="perlu_perbaikan">Perlu Perbaikan</option>
                <option value="rusak">Rusak</option>
                <option value="dalam_pemeliharaan">Dalam Pemeliharaan</option>
            </select>
        </div>
        <a href="{{ route('assets.create') }}"
            class="ml-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Aset
        </a>
    </div>

    <!-- Assets Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">No Registrasi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Nama Aset</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Lokasi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Nilai Aset</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Cara Perolehan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody id="asset-table-body" class="divide-y divide-gray-200">
                @include('assets.table_body')
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="pagination-container" class="mt-6">
        {{ $assets->links() }}
    </div>

    <!-- AJAX Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-input');
            const typeFilter = document.getElementById('type-filter');
            const statusFilter = document.getElementById('status-filter');
            const tableBody = document.getElementById('asset-table-body');
            const paginationContainer = document.getElementById('pagination-container');
            
            let debounceTimer;

            const fetchAssets = function (url = null) {
                const search = searchInput.value;
                const type = typeFilter.value;
                const status = statusFilter.value;

                // Simple loading animation
                tableBody.style.transition = 'opacity 0.3s ease';
                tableBody.style.opacity = '0.4';

                let targetUrl = url || '{{ route("assets.index") }}';
                
                // Mencegah error Mixed Content dengan memaksa https jika browser diakses via https
                if (window.location.protocol === 'https:') {
                    targetUrl = targetUrl.replace(/^http:\/\//i, 'https://');
                }
                
                // Build query string
                const urlObj = new URL(targetUrl);
                urlObj.searchParams.set('search', search);
                urlObj.searchParams.set('type', type);
                urlObj.searchParams.set('status', status);

                fetch(urlObj, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = data.html;
                    paginationContainer.innerHTML = data.pagination;
                    tableBody.style.opacity = '1';
                    
                    // Attach event listener for new pagination links
                    attachPaginationListeners();
                })
                .catch(error => {
                    console.error('Error fetching assets:', error);
                    tableBody.style.opacity = '1';
                });
            };

            const attachPaginationListeners = function () {
                const links = paginationContainer.querySelectorAll('a');
                links.forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        fetchAssets(this.href);
                    });
                });
            };

            // Event Listeners
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchAssets(), 400); // 400ms debounce
            });

            typeFilter.addEventListener('change', () => fetchAssets());
            statusFilter.addEventListener('change', () => fetchAssets());

            // Initial attachment
            attachPaginationListeners();
        });
    </script>
@endsection
