@extends('layouts.app')

@section('title', 'Informasi Kader')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center gap-4 mb-12">
        <i class="fas fa-user-md text-4xl text-emerald-400 bg-emerald-500/20 p-4 rounded-2xl shadow-lg"></i>
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">Status Kader Posyandu</h1>
            <p class="text-xl text-slate-400">Monitor kehadiran kader dengan indikator hijau/merah real-time</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($posyandus as $posyandu)
        <div class="group bg-slate-800/70 backdrop-blur-sm rounded-3xl p-8 border-4 {{ $posyandu->is_present ? 'border-emerald-500 shadow-emerald-500/25 shadow-2xl' : 'border-red-500 shadow-red-500/25 shadow-2xl' }} hover:shadow-3xl hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
            <!-- Status Badge Besar -->
            <div class="absolute -top-6 -right-6 w-24 h-24 rounded-3xl flex items-center justify-center {{ $posyandu->is_present ? 'bg-emerald-500' : 'bg-red-500' }} shadow-2xl">
                <i class="{{ $posyandu->is_present ? 'fas fa-check-circle text-3xl' : 'fas fa-times-circle text-3xl' }} text-white animate-ping"></i>
            </div>

            <!-- Header -->
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $posyandu->nama_posyandu }}</h3>
                        <p class="text-slate-400 text-lg">RW {{ $posyandu->rw ?? Auth::user()->rw }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-bold {{ $posyandu->is_present ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ $posyandu->is_present ? 'Ada' : 'Tidak Ada' }}
                        </span>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-bold">Kader</p>
                    </div>
                </div>

                <!-- Detail -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-center p-4 bg-slate-700/50 rounded-xl">
                        <i class="fas fa-clock text-yellow-400 mr-4 text-xl"></i>
                        <span class="text-slate-300 font-medium">Jam Operasional: 08.00 - 12.00</span>
                    </div>
                    <div class="flex items-center p-4 bg-slate-700/50 rounded-xl">
                        <i class="fas fa-users text-blue-400 mr-4 text-xl"></i>
                        <span class="text-slate-300 font-medium">Kapasitas: {{ $posyandu->kapasitas ?? '50 anak' }}</span>
                    </div>
                </div>

                <!-- Status Detail & CTA -->
                <div class="relative z-10 pt-6 border-t border-slate-700 space-y-4">
                    <div class="flex items-center justify-center py-4 px-8 bg-{{ $posyandu->is_present ? 'emerald' : 'red' }}-500/10 rounded-2xl">
                        <i class="{{ $posyandu->is_present ? 'fas fa-check-circle text-emerald-400' : 'fas fa-times-circle text-red-400' }} text-4xl mr-4"></i>
                        <div>
                            <p class="font-bold text-2xl {{ $posyandu->is_present ? 'text-emerald-300' : 'text-red-300' }}">
                                {{ $posyandu->is_present ? 'Kader Tersedia' : 'Kader Tidak Hadir' }}
                            </p>
                            <p class="text-sm text-slate-400">
                                {{ $posyandu->is_present ? 'Segera datangi posyandu' : 'Hubungi kader atau cek kembali nanti' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="#" class="flex-1 bg-white/10 hover:bg-white/20 text-white py-3 rounded-xl font-semibold text-center transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-phone"></i>
                            <span>Hubungi</span>
                        </a>
                        <a href="{{ route('dashboard.index') }}" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white py-3 rounded-xl font-semibold text-center transition-all">
                            <i class="fas fa-calendar"></i>
                            <span>Jadwal</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Legend -->
    <div class="flex flex-col md:flex-row gap-8 items-center justify-center py-12 bg-slate-900/50 rounded-2xl border border-slate-700">
        <div class="flex items-center space-x-6">
            <div class="flex items-center space-x-3">
                <div class="w-6 h-6 bg-emerald-500 rounded-full"></div>
                <span class="font-bold text-white">Kader Hadir - Layanan Tersedia</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-6 h-6 bg-red-500 rounded-full"></div>
                <span class="font-bold text-slate-300">Kader Tidak Hadir - Cek Kembali</span>
            </div>
        </div>
        <div class="text-center">
            <p class="text-slate-400 mb-4">Status diperbarui real-time</p>
            <div class="flex items-center space-x-2 text-sm text-slate-500">
                <i class="fas fa-clock text-xs"></i>
                <span>Dipantau otomatis sistem</span>
            </div>
        </div>
    </div>
</div>
@endsection
