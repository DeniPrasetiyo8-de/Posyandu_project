@extends('layouts.app')

@section('title', 'Informasi Ibu')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <!-- Menu Pemilihan Informasi -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('dashboard.informasi.anak') }}" class="group bg-white text-gray-700 p-6 rounded-2xl shadow-xl border-2 border-gray-200 hover:border-blue-500 hover:shadow-2xl transition-all text-center card-hover">
            <i class="fas fa-child text-5xl mb-3 text-gray-400 group-hover:text-blue-500 transition-colors"></i>
            <h2 class="text-xl font-bold mb-1">Data Anak</h2>
            <p class="text-gray-500 text-sm">Informasi lengkap tentang data anak, imunisasi, dan vitamin</p>
        </a>
        
        <a href="{{ route('dashboard.informasi.ibu') }}" class="group bg-gradient-to-br from-pink-500 to-pink-600 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all text-center card-hover">
            <i class="fas fa-female text-5xl mb-3 group-hover:scale-110 transition-transform"></i>
            <h2 class="text-xl font-bold mb-1">Data Ibu</h2>
            <p class="text-pink-100 text-sm">Informasi kehamilan, pemantauan KIA, dan tablet tambah darah</p>
        </a>
    </div>

    <div class="flex items-center gap-4 mb-8">
        <div class="w-16 h-16 bg-gradient-to-r from-pink-400 to-pink-500 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg">
            <i class="fas fa-female"></i>
        </div>
        <div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Informasi Ibu Hamil</h1>
            <p class="text-xl text-gray-500">Data ibu hamil dan pemantauan kesehatan</p>
        </div>
    </div>

    <!-- Jadwal Layanan -->
    <div class="bg-gradient-to-br from-pink-50 via-purple-50 to-blue-50 rounded-3xl p-10 border border-pink-200 hover:shadow-2xl transition-all">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Jadwal Pemberian Layanan</h3>
        <div class="grid md:grid-cols-3 gap-6 text-center">
            <div>
                <div class="text-3xl font-bold text-pink-500 mb-2">0-11 Bulan</div>
                <p class="text-gray-600">Vitamin A, Imunisasi DPT-HB-HiB</p>
            </div>
            <div>
                <div class="text-3xl font-bold text-purple-500 mb-2">12-23 Bulan</div>
                <p class="text-gray-600">Imunisasi Campak, Vitamin A</p>
            </div>
            <div>
                <div class="text-3xl font-bold text-blue-500 mb-2">24+ Bulan</div>
                <p class="text-gray-600">Pemeriksaan Gizi, Imunisasi Booster</p>
            </div>
        </div>
    </div>

<!-- Quick Links -->
    <div class="grid md:grid-cols-4 gap-6 pt-8 border-t border-gray-200">
        <a href="{{ route('dashboard.index') }}" class="group p-8 bg-white rounded-2xl border border-gray-200 hover:border-blue-500 hover:shadow-xl transition-all text-center">
            <i class="fas fa-calendar text-3xl text-blue-500 group-hover:text-blue-600 mb-4 block mx-auto"></i>
            <h4 class="font-bold text-gray-800 mb-2">Jadwal Kegiatan</h4>
            <p class="text-gray-500 group-hover:text-gray-600">Lihat jadwal posyandu terbaru</p>
        </a>
        <a href="{{ route('dashboard.kms') }}" class="group p-8 bg-white rounded-2xl border border-gray-200 hover:border-green-500 hover:shadow-xl transition-all text-center">
            <i class="fas fa-chart-line text-3xl text-green-500 group-hover:text-green-600 mb-4 block mx-auto"></i>
            <h4 class="font-bold text-gray-800 mb-2">Pemantauan KMS</h4>
            <p class="text-gray-500 group-hover:text-gray-600">Grafik perkembangan anak</p>
        </a>
        <a href="{{ route('dashboard.informasi.anak') }}" class="group p-8 bg-white rounded-2xl border border-gray-200 hover:border-pink-500 hover:shadow-xl transition-all text-center">
            <i class="fas fa-child text-3xl text-pink-500 group-hover:text-pink-600 mb-4 block mx-auto"></i>
            <h4 class="font-bold text-gray-800 mb-2">Data Anak</h4>
            <p class="text-gray-500 group-hover:text-gray-600">Informasi anak terdaftar</p>
        </a>
        <a href="{{ route('dashboard.kader') }}" class="group p-8 bg-white rounded-2xl border border-gray-200 hover:border-purple-500 hover:shadow-xl transition-all text-center">
            <i class="fas fa-user-md text-3xl text-purple-500 group-hover:text-purple-600 mb-4 block mx-auto"></i>
            <h4 class="font-bold text-gray-800 mb-2">Cek Kader</h4>
            <p class="text-gray-500 group-hover:text-gray-600">Status kader posyandu</p>
        </a>
    </div>

