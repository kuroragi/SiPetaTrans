@extends('layouts.app')

@section('title', 'Edit Pengguna - SIPETA-TRANS')
@section('page-title', 'Edit Pengguna')

@section('content')
@php
    $userRoleIds = $user->roles->pluck('id')->toArray();
@endphp
<div class="max-w-3xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-6">
            <h1 class="text-3xl font-bold flex items-center gap-3">
                <i class="fas fa-user-edit"></i> Edit Pengguna
            </h1>
            <p class="text-blue-100 mt-2">Perbarui informasi pengguna: <strong>{{ $user->name }}</strong></p>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')

            <!-- Info Saat Ini -->
            <div class="mb-8 p-6 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border-2 border-purple-200">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-circle-info"></i> Informasi Saat Ini
                </h3>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 text-xl"></i>
                    </div>
                    <div class="text-sm">
                        <p class="font-bold text-gray-800 text-base">{{ $user->name }}</p>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @forelse($user->roles as $role)
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ $role->name }}</span>
                            @empty
                                <span class="text-xs text-gray-400 italic">Tanpa role</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nama & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fas fa-user text-blue-600 mr-2"></i> Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        class="w-full px-4 py-3 border-2 @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-blue-500 transition"
                        placeholder="Nama lengkap pengguna"
                        value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fas fa-envelope text-blue-600 mr-2"></i> Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-3 border-2 @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-blue-500 transition"
                        placeholder="email@contoh.com"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Password (Opsional) -->
            <div class="mb-8 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                <p class="text-sm text-yellow-800 font-medium flex items-center gap-2 mb-3">
                    <i class="fas fa-lock"></i> Ubah Password (Opsional)
                </p>
                <p class="text-xs text-yellow-700 mb-4">Biarkan kosong jika tidak ingin mengubah password.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 mb-2">Password Baru</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-3 border-2 @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-blue-500 transition bg-white"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition bg-white"
                            placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <!-- Roles -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-3">
                    <i class="fas fa-user-shield text-blue-600 mr-2"></i> Role
                </label>
                @error('roles')
                    <p class="text-red-500 text-sm mb-3 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror

                @if($roles->count() > 0)
                    <div class="border-2 border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <span class="text-sm text-gray-700 font-medium">Pilih role untuk pengguna ini:</span>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($roles as $role)
                                @php
                                    $defaultChecked = in_array($role->id, old('roles', $userRoleIds));
                                @endphp
                                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer border border-transparent hover:border-blue-200 transition">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        class="w-4 h-4 text-blue-600 rounded role-check"
                                        {{ $defaultChecked ? 'checked' : '' }}>
                                    <div>
                                        <span class="text-sm font-medium text-gray-800">{{ $role->name }}</span>
                                        <span class="text-xs text-gray-400 ml-1">({{ $role->permissions_count }} permission)</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <p class="text-sm text-yellow-800">Belum ada role yang tersedia.</p>
                    </div>
                @endif
            </div>

            <!-- Preview -->
            <div class="mb-8 p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border-2 border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4">Preview Perubahan:</h3>
                <div class="flex items-center gap-4 p-4 bg-white rounded-lg">
                    <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center text-2xl">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <p id="previewName" class="font-bold text-gray-800 text-lg">{{ old('name', $user->name) }}</p>
                        <p id="previewEmail" class="text-sm text-gray-500">{{ old('email', $user->email) }}</p>
                        <p id="previewRoles" class="text-xs text-blue-600 mt-1">Memuat...</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t-2 border-gray-200">
                <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('users.index') }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-bold flex items-center justify-center gap-2">
                    <i class="fas fa-times-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('name').addEventListener('input', function () {
        document.getElementById('previewName').textContent = this.value || '{{ addslashes($user->name) }}';
    });

    document.getElementById('email').addEventListener('input', function () {
        document.getElementById('previewEmail').textContent = this.value || '{{ addslashes($user->email) }}';
    });

    document.querySelectorAll('.role-check').forEach(function (cb) {
        cb.addEventListener('change', updateRoles);
    });

    function updateRoles() {
        const labels = [...document.querySelectorAll('.role-check:checked')].map(function (cb) {
            return cb.closest('label').querySelector('span').textContent.trim();
        });
        document.getElementById('previewRoles').textContent = labels.length ? labels.join(', ') : 'Belum ada role dipilih';
    }

    updateRoles();
</script>
@endpush
