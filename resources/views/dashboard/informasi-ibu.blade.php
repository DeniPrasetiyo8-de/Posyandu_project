@extends('layouts.app')

@section('title', 'Informasi Ibu')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center gap-4 mb-12">
        <i class="fas fa-user-pregnant text-4xl text-pink-400 bg-pink-500/20 p-4 rounded-2xl shadow-lg"></i>
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">Informasi Ibu dan Balita</h1>
            <p class="text-xl text-slate-400">Panduan kesehatan untuk ibu hamil, menyusui, dan nutrisi balita</p>
        </div>
    </div>

    <!-- Grid Info Cards -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-gradient-to-br from-pink-500/10 to-purple-500/10 backdrop-blur-sm rounded-3xl p-10 border border-pink-500/30 hover:shadow-2xl hover:-translate-y-2 transition-all group">
            <div class="w-20 h-20 bg-gradient-to-br from-pink-400 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-baby text-white text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4 text-center">MP-ASI</h3>
            <p class="text-slate-300 leading-relaxed text-lg mb-6 text-center">Pengenalan Makanan Pendamping ASI dimulai usia 6 bulan. Berikan 3x sehari dengan variasi gizi seimbang.</p>
            <ul class="space-y-2 text-slate-300 text-sm">
                <li><i class="fas fa-check text-green-400 mr-2"></i>Usia 6-8 bulan: 2-3x bubur</li>
                <li><i class="fas fa-check text-green-400 mr-2"></i>Usia 9-11 bulan: 3-4x makanan padat</li>
                <li><i class="fas fa-check text-green-400 mr-2"></i>Usia 12+ bulan: makan keluarga</li>
            </ul>
        </div>

        <div class="bg-gradient-to-br from-blue-500/10 to-emerald-500/10 backdrop-blur-sm rounded-3xl p-10 border border-blue-500/30 hover:shadow-2xl hover:-translate-y-2 transition-all group">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-pills text-white text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4 text-center">Vitamin A</h3>
            <p class="text-slate-300 leading-relaxed text-lg mb-6 text-center">Suplementasi Vitamin A gratis setiap 6 bulan untuk mencegah kebutaan dan meningkatkan imunitas anak.</p>
            <ul class="space-y-2 text-slate-300 text-sm">
                <li><i class="fas fa-check text-green-400 mr-2"></i>6-11 bulan: 100.000 IU</li>
                <li><i class="fas fa-check text-green-400 mr-2"></i>12-59 bulan: 200.000 IU</li>
                <li><i class="fas fa-check text-green-400 mr-2"></i>Diberikan di posyandu</li>
            </ul>
        </div>

        <div class="bg-gradient-to-br from-purple-500/10 to-orange-500/10 backdrop-blur-sm rounded-3xl p-10 border border-purple-500/30 hover:shadow-2xl hover:-translate-y-2 transition-all group">
            <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-orange-400 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-heartbeat text-white text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4 text-center">Kesehatan Ibu Hamil</h3>
            <p class="text-slate-300 leading-relaxed text-lg mb-6 text-center">Pemeriksaan antenatal rutin, tablet Fe, dan imunisasi TT untuk ibu hamil sehat.</p>
            <ul class="space-y-2 text-slate-300 text-sm">
                <li><i class="fas fa-check text-green-400 mr-2"></i>Minimal 4x KIA</li>
                <li><i class="fas fa-check text-green-400 mr-2"></i>90 tablet Fe</li>
                <li><i class="fas fa-check text-green-400 mr-2"></i>2 dosis TT</li>
            </ul>
        </div>

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
</div>
@endsection
