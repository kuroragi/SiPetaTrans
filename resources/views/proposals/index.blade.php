@extends('layouts.app')

@section('title', 'Daftar Usulan Masyarakat - SIPETA-TRANS')
@section('page-title', 'Daftar Usulan Masyarakat')

@section('content')
    <!-- Header with Stats -->
    <div class="mb-8">
        <div class="grid grid-cols-4 gap-6">
            <!-- Pending -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                <p class="text-gray-600 text-sm font-medium">Pending</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $counts['pending'] }}</p>
            </div>

            <!-- Ditindak Lanjuti -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <p class="text-gray-600 text-sm font-medium">Ditindak Lanjuti</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $counts['ditindak_lanjuti'] }}</p>
            </div>

            <!-- Ditolak -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                <p class="text-gray-600 text-sm font-medium">Ditolak</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $counts['ditolak'] }}</p>
            </div>

            <!-- Selesai -->
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <p class="text-gray-600 text-sm font-medium">Selesai</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $counts['selesai'] }}</p>
            </div>
        </div>
    </div>

    <!-- Proposals List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-list text-blue-500"></i> Daftar Usulan Masyarakat
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Pengusul</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jenis Permintaan</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($proposals as $proposal)
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'ditindak lanjuti' => 'bg-blue-100 text-blue-800',
                                'ditolak' => 'bg-red-100 text-red-800',
                                'selesai' => 'bg-green-100 text-green-800',
                            ];
                            $statusClass = $statusColors[$proposal->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $proposal->pengusul }}
                                <div class="text-xs text-gray-500">{{ $proposal->email_pengusul }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $proposal->tanggal->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $proposal->jenis_permintaan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $proposal->jumlah }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusClass }}">
                                    {{ ucfirst($proposal->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('proposals.show', $proposal) }}"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:bg-blue-700 bg-blue-600 shadow hover:shadow-md">
                                    <i class="fas fa-eye"></i>
                                    <span>Detail</span>
                                </a>
                                @if($proposal->status === 'ditindak lanjuti')
                                <form method="POST" action="{{ route('proposals.mark-completed', $proposal) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:bg-green-700 bg-green-600 shadow hover:shadow-md" onclick="return confirm('Selesaikan usulan dan lanjutkan ke penambahan aset?')">
                                        <i class="fas fa-check"></i>
                                        <span>Selesai</span>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i class="fas fa-inbox text-4xl opacity-30"></i>
                                    <p>Belum ada usulan masyarakat</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200">
            {{ $proposals->links() }}
        </div>
    </div>
@endsection