<!-- Perkembangan Kehamilan dan TT - Hanya tampil jika ada ibu -->
    @if(isset($mothers) && !$mothers->isEmpty())
    @foreach($mothers as $mother)
    <div class="grid md:grid-cols-2 gap-6 pt-8 border-t border-gray-200">
        <!-- Perkembangan Kehamilan -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-pink-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-baby-carriage text-pink-500 mr-2"></i>Perkembangan Kehamilan
            </h3>
            <div class="space-y-4">
                @php
                    $trimesterStatus = json_decode($mother->trimester_status ?? '{}', true);
                    $status1 = $trimesterStatus['trimester1'] ?? 'belum';
                    $status2 = $trimesterStatus['trimester2'] ?? 'belum';
                    $status3 = $trimesterStatus['trimester3'] ?? 'belum';
                @endphp
                <div class="border-l-4 border-pink-500 pl-4">
                    <p class="text-sm text-gray-500">Trimester 1 (0-12 minggu)</p>
                    <p class="font-medium">Pemeriksaan awal, konsumsi asam folat</p>
                    <div class="flex items-center justify-between mt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" 
                                {{ $status1 == 'selesai' ? 'checked' : '' }}
                                onchange="toggleTrimester({{ $mother->id }}, 'trimester1', this.checked)">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-100 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                        </label>
                        <span class="text-xs font-bold {{ $status1 == 'selesai' ? 'text-green-600' : 'text-red-500' }}">
                            {{ $status1 == 'selesai' ? 'Selesai' : 'Belum' }}
                        </span>
                    </div>
                </div>
                <div class="border-l-4 border-pink-300 pl-4">
                    <p class="text-sm text-gray-500">Trimester 2 (13-24 minggu)</p>
                    <p class="font-medium">Pemeriksaan USG, pemantauan berat badan</p>
                    <div class="flex items-center justify-between mt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" 
                                {{ $status2 == 'selesai' ? 'checked' : '' }}
                                onchange="toggleTrimester({{ $mother->id }}, 'trimester2', this.checked)">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-100 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                        </label>
                        <span class="text-xs font-bold {{ $status2 == 'selesai' ? 'text-green-600' : 'text-red-500' }}">
                            {{ $status2 == 'selesai' ? 'Selesai' : 'Belum' }}
                        </span>
                    </div>
                </div>
                <div class="border-l-4 border-gray-300 pl-4">
                    <p class="text-sm text-gray-500">Trimester 3 (25-36 minggu)</p>
                    <p class="font-medium">Persiapan persalinan, pemeriksaan rutin</p>
                    <div class="flex items-center justify-between mt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" 
                                {{ $status3 == 'selesai' ? 'checked' : '' }}
                                onchange="toggleTrimester({{ $mother->id }}, 'trimester3', this.checked)">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-100 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                        </label>
                        <span class="text-xs font-bold {{ $status3 == 'selesai' ? 'text-green-600' : 'text-red-500' }}">
                            {{ $status3 == 'selesai' ? 'Selesai' : 'Belum' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tablet Tambah Darah (TT) -->
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-red-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-tint text-red-500 mr-2"></i>Tablet Tambah Darah (TT)
            </h3>
            <div class="space-y-3">
                @foreach(\App\Models\Mother::getDaftarTT() as $key => $nama)
                @php
                    $ttStatus = json_decode($mother->tt_status ?? '{}', true);
                    $status = $ttStatus[$key] ?? 'belum';
                @endphp
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="font-medium text-gray-700 text-sm">{{ $nama }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" 
                            {{ $status == 'sudah' ? 'checked' : '' }}
                            onchange="toggleTT({{ $mother->id }}, '{{ $key }}', this.checked)">
                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-100 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                        <span class="ms-2 text-xs font-medium {{ $status == 'sudah' ? 'text-green-600' : 'text-red-500' }}">
                            {{ $status == 'sudah' ? 'Sudah' : 'Belum' }}
                        </span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
    @endif

<script>
    function toggleTT(motherId, suntikan, isChecked) {
        const status = isChecked ? 'sudah' : 'belum';
        
        fetch('{{ route("dashboard.update.tt") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                mother_id: motherId,
                suntikan_tt: suntikan,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
    
    function toggleTrimester(motherId, trimester, isChecked) {
        const status = isChecked ? 'selesai' : 'belum';
        
        fetch('{{ route("dashboard.update.trimester") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                mother_id: motherId,
                trimester: trimester,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
    </script>

    <!-- Data Ibu Section -->
    <div class="mt-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Data Ibu Terdaftar</h2>
            <a href="{{ route('mothers.create') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">
                <i class="fas fa-plus mr-2"></i>Tambah Ibu
            </a>
        </div>
        
        @if(isset($mothers) && $mothers->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
                <i class="fas fa-user-pregnant text-5xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">Belum ada data ibu. Tambahkan untuk melihat di KMS.</p>
            </div>
        @elseif(isset($mothers))
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($mothers as $mother)
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center gap-4 mb-4">
                        @if($mother->foto_url)
                            <img src="{{ $mother->foto_url }}" alt="{{ $mother->nama_lengkap }}" class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center text-pink-500">
                                <i class="fas fa-user-pregnant text-2xl"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $mother->nama_lengkap }}</h3>
                            <p class="text-gray-500 text-sm">{{ $mother->nik }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm mb-4">
                        <p class="text-gray-600">Minggu Kehamilan: {{ $mother->umur_kehampuan ?? '-' }}</p>
                        <p class="text-gray-600">BB: {{ $mother->berat_badan ?? '-' }} kg</p>
                        <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold {{ $mother->status_kesehatan == 'Sehat' || $mother->status_kesehatan == 'Baik' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $mother->status_kesehatan ?? 'Belum Ada' }}
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('mothers.edit', $mother->id) }}" class="flex-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 py-2 rounded-lg text-center text-sm font-bold">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('mothers.destroy', $mother->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-600 py-2 rounded-lg text-sm font-bold">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
