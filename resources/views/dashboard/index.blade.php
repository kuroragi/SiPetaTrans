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

                <!-- Map Filter Bar -->
                <div class="map-filter-bar">
                    <div class="map-filter-group">
                        <label class="map-filter-label" for="filterCategory">
                            <i class="fas fa-layer-group"></i>
                            Kategori
                        </label>
                        <div class="map-select-wrapper">
                            <select id="filterCategory" class="map-select">
                                <option value="">Semua Kategori</option>
                                @foreach ($assetTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down map-select-arrow"></i>
                        </div>
                    </div>

                    <div class="map-filter-divider"></div>

                    <div class="map-filter-group">
                        <label class="map-filter-label" for="filterCondition">
                            <i class="fas fa-heartbeat"></i>
                            Kondisi
                        </label>
                        <div class="map-select-wrapper">
                            <select id="filterCondition" class="map-select">
                                <option value="">Semua Kondisi</option>
                                <option value="baik">Baik</option>
                                <option value="perlu_perbaikan">Perlu Perbaikan</option>
                                <option value="rusak">Rusak</option>
                                <option value="dalam_pemeliharaan">Dalam Pemeliharaan</option>
                            </select>
                            <i class="fas fa-chevron-down map-select-arrow"></i>
                        </div>
                    </div>

                    <button id="resetMapFilter" class="map-filter-reset" title="Reset Filter">
                        <i class="fas fa-rotate-left"></i>
                        Reset
                    </button>

                    <div class="map-filter-badge-wrap">
                        <span id="markerCount" class="map-marker-badge">0 aset</span>
                    </div>
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

    <style>
        /* ============================================================
                                       MAP FILTER BAR
                                    ============================================================ */
        .map-filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            background: linear-gradient(135deg, #f0f6ff 0%, #e8f0fe 100%);
            border: 1px solid #c7d9f8;
            border-radius: 12px;
            padding: 10px 16px;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.08);
        }

        .map-filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .map-filter-label {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 600;
            color: #4b68a8;
            white-space: nowrap;
            letter-spacing: 0.3px;
        }

        .map-filter-label i {
            font-size: 11px;
            color: #6b8cd4;
        }

        .map-select-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .map-select {
            appearance: none;
            -webkit-appearance: none;
            background: #ffffff;
            border: 1.5px solid #c7d9f8;
            border-radius: 8px;
            padding: 6px 32px 6px 12px;
            font-size: 13px;
            font-weight: 500;
            color: #1e3a6e;
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            min-width: 160px;
        }

        .map-select:hover {
            border-color: #6b9cde;
            background: #f7fbff;
        }

        .map-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            background: #fff;
        }

        .map-select-arrow {
            position: absolute;
            right: 10px;
            font-size: 10px;
            color: #6b8cd4;
            pointer-events: none;
            transition: transform 0.2s ease;
        }

        .map-select:focus~.map-select-arrow {
            transform: rotate(180deg);
        }

        .map-filter-divider {
            width: 1px;
            height: 32px;
            background: #c7d9f8;
            border-radius: 2px;
            flex-shrink: 0;
        }

        .map-filter-reset {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            border-radius: 8px;
            border: 1.5px solid #c7d9f8;
            background: #fff;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .map-filter-reset:hover {
            border-color: #ef4444;
            color: #ef4444;
            background: #fff5f5;
        }

        .map-filter-reset.active {
            border-color: #ef4444;
            color: #ef4444;
            background: #fff5f5;
        }

        .map-filter-reset i {
            font-size: 11px;
        }

        .map-filter-badge-wrap {
            margin-left: auto;
        }

        .map-marker-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
        }
    </style>

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

        // ==================== MAP FILTER (SELECT) ====================

        function applyMapFilter() {
            const selectedCategory = document.getElementById('filterCategory').value;
            const selectedCondition = document.getElementById('filterCondition').value;

            let visibleCount = 0;

            markerClusterGroup.clearLayers();

            allMarkers.forEach(marker => {
                const data = marker.assetData;

                const categoryMatch = !selectedCategory || String(data.typeId) === String(selectedCategory);
                const conditionMatch = !selectedCondition || data.status === selectedCondition;

                if (categoryMatch && conditionMatch) {
                    markerClusterGroup.addLayer(marker);
                    visibleCount++;
                }
            });

            document.getElementById('markerCount').textContent = visibleCount + ' aset';

            // Highlight reset button when filter is active
            const isFiltered = selectedCategory || selectedCondition;
            document.getElementById('resetMapFilter').classList.toggle('active', !!isFiltered);
        }

        document.getElementById('filterCategory').addEventListener('change', applyMapFilter);
        document.getElementById('filterCondition').addEventListener('change', applyMapFilter);

        document.getElementById('resetMapFilter').addEventListener('click', function() {
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterCondition').value = '';
            applyMapFilter();
        });

        // Init count
        applyMapFilter();

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
