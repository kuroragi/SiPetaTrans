@extends('layouts.app')

@section('title', 'Manajemen Role - SIPETA-TRANS')

@section('header', 'Manajemen Role')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Daftar Role</h2>
        <a href="{{ route('roles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Tambah Role</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse($role->permissions as $permission)
                                    <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded bg-green-100 text-green-800">
                                        {{ $permission->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-gray-400 italic">No permissions</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="#" class="text-indigo-600 hover:text-indigo-900"><i class="fas fa-edit"></i></a>
                            <a href="#" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data role.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $roles->links() }}
    </div>
</div>
@endsection