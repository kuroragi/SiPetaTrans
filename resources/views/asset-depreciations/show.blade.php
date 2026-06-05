@extends('layouts.app')

@section('title', 'Penyusutan Nilai Aset - ' . $asset->name . ' - SIPETA-TRANS')
@section('page-title', 'Penyusutan Nilai Aset')

@section('content')
<!-- Alert Messages -->
@if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800 font-semibold mb-2">Terjadi Kesalahan:</p>
        <ul class="text-red-700 text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4 text-green-800">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
@endif

<!-- Asset Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg shadow-lg p-6 mb-6">
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                <i class="fas {{ $asset->type->icon ?? 'fa-box' }} text-3xl" style="color: {{ $asset->type->color ?? '#fff' }}"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold">{{ $asset->name }}</h2>
                <p class="text-blue-100">
                    <i class="fas fa-tag mr-1"></i> {{ $asset->type->name ?? '-' }}
                    <span class="mx-2">•</span>
                    <i class="fas fa-money-bill-wave mr-1"></i> Nilai Saat Ini: Rp {{ number_format($asset->current_value, 0, ',', '.') }}
                </p>
                <p class="text-blue-100 text-sm mt-1">
                    Nilai Perolehan: Rp {{ number_format($asset->acquisition_value, 0, ',', '.') }} | Tanggal Perolehan: {{ $asset->acquired_at ? \Carbon\Carbon::parse($asset->acquired_at)->format('d M Y') : '-' }}
                </p>
            </div>
        </div>
        <a href="{{ route('assets.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-white font-medium rounded-lg transition-all duration-200 hover:bg-gray-700 bg-gray-600 shadow hover:shadow-md">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-3 gap-6 mb-6">
    <!-- Form Tambah Penyusutan -->
    <div class="col-span-1 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-plus-circle text-blue-500"></i> Tambah Penyusutan
        </h3>

        <form action="{{ route('asset-depreciations.store', $asset) }}" method="POST" id="form-depreciation" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penyusutan</label>
                <input type="date" name="depreciation_date" id="depreciation_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Setelah Penyusutan (Rp)</label>
                <input type="number" name="value" id="depreciation_value" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: 5000000" min="0" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Catatan penyusutan..."></textarea>
            </div>

            <button type="button" onclick="showConfirmModal()" class="w-full px-4 py-2.5 text-white font-medium rounded-lg transition-all duration-200 hover:bg-blue-700 bg-blue-600 shadow hover:shadow-md border-none cursor-pointer">
                <i class="fas fa-save mr-2"></i>
                <span>Simpan Penyusutan</span>
            </button>
        </form>
    </div>

    <!-- History Timeline -->
    <div class="col-span-2 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-history text-blue-500"></i> Histori Penyusutan
        </h3>

        @if($depreciations->count() > 0)
            <div class="space-y-6 max-h-96 overflow-y-auto">
                @foreach($depreciations as $dep)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-bold text-gray-800 text-lg">Rp {{ number_format($dep->value, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-600"><i class="fas fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($dep->depreciation_date)->format('d M Y') }}</p>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="openEditModal({{ $dep->id }}, '{{ $dep->depreciation_date->format('Y-m-d') }}', {{ $dep->value }}, '{{ addslashes($dep->notes) }}')" class="text-yellow-600 hover:bg-yellow-100 p-2 rounded transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('asset-depreciations.destroy', [$asset, $dep]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Peringatan: Menghapus data ini dapat mengubah nilai aset saat ini. Anda yakin ingin menghapus?')" class="text-red-600 hover:bg-red-100 p-2 rounded transition" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if($dep->notes)
                            <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded italic mt-2">
                                {{ $dep->notes }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-folder-open text-4xl opacity-30 mb-2"></i>
                <p>Belum ada histori penyusutan untuk aset ini</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Tambah -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-yellow-500"></i> Konfirmasi Penyusutan
            </h3>
            <p class="text-gray-700 mb-4">
                Apakah Anda yakin ingin menambahkan penyusutan ini? <br><br>
                <strong>Catatan:</strong> Data nilai saat ini (Current Value) pada aset bisa saja berubah dengan penyusutan ini.
            </p>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Batal</button>
                <button type="button" onclick="submitForm()" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">Ya, Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <form id="editForm" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-edit text-yellow-500"></i> Edit Penyusutan
            </h3>
            
            <p class="text-sm text-yellow-600 mb-4 bg-yellow-50 p-2 rounded">
                <i class="fas fa-info-circle"></i> Mengubah data ini dapat memengaruhi nilai saat ini dari aset.
            </p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Penyusutan</label>
                    <input type="date" name="depreciation_date" id="edit_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai (Rp)</label>
                    <input type="number" name="value" id="edit_value" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" id="edit_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Create Confirm Modal
    function showConfirmModal() {
        const dateVal = document.getElementById('depreciation_date').value;
        const valVal = document.getElementById('depreciation_value').value;
        
        if(!dateVal || !valVal) {
            alert('Harap isi Tanggal dan Nilai Penyusutan!');
            return;
        }
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    function submitForm() {
        document.getElementById('form-depreciation').submit();
    }

    // Edit Modal
    function openEditModal(id, date, value, notes) {
        const form = document.getElementById('editForm');
        // Set action URL dynamically
        form.action = `/assets/{{ $asset->id }}/depreciations/${id}`;
        
        document.getElementById('edit_date').value = date;
        document.getElementById('edit_value').value = value;
        document.getElementById('edit_notes').value = notes;
        
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endpush
