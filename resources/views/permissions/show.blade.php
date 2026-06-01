@extends('layouts.app')

@section('title', 'Detail Permission - SIPETA-TRANS')
@section('page-title', 'Detail Permission')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('permissions.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-6">
            <h1 class="text-3xl font-bold flex items-center gap-3">
                <i class="fas fa-key"></i> {{ $permission->name }}
            </h1>
            <p class="text-purple-100 mt-2">Detail informasi permission</p>
        </div>

        <div class="p-8">
            <!-- Info Grid -->
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                    <p class="text-xs text-gray-500 font-medium uppercase mb-1">ID</p>
                    <p class="font-bold text-gray-800 text-xl">{{ $permission->id }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 text-center">
                    <p class="text-xs text-blue-600 font-medium uppercase mb-1">Guard</p>
                    <p class="font-bold text-blue-800 font-mono">{{ $permission->guard_name }}</p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg border border-green-200 text-center">
                    <p class="text-xs text-green-600 font-medium uppercase mb-1">Dipakai Role</p>
                    <p class="font-bold text-green-800 text-xl">{{ $permission->roles->count() }}</p>
                </div>
            </div>

            <!-- Nama Permission -->
            <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-xs text-gray-500 font-medium uppercase mb-2">Nama Permission</p>
                <p class="font-bold text-gray-800 text-xl font-mono">{{ $permission->name }}</p>
            </div>

            <!-- Roles menggunakan permission ini -->
            <div class="mb-8">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-shield text-blue-500"></i>
                    Digunakan oleh Role ({{ $permission->roles->count() }})
                </h3>
                @if($permission->roles->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($permission->roles as $role)
                            <a href="{{ route('roles.show', $role) }}"
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 transition">
                                <i class="fas fa-user-shield text-xs"></i> {{ $role->name }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-500 italic text-sm text-center">Permission ini belum digunakan oleh role manapun.</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-6 border-t-2 border-gray-200">
                <a href="{{ route('permissions.edit', $permission) }}"
                    class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold flex items-center justify-center gap-2">
                    <i class="fas fa-edit"></i> Edit Permission
                </a>
                <form action="{{ route('permissions.destroy', $permission) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus permission \'{{ addslashes($permission->name) }}\'? Tindakan ini tidak dapat dibatalkan.')">
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
