@extends('layouts.app')

@section('title', 'Informasi Anak')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center gap-4 mb-12">
        <i class="fas fa-child text-4xl text-pink-400 bg-pink-500/20 p-4 rounded-2xl"></i>
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">Informasi Anak</h1>
            <p class="text-xl text-slate-400">Data anak terdaftar dan riwayat posyandu</p>
        </div>
    </div>

    @if($children->isEmpty())
        <div class="bg-slate-800/30 border-2 border-dashed border-slate-600 rounded-3xl p-16 text-center">
            <i class="fas fa-baby-carriage text-7xl text-slate-500 mb-8"></i>
            <h2 class="text-3xl font-bold text-slate-200 mb-4">Belum Ada Data Anak</h2>
            <p class="text-slate-400 text-lg mb-8 max-w-2xl mx-auto">
                Daftarkan anak Anda untuk memantau perkembangan kesehatan dan jadwal pemeriksaan.
            </p>
            <a href="{{ route('children.create') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-12 py-4 rounded-2xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all inline-flex items-center space-x-3">
                <i class="fas fa-plus"></i>
                <span>Daftarkan Anak Pertama</span>
            </a>
        </div>
    @else
        <!-- Cards Anak -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($children as $child)
            <div class="group bg-slate-800/70 backdrop-blur-sm rounded-3xl p-8 border border-slate-700 hover:border-pink-500 hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 overflow-hidden relative">
                <!-- Badge Umur -->
                <div class="absolute -top-4 -right-4 bg-gradient-to-r from-pink-500 to-purple-500 text-white px-6 py-2 rounded-bl-2xl font-bold shadow-lg">
                    {{ \Carbon\Carbon::parse($child->tanggal_lahir)->diffInMonths() }} Bulan
                </div>
                
                <!-- Avatar/Nama -->
                <div class="text-center mb-8">
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 shadow-2xl group-hover:scale-110 transition-transform overflow-hidden border-4 border-pink-500/50">
                        @if($child->foto_url)
                            <img src="{{ $child->foto_url }}" alt="{{ $child->nama }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-pink-400 to-purple-400 flex items-center justify-center">
                                <i class="fas fa-user-child text-3xl text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-1">{{ $child->nama }}</h3>
                    <span class="text-pink-400 font-semibold text-lg">{{ $child->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>

                <!-- Detail -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-center p-4 bg-slate-700/50 rounded-xl">
                        <i class="fas fa-calendar text-pink-400 mr-4 text-xl"></i>
                        <div>
                            <p class="text-sm text-slate-400">Tanggal Lahir</p>
                            <p class="font-bold text-white">{{ $child->tanggal_lahir->format('d M Y') }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $child->umur_bulan }} bulan</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-slate-700/50 rounded-xl">
                        <i class="fas fa-weight-hanging text-green-400 mr-4 text-xl"></i>
                        <div>
                            <p class="text-sm text-slate-400">Berat Badan</p>
                            <p class="font-bold text-white text-lg">{{ $child->berat_badan ? $child->berat_badan . ' kg' : 'Belum diukur' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-slate-700/50 rounded-xl">
                        <i class="fas fa-ruler-vertical text-blue-400 mr-4 text-xl"></i>
                        <div>
                            <p class="text-sm text-slate-400">Tinggi Badan</p>
                            <p class="font-bold text-white text-lg">{{ $child->tinggi_badan ? $child->tinggi_badan . ' cm' : 'Belum diukur' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-slate-700/50 rounded-xl">
                        <i class="fas fa-map-marker-alt text-green-400 mr-4 text-xl"></i>
                        <div>
                            <p class="text-sm text-slate-400">Posyandu</p>
                            <p class="font-bold text-white">{{ $child->posyandu->nama_posyandu }}</p>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="flex gap-2 pt-6 border-t border-slate-700">
                    <a href="{{ route('dashboard.kms') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-xl font-bold text-center transition-all flex items-center justify-center space-x-2">
                        <i class="fas fa-chart-line"></i>
                        <span>Lihat KMS</span>
                    </a>
                    <a href="{{ route('children.edit', $child->id) }}" class="flex-1 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white py-3 px-6 rounded-xl font-bold text-center transition-all flex items-center justify-center space-x-2">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </a>
                    <form action="{{ route('children.destroy', $child->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus data {{ $child->nama }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white py-3 px-6 rounded-xl font-bold text-center transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-trash"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="grid md:grid-cols-3 gap-6 pt-12 border-t border-slate-700">
        <div class="text-center p-8 bg-slate-800/50 rounded-2xl border border-slate-600 hover:border-green-500 hover:shadow-xl transition-all">
            <i class="fas fa-calendar-plus text-4xl text-green-400 mb-4"></i>
            <h3 class="text-xl font-bold text-white mb-2">Jadwal Pemeriksaan</h3>
            <p class="text-slate-400 mb-6">Lihat jadwal imunisasi dan pemeriksaan berikutnya</p>
            <a href="{{ route('dashboard.index') }}" class="text-green-400 hover:text-green-300 font-bold">Lihat Jadwal →</a>
        </div>
        <div class="text-center p-8 bg-slate-800/50 rounded-2xl border border-slate-600 hover:border-blue-500 hover:shadow-xl transition-all">
            <i class="fas fa-file-medical text-4xl text-blue-400 mb-4"></i>
            <h3 class="text-xl font-bold text-white mb-2">Rekam Medis</h3>
            <p class="text-slate-400 mb-6">Riwayat pertumbuhan dan status gizi anak</p>
            <a href="{{ route('dashboard.kms') }}" class="text-blue-400 hover:text-blue-300 font-bold">Lihat KMS →</a>
        </div>
        <div class="text-center p-8 bg-slate-800/50 rounded-2xl border border-slate-600 hover:border-purple-500 hover:shadow-xl transition-all">
            <i class="fas fa-user-md text-4xl text-purple-400 mb-4"></i>
            <h3 class="text-xl font-bold text-white mb-2">Status Kader</h3>
            <p class="text-slate-400 mb-6">Cek ketersediaan kader di posyandu</p>
            <a href="{{ route('dashboard.kader') }}" class="text-purple-400 hover:text-purple-300 font-bold">Lihat Kader →</a>
        </div>
    </div>
</div>
@endsection
