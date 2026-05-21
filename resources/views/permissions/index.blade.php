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
                            <td class="px-6 py-4 text-center">
                                <a href="#"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-lg transition-all duration-200 bg-blue-600 hover:bg-blue-700 shadow hover:shadow-md">
                                    <i class="fas fa-eye"></i>
                                    <span>Detail</span>
                                </a>
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

        <div class="p-6 border-t border-gray-200">
            {{ $permissions->links() }}
        </div>
    </div>
@endsection
