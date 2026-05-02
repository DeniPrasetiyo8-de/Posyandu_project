@extends('layouts.app')

@section('title', 'Daftar Anak')

@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">Daftar Data Anak</h1>
            <p class="text-xl text-slate-400">Semua anak Anda di posyandu</p>
        </div>
        <a href="{{ route('children.create') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-8 py-4 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transition-all">
            <i class="fas fa-plus mr-2"></i>Tambah Anak
        </a>
    </div>

    @if($children->isEmpty())
        <div class="text-center py-20 bg-slate-800/30 rounded-3xl border-2 border-dashed border-slate-600">
            <i class="fas fa-list text-7xl text-slate-500 mb-6"></i>
            <h3 class="text-2xl font-bold text-slate-300 mb-4">Belum Ada Data Anak</h3>
            <p class="text-slate-500 mb-8 max-w-lg mx-auto">Mulai daftarkan anak pertama Anda untuk pemantauan kesehatan.</p>
            <a href="{{ route('children.create') }}" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-12 py-4 rounded-2xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>Tambah Anak Pertama
            </a>
        </div>
    @else
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-3xl border border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-900/50">
                        <tr>
                            <th class="p-6 text-left text-slate-300 font-bold text-lg">Foto</th>
                            <th class="p-6 text-left text-slate-300 font-bold text-lg">Nama & Umur</th>
                            <th class="p-6 text-left text-slate-300 font-bold text-lg">Jenis Kelamin</th>
                            <th class="p-6 text-left text-slate-300 font-bold text-lg">Berat/Tinggi</th>
                            <th class="p-6 text-left text-slate-300 font-bold text-lg">Posyandu</th>
                            <th class="p-6 text-right text-slate-300 font-bold text-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach($children as $child)
                        <tr class="hover:bg-slate-800/50 transition-colors">
                            <td class="p-6">
                                @if($child->foto_url)
                                    <img src="{{ $child->foto_url }}" alt="{{ $child->nama }}" class="w-16 h-16 rounded-full object-cover border-2 border-slate-600">
                                @else
                                    <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-purple-400 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($child->nama, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td class="p-6 font-semibold text-white">
                                <div class="font-bold text-lg">{{ $child->nama }}</div>
                                <div class="text-sm text-slate-400">{{ $child->umur_bulan }} bulan</div>
                            </td>
                            <td class="p-6">
                                <span class="px-4 py-1 bg-pink-500/20 text-pink-400 rounded-full text-sm font-bold border border-pink-500/30">
                                    {{ $child->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </td>
                            <td class="p-6">
                                <div class="font-mono font-bold text-green-400">{{ $child->berat_badan ?? '-' }} kg</div>
                                <div class="font-mono text-blue-400">{{ $child->tinggi_badan ?? '-' }} cm</div>
                            </td>
                            <td class="p-6">
                                <div class="font-bold text-white">{{ $child->posyandu->nama_posyandu }}</div>
                            </td>
                            <td class="p-6 text-right space-x-2">
                                <a href="{{ route('dashboard.kms') }}#child-{{ $child->id }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-all inline-flex items-center space-x-1">
                                    <i class="fas fa-chart-line"></i><span>KMS</span>
                                </a>
                                <a href="{{ route('children.edit', $child->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-semibold transition-all inline-flex items-center space-x-1">
                                    <i class="fas fa-edit"></i><span>Edit</span>
                                </a>
                                <form action="{{ route('children.destroy', $child->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus {{ $child->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition-all inline-flex items-center space-x-1">
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
