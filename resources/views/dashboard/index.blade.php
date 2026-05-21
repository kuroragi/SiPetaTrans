@extends('layouts.app')

@section('title', 'Dashboard - SIPETA-TRANS')
@section('page-title', 'Dashboard SIPETA-TRANS')

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-5 gap-6 mb-8">
        <!-- Total Asset Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">TOTAL ASET</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalAssets }}</p>
                    <p class="text-xs text-gray-400 mt-2">Semua Aset</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cube text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Baik Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">KONDISI BAIK</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $assetsByStatus['baik'] ?? 0 }}</p>
                    <p class="text-xs text-green-600 mt-2">{{ $goodPercentage }}%</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Perlu Perbaikan Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">PERLU PERBAIKAN</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $assetsByStatus['perlu_perbaikan'] ?? 0 }}</p>
                    <p class="text-xs text-yellow-600 mt-2">{{ $needsRepairPercentage }}%</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-yellow-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Rusak Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">RUSAK</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $assetsByStatus['rusak'] ?? 0 }}</p>
                    <p class="text-xs text-red-600 mt-2">{{ $damagedPercentage }}%</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Dalam Pemeliharaan Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">DALAM PEMELIHARAAN</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $assetsByStatus['dalam_pemeliharaan'] ?? 0 }}</p>
                    <p class="text-xs text-purple-600 mt-2">{{ $maintenancePercentage }}%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hammer text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Map Section -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <!-- Map -->
        <div class="col-span-2 bg-white rounded-lg shadow p-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">PETA SEBARAN ASET TRANSPORTASI WILAYAH BUKITTINGGI</h3>
                <!-- Filter by Asset Type -->
                <div class="flex gap-2 flex-wrap">
                    <button
                        class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 cursor-pointer filter-btn active"
                        data-filter="all">
                        ✓ Semua Aset
                    </button>
                    <button
                        class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 cursor-pointer filter-btn"
                        data-filter="type" data-type-id="0">
                        Transportasi
                    </button>
                    <button
                        class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 cursor-pointer filter-btn"
                        data-filter="status" data-status="baik">
                        Kondisi Baik
                    </button>
                    <button
                        class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 cursor-pointer filter-btn"
                        data-filter="status" data-status="rusak">
                        Rusak
                    </button>
                </div>
            </div>
            <div id="map" style="height: 420px; border-radius: 8px; position: relative;"></div>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">RINGKASAN KONDISI ASET</h3>
            <div style="height: 400px;">
                <canvas id="conditionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-2 gap-6 mb-8">
        <!-- Bar Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">GRAFIK KONDISI ASET PER JENIS</h3>
            <div style="height: 300px;">
                <canvas id="assetTypeChart"></canvas>
            </div>
        </div>

        <!-- Status by Type Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">ASET DENGAN KONDISI RUSAK</h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($damagedAssets as $asset)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $asset->name }}</p>
                            <p class="text-xs text-gray-500">{{ $asset->location }}</p>
                        </div>
                        <span class="px-3 py-1 bg-red-200 text-red-800 text-xs font-semibold rounded-full">Rusak</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Tidak ada aset yang rusak</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Updates -->
    <div class="grid grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Update Terakhir/ Data</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $lastUpdate }}</p>
                </div>
                <i class="fas fa-history text-blue-500 text-3xl opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Akurasi Data</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $dataAccuracy }}%</p>
                </div>
                <i class="fas fa-check-double text-green-500 text-3xl opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Petugas Aktif</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $activeUsers }}</p>
                </div>
                <i class="fas fa-user-tie text-purple-500 text-3xl opacity-20"></i>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet.markercluster@1.5.0/dist/leaflet.markercluster.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.0/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.0/dist/MarkerCluster.Default.css" />

    <script>
        // Initialize Map - Fokus pada Bukittinggi
        const map = new L.map('map', {
            center: new L.LatLng(-0.3050688169603624, 100.3694228690046),
            zoom: 13,
            zoomControl: true
        });

        L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add scale control
        L.control.scale().addTo(map);

        // Asset data
        const assets = @json($assets);
        const assetTypes = @json($assetTypes ?? []);

        // Status colors
        const statusColors = {
            'baik': '#22c55e',
            'perlu_perbaikan': '#f59e0b',
            'rusak': '#ef4444',
            'dalam_pemeliharaan': '#a855f7'
        };

        // Marker cluster group
        const markerClusterGroup = L.markerClusterGroup({
            maxClusterRadius: 60,
            iconCreateFunction: function(cluster) {
                const childCount = cluster.getChildCount();
                return L.divIcon({
                    html: `<div style="background-color: #3b82f6; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">${childCount}</div>`,
                    iconSize: [40, 40],
                    className: 'cluster'
                });
            }
        });

        // Store all markers for filtering
        const allMarkers = [];

        // Add asset markers
        assets.forEach(asset => {
            if (asset.latitude && asset.longitude) {
                const color = statusColors[asset.status] || '#3b82f6';
                const icon = asset.type?.icon || 'fa-cube';
                const typeName = asset.type?.name || 'Aset';

                // Create custom marker with Font Awesome icon
                const customMarker = L.divIcon({
                    html: `<div style="background-color: ${color}; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="fas ${icon}"></i></div>`,
                    iconSize: [32, 32],
                    className: 'custom-marker'
                });

                const marker = L.marker([asset.latitude, asset.longitude], {
                    icon: customMarker,
                    title: asset.name
                }).bindPopup(`
                <div style="min-width: 250px; font-size: 13px;">
                    <div style="margin-bottom: 8px;">
                        <strong style="font-size: 14px; color: #1f2937;">${asset.name}</strong>
                        <div style="color: #6b7280; font-size: 12px; margin-top: 2px;">
                            <i class="fas ${icon}" style="color: ${color}; margin-right: 4px;"></i> ${typeName}
                        </div>
                    </div>
                    <hr style="margin: 8px 0; border: none; border-top: 1px solid #e5e7eb;">
                    <div style="color: #4b5563; margin-bottom: 6px;">
                        <strong>Lokasi:</strong> ${asset.location || '-'}
                    </div>
                    <div style="color: #4b5563; margin-bottom: 6px;">
                        <strong>Kondisi:</strong> 
                        <span style="padding: 2px 8px; border-radius: 4px; background-color: ${color}20; color: ${color}; font-weight: 500;">
                            ${asset.status?.replace('_', ' ').toUpperCase()}
                        </span>
                    </div>
                    <div style="color: #4b5563; margin-bottom: 6px;">
                        <strong>Koordinat:</strong> ${asset.latitude.toFixed(5)}, ${asset.longitude.toFixed(5)}
                    </div>
                    ${asset.last_maintenance ? `<div style="color: #4b5563;"><strong>Pemeliharaan:</strong> ${new Date(asset.last_maintenance).toLocaleDateString('id-ID')}</div>` : ''}
                </div>
            `);

                marker.assetData = {
                    id: asset.id,
                    typeId: asset.asset_type_id,
                    status: asset.status,
                    name: asset.name
                };

                markerClusterGroup.addLayer(marker);
                allMarkers.push(marker);
            }
        });

        map.addLayer(markerClusterGroup);

        // Add map legend
        const legend = L.control({
            position: 'bottomright'
        });

        legend.onAdd = function(map) {
            const div = L.DomUtil.create('div', 'info-legend');
            div.style.cssText = `
            background: white;
            padding: 12px 15px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            font-size: 12px;
            font-family: Arial, sans-serif;
            max-width: 200px;
        `;

            const legendTitle =
                '<div style="font-weight: bold; margin-bottom: 8px; font-size: 13px; border-bottom: 2px solid #3b82f6; padding-bottom: 6px;">Kondisi Aset</div>';

            const legendItems = `
            ${legendTitle}
            <div style="display: flex; align-items: center; margin-bottom: 6px;">
                <div style="width: 16px; height: 16px; border-radius: 50%; background-color: #22c55e; margin-right: 8px; border: 1px solid #ddd;"></div>
                <span>Baik</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 6px;">
                <div style="width: 16px; height: 16px; border-radius: 50%; background-color: #f59e0b; margin-right: 8px; border: 1px solid #ddd;"></div>
                <span>Perlu Perbaikan</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 6px;">
                <div style="width: 16px; height: 16px; border-radius: 50%; background-color: #ef4444; margin-right: 8px; border: 1px solid #ddd;"></div>
                <span>Rusak</span>
            </div>
            <div style="display: flex; align-items: center;">
                <div style="width: 16px; height: 16px; border-radius: 50%; background-color: #a855f7; margin-right: 8px; border: 1px solid #ddd;"></div>
                <span>Dalam Pemeliharaan</span>
            </div>
        `;

            div.innerHTML = legendItems;
            return div;
        };

        legend.addTo(map);

        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filterType = this.dataset.filter;

                // Update active button styling
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active',
                    'bg-blue-100', 'text-blue-700'));
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.add('bg-gray-100',
                    'text-gray-700'));
                this.classList.add('active', 'bg-blue-100', 'text-blue-700');

                // Filter markers
                allMarkers.forEach(marker => {
                    if (filterType === 'all') {
                        marker.setOpacity(1);
                    } else if (filterType === 'status') {
                        const targetStatus = this.dataset.status;
                        marker.setOpacity(marker.assetData.status === targetStatus ? 1 : 0.2);
                    } else if (filterType === 'type') {
                        // Can be expanded for type filtering
                        marker.setOpacity(1);
                    }
                });
            });
        });

        // Fit map bounds to markers
        map.fitBounds(markerClusterGroup.getBounds().pad(0.1));

        // ==================== CHART SCRIPTS ====================

        // Condition Pie Chart
        const ctx = document.getElementById('conditionChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Baik', 'Perlu Perbaikan', 'Rusak', 'Dalam Pemeliharaan'],
                datasets: [{
                    data: [
                        {{ $assetsByStatus['baik'] ?? 0 }},
                        {{ $assetsByStatus['perlu_perbaikan'] ?? 0 }},
                        {{ $assetsByStatus['rusak'] ?? 0 }},
                        {{ $assetsByStatus['dalam_pemeliharaan'] ?? 0 }}
                    ],
                    backgroundColor: ['#22c55e', '#f59e0b', '#ef4444', '#a855f7'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 12
                            },
                            padding: 15
                        }
                    }
                }
            }
        });

        // Asset Type Bar Chart
        const assetTypeCtx = document.getElementById('assetTypeChart').getContext('2d');
        new Chart(assetTypeCtx, {
            type: 'bar',
            data: {
                labels: @json($assetTypeLabels),
                datasets: [{
                        label: 'Baik',
                        data: @json($assetTypeGood),
                        backgroundColor: '#22c55e'
                    },
                    {
                        label: 'Perlu Perbaikan',
                        data: @json($assetTypeNeedsRepair),
                        backgroundColor: '#f59e0b'
                    },
                    {
                        label: 'Rusak',
                        data: @json($assetTypeDamaged),
                        backgroundColor: '#ef4444'
                    },
                    {
                        label: 'Dalam Pemeliharaan',
                        data: @json($assetTypeMaintenance),
                        backgroundColor: '#a855f7'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: false
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endpush
