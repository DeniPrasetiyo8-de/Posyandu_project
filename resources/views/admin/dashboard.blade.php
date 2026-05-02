@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">Dashboard Admin</h1>
            <p class="text-gray-300">Selamat datang, {{ Auth::user()->name }}</p>
        </div>
        <div class="text-right">
            <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-500/20 text-yellow-200 font-bold">
                <i class="fas fa-crown mr-2"></i> ADMIN MODE
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-8 rounded-3xl shadow-2xl hover:shadow-3xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-4 bg-white/20 rounded-2xl">
                    <i class="fas fa-baby text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-up text-green-300 text-2xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['total_anak'] }}</h3>
            <p class="text-blue-100">Total Anak</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-8 rounded-3xl shadow-2xl hover:shadow-3xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-4 bg-white/20 rounded-2xl">
                    <i class="fas fa-calendar-check text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-up text-green-300 text-2xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['total_jadwal'] }}</h3>
            <p class="text-emerald-100">Jadwal Kegiatan</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-8 rounded-3xl shadow-2xl hover:shadow-3xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-4 bg-white/20 rounded-2xl">
                    <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-up text-green-300 text-2xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['total_posyandu'] }}</h3>
            <p class="text-purple-100">Posyandu</p>
        </div>

        <div class="bg-gradient-to-br from-pink-500 to-pink-600 p-8 rounded-3xl shadow-2xl hover:shadow-3xl transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-4 bg-white/20 rounded-2xl">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-up text-green-300 text-2xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['total_kms'] }}</h3>
            <p class="text-pink-100">Rekam Medis KMS</p>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Jadwal -->
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-calendar mr-3 text-yellow-400"></i>Jadwal Terbaru
            </h3>
            <div class="space-y-4">
                @forelse($recentJadwals as $jadwal)
                    <div class="flex items-center p-4 bg-white/5 rounded-2xl hover:bg-white/10 transition-all">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-day text-white font-bold"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-white text-lg">{{ $jadwal->nama_kegiatan }}</h4>
                            <p class="text-gray-300">{{ $jadwal->tanggal }}</p>
                            <p class="text-sm text-gray-400">{{ $jadwal->posyandu->nama_posyandu ?? 'Semua RW' }}</p>
                        </div>
                        <span class="px-4 py-2 bg-green-500/20 text-green-200 rounded-full text-sm font-bold">
                            {{ $jadwal->rw ?? 'All' }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-8">Belum ada jadwal kegiatan</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Children -->
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-baby mr-3 text-pink-400"></i>Anak Terbaru
            </h3>
            <div class="space-y-4">
                @forelse($recentChildren as $child)
                    <div class="flex items-center p-4 bg-white/5 rounded-2xl hover:bg-white/10 transition-all">
                        <div class="w-12 h-12 bg-gradient-to-r from-pink-400 to-purple-400 rounded-full flex items-center justify-center mr-4 text-white font-bold">
                            {{ substr($child->nama, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-white truncate">{{ $child->nama }}</h4>
                            <p class="text-gray-300 text-sm">{{ $child->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400">{{ $child->posyandu->nama_posyandu ?? 'N/A' }}</p>
                        </div>
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-200 rounded-full text-xs font-bold">
                            RW {{ $child->user->rw ?? 'N/A' }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-8">Belum ada data anak</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
        <a href="/admin/jadwal" class="group bg-gradient-to-br from-blue-500 to-indigo-600 p-8 rounded-3xl text-white hover:from-blue-600 hover:to-indigo-700 shadow-2xl hover:shadow-3xl transition-all transform hover:-translate-y-2 block text-center">
            <i class="fas fa-calendar-plus text-4xl mb-4 block mx-auto group-hover:rotate-12 transition-transform"></i>
            <h3 class="text-2xl font-bold mb-2">Kelola Jadwal</h3>
            <p class="opacity-90">Tambah/Edit jadwal kegiatan</p>
        </a>
        <a href="/admin/kader" class="group bg-gradient-to-br from-emerald-500 to-teal-600 p-8 rounded-3xl text-white hover:from-emerald-600 hover:to-teal-700 shadow-2xl hover:shadow-3xl transition-all transform hover:-translate-y-2 block text-center">
            <i class="fas fa-user-md text-4xl mb-4 block mx-auto group-hover:rotate-12 transition-transform"></i>
            <h3 class="text-2xl font-bold mb-2">Kelola Kader</h3>
            <p class="opacity-90">Update status kader & foto</p>
        </a>
        <a href="/admin/informasi" class="group bg-gradient-to-br from-purple-500 to-pink-600 p-8 rounded-3xl text-white hover:from-purple-600 hover:to-pink-700 shadow-2xl hover:shadow-3xl transition-all transform hover:-translate-y-2 block text-center">
            <i class="fas fa-search text-4xl mb-4 block mx-auto group-hover:rotate-12 transition-transform"></i>
            <h3 class="text-2xl font-bold mb-2">Cari Anak/Ibu</h3>
            <p class="opacity-90">Data anak & ibu lengkap</p>
        </a>
    </div>
</div>
@endsection
