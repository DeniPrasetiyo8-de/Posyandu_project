@extends('layouts.app')

@section('title', 'Dashboard - Jadwal Kegiatan')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-12">
        <div>
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent mb-4">
                Selamat Datang, {{ Auth::user()->name }}!
            </h1>
            <p class="text-xl text-slate-400">Pantau jadwal kegiatan posyandu dan layanan kesehatan terbaru</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('children.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">
                <i class="fas fa-child mr-2"></i>Data Anak
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-4 gap-6 mb-12">
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700 hover:border-blue-500 transition-all hover:shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Jadwal Mendatang</p>
                    <p class="text-3xl font-bold text-white">{{ $jadwals->count() }}</p>
                </div>
                <i class="fas fa-calendar-alt text-4xl text-blue-400"></i>
            </div>
        </div>
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700 hover:border-green-500 transition-all hover:shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Anak Terdaftar</p>
                    <p class="text-3xl font-bold text-white">{{ Auth::user()->children->count() }}</p>
                </div>
                <i class="fas fa-baby text-4xl text-green-400"></i>
            </div>
        </div>
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700 hover:border-purple-500 transition-all hover:shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Rekam Medis</p>
                    <p class="text-3xl font-bold text-white">0</p>
                </div>
                <i class="fas fa-file-medical text-4xl text-purple-400"></i>
            </div>
        </div>
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-2xl p-6 border border-slate-700 hover:border-pink-500 transition-all hover:shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Posyandu RW</p>
                    <p class="text-3xl font-bold text-white">{{ Auth::user()->rw }}</p>
                </div>
                <i class="fas fa-map-marker-alt text-4xl text-pink-400"></i>
            </div>
        </div>
    </div>

    <!-- Jadwal Kegiatan -->
    <div>
        <h2 class="text-3xl font-bold text-white mb-8 flex items-center">
            <i class="fas fa-calendar-week mr-3 text-blue-400"></i>
            Jadwal Kegiatan Posyandu
        </h2>

        @if($jadwals->isEmpty())
            <div class="text-center py-20 bg-slate-800/30 rounded-2xl border-2 border-dashed border-slate-600">
                <i class="fas fa-calendar-times text-6xl text-slate-500 mb-6"></i>
                <h3 class="text-2xl font-bold text-slate-300 mb-2">Belum Ada Jadwal Kegiatan</h3>
                <p class="text-slate-500 mb-8 max-w-md mx-auto">Jadwal imunisasi dan kegiatan posyandu akan ditambahkan oleh admin. Silakan cek secara berkala.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('dashboard.kader') }}" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-bold">
                        <i class="fas fa-user-md mr-2"></i>Status Kader
                    </a>
                    <a href="{{ route('dashboard.kms') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-xl font-bold">
                        <i class="fas fa-chart-line mr-2"></i>Lihat KMS
                    </a>
                </div>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jadwals as $jadwal)
                <div class="group bg-slate-800/70 backdrop-blur-sm rounded-2xl p-8 border border-slate-700 hover:border-blue-500 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-calendar-day text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-1">{{ $jadwal->nama_kegiatan ?? 'Kegiatan Posyandu' }}</h3>
                                    <span class="px-4 py-1 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-semibold rounded-full shadow">
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal ?? now())->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <p class="text-slate-300 mb-6 leading-relaxed relative z-10">{{ Str::limit($jadwal->deskripsi ?? 'Jadwal kegiatan imunisasi dan pemantauan kesehatan rutin di posyandu.', 120) }}</p>
                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center text-sm text-slate-400">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span>{{ $jadwal->posyandu->nama_posyandu ?? 'Posyandu RW ' . Auth::user()->rw }}</span>
                            </div>
                            <a href="#" class="bg-white/10 hover:bg-white/20 text-white px-6 py-2 rounded-xl font-semibold transition-all flex items-center space-x-2">
                                <i class="fas fa-eye"></i>
                                <span>Lihat Detail</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
