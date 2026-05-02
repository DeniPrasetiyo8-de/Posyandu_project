@extends('layouts.app')

@section('title', 'Informasi Ibu')

@section('content')
        <div class="md:col-span-2 lg:col-span-3 bg-gradient-to-br from-emerald-500/10 via-green-500/5 to-teal-500/10 backdrop-blur-sm rounded-3xl p-10 border border-emerald-500/30 hover:shadow-2xl transition-all">
            <h3 class="text-2xl font-bold text-white mb-6 text-center">Jadwal Pemberian Layanan</h3>
            <div class="grid md:grid-cols-3 gap-6 text-center">
                <div>
                    <div class="text-3xl font-bold text-emerald-400 mb-2">0-11 Bulan</div>
                    <p class="text-slate-300">Vitamin A, Imunisasi DPT-HB-HiB</p>
                </div>
                <div>
                    <div class="text-3xl font-bold text-green-400 mb-2">12-23 Bulan</div>
                    <p class="text-slate-300">Imunisasi Campak, Vitamin A</p>
                </div>
                <div>
                    <div class="text-3xl font-bold text-teal-400 mb-2">24+ Bulan</div>
                    <p class="text-slate-300">Pemeriksaan Gizi, Imunisasi Booster</p>
                </div>
            </div>
        </div>
    </div>

<!-- Quick Links -->
    <div class="grid md:grid-cols-4 gap-6 pt-12 border-t border-slate-700">
        <a href="{{ route('dashboard.index') }}" class="group p-8 bg-slate-800/50 rounded-2xl border border-slate-600 hover:border-blue-500 hover:shadow-xl transition-all text-center">
            <i class="fas fa-calendar text-3xl text-blue-400 group-hover:text-blue-300 mb-4 block mx-auto"></i>
            <h4 class="font-bold text-white mb-2">Jadwal Kegiatan</h4>
            <p class="text-slate-400 group-hover:text-slate-300">Lihat jadwal posyandu terbaru</p>
        </a>
        <a href="{{ route('dashboard.kms') }}" class="group p-8 bg-slate-800/50 rounded-2xl border border-slate-600 hover:border-green-500 hover:shadow-xl transition-all text-center">
            <i class="fas fa-chart-line text-3xl text-green-400 group-hover:text-green-300 mb-4 block mx-auto"></i>
            <h4 class="font-bold text-white mb-2">Pemantauan KMS</h4>
            <p class="text-slate-400 group-hover:text-slate-300">Grafik perkembangan anak</p>
        </a>
        <a href="{{ route('dashboard.informasi.anak') }}" class="group p-8 bg-slate-800/50 rounded-2xl border border-slate-600 hover:border-pink-500 hover:shadow-xl transition-all text-center">
            <i class="fas fa-child text-3xl text-pink-400 group-hover:text-pink-300 mb-4 block mx-auto"></i>
            <h4 class="font-bold text-white mb-2">Data Anak</h4>
            <p class="text-slate-400 group-hover:text-slate-300">Informasi anak terdaftar</p>
        </a>
        <a href="{{ route('dashboard.kader') }}" class="group p-8 bg-slate-800/50 rounded-2xl border border-slate-600 hover:border-purple-500 hover:shadow-xl transition-all text-center">
            <i class="fas fa-user-md text-3xl text-purple-400 group-hover:text-purple-300 mb-4 block mx-auto"></i>
            <h4 class="font-bold text-white mb-2">Cek Kader</h4>
            <p class="text-slate-400 group-hover:text-slate-300">Status kader posyandu</p>
        </a>
    </div>

    <!-- Data Ibu Section -->
    <div class="mt-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-white">Data Ibu Terdaftar</h2>
            <a href="{{ route('mothers.create') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">
                <i class="fas fa-plus mr-2"></i>Data IBU
            </a>
        </div>
        
        @if(isset($mothers) && $mothers->isEmpty())
            <div class="text-center py-12 bg-slate-800/30 rounded-2xl border-2 border-dashed border-slate-600">
                <i class="fas fa-user-pregnant text-5xl text-slate-500 mb-4"></i>
                <p class="text-slate-400">Belum ada data ibu. Tambahkan untuk melihat di KMS.</p>
            </div>
        @elseif(isset($mothers))
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($mothers as $mother)
                <div class="bg-slate-800/50 rounded-2xl p-6 border border-slate-700">
                    <div class="flex items-center gap-4 mb-4">
                        @if($mother->foto_url)
                            <img src="{{ $mother->foto_url }}" alt="{{ $mother->nama_lengkap }}" class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center text-pink-400">
                                <i class="fas fa-user-pregnant text-2xl"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-bold text-white">{{ $mother->nama_lengkap }}</h3>
                            <p class="text-slate-400 text-sm">{{ $mother->nik }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm mb-4">
                        <p class="text-slate-300">Minggu Kehamilan: {{ $mother->umur_kehampuan }}</p>
                        <p class="text-slate-300">BB: {{ $mother->berat_badan ?? '-' }} kg</p>
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold {{ $mother->status_kesehatan == 'Sehat' || $mother->status_kesehatan == 'Baik' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $mother->status_kesehatan }}
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('mothers.edit', $mother->id) }}" class="flex-1 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 py-2 rounded-lg text-center text-sm font-bold">
                            <i class="fas fa-edit"></i> EDIT
                        </a>
                        <form action="{{ route('mothers.destroy', $mother->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full bg-red-500/20 hover:bg-red-500/30 text-red-400 py-2 rounded-lg text-sm font-bold">
                                <i class="fas fa-trash"></i> HAPUS
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
