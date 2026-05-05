@extends('layouts.app')

@section('title', 'Edit Data Ibu')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto p-8">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-pink-400 to-purple-500 rounded-2xl flex items-center justify-center text-white text-3xl mx-auto mb-4 shadow-lg">
                <i class="fas fa-user-pregnant"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Data Ibu</h1>
            <p class="text-gray-500">Perbarui informasi {{ $mother->nama_lengkap }}</p>
        </div>

        <!-- Foto Preview -->
        <div class="text-center mb-6">
            @if($mother->foto_url)
                <img src="{{ $mother->foto_url }}" alt="Foto {{ $mother->nama_lengkap }}" class="w-32 h-32 rounded-full mx-auto object-cover shadow-lg mb-2 border-4 border-pink-200">
                <p class="text-gray-500 text-sm">Foto saat ini</p>
            @else
                <div class="w-32 h-32 bg-pink-100 rounded-full mx-auto flex items-center justify-center text-pink-400 text-4xl mb-2">
                    <i class="fas fa-user-pregnant"></i>
                </div>
                <p class="text-gray-400 text-sm">Belum ada foto</p>
            @endif
        </div>

        <form action="{{ route('mothers.update', $mother->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-8 border border-gray-200 shadow-lg">
            @csrf
            @method('PUT')
            
            <!-- NIK -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', $mother->nik) }}" 
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all @error('nik') border-red-500 @enderror"
                       placeholder="Masukkan NIK (16 digit)">
                @error('nik')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Lengkap -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Nama Lengkap Ibu</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $mother->nama_lengkap) }}" 
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all @error('nama_lengkap') border-red-500 @enderror"
                       placeholder="Masukkan nama lengkap ibu">
                @error('nama_lengkap')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Kelamin (Fixed to Perempuan) -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Jenis Kelamin</label>
                <div class="p-4 border-2 border-gray-200 rounded-xl bg-gray-50">
                    <span class="font-semibold text-pink-600">Perempuan</span>
                    <input type="hidden" name="jenis_kelamin" value="P">
                </div>
            </div>

            <!-- Tanggal Kehamilan -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Tanggal Kehamilan</label>
<input type="date" name="tanggal_kehamilan" value="{{ old('tanggal_kehamilan', $mother->tanggal_kehamilan) }}" max="{{ date('Y-m-d') }}"
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all @error('tanggal_kehamilan') border-red-500 @enderror">
                @error('tanggal_kehamilan')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Berat Badan -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Berat Badan Saat Ini (Kg)</label>
                <input type="number" name="berat_badan" step="0.01" value="{{ old('berat_badan', $mother->berat_badan) }}" min="0"
                       class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all @error('berat_badan') border-red-500 @enderror"
                       placeholder="0.00">
                @error('berat_badan')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Baru -->
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Ganti Foto Profil (Opsional)</label>
                <input type="file" name="foto" accept="image/*" 
                       class="w-full p-4 rounded-xl border-2 border-dashed border-gray-300 text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-500 file:text-white file:cursor-pointer hover:file:bg-pink-600 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all @error('foto') border-red-500 @enderror">
                <p class="text-gray-400 mt-1 text-sm">Upload foto baru (JPG, PNG) max 2MB - biarkan kosong untuk tetap foto lama</p>
                @error('foto')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Posyandu -->
            <div class="mb-8">
                <label class="block text-gray-700 font-bold mb-2">Posyandu</label>
                <select name="posyandu_id" class="w-full p-4 rounded-xl border-2 border-gray-200 text-gray-800 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-all @error('posyandu_id') border-red-500 @enderror">
                    @foreach($posyandus as $p)
                        <option value="{{ $p->id }}" {{ old('posyandu_id', $mother->posyandu_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_posyandu }}</option>
                    @endforeach
                </select>
                @error('posyandu_id')
                    <p class="text-red-500 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-pink-500 to-purple-500 hover:from-pink-600 hover:to-purple-600 text-white py-4 px-6 rounded-full font-bold text-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>Update Data Ibu
                </button>
                <a href="{{ route('dashboard.informasi.ibu') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-4 px-6 rounded-full font-bold text-lg text-center transition-all flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
