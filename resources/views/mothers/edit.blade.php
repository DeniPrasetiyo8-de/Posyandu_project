@extends('layouts.app')

@section('title', 'Edit Data Ibu')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-pink-900 to-slate-900 py-12">
    <div class="max-w-2xl mx-auto p-8">
        <div class="text-center mb-12">
            <i class="fas fa-user-pregnant text-6xl text-pink-400 mb-6 bg-pink-500/20 p-6 rounded-3xl"></i>
            <h1 class="text-4xl font-bold text-white mb-4">Edit Data Ibu</h1>
            <p class="text-xl text-slate-300">Perbarui informasi {{ $mother->nama_lengkap }}</p>
        </div>

        <form action="{{ route('mothers.update', $mother->id) }}" method="POST" enctype="multipart/form-data" class="bg-slate-800/50 backdrop-blur-xl rounded-3xl p-10 border border-slate-700 shadow-2xl">
            @csrf
            @method('PUT')
            
            <!-- Foto Preview -->
            <div class="mb-8 text-center">
                @if($mother->foto_url)
                    <img src="{{ $mother->foto_url }}" alt="Foto {{ $mother->nama_lengkap }}" class="w-32 h-32 rounded-full mx-auto object-cover shadow-2xl mb-4 border-4 border-pink-400">
                    <p class="text-slate-400 text-lg">Foto saat ini</p>
                @else
                    <div class="w-32 h-32 bg-slate-700 rounded-full mx-auto flex items-center justify-center text-pink-400 text-4xl mb-4">
                        <i class="fas fa-user-pregnant"></i>
                    </div>
                    <p class="text-slate-400 text-lg">Belum ada foto</p>
                @endif
            </div>

            <!-- NIK -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', $mother->nik) }}" 
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20 transition-all @error('nik') border-red-500 @enderror"
                       placeholder="Masukkan NIK (16 digit)">
                @error('nik')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Lengkap -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Nama Lengkap Ibu</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $mother->nama_lengkap) }}" 
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20 transition-all @error('nama_lengkap') border-red-500 @enderror"
                       placeholder="Masukkan nama lengkap ibu">
                @error('nama_lengkap')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Kelamin (Fixed to Perempuan) -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Jenis Kelamin</label>
                <div class="p-5 bg-slate-700/50 border-2 border-slate-600 rounded-2xl">
                    <span class="text-lg font-semibold text-pink-400">Perempuan</span>
                    <input type="hidden" name="jenis_kelamin" value="P">
                </div>
            </div>

            <!-- Tanggal Kehamilan -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Tanggal Kehamilan</label>
                <input type="date" name="tanggal_kehamilan" value="{{ old('tanggal_kehamilan', $mother->tanggal_kehamilan) }}" max="{{ date('Y-m-d') }}"
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20 transition-all @error('tanggal_kehamilan') border-red-500 @enderror">
                @error('tanggal_kehamilan')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Berat Badan -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Berat Badan Saat Ini (Kg)</label>
                <input type="number" name="berat_badan" step="0.01" value="{{ old('berat_badan', $mother->berat_badan) }}" min="0"
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all @error('berat_badan') border-red-500 @enderror"
                       placeholder="0.00">
                @error('berat_badan')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Baru -->
            <div class="mb-12">
                <label class="block text-white font-bold mb-4 text-xl">Ganti Foto Profil (Opsional)</label>
                <div class="relative">
                    <input type="file" name="foto" accept="image/*" 
                           class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-dashed border-slate-600 text-white text-lg file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-lg file:font-bold file:bg-gradient-to-r file:from-pink-500 file:to-purple-500 file:text-white file:cursor-pointer hover:file:bg-gradient-to-r hover:file:from-pink-600 hover:file:to-purple-600 focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20 transition-all @error('foto') border-red-500 @enderror cursor-pointer">
                    <p class="text-slate-400 mt-2 text-sm">Upload foto baru (JPG, PNG) max 2MB - biarkan kosong untuk tetap foto lama</p>
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
                        <option value="{{ $p->id }}" {{ old('posyandu_id', $mother->posyandu_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_posyandu }}</option>
                    @endforeach
                </select>
                @error('posyandu_id')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white py-5 px-8 rounded-2xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all">
                    <i class="fas fa-save mr-2"></i>Update Data Ibu
                </button>
                <a href="{{ route('dashboard.informasi.ibu') }}" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white py-5 px-8 rounded-2xl font-bold text-xl text-center shadow-xl hover:shadow-2xl transition-all flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
