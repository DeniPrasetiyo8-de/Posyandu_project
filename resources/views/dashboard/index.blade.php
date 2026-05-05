@extends('layouts.app')

@section('title', 'Dashboard - Jadwal Kegiatan')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Selamat Datang, {{ Auth::user()->name }}!
            </h1>
            <p class="text-xl text-gray-500">Pantau jadwal kegiatan posyandu dan layanan kesehatan terbaru</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('children.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">
                <i class="fas fa-child mr-2"></i>Data Anak
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-lg hover:border-blue-400 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Jadwal Mendatang</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $jadwals->count() }}</p>
                </div>
                <i class="fas fa-calendar-alt text-4xl text-blue-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-lg hover:border-green-400 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Anak Terdaftar</p>
                    <p class="text-3xl font-bold text-gray-800">{{ Auth::user()->children->count() }}</p>
                </div>
                <i class="fas fa-baby text-4xl text-green-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-lg hover:border-purple-400 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Rekam Medis</p>
                    <p class="text-3xl font-bold text-gray-800">0</p>
                </div>
                <i class="fas fa-file-medical text-4xl text-purple-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-lg hover:border-pink-400 hover:shadow-xl transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Posyandu RW</p>
                    <p class="text-3xl font-bold text-gray-800">{{ Auth::user()->rw }}</p>
                </div>
                <i class="fas fa-map-marker-alt text-4xl text-pink-400"></i>
            </div>
        </div>
    </div>

    <!-- Jadwal Kegiatan -->
    <div>
        <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-calendar-week mr-3 text-blue-500"></i>
            Jadwal Kegiatan Posyandu
        </h2>

        @if($jadwals->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
                <i class="fas fa-calendar-times text-6xl text-gray-400 mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-600 mb-2">Belum Ada Jadwal Kegiatan</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Jadwal imunisasi dan kegiatan posyandu akan ditambahkan oleh admin. Silakan cek secara berkala.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('dashboard.kader') }}" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-xl font-bold">
                        <i class="fas fa-user-md mr-2"></i>Status Kader
                    </a>
                    <a href="{{ route('dashboard.kms') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-8 py-3 rounded-xl font-bold">
                        <i class="fas fa-chart-line mr-2"></i>Lihat KMS
                    </a>
                </div>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jadwals as $jadwal)
                <div class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-calendar-day text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $jadwal->nama_kegiatan ?? 'Kegiatan Posyandu' }}</h3>
                                <span class="px-4 py-1 bg-green-100 text-green-600 text-sm font-semibold rounded-full">
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal ?? now())->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4 leading-relaxed">{{ Str::limit($jadwal->deskripsi ?? 'Jadwal kegiatan imunisasi dan pemantauan kesehatan rutin di posyandu.', 120) }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $jadwal->posyandu->nama_posyandu ?? 'Posyandu RW ' . Auth::user()->rw }}</span>
                        </div>
                        <a href="#" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold transition-all flex items-center space-x-1">
                            <i class="fas fa-eye"></i>
                            <span>Detail</span>
                        </a>
                    </div>
                </div>
@endforeach
            </div>
        @endif
    </div>

    <!-- Laporan Kegiatan Section -->
    <div class="mt-12">
        <div class="text-center mb-20">
            <div class="inline-flex items-center bg-gradient-to-r from-pink-400 to-blue-400 text-white px-6 py-3 rounded-full mb-8 font-semibold">
                <i class="fas fa-clipboard-list mr-2"></i>
                Laporan Kegiatan Posyandu
            </div>
            <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-6">
                Aktivitas Layanan Kesehatan
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Berikut adalah laporan kegiatan layanan kesehatan yang dilakukan di Posyandu setiap bulannya.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Laporan Cek Kehamilan -->
            <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border border-pink-100">
                <div class="w-full h-64 bg-gradient-to-r from-pink-400 to-pink-500 rounded-2xl overflow-hidden mb-6 shadow-lg">
                    <img src="{{ asset('images/G Cek Kehamilan.jpg') }}" alt="Cek Kehamilan" class="w-full h-full object-cover">
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Cek Kehamilan</h3>
                <p class="text-gray-600 text-lg leading-relaxed text-justify">
                    Laporan pemeriksaan kehamilan di Posyandu meliputi pemeriksaan tekanan darah, berat badan, tinggi fundus uteri, posisi bayi, serta pengecekan kadar hemoglobin ibu hamil. Pemeriksaan rutin ini dilakukan setiap bulan untuk memastikan kesehatan ibu dan bayi selama kehamilan.
                </p>
            </div>

            <!-- Laporan Pemberian Vitamin -->
            <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border border-blue-100">
                <div class="w-full h-64 bg-gradient-to-r from-blue-400 to-blue-500 rounded-2xl overflow-hidden mb-6 shadow-lg">
                    <img src="{{ asset('images/G Pemberian Vitamin.jpg') }}" alt="Pemberian Vitamin" class="w-full h-full object-cover">
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Pemberian Vitamin</h3>
                <p class="text-gray-600 text-lg leading-relaxed text-justify">
                    Laporan pemberian vitamin di Posyandu meliputi pemberian vitamin A untuk ibu nifas dan balita, zat besi untuk ibu hamil, serta vitamin penambah nutrisi lainnya. Vitamin diberikan secara gratis sesuai jadwal program kesehatan nasional untuk mencegah kekurangan vitamin dan anemia.
                </p>
            </div>

            <!-- Laporan Imunisasi Anak -->
            <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border border-purple-100">
                <div class="w-full h-64 bg-gradient-to-r from-purple-400 to-purple-500 rounded-2xl overflow-hidden mb-6 shadow-lg">
                    <img src="{{ asset('images/G Imunisasi Anak.jpg') }}" alt="Imunisasi Anak" class="w-full h-full object-cover">
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Imunisasi Anak</h3>
                <p class="text-gray-600 text-lg leading-relaxed text-justify">
                    Laporan imunisasi anak di Posyandu meliputi imunisasi BCG, Polio, Campak, DPT-HB-Hib, dan Hepatitis B. Imunisasi diberikan secara gratis sesuai jadwal imunisasi nasional untuk melindungi anak dari berbagai penyakit menular berbahaya.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
