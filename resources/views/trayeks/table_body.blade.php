@forelse($trayeks as $index => $trayek)
    <tr class="hover:bg-gray-50 transition">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $trayeks->firstItem() + $index }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-bold text-gray-900">{{ $trayek->code }}</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900">{{ $trayek->name }}</div>
            <div class="text-xs text-gray-500">{{ $trayek->distance }} km</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                {{ $trayek->classification ? ucwords($trayek->classification) : '-' }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ $trayek->color ?? '-' }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @if($trayek->route_type == 'loop')
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Loop
                </span>
            @else
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    1 Way
                </span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <div class="flex gap-2">
                @can('edit trayeks')
                <a href="{{ route('trayeks.edit', $trayek->id) }}"
                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                @endcan
                @can('delete trayeks')
                <form action="{{ route('trayeks.destroy', $trayek->id) }}" method="POST"
                    class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus trayek ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endcan
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
            <div class="flex flex-col items-center justify-center">
                <i class="fas fa-route text-4xl mb-4 text-gray-300"></i>
                <p class="text-lg">Tidak ada data trayek ditemukan</p>
                <p class="text-sm">Coba sesuaikan filter pencarian atau tambah trayek baru</p>
            </div>
        </td>
    </tr>
@endforelse
