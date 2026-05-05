@extends('layouts.app')

@section('title', 'Daftar Anak')

@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Daftar Data Anak</h1>
            <p class="text-xl text-gray-500">Semua anak Anda di posyandu</p>
        </div>
        <a href="{{ route('children.create') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transition-all">
            <i class="fas fa-plus mr-2"></i>Tambah Anak
        </a>
    </div>

    @if($children->isEmpty())
        <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-300">
            <i class="fas fa-list text-7xl text-gray-400 mb-6"></i>
            <h3 class="text-2xl font-bold text-gray-600 mb-4">Belum Ada Data Anak</h3>
            <p class="text-gray-500 mb-8 max-w-lg mx-auto">Mulai daftarkan anak pertama Anda untuk pemantauan kesehatan.</p>
            <a href="{{ route('children.create') }}" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-12 py-4 rounded-2xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>Tambah Anak Pertama
            </a>
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-6 text-left text-gray-600 font-bold text-lg">Foto</th>
                            <th class="p-6 text-left text-gray-600 font-bold text-lg">Nama & Umur</th>
                            <th class="p-6 text-left text-gray-600 font-bold text-lg">Jenis Kelamin</th>
                            <th class="p-6 text-left text-gray-600 font-bold text-lg">Berat/Tinggi</th>
                            <th class="p-6 text-left text-gray-600 font-bold text-lg">Posyandu</th>
                            <th class="p-6 text-right text-gray-600 font-bold text-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($children as $child)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-6">
                                @if($child->foto_url)
                                    <img src="{{ $child->foto_url }}" alt="{{ $child->nama }}" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
                                @else
                                    <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-purple-400 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($child->nama, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td class="p-6 font-semibold text-gray-800">
                                <div class="font-bold text-lg">{{ $child->nama }}</div>
                                <div class="text-sm text-gray-500">{{ $child->umur_bulan }} bulan</div>
                            </td>
                            <td class="p-6">
                                <span class="px-4 py-1 bg-pink-100 text-pink-600 rounded-full text-sm font-bold">
                                    {{ $child->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </td>
                            <td class="p-6">
                                <div class="font-mono font-bold text-green-600">{{ $child->berat_badan ?? '-' }} kg</div>
                                <div class="font-mono text-blue-600">{{ $child->tinggi_badan ?? '-' }} cm</div>
                            </td>
                            <td class="p-6">
                                <div class="font-bold text-gray-700">{{ $child->posyandu->nama_posyandu }}</div>
                            </td>
                            <td class="p-6 text-right space-x-2">
                                <a href="{{ route('dashboard.kms') }}#child-{{ $child->id }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition-all inline-flex items-center space-x-1">
                                    <i class="fas fa-chart-line"></i><span>KMS</span>
                                </a>
                                <a href="{{ route('children.edit', $child->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-semibold transition-all inline-flex items-center space-x-1">
                                    <i class="fas fa-edit"></i><span>Edit</span>
                                </a>
                                <form action="{{ route('children.destroy', $child->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus {{ $child->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition-all inline-flex items-center space-x-1">
                                        <i class="fas fa-trash"></i><span>Hapus</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
