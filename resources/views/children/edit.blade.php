@extends('layouts.app')

@section('title', 'Edit Data Anak')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto p-8">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center text-white text-3xl mx-auto mb-4 shadow-lg">
                <i class="fas fa-edit"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Data Anak</h1>
            <p class="text-gray-500">Perbarui informasi {{ $child->nama }}</p>
        </div>

        <!-- Foto Preview -->
        <div class="text-center mb-6">
            @if($child->foto_url)
                <img src="{{ $child->foto_url }}" alt="Foto {{ $child->nama }}" class="w-32 h-32 rounded-full mx-auto object-cover shadow-lg mb-2 border-4 border-yellow-200">
                <p class="text-gray-500 text-sm">Foto saat ini</p>
            @else
                <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto flex items-center justify-center text-gray-400 text-3xl mb-2">
                    <i class="fas fa-user-child"></i>
                </div>
                <p class="text-gray-400 text-sm">Belum ada foto</p>
            @endif
        </div>

<form action="{{ route('children.update', $child->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg">
            @csrf
            @method('PUT')
            
            <!-- NIK -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', $child->nik) }}" 
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all @error('nik') border-red-500 @enderror"
                       placeholder="Masukkan NIK (16 digit)">
                @error('nik')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $child->nama) }}" 
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all @error('nama') border-red-500 @enderror"
                       placeholder="Nama lengkap anak">
                @error('nama')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Kelamin -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Jenis Kelamin</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-yellow-400 transition-all @error('jenis_kelamin') border-red-500 @enderror {{ old('jenis_kelamin', $child->jenis_kelamin) == 'L' ? 'border-yellow-400 bg-yellow-50' : '' }}">
                        <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $child->jenis_kelamin) == 'L' ? 'checked' : '' }} class="w-5 h-5 text-yellow-500 mr-3">
                        <span class="font-semibold text-gray-700">Laki-laki</span>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-yellow-400 transition-all @error('jenis_kelamin') border-red-500 @enderror {{ old('jenis_kelamin', $child->jenis_kelamin) == 'P' ? 'border-yellow-400 bg-yellow-50' : '' }}">
                        <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $child->jenis_kelamin) == 'P' ? 'checked' : '' }} class="w-5 h-5 text-yellow-500 mr-3">
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
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $child->tanggal_lahir ? date('Y-m-d', strtotime($child->tanggal_lahir)) : '') }}" max="{{ date('Y-m-d') }}"
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all @error('tanggal_lahir') border-red-500 @enderror">
                @error('tanggal_lahir')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Berat Badan -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Berat Badan Saat Ini (Kg)</label>
                <input type="number" name="berat_badan" step="0.01" value="{{ old('berat_badan', $child->berat_badan) }}" min="0"
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all @error('berat_badan') border-red-500 @enderror"
                       placeholder="0.00">
                @error('berat_badan')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tinggi Badan -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Tinggi Badan Saat Ini (Cm)</label>
                <input type="number" name="tinggi_badan" step="0.1" value="{{ old('tinggi_badan', $child->tinggi_badan ?? '') }}" min="0"
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('tinggi_badan') border-red-500 @enderror"
                       placeholder="0.0">
                @error('tinggi_badan')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Baru -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Ganti Foto Profil (Opsional)</label>
                <input type="file" name="foto" accept="image/*" 
                       class="w-full p-4 rounded-xl border-2 border-dashed border-gray-300 text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-yellow-500 file:text-white file:cursor-pointer hover:file:bg-yellow-600 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 transition-all @error('foto') border-red-500 @enderror">
                <p class="text-gray-400 mt-1 text-sm">Upload foto baru (JPG, PNG) max 2MB - biarkan kosong untuk tetap foto lama</p>
                @error('foto')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Posyandu -->
            <div class="mb-8">
                <label class="block text-gray-700 font-bold mb-2">Posyandu</label>
                <select name="posyandu_id" class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all @error('posyandu_id') border-red-500 @enderror">
                    @foreach($posyandus as $p)
                        <option value="{{ $p->id }}" {{ old('posyandu_id', $child->posyandu_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_posyandu }}</option>
                    @endforeach
                </select>
                @error('posyandu_id')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white py-4 px-6 rounded-full font-bold text-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>Update Data Anak
                </button>
                <a href="{{ route('dashboard.informasi.anak') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-4 px-6 rounded-full font-bold text-lg text-center transition-all flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
