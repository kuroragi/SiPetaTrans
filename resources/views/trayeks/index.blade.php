@extends('layouts.app')

@section('title', 'Manajemen Trayek - SIPETA-TRANS')
@section('page-title', 'Manajemen Trayek')

@section('content')
    <!-- Filter and Actions -->
    <div class="mb-6 flex justify-between items-center">
        <div class="flex gap-4 flex-1">
            <input type="text" id="search-input" placeholder="Cari rute trayek (nama/kode)..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select id="classification-filter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Klasifikasi</option>
                @foreach ($classifications as $class)
                    <option value="{{ $class }}">{{ ucwords($class) }}</option>
                @endforeach
            </select>
        </div>
        @can('create trayeks')
        <a href="{{ route('trayeks.create') }}"
            class="ml-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Trayek
        </a>
        @endcan
    </div>

    <!-- Trayeks Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">No</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Nama Rute</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Klasifikasi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Warna</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Tipe Rute</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody id="trayek-table-body" class="divide-y divide-gray-200">
                @include('trayeks.table_body')
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="pagination-container" class="mt-6">
        {{ $trayeks->links() }}
    </div>

    <!-- AJAX Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const classificationFilter = document.getElementById('classification-filter');
            const tableBody = document.getElementById('trayek-table-body');
            const paginationContainer = document.getElementById('pagination-container');

            let debounceTimer;

            const fetchTrayeks = function(url = null) {
                const search = searchInput.value;
                const classification = classificationFilter.value;

                tableBody.style.transition = 'opacity 0.3s ease';
                tableBody.style.opacity = '0.4';

                let targetUrl = url || '{{ route('trayeks.index') }}';

                if (window.location.protocol === 'https:') {
                    targetUrl = targetUrl.replace(/^http:\/\//i, 'https://');
                }

                const urlObj = new URL(targetUrl);
                urlObj.searchParams.set('search', search);
                urlObj.searchParams.set('classification', classification);

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
                        attachPaginationListeners();
                    })
                    .catch(error => {
                        console.error('Error fetching trayeks:', error);
                        tableBody.style.opacity = '1';
                    });
            };

            const attachPaginationListeners = function() {
                const links = paginationContainer.querySelectorAll('a');
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        fetchTrayeks(this.href);
                    });
                });
            };

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchTrayeks(), 400);
            });

            classificationFilter.addEventListener('change', () => fetchTrayeks());

            attachPaginationListeners();
        });
    </script>
@endsection
