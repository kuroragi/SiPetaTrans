@extends('layouts.app')

@section('title', 'Tambah Permission - SIPETA-TRANS')
@section('page-title', 'Tambah Permission Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('permissions.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-6">
            <h1 class="text-3xl font-bold flex items-center gap-3">
                <i class="fas fa-plus-circle"></i> Tambah Permission Baru
            </h1>
            <p class="text-green-100 mt-2">Buat permission baru untuk kontrol akses sistem</p>
        </div>

        <form action="{{ route('permissions.store') }}" method="POST" class="p-8">
            @csrf

            <!-- Nama Permission -->
            <div class="mb-8">
                <label for="name" class="block text-sm font-bold text-gray-700 mb-3">
                    <i class="fas fa-key text-blue-600 mr-2"></i> Nama Permission <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name"
                    class="w-full px-4 py-3 border-2 @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-blue-500 transition"
                    placeholder="Contoh: view assets"
                    value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
                <p class="text-gray-500 text-xs mt-2">
                    Gunakan format: <code class="bg-gray-100 px-1 py-0.5 rounded font-mono">action resource</code>
                    &mdash; contoh: <em>view assets</em>, <em>create users</em>, <em>delete roles</em>
                </p>
            </div>

            <!-- Guard Info -->
            <div class="mb-8 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                <p class="text-sm text-blue-900 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    Guard <strong class="font-mono">web</strong> akan digunakan secara otomatis.
                </p>
            </div>

            <!-- Preview -->
            <div class="mb-8 p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border-2 border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4">Preview Permission:</h3>
                <div class="flex items-center gap-4 p-4 bg-white rounded-lg">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-key text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p id="previewName" class="font-bold text-gray-800 text-lg">Nama Permission</p>
                        <span class="text-xs text-gray-500 font-mono bg-gray-100 px-2 py-0.5 rounded">guard: web</span>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t-2 border-gray-200">
                <button type="submit" class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Simpan Permission
                </button>
                <a href="{{ route('permissions.index') }}" class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-bold flex items-center justify-center gap-2">
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
        document.getElementById('previewName').textContent = this.value || 'Nama Permission';
    });
</script>
@endpush
