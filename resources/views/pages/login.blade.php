@extends('layouts.app')
@section('no-navbar', true)

@section('title', 'Masuk')

@section('content')
<section class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-pink-400 via-blue-400 to-purple-500 relative overflow-hidden">
    <!-- Animated backgrounds -->
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse delay-500"></div>
    
    <div class="max-w-2xl w-full">
        <!-- Tabs -->
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-1 border border-white/20 mb-8">
<nav class="flex space-x-1 rounded-2xl bg-white/20 backdrop-blur-sm p-1">
                <a href="#parent-tab" class="tab-button flex py-3 px-6 text-center font-bold rounded-xl transition-all duration-300 tab-active">
                    <i class="fas fa-child mr-2"></i>Orang Tua
                </a>
                <a href="#admin-tab" class="tab-button flex py-3 px-6 text-center font-bold rounded-xl transition-all duration-300 text-white/70 hover:text-white">
                    <i class="fas fa-user-shield mr-2"></i>Admin
                </a>
                <a href="#register-tab" class="tab-button flex py-3 px-6 text-center font-bold rounded-xl transition-all duration-300 text-white/70 hover:text-white">
                    <i class="fas fa-user-plus mr-2"></i>Daftar
                </a>
            </nav>
        </div>

        <!-- Orang Tua Login Form (default) -->
        <div id="parent-tab" class="form-container bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 tab-content-active">
            <div class="text-center mb-8">
                <div class="mx-auto h-20 w-20 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm shadow-2xl mb-4">
                     <img src="{{ asset('images/LG1.PNG') }}" alt="" class="w-full h-full object-cover rounded-full">
                    <i class="fas fa-child text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Login Orang Tua</h2>
                <p class="text-white/90 text-lg">Masuk dengan data RW Anda</p>
            </div>

            @if (session('success'))
                <div class="bg-green-500/20 backdrop-blur-sm border border-green-400 text-green-100 px-6 py-4 rounded-2xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <input type="hidden" name="role" value="orang_tua">
                <div class="space-y-6">
                    <div>
                        <label for="parent-name" class="block text-sm font-medium text-white mb-3">Nama Lengkap</label>
                        <input type="text" id="parent-name" name="name" value="{{ old('name') }}" required class="w-full px-5 py-4 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/60 focus:ring-3 focus:ring-pink-500/50 focus:border-transparent transition-all duration-300 text-lg @error('name') border-red-500 ring-red-500/50 @enderror" placeholder="Nama anda">
                        @error('name')
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                       <label for="parent-rw" class="block text-sm font-medium text-white mb-2">RW</label>
                    <select id="parent-rw" name="rw" required class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-black focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all @error('rw') border-red-500 ring-red-500/50 @enderror">
                            <option value="">Pilih RW</option>
                            <option value="1" {{ old('rw') == '1' ? 'selected' : '' }}>RW 01</option>
                            <option value="2" {{ old('rw') == '2' ? 'selected' : '' }}>RW 02</option>
                            <option value="3" {{ old('rw') == '3' ? 'selected' : '' }}>RW 03</option>
                            <option value="4" {{ old('rw') == '4' ? 'selected' : '' }}>RW 04</option>
                            <option value="5" {{ old('rw') == '5' ? 'selected' : '' }}>RW 05</option>
                            <option value="6" {{ old('rw') == '6' ? 'selected' : '' }}>RW 06</option>
                        </select>
                        @error('rw')
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="parent-password" class="block text-sm font-medium text-white mb-3">Password</label>
                        <div class="relative">
                            <input type="password" id="parent-password" name="password" required class="w-full pl-12 pr-5 py-4 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/60 focus:ring-3 focus:ring-pink-500/50 focus:border-transparent transition-all duration-300 text-lg @error('password') border-red-500 ring-red-500/50 @enderror" placeholder="Password anda">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-white/70"></i>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" class="group w-full bg-gradient-to-r from-emerald-500 to-blue-600 hover:from-emerald-600 hover:to-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center space-x-3 text-lg">
                        <i class="fas fa-sign-in-alt group-hover:translate-x-1 transition-transform"></i>
                        <span>Masuk Orang Tua</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Admin Login Form -->
        <div id="admin-tab" class="form-container bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 tab-content hidden">
            <div class="text-center mb-8">
                <div class="mx-auto h-20 w-20 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm shadow-2xl mb-4">
                     <img src="{{ asset('images/LG2.JPG') }}" alt="" class="w-full h-full object-cover rounded-full">
                    <i class="fas fa-user-shield text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Login Admin</h2>
                <p class="text-white/90 text-lg">Panel administrator SIPANDU</p>
            </div>

            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <input type="hidden" name="role" value="admin">
                <div class="space-y-6">
                    <div>
                        <label for="admin-email" class="block text-sm font-medium text-white mb-3">Email Admin</label>
                        <input type="email" id="admin-email" name="email" required class="w-full px-5 py-4 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/60 focus:ring-3 focus:ring-yellow-500/50 focus:border-transparent transition-all duration-300 text-lg" placeholder="Email">
                        @error('email')
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="admin-password" class="block text-sm font-medium text-white mb-3">Password Admin</label>
                        <div class="relative">
                            <input type="password" id="admin-password" name="password" required class="w-full pl-12 pr-5 py-4 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/60 focus:ring-3 focus:ring-yellow-500/50 focus:border-transparent transition-all duration-300 text-lg @error('password') border-red-500 ring-red-500/50 @enderror" placeholder="Password">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-white/70"></i>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" class="group w-full bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white font-bold py-4 px-6 rounded-2xl shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center space-x-3 text-lg">
                        <i class="fas fa-user-shield group-hover:translate-x-1 transition-transform"></i>
                        <span>Masuk Admin</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Register Form (hidden initially) -->
        <div id="register-tab" class="form-container bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 tab-content hidden">
            <div class="text-center mb-8">
                <div class="mx-auto h-20 w-20 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm shadow-2xl mb-4">
                     <img src="{{ asset('images/LG3.PNG') }}" alt="" class="w-full h-full object-cover rounded-full">
                    <i class="fas fa-seedling text-white text-3x1"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Buat Akun Baru</h2>
                <p class="text-white/90 text-lg">Daftar untuk mengakses layanan Posyandu</p>
            </div>

            @if (session('success'))
                <div class="bg-green-500/20 backdrop-blur-sm border border-green-400 text-green-100 px-6 py-4 rounded-2xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/register') }}">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="reg-name" class="block text-sm font-medium text-white mb-3">Nama Lengkap</label>
                        <input type="text" id="reg-name" name="name" value="{{ old('name') }}" required class="w-full px-5 py-4 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/60 focus:ring-3 focus:ring-pink-500/50 focus:border-transparent transition-all duration-300 text-lg @error('name') border-red-500 ring-red-500/50 @enderror" placeholder="Nama lengkap anda">
                        @error('name')
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone and Address etc would be here in full version -->
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="reg-phone" class="block text-sm font-medium text-white mb-3">No Telepon</label>
                            <input type="tel" id="reg-phone" name="phone" value="{{ old('phone') }}" required class="w-full px-5 py-4 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/60 focus:ring-3 focus:ring-pink-500/50 focus:border-transparent transition-all duration-300 text-lg @error('phone') border-red-500 ring-red-500/50 @enderror" placeholder="08xxxxxxxxxx">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="reg-address" class="block text-sm font-medium text-white mb-3">Alamat Lengkap</label>
                            <textarea id="reg-address" name="address" rows="2" required class="w-full px-5 py-4 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/60 focus:ring-3 focus:ring-pink-500/50 focus:border-transparent transition-all duration-300 text-lg @error('address') border-red-500 ring-red-500/50 @enderror" placeholder="Alamat lengkap anda..."></textarea>
                            @error('address')
                                <p class="mt-2 text-sm text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="reg-rt" class="block text-sm font-medium text-white mb-3">RT</label>
                            <input type="text" id="reg-rt" name="rt" value="{{ old('rt') }}" required class="w-full px-5 py-4 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/60 focus:ring-3 focus:ring-pink-500/50 focus:border-transparent transition-all duration-300 text-lg @error('rt') border-red-500 ring-red-500/50 @enderror" placeholder="001">
                            @error('rt')
                                <p class="mt-2 text-sm text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="reg-rw" class="block text-sm font-medium text-white mb-3">RW</label>
                            <select id="reg-rw" name="rw" required class="w-full px-5 py-4 bg-white/20 border border-white/30 rounded-2xl text-black focus:ring-3 focus:ring-pink-500/50 focus:border-transparent transition-all duration-300 text-lg @error('rw') border-red-500 ring-red-500/50 @enderror">
                                <option value="">Pilih RW</option>
                                <option value="1" {{ old('rw') == '1' ? 'selected' : '' }}>RW 01</option>
                                <option value="2" {{ old('rw') == '2' ? 'selected' : '' }}>RW 02</option>
                                <option value="3" {{ old('rw') == '3' ? 'selected' : '' }}>RW 03</option>
                                <option value="4" {{ old('rw') == '4' ? 'selected' : '' }}>RW 04</option>
                                <option value="5" {{ old('rw') == '5' ? 'selected' : '' }}>RW 05</option>
                                <option value="6" {{ old('rw') == '6' ? 'selected' : '' }}>RW 06</option>
                            </select>
                            @error('rw')
                                <p class="mt-2 text-sm text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="reg-password" class="block text-sm font-medium text-white mb-3">Password</label>
                            <input type="password" id="reg-password" name="password" required minlength="8" class="w-full px-5 py-4 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/60 focus:ring-3 focus:ring-pink-500/50 focus:border-transparent transition-all duration-300 text-lg @error('password') border-red-500 ring-red-500/50 @enderror" placeholder="Minimal 8 karakter">
                            @error('password')
                                <p class="mt-2 text-sm text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="reg-password_confirmation" class="block text-sm font-medium text-white mb-3">Konfirmasi Password</label>
                            <input type="password" id="reg-password_confirmation" name="password_confirmation" required class="w-full px-5 py-4 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/60 focus:ring-3 focus:ring-pink-500/50 focus:border-transparent transition-all duration-300 text-lg" placeholder="Ulangi password">
                        </div>
                    </div>

                    <button type="submit" class="group w-full bg-gradient-to-r from-pink-500 to-emerald-500 hover:from-pink-600 hover:to-emerald-600 text-white font-bold py-5 px-6 rounded-2xl shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center space-x-3 text-lg">
                        <i class="fas fa-user-plus group-hover:translate-x-1 transition-transform"></i>
                        <span>Daftar Akun Baru</span>
                    </button>
                </div>
            </form>

            <div class="text-center mt-8 pt-8 border-t border-white/20">
                <p class="text-white/80">
                    Sudah punya akun? 
                    <a href="/login" class="font-bold text-emerald-200 hover:text-emerald-100 transition-colors">Masuk sekarang</a>
                </p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const formContainers = document.querySelectorAll('.form-container');
    
    // Fungsi untuk switch tab
    function switchTab(targetId) {
        // Reset semua tab buttons
        tabButtons.forEach(btn => {
            btn.classList.remove('tab-active');
            btn.classList.add('text-white/70');
            btn.style.background = '';
            btn.style.color = '';
        });
        
        // Reset semua form containers
        formContainers.forEach(container => {
            container.classList.add('hidden');
            container.classList.remove('tab-content-active');
        });
        
        // Aktifkan tab yang dipilih
        const activeButton = document.querySelector(`[href="#${targetId}"]`);
        if (activeButton) {
            activeButton.classList.add('tab-active');
            activeButton.classList.remove('text-white/70');
        }
        
        // Tampilkan form yang dipilih
        const targetContainer = document.getElementById(targetId);
        if (targetContainer) {
            targetContainer.classList.remove('hidden');
            targetContainer.classList.add('tab-content-active');
        }
    }
    
    // Event listener untuk tab buttons
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            switchTab(targetId);
        });
    });
    
    // Event listener untuk link "Daftar sekarang" dan "Masuk sekarang"
    const switchLinks = document.querySelectorAll('a[href="#login-tab"], a[href="#register-tab"]');
    switchLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            switchTab(targetId);
        });
    });
    
    // Set default tab (login) saat halaman load
    switchTab('login-tab');
});
</script>

<style>
.tab-active {
    background: white;
    color: #ec4899 !important;
}
</style>
@endsection

