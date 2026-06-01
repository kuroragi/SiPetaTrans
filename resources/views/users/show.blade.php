@extends('layouts.app')

@section('title', 'Detail Pengguna - SIPETA-TRANS')
@section('page-title', 'Detail Pengguna')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-3xl">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                    <p class="text-purple-100 mt-1">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <div class="p-8">
            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-center">
                    <p class="text-xs text-gray-500 font-medium uppercase mb-1">ID</p>
                    <p class="font-bold text-gray-800 text-2xl">{{ $user->id }}</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 text-center">
                    <p class="text-xs text-blue-600 font-medium uppercase mb-1">Role</p>
                    <p class="font-bold text-blue-800 text-2xl">{{ $user->roles->count() }}</p>
                </div>
                <div class="p-4 bg-green-50 rounded-lg border border-green-200 text-center">
                    <p class="text-xs text-green-600 font-medium uppercase mb-1">Bergabung</p>
                    <p class="font-bold text-green-800 text-sm">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <!-- Info -->
            <div class="grid grid-cols-1 gap-4 mb-8">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 font-medium uppercase mb-1">Email</p>
                    <p class="font-semibold text-gray-800">{{ $user->email }}</p>
                    @if($user->email_verified_at)
                        <span class="inline-flex items-center gap-1 text-xs text-green-700 bg-green-100 px-2 py-0.5 rounded-full mt-1">
                            <i class="fas fa-check-circle"></i> Terverifikasi
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded-full mt-1">
                            <i class="fas fa-clock"></i> Belum Terverifikasi
                        </span>
                    @endif
                </div>
            </div>

            <!-- Roles -->
            <div class="mb-8">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-shield text-blue-500"></i>
                    Role ({{ $user->roles->count() }})
                </h3>
                @if($user->roles->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->roles as $role)
                            <a href="{{ route('roles.show', $role) }}"
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 transition">
                                <i class="fas fa-user-shield text-xs"></i> {{ $role->name }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-500 italic text-sm text-center">Pengguna ini belum memiliki role.</p>
                    </div>
                @endif
            </div>

            <!-- Permissions (gabungan dari semua role) -->
            @php
                $allPermissions = $user->roles->flatMap(fn($role) => $role->permissions)->unique('id')->sortBy('name');
            @endphp
            <div class="mb-8">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-key text-green-500"></i>
                    Permission Efektif ({{ $allPermissions->count() }})
                </h3>
                @if($allPermissions->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($allPermissions as $permission)
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium font-mono">
                                {{ $permission->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-500 italic text-sm text-center">Tidak ada permission efektif.</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-6 border-t-2 border-gray-200">
                <a href="{{ route('users.edit', $user) }}"
                    class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold flex items-center justify-center gap-2">
                    <i class="fas fa-edit"></i> Edit Pengguna
                </a>
                @if(Auth::id() !== $user->id)
                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus pengguna \'{{ addslashes($user->name) }}\'? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold flex items-center justify-center gap-2">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                @else
                    <div class="px-6 py-3 bg-gray-100 text-gray-500 rounded-lg font-bold flex items-center justify-center gap-2 cursor-not-allowed"
                        title="Tidak dapat menghapus akun sendiri">
                        <i class="fas fa-ban"></i> Hapus
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
