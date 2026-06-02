@extends('layouts.app')

@section('title', 'Tambah Role - SIPETA-TRANS')
@section('page-title', 'Tambah Role Baru')

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('roles.index') }}"
                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-6">
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <i class="fas fa-plus-circle"></i> Tambah Role Baru
                </h1>
                <p class="text-green-100 mt-2">Buat role baru dan tentukan permission yang dimilikinya</p>
            </div>

            <form action="{{ route('roles.store') }}" method="POST" class="p-8">
                @csrf

                <!-- Nama Role -->
                <div class="mb-8">
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fas fa-user-shield text-blue-600 mr-2"></i> Nama Role <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        class="w-full px-4 py-3 border-2 @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:border-blue-500 transition"
                        placeholder="Contoh: operator" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Permissions -->
                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        <i class="fas fa-key text-blue-600 mr-2"></i> Permissions
                    </label>
                    @error('permissions')
                        <p class="text-red-500 text-sm mb-3 flex items-center gap-1">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror

                    @if ($permissions->count() > 0)
                        <div class="border-2 border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-b border-gray-200">
                                <span class="text-sm text-gray-700 font-medium">
                                    Pilih permission yang dimiliki role ini:
                                </span>
                                <div class="flex gap-2">
                                    <button type="button" onclick="toggleAll(true)"
                                        class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Pilih Semua
                                    </button>
                                    <button type="button" onclick="toggleAll(false)"
                                        class="text-xs px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500 transition">
                                        Hapus Semua
                                    </button>
                                </div>
                            </div>
                            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-2 max-h-72 overflow-y-auto">
                                @foreach ($permissions as $permission)
                                    <label
                                        class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer border border-transparent hover:border-blue-200 transition">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            class="w-4 h-4 text-blue-600 rounded permission-check"
                                            {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                        <span
                                            class="text-sm font-medium text-gray-800 font-mono">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                            <p class="text-sm text-yellow-800">
                                Belum ada permission yang tersedia.
                                <a href="{{ route('permissions.create') }}" class="underline font-medium">Buat permission
                                    terlebih dahulu.</a>
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Preview -->
                <div class="mb-8 p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border-2 border-gray-200">
                    <h3 class="font-bold text-gray-800 mb-4">Preview Role:</h3>
                    <div class="flex items-center gap-4 p-4 bg-white rounded-lg">
                        <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center text-2xl">
                            <i class="fas fa-user-shield text-blue-600"></i>
                        </div>
                        <div>
                            <p id="previewName" class="font-bold text-gray-800 text-lg">Nama Role</p>
                            <p id="previewCount" class="text-sm text-gray-500">0 permission dipilih</p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t-2 border-gray-200">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Simpan Role
                    </button>
                    <a href="{{ route('roles.index') }}"
                        class="flex-1 px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-bold flex items-center justify-center gap-2">
                        <i class="fas fa-times-circle"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('name').addEventListener('input', function() {
            document.getElementById('previewName').textContent = this.value || 'Nama Role';
        });

        document.querySelectorAll('.permission-check').forEach(function(cb) {
            cb.addEventListener('change', updateCount);
        });

        function updateCount() {
            const count = document.querySelectorAll('.permission-check:checked').length;
            document.getElementById('previewCount').textContent = count + ' permission dipilih';
        }

        function toggleAll(checked) {
            document.querySelectorAll('.permission-check').forEach(function(cb) {
                cb.checked = checked;
            });
            updateCount();
        }

        updateCount();
    </script>
@endpush
