@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-Black mb-2">Dashboard Admin</h1>
            <p class="text-Black-300">Selamat datang, {{ Auth::user()->name }}</p>
        </div>
        <div class="text-right">
            <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-500/20 text-yellow-200 font-bold">
                <i class="fas fa-crown mr-2"></i> ADMIN MODE
            </span>
        </div>
    </div>

<!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-gray-900 p-8 rounded-3xl border border-gray-700 hover:border-gray-600 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-4 bg-gray-800 rounded-2xl">
                    <i class="fas fa-baby text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-up text-green-400 text-2xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['total_anak'] }}</h3>
            <p class="text-gray-300">Total Anak</p>
        </div>

        <div class="bg-gray-900 p-8 rounded-3xl border border-gray-700 hover:border-gray-600 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-4 bg-gray-800 rounded-2xl">
                    <i class="fas fa-calendar-check text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-up text-green-400 text-2xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['total_jadwal'] }}</h3>
            <p class="text-gray-300">Jadwal Kegiatan</p>
        </div>

        <div class="bg-gray-900 p-8 rounded-3xl border border-gray-700 hover:border-gray-600 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-4 bg-gray-800 rounded-2xl">
                    <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-up text-green-400 text-2xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['total_posyandu'] }}</h3>
            <p class="text-gray-300">Posyandu</p>
        </div>

        <div class="bg-gray-900 p-8 rounded-3xl border border-gray-700 hover:border-gray-600 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-4 bg-gray-800 rounded-2xl">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-up text-green-400 text-2xl"></i>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['total_kms'] }}</h3>
            <p class="text-gray-300">Rekam Medis KMS</p>
        </div>
    </div>

<!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Jadwal -->
        <div class="bg-gray-900 rounded-3xl p-8 border border-gray-700">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-calendar mr-3 text-yellow-400"></i>Jadwal Terbaru
            </h3>
            <div class="space-y-4">
                @forelse($recentJadwals as $jadwal)
                    <div class="flex items-center p-4 bg-gray-800 rounded-2xl hover:bg-gray-700 transition-all">
                        <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-day text-white font-bold"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-white text-lg">{{ $jadwal->kegiatan }}</h4>
                            <p class="text-gray-300">{{ $jadwal->tanggal }}</p>
                            <p class="text-sm text-gray-400">{{ $jadwal->posyandu->nama_posyandu ?? 'Semua RW' }}</p>
                        </div>
                        <span class="px-4 py-2 bg-green-500/20 text-green-400 rounded-full text-sm font-bold">
                            {{ $jadwal->rw ?? 'All' }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-8">Belum ada jadwal kegiatan</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Children -->
        <div class="bg-gray-900 rounded-3xl p-8 border border-gray-700">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <i class="fas fa-baby mr-3 text-pink-400"></i>Anak Terbaru
            </h3>
            <div class="space-y-4">
                @forelse($recentChildren as $child)
                    <div class="flex items-center p-4 bg-gray-800 rounded-2xl hover:bg-gray-700 transition-all">
                        <div class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center mr-4 text-white font-bold">
                            {{ substr($child->nama, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-white truncate">{{ $child->nama }}</h4>
                            <p class="text-gray-300 text-sm">{{ $child->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-400">{{ $child->posyandu->nama_posyandu ?? 'N/A' }}</p>
                        </div>
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-bold">
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
        <a href="/admin/jadwal" class="group bg-gray-900 p-8 rounded-3xl border border-gray-700 hover:border-gray-600 transition-all transform hover:-translate-y-2 block text-center">
            <i class="fas fa-calendar-plus text-4xl mb-4 block mx-auto group-hover:rotate-12 transition-transform text-blue-400"></i>
            <h3 class="text-2xl font-bold mb-2 text-white">Kelola Jadwal</h3>
            <p class="opacity-90 text-gray-300">Tambah/Edit jadwal kegiatan</p>
        </a>
        <a href="/admin/kader" class="group bg-gray-900 p-8 rounded-3xl border border-gray-700 hover:border-gray-600 transition-all transform hover:-translate-y-2 block text-center">
            <i class="fas fa-user-md text-4xl mb-4 block mx-auto group-hover:rotate-12 transition-transform text-green-400"></i>
            <h3 class="text-2xl font-bold mb-2 text-white">Kelola Kader</h3>
            <p class="opacity-90 text-gray-300">Update status kader & foto</p>
        </a>
        <a href="/admin/informasi" class="group bg-gray-900 p-8 rounded-3xl border border-gray-700 hover:border-gray-600 transition-all transform hover:-translate-y-2 block text-center">
            <i class="fas fa-search text-4xl mb-4 block mx-auto group-hover:rotate-12 transition-transform text-purple-400"></i>
            <h3 class="text-2xl font-bold mb-2 text-white">Cari Anak/Ibu</h3>
            <p class="opacity-90 text-gray-300">Data anak & ibu lengkap</p>
        </a>
    </div>
</div>
@endsection
