@extends('layouts.admin')

@section('title', 'Edit Informasi Ibu - Admin')
@section('page_title', 'Edit Informasi Ibu')
@section('page_description', 'Edit perkembangan kehamilan dan tablet tambah darah.')

@section('admin_content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('admin.informasi') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <button type="button" onclick="saveAllStatus()" id="saveBtn" class="bg-gray-600 hover:bg-gray-500 text-white px-8 py-3 rounded-xl font-bold transition-all">
            <i class="fas fa-save mr-2"></i>Simpan Perubahan
        </button>
    </div>

    <!-- Mother Info Card -->
    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-gradient-to-r from-pink-400 to-pink-500 flex items-center justify-center text-white text-3xl shadow-lg">
                    <i class="fas fa-female"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-black mb-2">{{ $mother->nama_lengkap }}</h2>
                    <div class="flex items-center gap-4 text-black-300">
                        <span><i class="fas fa-calendar mr-2"></i>{{ $mother->tanggal_kehamilan ? \Carbon\Carbon::parse($mother->tanggal_kehaminan)->format('d M Y') : 'N/A' }}</span>
                        <span><i class="fas fa-map-marker mr-2"></i>{{ $mother->posyandu->nama_posyandu ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-end">
                <label class="block text-black text-sm font-bold mb-2">Status Data</label>
                <select name="status" id="statusSelect" class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none" onchange="updateStatusData()">
                    <option value="AKTIF" {{ ($mother->status ?? 'AKTIF') == 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                    <option value="NONAKTIF" {{ ($mother->status ?? 'AKTIF') == 'NONAKTIF' ? 'selected' : '' }}>NONAKTIF</option>
                </select>
                <span id="statusText" class="mt-2 text-sm font-bold {{ ($mother->status ?? 'AKTIF') == 'AKTIF' ? 'text-green-400' : 'text-red-400' }}">
                    {{ ($mother->status ?? 'AKTIF') == 'AKTIF' ? 'Data Aktif' : 'Data Nonaktif' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Perkembangan Kehamilan and TT Grid -->
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Perkembangan Kehamilan -->
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-black mb-6 flex items-center">
                <i class="fas fa-baby-carriage text-pink-400 mr-3"></i>Perkembangan Kehamilanan
            </h3>
            <div class="space-y-4">
                @php
                    $trimesterStatus = json_decode($mother->trimester_status ?? '{}', true);
                    $status1 = $trimesterStatus['trimester1'] ?? 'Belum';
                    $status2 = $trimesterStatus['trimester2'] ?? 'Belum';
                    $status3 = $trimesterStatus['trimester3'] ?? 'Belum';
                @endphp
                <div class="border-l-4 border-pink-500 pl-4">
                    <p class="text-sm text-black-400">Trimester 1 (0-12 minggu)</p>
                    <p class="text- font-medium">Pemeriksaan awal, konsumsi asam folat</p>
                    <div class="flex items-center justify-between mt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer trimester-checkbox" 
                                data-trimester="trimester1"
                                {{ $status1 == 'selesai' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                        </label>
                        <span class="text-sm font-bold trimester-status-text-1 {{ $status1 == 'Selesai' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $status1 == 'Selesai' ? 'Selesai' : 'Belum' }}
                        </span>
                    </div>
                </div>
                <div class="border-l-4 border-pink-300 pl-4">
                    <p class="text-sm text-black-400">Trimester 2 (13-24 minggu)</p>
                    <p class="text-black font-medium">Pemeriksaan USG, pemantauan berat badan</p>
                    <div class="flex items-center justify-between mt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer trimester-checkbox" 
                                data-trimester="trimester2"
                                {{ $status2 == 'selesai' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                        </label>
                        <span class="text-sm font-bold trimester-status-text-2 {{ $status2 == 'Selesai' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $status2 == 'Selesai' ? 'Selesai' : 'Belum' }}
                        </span>
                    </div>
                </div>
                <div class="border-l-4 border-gray-400 pl-4">
                    <p class="text-sm text-black-400">Trimester 3 (25-36 minggu)</p>
                    <p class="text-black font-medium">Persiapan persalinan, pemeriksaan rutin</p>
                    <div class="flex items-center justify-between mt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer trimester-checkbox" 
                                data-trimester="trimester3"
                                {{ $status3 == 'selesai' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                        </label>
                        <span class="text-sm font-bold trimester-status-text-3 {{ $status3 == 'Selesai' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $status3 == 'Selesai' ? 'Selesai' : 'Belum' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tablet Tambah Darah (TT) -->
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-black mb-6 flex items-center">
                <i class="fas fa-tint text-red-400 mr-3"></i>Tablet Tambah Darah (TT)
            </h3>
            <div class="space-y-3">
                @foreach(\App\Models\Mother::getDaftarTT() as $key => $nama)
                @php
                    $status = $mother->$key ?? 'Belum';
                @endphp
                <div class="flex items-center justify-between p-4 bg-gray-800/50 rounded-xl">
                    <span class="text-white font-medium">{{ $nama }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer tt-checkbox" 
                            data-key="{{ $key }}"
                            {{ $status == 'Sudah' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                        <span class="ms-3 text-sm font-bold tt-status-text-{{ $key }} {{ $status == 'Sudah' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $status == 'Sudah' ? 'Sudah' : 'Belum' }}
                        </span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
    let motherId = {{ $mother->id }};
    let dataStatus = '{{ $mother->status ?? 'AKTIF' }}';
    let trimesters = {};
    let ttStatus = {};
    
    @php
        $trimesterStatus = json_decode($mother->trimester_status ?? '{}', true);
    @endphp
    trimesters['trimester1'] = '{{ $trimesterStatus['trimester1'] ?? 'Belum' }}';
    trimesters['trimester2'] = '{{ $trimesterStatus['trimester2'] ?? 'Belum' }}';
    trimesters['trimester3'] = '{{ $trimesterStatus['trimester3'] ?? 'Belum' }}';
    
    @foreach(\App\Models\Mother::getDaftarTT() as $key => $nama)
    ttStatus['{{ $key }}'] = '{{ $mother->$key ?? 'Belum' }}';
    @endforeach

    function updateStatusData() {
        const select = document.getElementById('statusSelect');
        const statusText = document.getElementById('statusText');
        const newStatus = select.value;
        
        if (newStatus !== dataStatus) {
            dataStatus = newStatus;
            statusText.textContent = newStatus === 'AKTIF' ? 'Data Aktif' : 'Data Nonaktif';
            statusText.className = 'mt-2 text-sm font-bold ' + (newStatus === 'AKTIF' ? 'text-green-400' : 'text-red-400');
            updateSaveButton();
        }
    }

    document.querySelectorAll('.trimester-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const trimester = this.dataset.trimester;
            const status = this.checked ? 'Selesai' : 'Belum';
            trimesters[trimester] = status;
            const num = trimester.replace('trimester', '');
            const statusText = document.querySelector('.trimester-status-text-' + num);
            statusText.textContent = status === 'Selesai' ? 'Selesai' : 'Belum';
            statusText.className = 'text-sm font-bold trimester-status-text-' + num + ' ' + (status === 'Selesai' ? 'text-green-400' : 'text-red-400');
            updateSaveButton();
        });
    });

    document.querySelectorAll('.tt-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const key = this.dataset.key;
            const status = this.checked ? 'Sudah' : 'Belum';
            ttStatus[key] = status;
            const statusText = document.querySelector('.tt-status-text-' + key);
            statusText.textContent = status === 'Sudah' ? 'Sudah' : 'Belum';
            statusText.className = 'ms-3 text-sm font-bold tt-status-text-' + key + ' ' + (status === 'Sudah' ? 'text-green-400' : 'text-red-400');
            updateSaveButton();
        });
    });

    function updateSaveButton() {
        const saveBtn = document.getElementById('saveBtn');
        let hasChanges = false;
        
        if (document.getElementById('statusSelect').value !== '{{ $mother->status ?? 'AKTIF' }}') {
            hasChanges = true;
        }
        
        document.querySelectorAll('.trimester-checkbox').forEach(checkbox => {
            const trimester = checkbox.dataset.trimester;
            const currentStatus = checkbox.checked ? 'Selesai' : 'Belum';
            if (currentStatus !== trimesters[trimester]) hasChanges = true;
        });
        
        document.querySelectorAll('.tt-checkbox').forEach(checkbox => {
            const key = checkbox.dataset.key;
            const currentStatus = checkbox.checked ? 'Sudah' : 'Belum';
            if (currentStatus !== ttStatus[key]) hasChanges = true;
        });
        
        if (hasChanges) {
            saveBtn.classList.remove('bg-gray-600');
            saveBtn.classList.add('bg-yellow-600', 'hover:bg-yellow-500');
            saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Simpan Perubahan';
        } else {
            saveBtn.classList.remove('bg-yellow-600', 'hover:bg-yellow-500');
            saveBtn.classList.add('bg-green-600', 'hover:bg-green-500');
            saveBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Tersimpan';
        }
    }

    function saveAllStatus() {
        const saveBtn = document.getElementById('saveBtn');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        
        const csrfToken = '{{ csrf_token() }}';
        const baseUrl = '/admin/informasi';
        const currentStatus = document.getElementById('statusSelect').value;
        
        // Simpan status data terlebih dahulu
        fetch(baseUrl + '/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                mother_id: motherId,
                status: currentStatus
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Status HTTP ' + response.status);
            return response.json();
        })
        .then(data => {
            console.log('Status saved:', data);
            // Simpan trimesters
            return fetch(baseUrl + '/update-trimester', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    mother_id: motherId,
                    all_trimester: trimesters
                })
            });
        })
        .then(response => {
            if (!response.ok) throw new Error('Trimester HTTP ' + response.status);
            return response.json();
        })
        .then(data => {
            console.log('Trimester saved:', data);
            // Simpan TT
            return fetch(baseUrl + '/update-tt', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    mother_id: motherId,
                    all_tt: ttStatus
                })
            });
        })
        .then(response => {
            if (!response.ok) throw new Error('TT HTTP ' + response.status);
            return response.json();
        })
        .then(data => {
            console.log('TT saved:', data);
            saveBtn.classList.remove('bg-yellow-600', 'hover:bg-yellow-500', 'bg-gray-600');
            saveBtn.classList.add('bg-green-600', 'hover:bg-green-500');
            saveBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Tersimpan';
            saveBtn.disabled = false;
            alert('Data berhasil disimpan!');
        })
        .catch(error => {
            console.error('Error:', error);
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Simpan Perubahan';
            alert('Gagal menyimpan: ' + error.message);
        });
    }
    </script>
@endsection
