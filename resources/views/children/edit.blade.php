@extends('layouts.app')

@section('title', 'Edit Data Anak')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-yellow-900 to-slate-900 py-12">
    <div class="max-w-2xl mx-auto p-8">
        <div class="text-center mb-12">
            <i class="fas fa-edit text-6xl text-yellow-400 mb-6 bg-yellow-500/20 p-6 rounded-3xl"></i>
            <h1 class="text-4xl font-bold text-white mb-4">Edit Data Anak</h1>
            <p class="text-xl text-slate-300">Perbarui informasi {{ $child->nama }}</p>
        </div>

        <form action="{{ route('children.update', $child->id) }}" method="POST" enctype="multipart/form-data" class="bg-slate-800/50 backdrop-blur-xl rounded-3xl p-10 border border-slate-700 shadow-2xl">
            @csrf
            @method('PUT')
            
            <!-- Foto Preview -->
            <div class="mb-12 text-center">
                @if($child->foto_url)
                    <img src="{{ $child->foto_url }}" alt="Foto {{ $child->nama }}" class="w-48 h-48 rounded-full mx-auto object-cover shadow-2xl mb-4 border-4 border-yellow-400">
                    <p class="text-slate-400 text-lg">Foto saat ini</p>
                @else
                    <div class="w-48 h-48 bg-slate-700 rounded-full mx-auto flex items-center justify-center text-slate-500 text-2xl mb-4">
                        <i class="fas fa-user-child"></i>
                    </div>
                    <p class="text-slate-400 text-lg">Belum ada foto</p>
                @endif
            </div>

            <!-- Nama -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $child->nama) }}" 
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/20 transition-all @error('nama') border-red-500 @enderror"
                       placeholder="Nama lengkap anak">
                @error('nama')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Kelamin -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Jenis Kelamin</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center p-5 bg-slate-700/50 border-2 border-slate-600 rounded-2xl cursor-pointer hover:border-yellow-500 transition-all @error('jenis_kelamin') border-red-500 @enderror {{ old('jenis_kelamin', $child->jenis_kelamin) == 'L' ? 'border-yellow-500 bg-yellow-500/10' : '' }}">
                        <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $child->jenis_kelamin) == 'L' ? 'checked' : '' }} class="w-5 h-5 text-yellow-500 bg-slate-700 border-slate-600 focus:ring-yellow-500 mr-4">
                        <span class="text-lg font-semibold text-white">Laki-laki</span>
                    </label>
                    <label class="flex items-center p-5 bg-slate-700/50 border-2 border-slate-600 rounded-2xl cursor-pointer hover:border-yellow-500 transition-all @error('jenis_kelamin') border-red-500 @enderror {{ old('jenis_kelamin', $child->jenis_kelamin) == 'P' ? 'border-yellow-500 bg-yellow-500/10' : '' }}">
                        <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $child->jenis_kelamin) == 'P' ? 'checked' : '' }} class="w-5 h-5 text-yellow-500 bg-slate-700 border-slate-600 focus:ring-yellow-500 mr-4">
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
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $child->tanggal_lahir) }}" max="{{ date('Y-m-d') }}"
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/20 transition-all @error('tanggal_lahir') border-red-500 @enderror">
                @error('tanggal_lahir')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Berat Badan -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Berat Badan Saat Ini (Kg)</label>
                <input type="number" name="berat_badan" step="0.01" value="{{ old('berat_badan', $child->berat_badan) }}" min="0"
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-green-500 focus:ring-4 focus:ring-green-500/20 transition-all @error('berat_badan') border-red-500 @enderror"
                       placeholder="0.00">
                @error('berat_badan')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tinggi Badan -->
            <div class="mb-8">
                <label class="block text-white font-bold mb-4 text-xl">Tinggi Badan Saat Ini (Cm)</label>
                <input type="number" name="tinggi_badan" step="0.1" value="{{ old('tinggi_badan', $child->tinggi_badan) }}" min="0"
                       class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all @error('tinggi_badan') border-red-500 @enderror"
                       placeholder="0.0">
                @error('tinggi_badan')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Baru -->
            <div class="mb-12">
                <label class="block text-white font-bold mb-4 text-xl">Ganti Foto Profil (Opsional)</label>
                <div class="relative">
                    <input type="file" name="foto" accept="image/*" 
                           class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-dashed border-slate-600 text-white text-lg file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-lg file:font-bold file:bg-gradient-to-r file:from-yellow-500 file:to-orange-500 file:text-white file:cursor-pointer hover:file:bg-gradient-to-r hover:file:from-yellow-600 hover:file:to-orange-600 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/20 transition-all @error('foto') border-red-500 @enderror cursor-pointer">
                    <p class="text-slate-400 mt-2 text-sm">Upload foto baru (JPG, PNG) max 2MB - biarkan kosong untuk tetap foto lama</p>
                </div>
                @error('foto')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Posyandu -->
            <div class="mb-12">
                <label class="block text-white font-bold mb-4 text-xl">Posyandu</label>
                <select name="posyandu_id" class="w-full p-5 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all @error('posyandu_id') border-red-500 @enderror">
                    @foreach($posyandus as $p)
                        <option value="{{ $p->id }}" {{ old('posyandu_id', $child->posyandu_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_posyandu }}</option>
                    @endforeach
                </select>
                @error('posyandu_id')
                    <p class="text-red-400 mt-2 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white py-5 px-8 rounded-2xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all">
                    <i class="fas fa-save mr-2"></i>Update Data Anak
                </button>
                <a href="{{ route('dashboard.informasi.anak') }}" class="flex-1 bg-slate-700 hover:bg-slate-600 text-white py-5 px-8 rounded-2xl font-bold text-xl text-center shadow-xl hover:shadow-2xl transition-all flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
