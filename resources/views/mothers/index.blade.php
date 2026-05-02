@extends('layouts.app')

@section('title', 'Data Ibu')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-user-pregnant mr-4 text-pink-400 text-3xl"></i>
                Data Ibu
            </h1>
            <p class="text-xl text-slate-400">Kelola data ibu hamil dan menyusui untuk pemantauan KMS</p>
        </div>
        <a href="{{ route('mothers.create') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white px-8 py-4 rounded-2xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all inline-flex items-center space-x-3">
            <i class="fas fa-plus"></i>
            <span>Tambah Ibu</span>
        </a>
    </div>

    @if($mothers->isEmpty())
        <div class="text-center py-20 bg-slate-800/30 rounded-3xl border-2 border-dashed border-slate-600">
            <i class="fas fa-user-pregnant text-7xl text-slate-500 mb-6"></i>
            <h3 class="text-2xl font-bold text-slate-300 mb-4">Belum Ada Data Ibu</h3>
            <p class="text-slate-500 mb-8 max-w-lg mx-auto">Tambahkan data ibu di menu <strong>Informasi Ibu</strong> untuk melihat data ibu dan status KMS.</p>
            <a href="{{ route('mothers.create') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white px-12 py-4 rounded-2xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all inline-flex items-center space-x-3">
                <i class="fas fa-arrow-right"></i>
                <span>Tambah Data Ibu</span>
            </a>
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($mothers as $mother)
            <div class="group bg-slate-800/70 backdrop-blur-sm rounded-3xl p-8 border border-slate-700 hover:border-pink-500 hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 overflow-hidden relative">
                <!-- Foto -->
                <div class="text-center mb-6">
                    @if($mother->foto_url)
                        <img src="{{ $mother->foto_url }}" alt="Foto {{ $mother->nama_lengkap }}" class="w-32 h-32 rounded-full mx-auto object-cover shadow-2xl border-4 border-pink-400">
                    @else
                        <div class="w-32 h-32 bg-slate-700 rounded-full mx-auto flex items-center justify-center text-pink-400 text-4xl">
                            <i class="fas fa-user-pregnant"></i>
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-white mb-2">{{ $mother->nama_lengkap }}</h3>
                    @if($mother->nik)
                        <p class="text-slate-400 text-sm mb-2">NIK: {{ $mother->nik }}</p>
                    @endif
                    <p class="text-slate-400 text-sm mb-2">Minggu Kehamilan: {{ $mother->umur_kehampuan }}</p>
                    <p class="text-slate-400 text-sm mb-4">BB: {{ $mother->berat_badan ? $mother->berat_badan . ' kg' : '-' }}</p>
                    
                    <!-- Status -->
                    <div class="inline-flex items-center px-4 py-2 rounded-full font-bold text-sm mb-6 {{ $mother->status_kesehatan == 'Sehat' || $mother->status_kesehatan == 'Baik' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                        {{ $mother->status_kesehatan }}
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 mt-4">
                    <a href="{{ route('mothers.edit', $mother->id) }}" class="flex-1 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white py-3 px-6 rounded-xl font-bold text-center transition-all flex items-center justify-center space-x-2">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('mothers.destroy', $mother->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus data {{ $mother->nama_lengkap }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-3 px-6 rounded-xl font-bold transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
