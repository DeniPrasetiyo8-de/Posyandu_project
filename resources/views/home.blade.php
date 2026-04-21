@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-br from-pink-400 and via-blue-400 text-white py-24">
        <div class="absolute inset-0">
        <div class="absolute inset-0 bg-white/20"></div>
        <!-- Kotak Kanan seperti navbar -->
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full blur-xl animate-pulse">
            <img src="{{ asset('images/G HC1.jpg') }}" alt="" class="w-full h-full object-cover rounded-full animate-pulse">
        </div>
        <!-- Kotak Kiri -->
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full blur-xl animate-pulse delay-500">
            <img src="{{ asset('images/G HC2.jpg') }}" alt="" class="w-full h-full object-cover rounded-full animate-pulse">
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="max-w-4xl mx-auto">
            <div class="inline-flex items-center bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full mb-8">
                <i class="fas fa-star text-yellow-300 mr-2"></i>
                <span class="font-semibold">Posyandu Terbaik di Kota Kami!</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                Selamat Datang di 
                <span class="bg-gradient-to-r from-yellow-300 to-pink-300 bg-clip-text text-transparent">
                    SIPANDU
                </span>
            </h1>
            
            <p class="text-xl md:text-2xl mb-12 text-white/90 max-w-2xl mx-auto leading-relaxed">
                Tempat terbaik untuk tumbuh kembang anak Anda yang sehat dan ceria! 
                <i class="fas fa-heart text-pink-200 ml-2"></i>
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center max-w-2xl mx-auto">
                <a href="https://www.google.com/maps/search/?api=" class="group bg-white text-pink-600 px-10 py-4 rounded-2xl font-bold text-lg shadow-2xl hover:shadow-3xl transform hover:-translate-y-2 transition-all duration-300 flex items-center space-x-3">
                    <i class="fas fa-map-marker-alt group-hover:rotate-12 transition-transform"></i>
                    <span>Cari Posyandu Terdekat</span>
                </a>
            </div>
        </div>
        
        <!-- Floating Images G HC1 & G HC2 with bounce -->
        <div class="absolute top-20 left-10 w-24 h-24 md:w-32 md:h-32 animate-bounce">
            <img src="{{ asset('images/G HC1.jpg') }}" alt="Gambar 1" class="w-full h-full rounded-3xl object-cover shadow-2xl bg-white/30">
        </div>
        <div class="absolute bottom-20 right-10 w-20 h-20 md:w-28 md:h-28 animate-bounce delay-300">
            <img src="{{ asset('images/G HC2.jpg') }}" alt="Gambar 2" class="w-full h-full rounded-2xl object-cover shadow-xl bg-white/20">
        </div>
    </div>
</section>

