@extends('layouts.admin')

@section('title', 'KMS Ibu - Admin')
@section('page_title', 'KMS Ibu Hamil')
@section('page_description', 'Pemantauan kesehatan ibu hamil (Berat Badan, LILA, Tekanan Darah)')

@section('admin_content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

<!-- Filter & Search -->
    <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700 mb-8">
        <!-- Alert Error Kode -->
        @if(session('error_kode'))
        <div class="bg-red-500/20 border border-red-500/30 rounded-xl p-4 mb-4">
            <p class="text-red-400 font-bold">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error_kode') }}
            </p>
        </div>
        @endif
        
        <!-- Alert Success Kode -->
        @if(session('success_kode'))
        <div class="bg-green-500/20 border border-green-500/30 rounded-xl p-4 mb-4">
            <p class="text-green-400 font-bold">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success_kode') }}
            </p>
        </div>
        @endif
        
        <form action="{{ route('admin.kms-ibu') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="w-40">
                <label class="text-white font-bold mb-2 block">Kode Akses:</label>
                <input type="text" name="kode_akses" value="{{ session('kode_akses') }}" 
                    placeholder="Isi Disini"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-pink-500 focus:outline-none">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="text-white font-bold mb-2 block">Cari Nama Ibu:</label>
                <input type="text" name="search" value="{{ $search }}" 
                    placeholder="Nama ibu atau NIK..."
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-pink-500 focus:outline-none">
            </div>
            <div class="w-48">
                <label class="text-white font-bold mb-2 block">Posyandu:</label>
                <select name="posyandu_id" onchange="this.form.submit()" class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-pink-500 focus:outline-none">
                    <option value="">Semua</option>
                    @foreach($posyandus as $posyandu)
                        <option value="{{ $posyandu->id }}" {{ $posyanduId == $posyandu->id ? 'selected' : '' }}>
                            {{ $posyandu->nama_posyandu }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-pink-600 hover:bg-pink-500 text-white px-6 py-2 rounded-xl font-bold">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Ibu -->
    <div class="bg-gray-900 rounded-3xl p-8 border border-gray-700">
        <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-user-pregnant mr-3 text-pink-400"></i>Daftar Ibu Hamil
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b border-gray-700">
                        <th class="pb-4 font-bold text-white">No</th>
                        <th class="pb-4 font-bold text-white">Nama Ibu</th>
                        <th class="pb-4 font-bold text-white">NIK</th>
                        <th class="pb-4 font-bold text-white">Posyandu</th>
                        <th class="pb-4 font-bold text-white">Minggu Kehamilan</th>
                        <th class="pb-4 font-bold text-white">Bulan ke-</th>
                        <th class="pb-4 font-bold text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mothers as $index => $mother)
                        @php
                            $mingguKehamil = 1;
                            if ($mother->tanggal_kehamiltonan) {
                                $mingguKehamil = \Carbon\Carbon::parse($mother->tanggal_kehamiltonan)->diffInMonths(now()) * 4 + 1;
                                $mingguKehamil = max(1, min(36, $mingguKehamil));
                            }
                            $bulanKe = ceil($mingguKehamil / 4);
                            $bulanKe = max(1, min(9, $bulanKe));
                        @endphp
                        <tr class="border-b border-gray-800 hover:bg-white/5">
                            <td class="py-4 text-white">{{ $index + 1 }}</td>
                            <td class="py-4">
                                <div class="text-white font-bold">{{ $mother->nama_lengkap ?? $mother->nama }}</div>
                                <div class="text-gray-400 text-sm">{{ $mother->tanggal_kehamiltonan ? \Carbon\Carbon::parse($mother->tanggal_kehamiltonan)->format('d M Y') : '-' }}</div>
                            </td>
                            <td class="py-4 text-gray-300">{{ $mother->nik ?? '-' }}</td>
                            <td class="py-4 text-gray-300">{{ $mother->posyandu->nama_posyandu ?? '-' }}</td>
                            <td class="py-4 text-pink-400 font-bold">Minggu ke-{{ $mingguKehamil }}</td>
                            <td class="py-4">
                                <span class="px-3 py-1 bg-pink-500/20 text-pink-400 rounded-full text-sm font-bold">
                                    {{ $bulanKe }}
                                </span>
                            </td>
                            <td class="py-4">
                                <button type="button" 
onclick="openKmsModal({{ $mother->id }}, '{{ $mother->nama_lengkap ?? $mother->nama }}', {{ $bulanKe }})"
                                    class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-lg font-bold text-sm transition-all">
                                    <i class="fas fa-edit mr-1"></i>Tambah
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">
                                <i class="fas fa-user-slash text-4xl mb-4"></i>
                                <p>Belum ada data ibu hamil</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
    @if(method_exists($mothers, 'links'))
    <div class="mt-6">
        {{ $mothers->links() }}
    </div>
    @endif
    </div>

    <!-- Modal Edit KMS Ibu -->
    <div id="kmsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gray-900 rounded-3xl p-8 border border-gray-700 max-w-lg w-full">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-user-pregnant mr-3 text-pink-400"></i>Edit KMS Ibu
                    </h3>
                    <button type="button" onclick="closeKmsModal()" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="kmsForm">
                    @csrf
                    <input type="hidden" name="mother_id" id="modalMotherId">
                    <input type="hidden" name="bulan_ke" id="modalBulanKe">
                    
                    <div class="mb-4">
                        <label class="text-white font-bold mb-2 block">Nama Ibu:</label>
                        <div id="modalNamaIbu" class="text-pink-400 font-bold text-lg">-</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-white font-bold mb-2 block">Bulan Ke:</label>
                        <div id="modalBulanDisplay" class="text-pink-400 font-bold">-</div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-white font-bold mb-2 block">Berat Badan (kg):</label>
                            <input type="number" name="berat_badan" id="modalBeratBadan" 
                                step="0.1" min="30" max="200"
                                placeholder="Contoh: 55"
                                class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-pink-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-white font-bold mb-2 block">LILA (cm):</label>
                            <input type="number" name="lila" id="modalLila" 
                                step="0.1" min="15" max="40"
                                placeholder="Contoh: 23.5"
                                class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-pink-500 focus:outline-none">
                            <p class="text-xs text-gray-400 mt-1">Normal: ≥ 23.5 cm</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-white font-bold mb-2 block">Tekanan Darah:</label>
                        <input type="text" name="tekanan_darah" id="modalTekananDarah" 
                            placeholder="Contoh: 120/80"
                            class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-pink-500 focus:outline-none">
                        <p class="text-xs text-gray-400 mt-1">Format: sistol/diastol (contoh: 120/80)</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-white font-bold mb-2 block">Tanggal Pemeriksaan:</label>
                        <input type="date" name="recorded_at" id="modalRecordedAt" 
                            value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-pink-500 focus:outline-none">
                    </div>
                    
                    <div class="mb-6">
                        <label class="text-white font-bold mb-2 block">Catatan:</label>
                        <textarea name="catatan" id="modalCatatan" rows="2"
                            placeholder="Catatan opsional..."
                            class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-pink-500 focus:outline-none"></textarea>
                    </div>
                    
                    <!-- Warning Message -->
                    <div id="modalWarning" class="hidden mb-4 p-4 bg-yellow-500/20 border border-yellow-500/30 rounded-xl">
                        <p class="text-yellow-400 text-sm">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span id="warningText">-</span>
                        </p>
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="button" onclick="closeKmsModal()" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 rounded-xl font-bold">
                            Batal
                        </button>
                        <button type="button" onclick="saveKmsData()" class="flex-1 bg-pink-600 hover:bg-pink-500 text-white py-3 rounded-xl font-bold">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentMotherId = null;
        let currentBulanKe = null;
        
        function openKmsModal(motherId, namaIbu, bulanKe) {
            currentMotherId = motherId;
            currentBulanKe = bulanKe;
            
            document.getElementById('modalMotherId').value = motherId;
            document.getElementById('modalNamaIbu').textContent = namaIbu;
            document.getElementById('modalBulanKe').value = bulanKe;
            document.getElementById('modalBulanDisplay').textContent = 'Bulan ke-' + bulanKe;
            
            // Reset form
            document.getElementById('kmsForm').reset();
            document.getElementById('modalRecordedAt').value = new Date().toISOString().split('T')[0];
            document.getElementById('modalWarning').classList.add('hidden');
            
            // Fetch existing data for this mother
            fetch("{{ route('admin.kms-ibu.get-data') }}?mother_id=" + motherId, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Check if record exists for current month
                    const currentRecord = data.current_month_record;
                    if (currentRecord) {
                        // Show warning but still allow viewing
                        document.getElementById('modalWarning').classList.remove('hidden');
                        document.getElementById('warningText').textContent = 'Data untuk bulan ke-' + bulanKe + ' sudah ada. Anda tidak dapat menginput dua kali dalam bulan yang sama.';
                        
                        // Fill form with existing data (read-only)
                        document.getElementById('modalBeratBadan').value = currentRecord.berat_badan || '';
                        document.getElementById('modalLila').value = currentRecord.lila || '';
                        document.getElementById('modalTekananDarah').value = currentRecord.tekanan_darah || '';
                        document.getElementById('modalCatatan').value = currentRecord.catatan || '';
                        if (currentRecord.recorded_at) {
                            document.getElementById('modalRecordedAt').value = currentRecord.recorded_at;
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
            
            document.getElementById('kmsModal').classList.remove('hidden');
        }
        
        function closeKmsModal() {
            document.getElementById('kmsModal').classList.add('hidden');
            currentMotherId = null;
            currentBulanKe = null;
        }
        
        function saveKmsData() {
            if (!currentMotherId || !currentBulanKe) {
                alert('Data ibu tidak valid');
                return;
            }
            
            const formData = new FormData(document.getElementById('kmsForm'));
            
            // Show loading
            const saveBtn = document.querySelector('#kmsModal button[type="button"]:last-child');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            saveBtn.disabled = true;
            
            fetch("{{ route('admin.kms-ibu.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Data KMS ibu berhasil disimpan!');
                    closeKmsModal();
                    // Reload page to show updated data
                    location.reload();
                } else {
                    alert(data.message || 'Gagal menyimpan data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan data');
            })
            .finally(() => {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }
        
        // Close modal when clicking outside
        document.getElementById('kmsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeKmsModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeKmsModal();
            }
        });
    </script>
@endsection
