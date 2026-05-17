@extends('layouts.app')
@section('no-navbar', true)

@section('title', 'Masuk')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Poppins', sans-serif; }
</style>

<section class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 via-pink-50 to-purple-50 relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-200/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-pink-200/20 rounded-full blur-3xl"></div>
    
    <div class="max-w-md w-full">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-blue-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg mb-4">
                <i class="fas fa-heartbeat text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">SIPANDU</h1>
            <p class="text-gray-500 mt-1">Sistem Informasi Posyandu Desa Sehat</p>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-2xl p-1 shadow-lg border border-gray-100 mb-6">
            <div class="grid grid-cols-3 gap-1">
                <button onclick="switchTab('parent-tab')" id="tab-parent" class="tab-button py-3 px-4 text-center font-semibold rounded-xl transition-all duration-300 tab-active">
                    <i class="fas fa-child mr-1"></i>Orang Tua
                </button>
                <button onclick="switchTab('admin-tab')" id="tab-admin" class="tab-button py-3 px-4 text-center font-semibold rounded-xl transition-all duration-300 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-user-shield mr-1"></i>Admin
                </button>
                <button onclick="switchTab('register-tab')" id="tab-register" class="tab-button py-3 px-4 text-center font-semibold rounded-xl transition-all duration-300 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-user-plus mr-1"></i>Daftar
                </button>
            </div>
        </div>

        <!-- Orang Tua Login Form (default) -->
        <div id="parent-tab" class="form-container bg-white rounded-2xl p-8 shadow-lg border border-gray-100 tab-content-active">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Login Orang Tua</h2>
                <p class="text-gray-500 text-sm">Masuk dengan data RW Anda</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <input type="hidden" name="role" value="orang_tua">
                <div class="space-y-4">
                    <div>
                        <label for="parent-name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="parent-name" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('name') border-red-500 @enderror" placeholder="Nama anda">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="parent-rw" class="block text-sm font-medium text-gray-700 mb-2">RW</label>
                        <select id="parent-rw" name="rw" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('rw') border-red-500 @enderror">
                            <option value="">Pilih RW</option>
                            <option value="1" {{ old('rw') == '1' ? 'selected' : '' }}>RW 01</option>
                            <option value="2" {{ old('rw') == '2' ? 'selected' : '' }}>RW 02</option>
                            <option value="3" {{ old('rw') == '3' ? 'selected' : '' }}>RW 03</option>
                            <option value="4" {{ old('rw') == '4' ? 'selected' : '' }}>RW 04</option>
                            <option value="5" {{ old('rw') == '5' ? 'selected' : '' }}>RW 05</option>
                            <option value="6" {{ old('rw') == '6' ? 'selected' : '' }}>RW 06</option>
                        </select>
                        @error('rw')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="parent-password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="parent-password" name="password" required class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror" placeholder="Password anda">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

<button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-pink-500 hover:from-blue-600 hover:to-pink-600 text-white font-semibold py-3 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Masuk Orang Tua
                    </button>

                    <div class="text-center mt-4">
                        <a href="{{ url('/forgot-password') }}" class="text-sm text-blue-500 hover:text-blue-600">
                            <i class="fas fa-question-circle mr-1"></i>
                            Lupa Password?
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Admin Login Form -->
        <div id="admin-tab" class="form-container bg-white rounded-2xl p-8 shadow-lg border border-gray-100 tab-content hidden">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Login Admin</h2>
                <p class="text-gray-500 text-sm">Panel administrator SIPANDU</p>
            </div>

            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <input type="hidden" name="role" value="admin">
                <div class="space-y-4">
                    <div>
                        <label for="admin-email" class="block text-sm font-medium text-gray-700 mb-2">Email Admin</label>
                        <input type="email" id="admin-email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" placeholder="Email admin">
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admin-password" class="block text-sm font-medium text-gray-700 mb-2">Password Admin</label>
                        <div class="relative">
                            <input type="password" id="admin-password" name="password" required class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror" placeholder="Password">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white font-semibold py-3 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-user-shield mr-2"></i>
                        Masuk Admin
                    </button>
                </div>
            </form>
        </div>

        <!-- Register Form (hidden initially) -->
        <div id="register-tab" class="form-container bg-white rounded-2xl p-8 shadow-lg border border-gray-100 tab-content hidden">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Buat Akun Baru</h2>
                <p class="text-gray-500 text-sm">Daftar untuk mengakses layanan Posyandu</p>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/register') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="reg-name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="reg-name" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('name') border-red-500 @enderror" placeholder="Nama lengkap anda">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reg-phone" class="block text-sm font-medium text-gray-700 mb-2">No Telepon</label>
                        <input type="tel" id="reg-phone" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('phone') border-red-500 @enderror" placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reg-address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                        <textarea id="reg-address" name="address" rows="2" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('address') border-red-500 @enderror" placeholder="Alamat lengkap anda..."></textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="reg-rt" class="block text-sm font-medium text-gray-700 mb-2">RT</label>
                            <input type="text" id="reg-rt" name="rt" value="{{ old('rt') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('rt') border-red-500 @enderror" placeholder="001">
                            @error('rt')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="reg-rw" class="block text-sm font-medium text-gray-700 mb-2">RW</label>
                            <select id="reg-rw" name="rw" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('rw') border-red-500 @enderror">
                                <option value="">Pilih RW</option>
                                <option value="1" {{ old('rw') == '1' ? 'selected' : '' }}>RW 01</option>
                                <option value="2" {{ old('rw') == '2' ? 'selected' : '' }}>RW 02</option>
                                <option value="3" {{ old('rw') == '3' ? 'selected' : '' }}>RW 03</option>
                                <option value="4" {{ old('rw') == '4' ? 'selected' : '' }}>RW 04</option>
                                <option value="5" {{ old('rw') == '5' ? 'selected' : '' }}>RW 05</option>
                                <option value="6" {{ old('rw') == '6' ? 'selected' : '' }}>RW 06</option>
                            </select>
                            @error('rw')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="reg-password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="reg-password" name="password" required minlength="8" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror" placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reg-password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" id="reg-password_confirmation" name="password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" placeholder="Ulangi password">
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-purple-500 hover:from-pink-600 hover:to-purple-600 text-white font-semibold py-3 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar Akun Baru
                    </button>
                </div>
            </form>

            <div class="text-center mt-6 pt-6 border-t border-gray-100">
                <p class="text-gray-500 text-sm">
                    Sudah punya akun? 
                    <a href="#" onclick="switchTab('parent-tab')" class="font-semibold text-blue-500 hover:text-blue-600">Masuk sekarang</a>
                </p>
            </div>
        </div>
    </div>
</section>

<script>
function switchTab(targetId) {
    // Reset all tabs
    const tabs = ['parent', 'admin', 'register'];
    tabs.forEach(tab => {
        const btn = document.getElementById('tab-' + tab);
        const content = document.getElementById(tab + '-tab');
        
        if (tab + '-tab' === targetId) {
            btn.classList.add('tab-active');
            btn.classList.remove('text-gray-500');
            content.classList.remove('hidden');
            content.classList.add('tab-content-active');
        } else {
            btn.classList.remove('tab-active');
            btn.classList.add('text-gray-500');
            content.classList.add('hidden');
            content.classList.remove('tab-content-active');
        }
    });
}
</script>

<style>
.tab-active {
    background: linear-gradient(135deg, #3b82f6 0%, #ec4899 100%);
    color: white !important;
}
</style>
@endsection
