@extends('layouts.app')

@section('title', 'Manajemen Permission - SIPETA-TRANS')

@section('page-title', 'Manajemen Permission')

@section('content')
    <!-- Header with Stats -->
    <div class="mb-8">
        <div class="grid grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <p class="text-gray-600 text-sm font-medium">Total Permission</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPermissions }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <p class="text-gray-600 text-sm font-medium">Dipakai di Role</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $permissionsAssignedToRoles }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                <p class="text-gray-600 text-sm font-medium">Total Assignment</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalAssignments }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                <p class="text-gray-600 text-sm font-medium">Belum Dipakai</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $permissionsUnassigned }}</p>
            </div>
        </div>
    </div>

    <!-- Permissions List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-key text-blue-500"></i> Daftar Permission
            </h3>
            <a href="{{ route('permissions.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:bg-blue-700 bg-blue-600 shadow hover:shadow-md">
                <i class="fas fa-plus"></i>
                <span>Tambah</span>
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('permissions.index') }}">
                @if($guards->count() > 1)
                {{-- 4-column layout: search(2) + guard(1) + status(1) --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Cari Nama Permission</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nama permission..."
                                class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Guard</label>
                        <select name="guard" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">Semua Guard</option>
                            @foreach($guards as $guard)
                                <option value="{{ $guard }}" {{ request('guard') === $guard ? 'selected' : '' }}>{{ $guard }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Status Penggunaan</label>
                        <select name="status" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">Semua Status</option>
                            <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Dipakai di Role</option>
                            <option value="unused" {{ request('status') === 'unused' ? 'selected' : '' }}>Belum Dipakai</option>
                        </select>
                    </div>
                </div>
                @else
                {{-- 3-column layout: search(2) + status(1) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Cari Nama Permission</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nama permission..."
                                class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Status Penggunaan</label>
                        <select name="status" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">Semua Status</option>
                            <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>Dipakai di Role</option>
                            <option value="unused" {{ request('status') === 'unused' ? 'selected' : '' }}>Belum Dipakai</option>
                        </select>
                    </div>
                </div>
                @endif

                <!-- Action Buttons + Result Info -->
                <div class="flex items-center justify-between mt-3">
                    <div class="flex gap-2">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                        @if(request()->hasAny(['search', 'guard', 'status']))
                            <a href="{{ route('permissions.index') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        @endif
                    </div>
                    @if(request()->hasAny(['search', 'guard', 'status']))
                        <span class="text-xs text-gray-500">
                            <span class="font-semibold text-gray-700">{{ $permissions->total() }}</span> hasil ditemukan
                            @if(request('search')) untuk &ldquo;<em>{{ request('search') }}</em>&rdquo;@endif
                        </span>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Permission</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Guard</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Dipakai</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($permissions as $permission)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ ($permissions->currentPage() - 1) * $permissions->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-key text-blue-600 text-lg"></i>
                                    </div>
                                    {{ $permission->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $permission->guard_name }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($permission->roles_count > 0)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full font-medium text-xs">
                                        {{ $permission->roles_count }} role
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full font-medium text-xs">
                                        0 role
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('permissions.show', $permission) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-white text-xs font-medium rounded-lg bg-blue-600 hover:bg-blue-700 transition">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('permissions.edit', $permission) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-white text-xs font-medium rounded-lg bg-yellow-500 hover:bg-yellow-600 transition">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('permissions.destroy', $permission) }}" method="POST"
                                        onsubmit="return confirm('Hapus permission ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-white text-xs font-medium rounded-lg bg-red-600 hover:bg-red-700 transition">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-inbox text-4xl opacity-30"></i>
                                    <p>Tidak ada data permission</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        

        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Menampilkan
                <span class="font-semibold text-gray-700">{{ $permissions->firstItem() ?? 0 }}&ndash;{{ $permissions->lastItem() ?? 0 }}</span>
                dari <span class="font-semibold text-gray-700">{{ $permissions->total() }}</span> permission
            </p>
            <div>{{ $permissions->links('pagination::tailwind') }}</div>
        </div>
    </div>
@endsection
