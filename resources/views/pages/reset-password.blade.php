@extends('layouts.app')
@section('no-navbar', true)

@section('title', 'Reset Password')

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
                <i class="fas fa-key text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Password Baru</h1>
            <p class="text-gray-500 mt-1">Masukkan password baru Anda</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-4 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/reset-password') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" id="password" name="password" required minlength="8" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror" placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300" placeholder="Ulangi password">
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-pink-500 hover:from-blue-600 hover:to-pink-600 text-white font-semibold py-3 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Password
                    </button>
                </div>
            </form>

            <div class="text-center mt-6 pt-6 border-t border-gray-100">
                <p class="text-gray-500 text-sm">
                    Batal? 
                    <a href="{{ url('/forgot-password') }}" class="font-semibold text-blue-500 hover:text-blue-600">Kembali</a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
