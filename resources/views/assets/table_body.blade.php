@forelse($assets as $key => $asset)
    <tr class="hover:bg-gray-50 transition">
        <td class="px-6 py-4 text-sm text-gray-700">{{ ($assets->currentPage() - 1) * $assets->perPage() + $key + 1 }}
        </td>
        <td class="px-6 py-4 text-sm font-medium text-gray-800 break-all">
            {{ $asset->registration_number }}
        </td>
        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $asset->name }}</td>
        <td class="px-6 py-4 text-sm text-gray-700">
            {{ $asset->type->name ?? '-' }}{{ $asset->subtype?->name ? ' - ' . $asset->subtype?->name : '' }}
        </td>
        <td class="px-6 py-4 text-sm">
            @php
                $statusClass = match ($asset->status) {
                    'baik' => 'bg-green-100 text-green-800',
                    'perlu_perbaikan' => 'bg-yellow-100 text-yellow-800',
                    'rusak' => 'bg-red-100 text-red-800',
                    'dalam_pemeliharaan' => 'bg-purple-100 text-purple-800',
                    default => 'bg-gray-100 text-gray-800',
                };
            @endphp
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
            </span>
        </td>
        <td class="px-6 py-4 text-sm text-gray-700">{{ $asset->location ?? '-' }}</td>
        <td class="px-6 py-4 text-sm text-gray-700">{{ $asset->current_value ?? '0' }}</td>
        <td class="px-6 py-4 text-sm text-gray-700">{{ $asset->acquisition_source ?? '-' }}</td>
        <td class="px-6 py-4 text-sm flex gap-2">
            <a href="{{ route('assets.show', $asset) }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('asset-depreciations.show', $asset) }}" class="text-yellow-600 hover:text-yellow-800"
                title="Penyusutan Nilai Asset">
                <i class="fas fa-angles-down"></i>
            </a>
            <a href="{{ route('assets.edit', $asset) }}" class="text-yellow-600 hover:text-yellow-800">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('assets.destroy', $asset) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin ingin menghapus?')"
                    class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
            <p>Tidak ada aset ditemukan</p>
        </td>
    </tr>
@endforelse
