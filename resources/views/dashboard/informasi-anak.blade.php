@extends('layouts.app')

@section('title', 'Informasi Anak')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <!-- Menu Pemilihan Informasi -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('dashboard.informasi.anak') }}" class="group bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all text-center card-hover">
            <i class="fas fa-child text-5xl mb-3 group-hover:scale-110 transition-transform"></i>
            <h2 class="text-xl font-bold mb-1">Data Anak</h2>
            <p class="text-blue-100 text-sm">Informasi lengkap tentang data anak, imunisasi, dan vitamin</p>
        </a>
        
        <a href="{{ route('dashboard.informasi.ibu') }}" class="group bg-white text-gray-700 p-6 rounded-2xl shadow-xl border-2 border-gray-200 hover:border-pink-500 hover:shadow-2xl transition-all text-center card-hover">
            <i class="fas fa-female text-5xl mb-3 text-gray-400 group-hover:text-pink-500 transition-colors"></i>
            <h2 class="text-xl font-bold mb-1">Data Ibu</h2>
            <p class="text-gray-500 text-sm">Informasi kehamilan, pemantauan KIA, dan tablet tambah darah</p>
        </a>
    </div>

    <div class="flex items-center gap-4 mb-8">
        <div class="w-16 h-16 bg-gradient-to-r from-pink-400 to-pink-500 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg">
            <i class="fas fa-child"></i>
        </div>
        <div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Informasi Anak</h1>
            <p class="text-xl text-gray-500">Data anak terdaftar dan riwayat posyandu</p>
        </div>
    </div>

    @if($children->isEmpty())
        <div class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-3xl p-16 text-center">
            <i class="fas fa-baby-carriage text-7xl text-gray-400 mb-8"></i>
            <h2 class="text-3xl font-bold text-gray-600 mb-4">Belum Ada Data Anak</h2>
            <p class="text-gray-500 text-lg mb-8 max-w-2xl mx-auto">
                Daftarkan anak Anda untuk memantau perkembangan kesehatan dan jadwal pemeriksaan.
            </p>
            <a href="{{ route('children.create') }}" class="bg-gradient-to-r from-pink-500 to-purple-500 text-white px-12 py-4 rounded-2xl font-bold text-xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all inline-flex items-center space-x-3">
                <i class="fas fa-plus"></i>
                <span>Daftarkan Anak Pertama</span>
            </a>
        </div>
    @else
        <!-- Cards Anak dengan Status Imunisasi dan Vitamin -->
        @foreach($children as $child)
        <div class="space-y-6">
            <div class="group bg-white rounded-2xl p-6 border border-gray-100 shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-300 relative">
                <!-- Badge Umur -->
                <div class="absolute -top-3 -right-3 bg-gradient-to-r from-pink-500 to-purple-500 text-white px-4 py-1 rounded-full font-bold text-sm shadow-lg">
                    {{ \Carbon\Carbon::parse($child->tanggal_lahir)->diffInMonths() }} Bulan
                </div>
                
                <!-- Avatar/Nama -->
                <div class="text-center mb-6">
                    <div class="w-20 h-20 rounded-full mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform overflow-hidden border-4 border-pink-200">
                        @if($child->foto_url)
                            <img src="{{ $child->foto_url }}" alt="{{ $child->nama }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-pink-400 to-purple-400 flex items-center justify-center">
                                <i class="fas fa-user-child text-2xl text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $child->nama }}</h3>
                    <span class="text-pink-500 font-semibold">{{ $child->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>

                <!-- Detail -->
                <div class="space-y-3 mb-6">
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                        <i class="fas fa-calendar text-pink-500 mr-3"></i>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Lahir</p>
                            <p class="font-bold text-gray-800 text-sm">{{ $child->tanggal_lahir->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $child->umur_bulan }} bulan</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                        <i class="fas fa-weight-hanging text-green-500 mr-3"></i>
                        <div>
                            <p class="text-xs text-gray-500">Berat Badan</p>
                            <p class="font-bold text-gray-800">{{ $child->berat_badan ? $child->berat_badan . ' kg' : 'Belum diukur' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                        <i class="fas fa-ruler-vertical text-blue-500 mr-3"></i>
                        <div>
                            <p class="text-xs text-gray-500">Tinggi Badan</p>
                            <p class="font-bold text-gray-800">{{ $child->tinggi_badan ? $child->tinggi_badan . ' cm' : 'Belum diukur' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                        <i class="fas fa-map-marker-alt text-green-500 mr-3"></i>
                        <div>
                            <p class="text-xs text-gray-500">Posyandu</p>
                            <p class="font-bold text-gray-800">{{ $child->posyandu->nama_posyandu }}</p>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="flex gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('dashboard.kms') }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-lg font-semibold text-center transition-all flex items-center justify-center text-sm">
                        <i class="fas fa-chart-line mr-1"></i>KMS
                    </a>
                    <a href="{{ route('children.edit', $child->id) }}" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded-lg font-semibold text-center transition-all flex items-center justify-center text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form action="{{ route('children.destroy', $child->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus data {{ $child->nama }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded-lg font-semibold text-center transition-all flex items-center justify-center text-sm">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Daftar Imunisasi dan Vitamin untuk Setiap Anak -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Daftar Imunisasi -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-blue-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-syringe text-blue-500 mr-2"></i>Imunisasi - {{ $child->nama }}
                    </h3>
                    <div class="space-y-3">
                        @foreach(\App\Models\Child::getDaftarImunisasi() as $key => $nama)
                        @php
                            $imunisasiStatus = json_decode($child->imunisasi_status ?? '{}', true);
                            $status = $imunisasiStatus[$key] ?? 'belum';
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="font-medium text-gray-700 text-sm">{{ $nama }}</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" 
                                    {{ $status == 'sudah' ? 'checked' : '' }}
                                    onchange="toggleStatus({{ $child->id }}, '{{ $key }}', this.checked, 'imunisasi')">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                                <span class="ms-2 text-xs font-medium {{ $status == 'sudah' ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $status == 'sudah' ? 'Sudah' : 'Belum' }}
                                </span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Daftar Vitamin -->
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-pink-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-pills text-pink-500 mr-2"></i>Vitamin - {{ $child->nama }}
                    </h3>
                    <div class="space-y-3">
                        @foreach(\App\Models\Child::getDaftarVitamin() as $key => $nama)
                        @php
                            $vitaminStatus = json_decode($child->vitamin_status ?? '{}', true);
                            $status = $vitaminStatus[$key] ?? 'belum';
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="font-medium text-gray-700 text-sm">{{ $nama }}</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" 
                                    {{ $status == 'sudah' ? 'checked' : '' }}
                                    onchange="toggleStatus({{ $child->id }}, '{{ $key }}', this.checked, 'vitamin')">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-100 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                                <span class="ms-2 text-xs font-medium {{ $status == 'sudah' ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $status == 'sudah' ? 'Sudah' : 'Belum' }}
                                </span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif

    <!-- Quick Actions - Always visible -->
    <div class="grid md:grid-cols-3 gap-6 pt-8 border-t border-gray-200">
        <div class="text-center p-6 bg-gray-50 rounded-2xl border border-gray-200 hover:border-green-500 hover:shadow-lg transition-all">
            <i class="fas fa-calendar-plus text-3xl text-green-500 mb-3"></i>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Jadwal Pemeriksaan</h3>
            <p class="text-gray-500 text-sm mb-4">Lihat jadwal imunisasi dan pemeriksaan berikutnya</p>
            <a href="{{ route('dashboard.index') }}" class="text-green-500 font-semibold hover:underline">Lihat Jadwal →</a>
        </div>
        <div class="text-center p-6 bg-gray-50 rounded-2xl border border-gray-200 hover:border-blue-500 hover:shadow-lg transition-all">
            <i class="fas fa-file-medical text-3xl text-blue-500 mb-3"></i>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Rekam Medis</h3>
            <p class="text-gray-500 text-sm mb-4">Riwayat pertumbuhan dan status gizi anak</p>
            <a href="{{ route('dashboard.kms') }}" class="text-blue-500 font-semibold hover:underline">Lihat KMS →</a>
        </div>
        <div class="text-center p-6 bg-gray-50 rounded-2xl border border-gray-200 hover:border-purple-500 hover:shadow-lg transition-all">
            <i class="fas fa-user-md text-3xl text-purple-500 mb-3"></i>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Status Kader</h3>
            <p class="text-gray-500 text-sm mb-4">Cek ketersediaan kader di posyandu</p>
            <a href="{{ route('dashboard.kader') }}" class="text-purple-500 font-semibold hover:underline">Lihat Kader →</a>
        </div>
    </div>

    <script>
    function toggleStatus(childId, jenis, isChecked, tipe) {
        const status = isChecked ? 'sudah' : 'belum';
        const url = tipe === 'imunisasi' 
            ? '{{ route("dashboard.update.imunisasi") }}' 
            : '{{ route("dashboard.update.vitamin") }}';
        const keyName = tipe === 'imunisasi' ? 'jenis_imunisasi' : 'jenis_vitamin';
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                child_id: childId,
                [keyName]: jenis,
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
</div>
@endsection
