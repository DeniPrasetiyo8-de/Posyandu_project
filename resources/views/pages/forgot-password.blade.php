@extends('layouts.app')
@section('no-navbar', true)

@section('title', 'Lupa Password')

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
            <h1 class="text-3xl font-bold text-gray-800">Lupa Password</h1>
            <p class="text-gray-500 mt-1">Verifikasi identitas untuk reset password</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-4 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/forgot-password') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('name') border-red-500 @enderror" placeholder="Nama lengkap Anda">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">No Telepon</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('phone') border-red-500 @enderror" placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="rw" class="block text-sm font-medium text-gray-700 mb-2">RW</label>
                        <select id="rw" name="rw" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('rw') border-red-500 @enderror">
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

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-pink-500 hover:from-blue-600 hover:to-pink-600 text-white font-semibold py-3 px-6 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Verifikasi Identitas
                    </button>
                </div>
            </form>

            <div class="text-center mt-6 pt-6 border-t border-gray-100">
                <p class="text-gray-500 text-sm">
                    Ingat password? 
                    <a href="{{ url('/login') }}" class="font-semibold text-blue-500 hover:text-blue-600">Masuk sekarang</a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