<!-- Posyandu Cards Section -->
<section class="py-24 bg-white/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <div class="inline-flex items-center bg-gradient-to-r from-pink-400 to-blue-400 text-white px-6 py-3 rounded-full mb-8 font-semibold">
                <i class="fas fa-map-marked-alt mr-2"></i>
                6 Posyandu Terbaik di Kota Kami
            </div>
            <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-6">
                Pilih Posyandu Terdekat Anda
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Setiap Posyandu dilengkapi dokter, bidan, dan fasilitas lengkap untuk 
                kesehatan ibu dan anak Anda.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Posyandu 1 -->
            <div class="group bg-white/70 backdrop-blur-sm rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border border-pink-100 hover:border-pink-200">
                <div class="w-20 h-20 bg-gradient-to-r from-pink-400 to-pink-500 rounded-2xl flex items-center justify-center mb-6 mx-auto shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-seedling text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center group-hover:text-pink-600 transition-colors">Posyandu Mawar</h3>
                <p class="text-gray-600 mb-6 text-center leading-relaxed">Jl. Mawar No. 123, Kel. Sejahtera, Kec. Pusat</p>
                <div class="flex items-center justify-center space-x-4 mb-6 text-sm">
                    <span class="flex items-center text-green-600">
                        <i class="fas fa-clock mr-1"></i> Setiap Rabu 08.00
                    </span>
                    <span class="flex items-center text-blue-600">
                        <i class="fas fa-users mr-1"></i> 50 Anak
                    </span>
                </div>
                <a href="https://www.google.com/maps/search/?api=" class="w-full bg-gradient-to-r from-pink-500 to-blue-500 text-white py-4 px-6 rounded-2xl font-bold text-center block hover:from-pink-600 hover:to-blue-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-directions mr-2"></i>Lihat Detail
                </a>
            </div>

            <!-- Posyandu 2 -->
            <div class="group bg-white/70 backdrop-blur-sm rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border border-blue-100 hover:border-blue-200">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-400 to-blue-500 rounded-2xl flex items-center justify-center mb-6 mx-auto shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-flower-tulip text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center group-hover:text-blue-600 transition-colors">Posyandu Melati</h3>
                <p class="text-gray-600 mb-6 text-center leading-relaxed">Jl. Melati No. 45, Kel. Bahagia, Kec. Barat</p>
                <div class="flex items-center justify-center space-x-4 mb-6 text-sm flex-wrap">
                    <span class="flex items-center text-green-600">
                        <i class="fas fa-clock mr-1"></i> Setiap Selasa 09.00
                    </span>
                    <span class="flex items-center text-blue-600">
                        <i class="fas fa-users mr-1"></i> 45 Anak
                    </span>
                </div>
                <a href="https://www.google.com/maps/search/?api=" class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white py-4 px-6 rounded-2xl font-bold text-center block hover:from-blue-600 hover:to-purple-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-directions mr-2"></i>Lihat Detail
                </a>
            </div>

            <!-- Posyandu 3 -->
            <div class="group bg-white/70 backdrop-blur-sm rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border border-purple-100 hover:border-purple-200">
                <div class="w-20 h-20 bg-gradient-to-r from-purple-400 to-pink-500 rounded-2xl flex items-center justify-center mb-6 mx-auto shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-spa text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center group-hover:text-purple-600 transition-colors">Posyandu Anggrek</h3>
                <p class="text-gray-600 mb-6 text-center leading-relaxed">Jl. Anggrek No. 78, Kel. Makmur, Kec. Timur</p>
                <div class="flex items-center justify-center space-x-4 mb-6 text-sm">
                    <span class="flex items-center text-green-600">
                        <i class="fas fa-clock mr-1"></i> Setiap Kamis 08.30
                    </span>
                    <span class="flex items-center text-blue-600">
                        <i class="fas fa-users mr-1"></i> 60 Anak
                    </span>
                </div>
                <a href="https://www.google.com/maps/search/?api=" class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white py-4 px-6 rounded-2xl font-bold text-center block hover:from-purple-600 hover:to-pink-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-directions mr-2"></i>Lihat Detail
                </a>
            </div>

            <!-- Posyandu 4 -->
            <div class="group bg-white/70 backdrop-blur-sm rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border border-green-100 hover:border-green-200">
                <div class="w-20 h-20 bg-gradient-to-r from-green-400 to-blue-400 rounded-2xl flex items-center justify-center mb-6 mx-auto shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-leaf text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center group-hover:text-green-600 transition-colors">Posyandu Daun</h3>
                <p class="text-gray-600 mb-6 text-center leading-relaxed">Jl. Daun No. 12, Kel. Subur, Kec. Utara</p>
                <div class="flex items-center justify-center space-x-4 mb-6 text-sm">
                    <span class="flex items-center text-green-600">
                        <i class="fas fa-clock mr-1"></i> Setiap Senin 09.00
                    </span>
                    <span class="flex items-center text-blue-600">
                        <i class="fas fa-users mr-1"></i> 40 Anak
                    </span>
                </div>
                <a href="https://www.google.com/maps/search/?api=" class="w-full bg-gradient-to-r from-green-500 to-blue-500 text-white py-4 px-6 rounded-2xl font-bold text-center block hover:from-green-600 hover:to-blue-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-directions mr-2"></i>Lihat Detail
                </a>
            </div>

            <!-- Posyandu 5 -->
            <div class="group bg-white/70 backdrop-blur-sm rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border border-yellow-100 hover:border-yellow-200">
                <div class="w-20 h-20 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-2xl flex items-center justify-center mb-6 mx-auto shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-sun-plant-wilt text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center group-hover:text-yellow-600 transition-colors">Posyandu Matahari</h3>
                <p class="text-gray-600 mb-6 text-center leading-relaxed">Jl. Matahari No. 56, Kel. Cerah, Kec. Selatan</p>
                <div class="flex items-center justify-center space-x-4 mb-6 text-sm">
                    <span class="flex items-center text-green-600">
                        <i class="fas fa-clock mr-1"></i> Setiap Jumat 08.00
                    </span>
                    <span class="flex items-center text-blue-600">
                        <i class="fas fa-users mr-1"></i> 55 Anak
                    </span>
                </div>
                <a href="https://www.google.com/maps/search/?api=" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white py-4 px-6 rounded-2xl font-bold text-center block hover:from-yellow-600 hover:to-orange-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-directions mr-2"></i>Lihat Detail
                </a>
            </div>

            <!-- Posyandu 6 -->
            <div class="group bg-white/70 backdrop-blur-sm rounded-3xl p-8 shadow-xl hover:shadow-2xl hover:-translate-y-4 transition-all duration-500 border border-indigo-100 hover:border-indigo-200">
                <div class="w-20 h-20 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-2xl flex items-center justify-center mb-6 mx-auto shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-dove text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center group-hover:text-indigo-600 transition-colors">Posyandu Merpati</h3>
                <p class="text-gray-600 mb-6 text-center leading-relaxed">Jl. Merpati No. 89, Kel. Damai, Kec. Tengah</p>
                <div class="flex items-center justify-center space-x-4 mb-6 text-sm">
                    <span class="flex items-center text-green-600">
                        <i class="fas fa-clock mr-1"></i> Setiap Sabtu 09.30
                    </span>
                    <span class="flex items-center text-blue-600">
                        <i class="fas fa-users mr-1"></i> 65 Anak
                    </span>
                </div>
                <a href="https://www.google.com/maps/search/?api=" class="w-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white py-4 px-6 rounded-2xl font-bold text-center block hover:from-indigo-600 hover:to-purple-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-directions mr-2"></i>Lihat Detail
                </a>
            </div>
        </div>

        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-24 bg-gradient-to-br from-pink-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-pink-600 to-blue-600 bg-clip-text text-transparent mb-6">
                Layanan Lengkap untuk Keluarga
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Dari pemeriksaan rutin sampai imunisasi, semua ada di Posyandu Sehatku!
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center group">
                <div class="w-24 h-24 bg-gradient-to-r from-pink-400 to-pink-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-baby-carriage text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Pemeriksaan Bayi</h3>
                <p class="text-gray-600 text-lg leading-relaxed">Timbang berat badan, tinggi badan, dan perkembangan anak secara rutin</p>
            </div>

            <div class="text-center group">
                <div class="w-24 h-24 bg-gradient-to-r from-blue-400 to-blue-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-syringe text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Imunisasi</h3>
                <p class="text-gray-600 text-lg leading-relaxed">Vaksin lengkap dan gratis sesuai jadwal program kesehatan nasional</p>
            </div>

            <div class="text-center group">
                <div class="w-24 h-24 bg-gradient-to-r from-purple-400 to-purple-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl group-hover:scale-110 transition-all duration-300">
                    <i class="fas fa-hand-holding-heart text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Konseling Ibu</h3>
                <p class="text-gray-600 text-lg leading-relaxed">Konsultasi gizi, MP-ASI, dan kesehatan ibu hamil secara gratis</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 bg-gradient-to-r from-pink-500 to-blue-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 text-center text-white">
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">6</div>
                <div class="text-xl font-semibold opacity-90">Posyandu</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">350+</div>
                <div class="text-xl font-semibold opacity-90">Anak Terlayani</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">50+</div>
                <div class="text-xl font-semibold opacity-90">Tenaga Medis</div>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-bold mb-2">100%</div>
                <div class="text-xl font-semibold opacity-90">Gratis</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-gradient-to-r from-pink-400 to-blue-400 rounded-3xl p-12 shadow-2xl">
            <div class="w-24 h-24 bg-white rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-xl">
                <i class="fas fa-heart-broken text-pink-500 text-4xl"></i>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Jangan Lewatkan Pemeriksaan Anak!
            </h2>
            <p class="text-xl text-white/90 mb-12 max-w-2xl mx-auto leading-relaxed">
                Datangi Posyandu terdekat sekarang juga. Kesehatan anak adalah investasi masa depan!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="/whatsapp-generator" target="_blank" class="group bg-transparent border-2 border-white text-white px-10 py-5 rounded-2xl font-bold text-xl hover:bg-white hover:text-pink-600 transition-all duration-300 flex items-center space-x-3 w-full sm:w-auto justify-center">
                    <i class="fab fa-whatsapp text-2xl group-hover:text-green-500 transition-colors"></i>
                    <span>Hubungi WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endsection