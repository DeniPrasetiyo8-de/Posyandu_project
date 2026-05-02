@extends('layouts.app')

@section('title', 'Artikel Kesehatan')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center gap-4 mb-12">
        <i class="fas fa-newspaper text-4xl text-orange-400 bg-orange-500/20 p-4 rounded-2xl shadow-lg"></i>
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">Artikel Kesehatan</h1>
            <p class="text-xl text-slate-400">Tips kesehatan ibu, anak, dan gizi seimbang dari ahli posyandu</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative max-w-md w-full">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></i>
                <input type="text" placeholder="Cari artikel kesehatan..." class="w-full pl-12 pr-6 py-4 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
            </div>
            <div class="flex flex-wrap gap-2">
                <button class="px-6 py-2 bg-blue-600/50 hover:bg-blue-600 text-white rounded-xl font-semibold border border-blue-500/30 transition-all whitespace-nowrap">Semua</button>
                <button class="px-6 py-2 bg-emerald-600/50 hover:bg-emerald-600 text-white rounded-xl font-semibold border border-emerald-500/30 transition-all whitespace-nowrap">Gizi Anak</button>
                <button class="px-6 py-2 bg-pink-600/50 hover:bg-pink-600 text-white rounded-xl font-semibold border border-pink-500/30 transition-all whitespace-nowrap">Ibu Hamil</button>
                <button class="px-6 py-2 bg-purple-600/50 hover:bg-purple-600 text-white rounded-xl font-semibold border border-purple-500/30 transition-all whitespace-nowrap">Imunisasi</button>
            </div>
        </div>
    </div>

    <!-- Artikel Cards -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($artikels as $artikel)
        <div class="group bg-slate-800/70 backdrop-blur-sm rounded-3xl p-8 border border-slate-700 hover:border-orange-500 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden h-full">
            <!-- Category Badge -->
            <span class="inline-block px-4 py-2 bg-gradient-to-r from-emerald-600 text-white text-xs font-bold uppercase tracking-wider rounded-full mb-6 shadow-lg">Gizi Anak</span>
            
            <!-- Thumbnail Placeholder -->
            <div class="w-full h-48 bg-gradient-to-br from-orange-400/20 to-red-400/20 rounded-2xl mb-6 group-hover:scale-105 transition-transform shadow-inner"></div>
            
            <div class="space-y-4">
                <div>
                    <h3 class="text-2xl font-bold text-white leading-tight line-clamp-2 group-hover:text-orange-300 transition-colors mb-3">
                        {{ $artikel['judul'] }}
                    </h3>
                    <p class="text-slate-400 leading-relaxed line-clamp-3 text-lg">
                        {{ $artikel['isi'] }}
                    </p>
                </div>
                
                <div class="flex items-center justify-between pt-6 border-t border-slate-700">
                    <div class="flex items-center text-sm text-slate-500 space-x-4">
                        <span><i class="fas fa-calendar mr-1"></i>12 Okt 2024</span>
                    </div>
                    <button class="group/btn flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all whitespace-nowrap">
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        <span>Baca Selengkapnya</span>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination & CTA -->
    <div class="flex flex-col md:flex-row gap-6 items-center justify-between pt-12 border-t border-slate-700">
        <div class="text-sm text-slate-500">
            Menampilkan 1-3 dari 15 artikel
        </div>
        <div class="flex gap-2">
            <button class="w-12 h-12 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-bold transition-all">←</button>
            <button class="w-12 h-12 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-bold shadow-lg transition-all">1</button>
            <button class="w-12 h-12 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-bold transition-all">2</button>
            <button class="w-12 h-12 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-bold transition-all">3</button>
            <button class="w-12 h-12 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-bold transition-all">→</button>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
