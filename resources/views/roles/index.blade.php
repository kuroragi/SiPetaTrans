@extends('layouts.app')

@section('title', 'Manajemen Role - SIPETA-TRANS')

@section('page-title', 'Manajemen Role')

@section('content')
    <!-- Header with Stats -->
    <div class="mb-8">
        <div class="grid grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <p class="text-gray-600 text-sm font-medium">Total Role</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalRoles }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <p class="text-gray-600 text-sm font-medium">Role dengan Permission</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $rolesWithPermissions }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                <p class="text-gray-600 text-sm font-medium">Total Permission</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPermissions }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                <p class="text-gray-600 text-sm font-medium">Tanpa Permission</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $rolesWithoutPermissions }}</p>
            </div>
        </div>
    </div>

    <!-- Roles List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-user-shield text-blue-500"></i> Daftar Role
            </h3>
            <a href="{{ route('roles.create') }}"
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
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Role</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jumlah Permission</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Preview</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($roles as $role)
                        @php
                            $preview = $role->permissions->take(4);
                            $remaining = max(0, ($role->permissions_count ?? $role->permissions->count()) - $preview->count());
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-shield text-blue-600 text-lg"></i>
                                    </div>
                                    {{ $role->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-medium text-xs">
                                    {{ $role->permissions_count ?? $role->permissions->count() }} permission
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($preview as $permission)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded font-semibold text-xs">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-500 italic text-xs">Tanpa permission</span>
                                    @endforelse
                                    @if($remaining > 0)
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded font-semibold text-xs">
                                            +{{ $remaining }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('roles.show', $role) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-white text-xs font-medium rounded-lg bg-blue-600 hover:bg-blue-700 transition">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('roles.edit', $role) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-white text-xs font-medium rounded-lg bg-yellow-500 hover:bg-yellow-600 transition">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                        onsubmit="return confirm('Hapus role ini?')">
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
                                    <p>Tidak ada data role</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-200">
            {{ $roles->links() }}
        </div>
    </div>
@endsection
