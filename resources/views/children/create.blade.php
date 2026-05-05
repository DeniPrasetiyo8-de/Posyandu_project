@extends('layouts.app')

@section('title', 'Tambah Data Anak')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto p-8">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-pink-400 to-purple-500 rounded-2xl flex items-center justify-center text-white text-3xl mx-auto mb-4 shadow-lg">
                <i class="fas fa-baby-carriage"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Tambah Data Anak</h1>
            <p class="text-gray-500">Isi data lengkap anak untuk pemantauan KMS</p>
        </div>

<form action="{{ route('children.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg">
            @csrf
            
            <!-- NIK -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">NIK</label>
                <input type="text" name="nik" value="{{ old('nik') }}" 
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all @error('nik') border-red-500 @enderror"
                       placeholder="Masukkan NIK (16 digit)">
                @error('nik')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Nama Lengkap Anak</label>
                <input type="text" name="nama" value="{{ old('nama') }}" 
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all @error('nama') border-red-500 @enderror"
                       placeholder="Masukkan nama lengkap anak">
                @error('nama')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Kelamin -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Jenis Kelamin</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-pink-400 transition-all @error('jenis_kelamin') border-red-500 @enderror">
                        <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} class="w-5 h-5 text-pink-500 mr-3">
                        <span class="font-semibold text-gray-700">Laki-laki</span>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-pink-400 transition-all @error('jenis_kelamin') border-red-500 @enderror">
                        <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} class="w-5 h-5 text-pink-500 mr-3">
                        <span class="font-semibold text-gray-700">Perempuan</span>
                    </label>
                </div>
                @error('jenis_kelamin')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Lahir -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}"
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all @error('tanggal_lahir') border-red-500 @enderror">
                @error('tanggal_lahir')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Berat Badan -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Berat Badan (Kg)</label>
                <input type="number" name="berat_badan" step="0.01" value="{{ old('berat_badan') }}" min="0"
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all @error('berat_badan') border-red-500 @enderror"
                       placeholder="0.00">
                @error('berat_badan')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tinggi Badan -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Tinggi Badan (Cm)</label>
                <input type="number" name="tinggi_badan" step="0.1" value="{{ old('tinggi_badan') }}" min="0"
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('tinggi_badan') border-red-500 @enderror"
                       placeholder="0.0">
                @error('tinggi_badan')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Profil -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Foto Profil Anak</label>
                <input type="file" name="foto" accept="image/*" 
                       class="w-full p-4 rounded-xl border-2 border-dashed border-gray-300 text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-500 file:text-white file:cursor-pointer hover:file:bg-pink-600 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all @error('foto') border-red-500 @enderror">
                <p class="text-gray-400 mt-1 text-sm">Upload foto ukuran maks 2MB (JPG, PNG)</p>
                @error('foto')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Posyandu -->
            <div class="mb-8">
                <label class="block text-gray-700 font-bold mb-2">Posyandu</label>
                <select name="posyandu_id" class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all @error('posyandu_id') border-red-500 @enderror">
                    @foreach($posyandus as $p)
                        <option value="{{ $p->id }}" {{ old('posyandu_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_posyandu }}</option>
                    @endforeach
                </select>
                @error('posyandu_id')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-pink-500 to-purple-500 hover:from-pink-600 hover:to-purple-600 text-white py-4 px-6 rounded-full font-bold text-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>Simpan Data Anak
                </button>
                <a href="{{ route('dashboard.informasi.anak') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-4 px-6 rounded-full font-bold text-lg text-center transition-all flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
