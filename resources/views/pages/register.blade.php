@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<section class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-pink-400 via-blue-400 to-purple-500">
    <div class="max-w-md w-full space-y-8">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white/10 rounded-full blur-3xl animate-pulse delay-500"></div>
        
        <div>
            <div class="mx-auto h-16 w-16 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm shadow-2xl">
                <i class="fas fa-user-plus text-white text-3xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-white">
                Buat Akun Baru
            </h2>
            <p class="mt-2 text-center text-white/90 text-sm">
                Daftar untuk mengakses layanan Posyandu
            </p>
        </div>

        @if (session('success'))
            <div class="bg-green-500/20 backdrop-blur-sm border border-green-400 text-green-100 px-4 py-3 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        <form class="mt-8 space-y-6 bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20" method="POST" action="{{ route('register.post') }}">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-white mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" required class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all @error('name') border-red-500 @enderror" placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-white mb-2">No Telepon</label>
                <input type="tel" id="phone" name="phone" required class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all @error('phone') border-red-500 @enderror" placeholder="08xxxxxxxxxx">
                @error('phone')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-white mb-2">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="2" required class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all @error('address') border-red-500 @enderror" placeholder="Masukkan alamat lengkap"></textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="rt" class="block text-sm font-medium text-white mb-2">RT</label>
                    <input type="text" id="rt" name="rt" required class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all @error('rt') border-red-500 @enderror" placeholder="001">
                    @error('rt')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="rw" class="block text-sm font-medium text-white mb-2">RW</label>
class="w-full px-4 py-3 bg-amber-500/20 border border-amber-400/50 rounded-2xl text-white focus:ring-2 focus:ring-amber-500/50 focus:border-transparent transition-all @error('rw') border-red-500 @enderror"

                        <option value="">Pilih RW</option>
                        <option value="1">RW 1</option>
                        <option value="2">RW 2</option>
                        <option value="3">RW 3</option>
                        <option value="4">RW 4</option>
                        <option value="5">RW 5</option>
                        <option value="6">RW 6</option>
                    </select>
                    @error('rw')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-white mb-2">Password</label>
                <input type="password" id="password" name="password" required minlength="8" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all @error('password') border-red-500 @enderror" placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-2xl text-white placeholder-white/70 focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all" placeholder="Ulangi password">
            </div>

            <button type="submit" class="group w-full bg-gradient-to-r from-pink-500 to-blue-500 hover:from-pink-600 hover:to-blue-600 text-white font-bold py-4 px-6 rounded-2xl shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>Daftar Sekarang</span>
            </button>
        </form>

        <div class="text-center">
            <p class="text-white/80">
                Sudah punya akun? 
                <a href="/login" class="font-bold text-white hover:text-pink-200 transition-colors">Masuk sekarang</a>
            </p>
        </div>
    </div>
</section>
@endsection

