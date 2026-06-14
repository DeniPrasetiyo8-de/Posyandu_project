@extends('layouts.admin')

@section('title', 'Edit Informasi Anak - Admin')
@section('page_title', 'Edit Informasi Anak')
@section('page_description', 'Edit imunisasi dan vitamin anak.')

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

<!-- Child Info Card -->
    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-gradient-to-r from-pink-400 to-purple-400 flex items-center justify-center text-white text-3xl shadow-lg">
                    <i class="fas fa-child"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-black mb-2">{{ $child->nama }}</h2>
                    <div class="flex items-center gap-4 text-black-300">
                        <span><i class="fas fa-calendar mr-2"></i>{{ $child->tanggal_lahir->format('d M Y') }}</span>
                        <span><i class="fas fa-venus mr-2"></i>{{ $child->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        <span><i class="fas fa-map-marker mr-2"></i>{{ $child->posyandu->nama_posyandu ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-end">
                <label class="block text-black text-sm font-bold mb-2">Status Data</label>
                <select name="status" id="statusSelect" class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none" onchange="updateStatusData()">
                    <option value="AKTIF" {{ ($child->status ?? 'AKTIF') == 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                    <option value="NONAKTIF" {{ ($child->status ?? 'AKTIF') == 'NONAKTIF' ? 'selected' : '' }}>NONAKTIF</option>
                </select>
                <span id="statusText" class="mt-2 text-sm font-bold {{ ($child->status ?? 'AKTIF') == 'AKTIF' ? 'text-green-400' : 'text-red-400' }}">
                    {{ ($child->status ?? 'AKTIF') == 'AKTIF' ? 'Data Aktif' : 'Data Nonaktif' }}
                </span>
            </div>
        </div>
    </div>

<!-- Imunisasi and Vitamin Grid -->
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Imunisasi -->
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-black mb-6 flex items-center">
                <i class="fas fa-syringe text-blue-400 mr-3"></i>Imunisasi
            </h3>
            <div class="space-y-3">
                @foreach(\App\Models\Child::getDaftarImunisasi() as $key => $nama)
                @php
                    // Use ENUM column directly from database
                    $status = $child->$key ?? 'Belum';
                @endphp
                <div class="flex items-center justify-between p-4 bg-gray-800/50 rounded-xl">
                    <span class="text-black font-medium">{{ $nama }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer imunisasi-checkbox" 
                            data-key="{{ $key }}"
                            data-column="{{ $key }}"
                            {{ $status == 'Sudah' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                        <span class="ms-3 text-sm font-bold imunisasi-status-text {{ $status == 'Sudah' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $status == 'Sudah' ? 'Sudah' : 'Belum' }}
                        </span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Vitamin -->
        <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-black mb-6 flex items-center">
                <i class="fas fa-pills text-pink-400 mr-3"></i>Vitamin
            </h3>
            <div class="space-y-3">
                @foreach(\App\Models\Child::getDaftarVitamin() as $key => $nama)
                @php
                    // Use ENUM column directly from database
                    $status = $child->$key ?? 'Belum';
                @endphp
                <div class="flex items-center justify-between p-4 bg-gray-800/50 rounded-xl">
                    <span class="text-black font-medium">{{ $nama }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer vitamin-checkbox" 
                            data-key="{{ $key }}"
                            data-column="{{ $key }}"
                            {{ $status == 'Sudah' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500"></div>
                        <span class="ms-3 text-sm font-bold vitamin-status-text {{ $status == 'Sudah' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $status == 'Sudah' ? 'Sudah' : 'Belum' }}
                        </span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

<script>
    let childId = {{ $child->id }};
    let immunizations = {};
    let vitamins = {};
    let dataStatus = '{{ $child->status ?? 'AKTIF' }}';
    
    // Initialize status from ENUM columns in database
    @foreach(\App\Models\Child::getDaftarImunisasi() as $key => $nama)
    immunizations['{{ $key }}'] = '{{ $child->$key ?? 'Belum' }}';
    @endforeach
    
    @foreach(\App\Models\Child::getDaftarVitamin() as $key => $nama)
    vitamins['{{ $key }}'] = '{{ $child->$key ?? 'Belum' }}';
    @endforeach

    function updateStatusData() {
        const select = document.getElementById('statusSelect');
        const statusText = document.getElementById('statusText');
        dataStatus = select.value;
        
        if (dataStatus === 'AKTIF') {
            statusText.textContent = 'Data Aktif';
            statusText.className = 'mt-2 text-sm font-bold text-green-400';
        } else {
            statusText.textContent = 'Data Nonaktif';
            statusText.className = 'mt-2 text-sm font-bold text-red-400';
        }
        
        updateSaveButton();
    }

    // Track changes on checkbox toggle (don't save immediately)
    document.querySelectorAll('.imunisasi-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const key = this.dataset.key;
            const status = this.checked ? 'Sudah' : 'Belum';
            immunizations[key] = status;
            
            // Update status text
            const statusText = this.parentElement.querySelector('.imunisasi-status-text');
            statusText.textContent = status;
            statusText.className = 'ms-3 text-sm font-bold imunisasi-status-text ' + (status === 'Sudah' ? 'text-green-400' : 'text-red-400');
            
            updateSaveButton();
        });
    });

    document.querySelectorAll('.vitamin-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const key = this.dataset.key;
            const status = this.checked ? 'Sudah' : 'Belum';
            vitamins[key] = status;
            
            // Update status text
            const statusText = this.parentElement.querySelector('.vitamin-status-text');
            statusText.textContent = status;
            statusText.className = 'ms-3 text-sm font-bold vitamin-status-text ' + (status === 'Sudah' ? 'text-green-400' : 'text-red-400');
            
            updateSaveButton();
        });
    });

function updateSaveButton() {
        const saveBtn = document.getElementById('saveBtn');
        
        // Check if any changes
        let hasChanges = false;
        
        // Check status change
        const originalStatus = '{{ $child->status ?? 'AKTIF' }}';
        if (dataStatus !== originalStatus) {
            hasChanges = true;
        }
        
        // Check current DOM state vs original
        document.querySelectorAll('.imunisasi-checkbox').forEach(checkbox => {
            const key = checkbox.dataset.key;
            const currentStatus = checkbox.checked ? 'Sudah' : 'Belum';
            if (currentStatus !== immunizations[key]) {
                hasChanges = true;
            }
        });
        
        document.querySelectorAll('.vitamin-checkbox').forEach(checkbox => {
            const key = checkbox.dataset.key;
            const currentStatus = checkbox.checked ? 'Sudah' : 'Belum';
            if (currentStatus !== vitamins[key]) {
                hasChanges = true;
            }
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
        
        // Using full URL paths
        const baseUrl = '/admin/informasi';
        const statusUrl = baseUrl + '/update-status';
        const imunisasiUrl = baseUrl + '/update-imunisasi';
        const vitaminUrl = baseUrl + '/update-vitamin';
        
        console.log('Saving data:');
        console.log('- childId:', childId);
        console.log('- status:', dataStatus);
        console.log('- all_imunisasi:', immunizations);
        console.log('- all_vitamin:', vitamins);
        
        // Save status first (AKTIF/NONAKTIF)
        const originalStatus = '{{ $child->status ?? 'AKTIF' }}';
        
        // Create array of save promises
        const savePromises = [];
        
        // Only save status if changed
        if (dataStatus !== originalStatus) {
            savePromises.push(
                fetch(statusUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        child_id: childId,
                        status: dataStatus
                    })
                })
                .then(response => {
                    console.log('Status response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Status HTTP ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Status saved:', data);
                })
            );
        }
        
        // Save imunisasi
        savePromises.push(
            fetch(imunisasiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    child_id: childId,
                    all_imunisasi: immunizations
                })
            })
            .then(response => {
                console.log('Imunisasi response status:', response.status);
                if (!response.ok) {
                    throw new Error('Imunisasi HTTP ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Imunisasi saved:', data);
            })
        );
        
        // Save vitamins
        savePromises.push(
            fetch(vitaminUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    child_id: childId,
                    all_vitamin: vitamins
                })
            })
            .then(response => {
                console.log('Vitamin response status:', response.status);
                if (!response.ok) {
                    throw new Error('Vitamin HTTP ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Vitamin saved:', data);
            })
        );
        
        // Wait for all saves to complete
        Promise.all(savePromises)
        .then(() => {
            // Update button to green (saved)
            saveBtn.classList.remove('bg-yellow-600', 'hover:bg-yellow-500', 'bg-gray-600');
            saveBtn.classList.add('bg-green-600', 'hover:bg-green-500');
            saveBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Tersimpan';
            saveBtn.disabled = false;
            
            // Update local state to reflect saved values
            document.querySelectorAll('.imunisasi-checkbox').forEach(checkbox => {
                const key = checkbox.dataset.key;
                immunizations[key] = checkbox.checked ? 'Sudah' : 'Belum';
            });
            document.querySelectorAll('.vitamin-checkbox').forEach(checkbox => {
                const key = checkbox.dataset.key;
                vitamins[key] = checkbox.checked ? 'Sudah' : 'Belum';
            });
            
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
