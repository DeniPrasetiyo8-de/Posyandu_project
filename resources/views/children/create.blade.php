@extends('layouts.app')

@section('title', 'Tambah Data Anak')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 py-12">
    <div class="max-w-2xl mx-auto p-8">
        <div class="text-center mb-12">
            <i class="fas fa-baby-carriage text-6xl text-pink-400 mb-6 bg-pink-500/20 p-6 rounded-3xl"></i>
            <h1 class="text-4xl font-bold text-white mb-4">Tambah Data Anak</h1>
            <p class="text-xl text-slate-300">Isi data lengkap anak untuk pemantauan KMS</p>
        </div>

        <form action="{{ route('children.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-800/50 backdrop-blur-xl rounded-3xl p-10 border border-slate-700 shadow-2xl">
            @csrf
            
            <!-- Nama -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Nama Lengkap Anak</label>
                <input type="text" name="nama" value="{{ old('nama') }}" 
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20 transition-all @error('nama') border-red-500 @enderror"
                       placeholder="Masukkan nama lengkap anak">
                @error('nama')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Kelamin -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Jenis Kelamin</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center p-5 bg-slate-700/50 border-2 border-slate-600 rounded-2xl cursor-pointer hover:border-pink-500 transition-all @error('jenis_kelamin') border-red-500 @enderror">
                        <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} class="w-5 h-5 text-pink-500 bg-slate-700 border-slate-600 focus:ring-pink-500 mr-4">
                        <span class="text-lg font-semibold text-white">Laki-laki</span>
                    </label>
                    <label class="flex items-center p-5 bg-slate-700/50 border-2 border-slate-600 rounded-2xl cursor-pointer hover:border-pink-500 transition-all @error('jenis_kelamin') border-red-500 @enderror">
                        <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} class="w-5 h-5 text-pink-500 bg-slate-700 border-slate-600 focus:ring-pink-500 mr-4">
                        <span class="text-lg font-semibold text-white">Perempuan</span>
                    </label>
                </div>
                @error('jenis_kelamin')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Lahir -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}"
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20 transition-all @error('tanggal_lahir') border-red-500 @enderror">
                @error('tanggal_lahir')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Berat Badan -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Berat Badan (Kg)</label>
                <input type="number" name="berat_badan" step="0.01" value="{{ old('berat_badan') }}" min="0"
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all @error('berat_badan') border-red-500 @enderror"
                       placeholder="0.00">
                @error('berat_badan')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tinggi Badan -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Tinggi Badan (Cm)</label>
                <input type="number" name="tinggi_badan" step="0.1" value="{{ old('tinggi_badan') }}" min="0"
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all @error('tinggi_badan') border-red-500 @enderror"
                       placeholder="0.0">
                @error('tinggi_badan')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Profil -->
            <div class="mb-12">
                <label class="block text-white font-bold mb-4 text-xl">Foto Profil Anak</label>
                <div class="relative">
                    <input type="file" name="foto" accept="image/*" 
                           class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-dashed border-slate-600 text-white text-lg file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-lg file:font-bold file:bg-gradient-to-r file:from-pink-500 file:to-purple-500 file:text-white file:cursor-pointer hover:file:bg-gradient-to-r hover:file:from-pink-600 hover:file:to-purple-600 focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20 transition-all @error('foto') border-red-500 @enderror cursor-pointer">
                    <p class="text-slate-400 mt-2 text-sm">Upload foto ukuran maks 2MB (JPG, PNG)</p>
                </div>
                @error('foto')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Posyandu -->
            <div class="mb-12">
                <label class="block text-white font-bold mb-4 text-xl">Posyandu</label>
                <select name="posyandu_id" class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 transition-all @error('posyandu_id') border-red-500 @enderror">
                    @foreach($posyandus as $p)
                        <option value="{{ $p->id }}" {{ old('posyandu_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_posyandu }}</option>
                    @endforeach
                </select>
                @error('posyandu_id')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white py-5 px-8 rounded-2xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all">
                    <i class="fas fa-save mr-2"></i>Simpan Data Anak
                </button>
                <a href="{{ route('dashboard.informasi.anak') }}" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white py-5 px-8 rounded-2xl font-bold text-xl text-center shadow-xl hover:shadow-2xl transition-all flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
