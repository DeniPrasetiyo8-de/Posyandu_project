@extends('layouts.admin')

@section('title', 'Cari Informasi - Admin')
@section('page_title', 'Cari Informasi')
@section('page_description', 'Pencarian data anak dan ibu hamil.')

@section('admin_content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

<!-- Search Form -->
    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 mb-8">
        <!-- Alert Error Kode -->
        @if(session('error_kode'))
        <div class="bg-red-500/20 border border-red-500/30 rounded-xl p-4 mb-6">
            <p class="text-red-400 font-bold">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error_kode') }}
            </p>
        </div>
        @endif
        
        <!-- Alert Success Kode -->
        @if(session('success_kode'))
        <div class="bg-green-500/20 border border-green-500/30 rounded-xl p-4 mb-6">
            <p class="text-green-400 font-bold">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success_kode') }}
            </p>
        </div>
        @endif
        
        <form action="{{ route('admin.informasi') }}" method="GET" class="space-y-6">
            <div class="grid md:grid-cols-5 gap-6">
                <div>
                    <label class="block text-black text-sm font-bold mb-2">Kode Akses</label>
                    <input type="text" name="kode_akses" value="{{ session('kode_akses') }}" placeholder="Isi Disini" 
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none"
                        {{ session('kode_akses') ? '' : '' }}>
                    <p class="text-xs text-gray-400 mt-1">Isi: Kode Sesuai Posyandu</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-black text-sm font-bold mb-2">Cari (Nama atau NIK)</label>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Ketik nama atau NIK..." 
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-black text-sm font-bold mb-2">Tipe Data</label>
                    <select name="type" class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                        <option value="anak" {{ ($type ?? 'anak') == 'anak' ? 'selected' : '' }}>Data Anak</option>
                        <option value="ibu" {{ ($type ?? 'anak') == 'ibu' ? 'selected' : '' }}>Data Ibu Hamil</option>
                    </select>
                </div>
                <div>
                    <label class="block text-black text-sm font-bold mb-2">Filter RW</label>
                    <select name="rw" class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                        <option value="">Semua RW</option>
                        <option value="01" {{ ($rw ?? '') == '01' ? 'selected' : '' }}>RW 01</option>
                        <option value="02" {{ ($rw ?? '') == '02' ? 'selected' : '' }}>RW 02</option>
                        <option value="03" {{ ($rw ?? '') == '03' ? 'selected' : '' }}>RW 03</option>
                        <option value="04" {{ ($rw ?? '') == '04' ? 'selected' : '' }}>RW 04</option>
                        <option value="05" {{ ($rw ?? '') == '05' ? 'selected' : '' }}>RW 05</option>
                        <option value="06" {{ ($rw ?? '') == '06' ? 'selected' : '' }}>RW 06</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-xl font-bold">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </form>
    </div>

    <!-- Results Table -->
    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
        <h3 class="text-2xl font-bold text-black mb-6 flex items-center">
            @if(($type ?? 'anak') == 'anak')
                <i class="fas fa-baby mr-3 text-pink-400"></i>Hasil Pencarian Data Anak
            @else
                <i class="fas fa-female mr-3 text-pink-400"></i>Hasil Pencarian Data Ibu Hamil
            @endif
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
<tr class="text-left text-black border-b border-black-700">
                        <th class="pb-4 font-bold">#</th>
@if(($type ?? 'anak') == 'anak')
                            <th class="pb-4 font-bold">Nama Anak</th>
                            <th class="pb-4 font-bold">NIK</th>
                            <th class="pb-4 font-bold">Tanggal Lahir</th>
                            <th class="pb-4 font-bold">Orang Tua</th>
                            <th class="pb-4 font-bold">Posyandu</th>
                            <th class="pb-4 font-bold">Status</th>
                        @else
                            <th class="pb-4 font-bold">Nama Ibu</th>
                            <th class="pb-4 font-bold">NIK</th>
                            <th class="pb-4 font-bold">Tanggal Kehamilan</th>
                            <th class="pb-4 font-bold">Orang Tua</th>
                            <th class="pb-4 font-bold">Posyandu</th>
                            <th class="pb-4 font-bold">Status</th>
                        @endif
                        <th class="pb-4 font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $index => $item)
                        <tr class="border-b border-gray-800 hover:bg-white/5">
<td class="py-4 text-black">{{ $index + 1 }}</td>
                            
@if(($type ?? 'anak') == 'anak')
                                <td class="py-4">
                                    <span class="text-black font-bold">{{ $item->nama }}</span>
                                </td>
                                <td class="py-4 text-black">{{ $item->nik ?? '-' }}</td>
                                <td class="py-4 text-black">
                                    {{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d M Y') : '-' }}
                                </td>
                                <td class="py-4 text-black">
                                    {{ $item->user->name ?? 'N/A' }}
                                </td>
                                <td class="py-4 text-black">
                                    {{ $item->posyandu->nama_posyandu ?? 'N/A' }}
                                </td>
                                <td class="py-4">
                                    @php $status = $item->status ?? 'AKTIF'; @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ $status == 'AKTIF' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                        {{ $status }}
                                    </span>
                                </td>
                            @else
                                <td class="py-4">
                                    <span class="text-black font-bold">{{ $item->nama_lengkap }}</span>
                                </td>
                                <td class="py-4 text-black">{{ $item->nik ?? '-' }}</td>
                                <td class="py-4 text-black">
                                    {{ $item->tanggal_kehamilan ? \Carbon\Carbon::parse($item->tanggal_kehamilan)->format('d M Y') : '-' }}
                                </td>
                                <td class="py-4 text-black">
                                    {{ $item->user->name ?? 'N/A' }}
                                </td>
                                <td class="py-4 text-black">
                                    {{ $item->posyandu->nama_posyandu ?? 'N/A' }}
                                </td>
                                <td class="py-4">
                                    @php $status = $item->status ?? 'AKTIF'; @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ $status == 'AKTIF' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                        {{ $status }}
                                    </span>
                                </td>
                            @endif
                            
<td class="py-4">
                                <a href="{{ ($type ?? 'anak') == 'anak' ? route('admin.informasi.edit.anak', $item->id) : route('admin.informasi.edit.ibu', $item->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg text-sm inline-block">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
<td colspan="7" class="py-8 text-center text-black">
                                <i class="fas fa-search text-4xl mb-4 block"></i>
                                @if($search)
                                    Tidak ada hasil untuk "{{ $search }}"
                                @else
                                    Masukkan kata kunci untuk mencari
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($results->hasPages())
        <div class="mt-6">
            {{ $results->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.bg-gray-50 { background-color: #1f2937; }
</style>
@endsection
