@extends('layouts.app')

@section('title', 'Detail Role - SIPETA-TRANS')
@section('page-title', 'Detail Role')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('roles.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-6">
            <h1 class="text-3xl font-bold flex items-center gap-3">
                <i class="fas fa-user-shield"></i> {{ $role->name }}
            </h1>
            <p class="text-purple-100 mt-2">Detail informasi role</p>
        </div>

        <div class="p-8">
            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                    <p class="text-xs text-gray-500 font-medium uppercase mb-1">ID</p>
                    <p class="font-bold text-gray-800 text-2xl">{{ $role->id }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 text-center">
                    <p class="text-xs text-blue-600 font-medium uppercase mb-1">Permission</p>
                    <p class="font-bold text-blue-800 text-2xl">{{ $role->permissions->count() }}</p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg border border-green-200 text-center">
                    <p class="text-xs text-green-600 font-medium uppercase mb-1">Pengguna</p>
                    <p class="font-bold text-green-800 text-2xl">{{ $role->users->count() }}</p>
                </div>
            </div>

            <!-- Guard Info -->
            <div class="mb-8 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                <p class="text-sm text-blue-900 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    Guard: <strong class="font-mono ml-1">{{ $role->guard_name }}</strong>
                </p>
            </div>

            <!-- Permissions -->
            <div class="mb-8">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-key text-blue-500"></i>
                    Permissions ({{ $role->permissions->count() }})
                </h3>
                @if($role->permissions->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($role->permissions as $permission)
                            <a href="{{ route('permissions.show', $permission) }}"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-medium font-mono hover:bg-green-200 transition">
                                <i class="fas fa-key text-xs"></i> {{ $permission->name }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-500 italic text-sm text-center">Role ini tidak memiliki permission.</p>
                    </div>
                @endif
            </div>

            <!-- Pengguna dengan role ini -->
            <div class="mb-8">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-users text-green-500"></i>
                    Pengguna dengan Role ini ({{ $role->users->count() }})
                </h3>
                @if($role->users->count() > 0)
                    <div class="flex flex-col gap-2">
                        @foreach($role->users as $user)
                            <a href="{{ route('users.show', $user) }}"
                                class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition border border-transparent hover:border-blue-200">
                                <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-500 italic text-sm text-center">Belum ada pengguna dengan role ini.</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-6 border-t-2 border-gray-200">
                <a href="{{ route('roles.edit', $role) }}"
                    class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold flex items-center justify-center gap-2">
                    <i class="fas fa-edit"></i> Edit Role
                </a>
                <form action="{{ route('roles.destroy', $role) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus role \'{{ addslashes($role->name) }}\'? Pengguna yang memiliki role ini akan kehilangan aksesnya.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
