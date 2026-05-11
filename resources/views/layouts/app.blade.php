<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPETA-TRANS - Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white shadow-lg">
            <!-- Logo -->
            <div class="p-6 border-b border-blue-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center">
                        <i class="fas fa-map-location-dot text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">SIPETA-TRANS</h1>
                        <p class="text-xs text-blue-200">Asset Management</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-8 px-4">
                <div class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ Route::is('dashboard') ? 'bg-blue-600' : 'hover:bg-blue-700' }} transition">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('assets.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ Route::is('assets.*') ? 'bg-blue-600' : 'hover:bg-blue-700' }} transition">
                        <i class="fas fa-map w-5"></i>
                        <span>Peta Aset</span>
                    </a>
                    <a href="{{ route('asset-types.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ Route::is('asset-types.*') ? 'bg-blue-600' : 'hover:bg-blue-700' }} transition">
                        <i class="fas fa-palette w-5"></i>
                        <span>Kelola Kategori</span>
                    </a>
                    <a href="{{ route('assets.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus-circle w-5"></i>
                        <span>Input Aset Baru</span>
                    </a>
                    <a href="{{ route('asset-monitoring.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ Route::is('asset-monitoring.*') ? 'bg-blue-600' : 'hover:bg-blue-700' }} transition">
                        <i class="fas fa-camera w-5"></i>
                        <span>Monitoring Kondisi</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-wrench w-5"></i>
                        <span>Pemeliharaan</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-file-pdf w-5"></i>
                        <span>Laporan</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-bell w-5"></i>
                        <span>Notifikasi</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-sliders w-5"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-users w-5"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                </div>
            </nav>

            <!-- User Info -->
            <div class="absolute bottom-0 w-64 p-4 border-t border-blue-700">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">{{ Auth::user()->name ?? 'Guest' }}</p>
                        <p class="text-xs text-blue-200 truncate">Admin</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-b">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-bell text-gray-600 text-xl"></i>
                        </button>
                        <div class="h-8 w-px bg-gray-200"></div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">{{ date('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="flex-1 overflow-auto">
                <div class="p-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
