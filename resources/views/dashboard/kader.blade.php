@extends('layouts.app')

@section('title', 'Informasi Kader')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-pink-500 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg">
            <i class="fas fa-user-md"></i>
        </div>
        <div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Status Kader Posyandu</h1>
            <p class="text-xl text-gray-500">Monitor kehadiran kader dengan indikator hijau/merah real-time</p>
        </div>
    </div>

    <!-- Tampilkan data kader yang sebenarnya -->
    @if($kaders->count() > 0)
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($kaders as $kader)
        <div class="group bg-white rounded-2xl p-6 border-2 {{ $kader->status_kehadiran === 'hadir' ? 'border-green-200 shadow-green-100' : 'border-red-200 shadow-red-100' }} hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative">
            <!-- Status Badge Besar -->
            <div class="absolute -top-4 -right-4 w-16 h-16 rounded-2xl flex items-center justify-center {{ $kader->status_kehadiran === 'hadir' ? 'bg-green-500' : 'bg-red-500' }} shadow-lg">
                <i class="{{ $kader->status_kehadiran === 'hadir' ? 'fas fa-check-circle text-2xl' : 'fas fa-times-circle text-2xl' }} text-white"></i>
            </div>

            <!-- Header -->
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $kader->nama_kader }}</h3>
                        <p class="text-gray-500">RW {{ $kader->rw ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold {{ $kader->status_kehadiran === 'hadir' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $kader->status_kehadiran === 'hadir' ? 'Hadir' : 'Tidak' }}
                        </span>
                        <p class="text-xs text-gray-400 uppercase font-semibold">Kader</p>
                    </div>
                </div>

                <!-- Detail Posyandu -->
                <div class="space-y-3 mb-6">
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                        <i class="fas fa-hospital text-blue-500 mr-3"></i>
                        <span class="text-gray-600 font-medium">{{ $kader->posyandu->nama_posyandu ?? 'Posyandu' }}</span>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                        <i class="fas fa-map-marker-alt text-red-500 mr-3"></i>
                        <span class="text-gray-600 font-medium">{{ $kader->alamat ?? '-' }}</span>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                        <i class="fas fa-clock text-yellow-500 mr-3"></i>
                        <span class="text-gray-600 font-medium">Jam Operasional: 08.00 - 12.00</span>
                    </div>
                </div>

                <!-- Status Detail & CTA -->
                <div class="relative z-10 pt-4 border-t border-gray-100 space-y-3">
                    <div class="flex items-center justify-center p-4 bg-{{ $kader->status_kehadiran === 'hadir' ? 'green' : 'red' }}-50 rounded-xl">
                        <span class="status-dot {{ $kader->status_kehadiran === 'hadir' ? 'status-hadir' : 'status-tidak-hadir' }} mr-3"></span>
                        <div>
                            <p class="font-bold {{ $kader->status_kehadiran === 'hadir' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $kader->status_kehadiran === 'hadir' ? 'Kader Tersedia' : 'Kader Tidak Hadir' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $kader->status_kehadiran === 'hadir' ? 'Segera datangi posyandu' : 'Hubungi kader atau cek kembali nanti' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="#" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg font-semibold text-center transition-all flex items-center justify-center text-sm">
                            <i class="fas fa-phone mr-1"></i>Hubungi
                        </a>
                        <a href="{{ route('dashboard.index') }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg font-semibold text-center transition-all flex items-center justify-center text-sm">
                            <i class="fas fa-calendar mr-1"></i>Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Jika tidak ada kader, tampilkan pesan -->
    <div class="bg-white rounded-2xl p-12 border border-gray-200 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user-md text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Data Kader</h3>
        <p class="text-gray-500 mb-6">Saat ini belum ada kader yang terdaftar di sistem.</p>
        <a href="{{ route('dashboard.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
        </a>
    </div>
    @endif

    <!-- Legend -->
    <div class="flex flex-col md:flex-row gap-6 items-center justify-center py-8 bg-white rounded-2xl border border-gray-200">
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                <span class="font-semibold text-gray-700">Kader Hadir - Layanan Tersedia</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                <span class="font-semibold text-gray-500">Kader Tidak Hadir</span>
            </div>
        </div>
        <div class="text-center">
            <p class="text-gray-400 text-sm">Status diperbarui secara real-time oleh admin</p>
        </div>
    </div>
</div>

<style>
.status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}
.status-hadir {
    background-color: #10B981;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
}
.status-tidak-hadir {
    background-color: #EF4444;
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
}
</style>
@endsection
