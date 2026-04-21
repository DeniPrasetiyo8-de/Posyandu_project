<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posyandu Sehatku - {{ $title ?? 'Home' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-poppins bg-gradient-to-br from-pink-50 via-blue-50">
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md shadow-lg fixed w-full z-50 border-b border-pink-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-pink-400 to-blue-400 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-baby text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-pink-500 to-blue-500 bg-clip-text text-transparent">
                        Posyandu Sehatku
                    </span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-pink-600 font-semibold hover:text-pink-700 transition-all">Beranda</a>
                    <a href="/posyandu" class="text-gray-700 hover:text-pink-600 font-medium transition-all">Posyandu</a>
                    <a href="/jadwal" class="text-gray-700 hover:text-pink-600 font-medium transition-all">Jadwal</a>
                    <a href="/kontak" class="text-gray-700 hover:text-pink-600 font-medium transition-all">Kontak</a>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-pink-600 hover:text-pink-700">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20 pb-12">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-pink-500 to-blue-500 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-heart text-white text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold">Posyandu Sehatku</h3>
                    </div>
                    <p class="text-white/90">Menciptakan generasi emas yang sehat dan ceria!</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Posyandu Kami</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white/80 transition">Posyandu Mawar</a></li>
                        <li><a href="#" class="hover:text-white/80 transition">Posyandu Melati</a></li>
                        <li><a href="#" class="hover:text-white/80 transition">Posyandu Anggrek</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Hubungi Kami</h4>
                    <div class="space-y-2 text-sm">
                        <p><i class="fas fa-phone mr-2"></i> 0812-3456-7890</p>
                        <p><i class="fas fa-envelope mr-2"></i> info@posyandusehatku.com</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/20 mt-8 pt-8 text-center">
                <p>&copy; 2024 Posyandu Sehatku. Dibuat dengan <i class="fas fa-heart text-red-400"></i> untuk ibu dan anak.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const nav = document.querySelector('nav');
            nav.classList.toggle('md:flex');
        });
    </script>
</body>
</html>