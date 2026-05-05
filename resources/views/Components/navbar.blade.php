<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-9xl mx-auto px-4 lg:px-8 py-4 flex justify-between items-center">

        <div class="flex items-center gap-3">
            <img src="{{ asset('images/Sipandu Logo.PNG') }}" alt="Logo" class="w-10 h-10 object-contain">
            <span class="font-bold text-xl text-gray-800">SIPANDU</span>
        </div>

        <div class="flex items-center gap-6">
            @auth
                @php
                    $userRole = Auth::user()->role;
                @endphp
                
                <!-- Menu berdasarkan Role -->
                <div class="hidden md:flex items-center gap-2">
                    @if($userRole === 'admin')
                        <!-- Admin Menu -->
                        <a href="/admin/dashboard" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg transition-all hover:bg-gray-100 flex items-center space-x-1">
                            <i class="fas fa-tachometer-alt text-sm"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="/admin/jadwal" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg transition-all hover:bg-gray-100 flex items-center space-x-1">
                            <i class="fas fa-calendar-plus text-sm"></i>
                            <span>Jadwal</span>
                        </a>
                        <div class="relative group">
                            <button class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg transition-all hover:bg-gray-100 flex items-center space-x-1">
                                <i class="fas fa-search text-sm"></i>
                                <span>Informasi</span>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="/admin/informasi/anak" class="block px-4 py-3 text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-t-xl">
                                    <i class="fas fa-child mr-2"></i>Cari Anak
                                </a>
                                <a href="/admin/informasi/ibu" class="block px-4 py-3 text-gray-600 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-user-pregnant mr-2"></i>Cari Ibu
                                </a>
                            </div>
                        </div>
                        <a href="/admin/kms" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg transition-all hover:bg-gray-100 flex items-center space-x-1">
                            <i class="fas fa-chart-bar text-sm"></i>
                            <span>KMS Analytics</span>
                        </a>
                        <a href="/admin/kader" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg transition-all hover:bg-gray-100 flex items-center space-x-1">
                            <i class="fas fa-users text-sm"></i>
                            <span>Kader</span>
                        </a>
                    @else
                        <!-- User Menu (orang_tua) -->
                        <a href="{{ route('dashboard.index') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg transition-all hover:bg-gray-100 flex items-center space-x-1">
                            <i class="fas fa-home text-sm"></i>
                            <span>Beranda</span>
                        </a>
                        
<!-- Menu Informasi Langsung ke Halaman Informasi -->
                        <a href="{{ route('dashboard.informasi.anak') }}" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg transition-all hover:bg-gray-100 flex items-center space-x-1">
                            <i class="fas fa-info-circle text-sm"></i>
                            <span>Informasi</span>
                        </a>

                        <a href="{{ route('dashboard.kms') }}" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-lg transition-all hover:bg-gray-100 flex items-center space-x-1">
                            <i class="fas fa-chart-line text-sm"></i>
                            <span>KMS</span>
                        </a>
                        
                        <a href="{{ route('dashboard.kader') }}" class="text-gray-600 hover:text-green-600 px-3 py-2 rounded-lg transition-all hover:bg-gray-100 flex items-center space-x-1">
                            <i class="fas fa-user-md text-sm"></i>
                            <span>Kader</span>
                        </a>
                        
                        <a href="{{ route('dashboard.artikel') }}" class="text-gray-600 hover:text-pink-600 px-3 py-2 rounded-lg transition-all hover:bg-gray-100 flex items-center space-x-1">
                            <i class="fas fa-newspaper text-sm"></i>
                            <span>Artikel</span>
                        </a>
                    @endif
                </div>

                <!-- User Info & Logout -->
                <div class="flex items-center gap-4">
                    @if(Auth::user()->role === 'admin')
                        <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-bold">
                            <i class="fas fa-crown mr-1"></i>ADMIN
                        </span>
                    @endif
                    <span class="text-gray-800 font-semibold hidden sm:inline truncate max-w-32">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-4 py-2 rounded-full font-bold transition-all hover:shadow-lg flex items-center space-x-1">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>

                <!-- Mobile menu button -->
                <button class="md:hidden text-gray-800 p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            @else
                <a href="{{ route('login') }}" class="bg-gradient-to-r from-emerald-500 to-blue-500 hover:from-emerald-600 hover:to-blue-600 text-white px-6 py-2 rounded-full font-bold transition-all hover:shadow-lg flex items-center space-x-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk</span>
                </a>
            @endauth
        </div>

    </div>
</nav>
