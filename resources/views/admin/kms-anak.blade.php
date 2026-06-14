@extends('layouts.admin')

@section('title', 'KMS Anak - Admin')
@section('page_title', 'KMS Anak')
@section('page_description', 'Pemantauan kesehatan anak (Berat Badan, Tinggi Badan, Status Gizi, Stunting)')

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
        
        <form action="{{ route('admin.kms-anak') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="w-40">
                <label class="text-white font-bold mb-2 block">Kode Akses:</label>
                <input type="text" name="kode_akses" value="{{ session('kode_akses') }}" 
                    placeholder="Isi Disini"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="text-white font-bold mb-2 block">Cari Nama Anak:</label>
                <input type="text" name="search" value="{{ $search }}" 
                    placeholder="Nama anak atau NIK..."
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
            </div>
            <div class="w-48">
                <label class="text-white font-bold mb-2 block">Posyandu:</label>
                <select name="posyandu_id" onchange="this.form.submit()" class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                    <option value="">Semua</option>
                    @foreach($posyandus as $posyandu)
                        <option value="{{ $posyandu->id }}" {{ $posyanduId == $posyandu->id ? 'selected' : '' }}>
                            {{ $posyandu->nama_posyandu }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-xl font-bold">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Anak -->
    <div class="bg-gray-900 rounded-3xl p-8 border border-gray-700">
        <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-child mr-3 text-blue-400"></i>Daftar Anak
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b border-gray-700">
                        <th class="pb-4 font-bold text-white">No</th>
                        <th class="pb-4 font-bold text-white">Nama Anak</th>
                        <th class="pb-4 font-bold text-white">NIK</th>
                        <th class="pb-4 font-bold text-white">Posyandu</th>
                        <th class="pb-4 font-bold text-white">Umur</th>
                        <th class="pb-4 font-bold text-white">BB Terakhir</th>
                        <th class="pb-4 font-bold text-white">Status Gizi</th>
                        <th class="pb-4 font-bold text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($children as $index => $child)
                        @php
                            $umurBulan = $child->tanggal_lahir ? \Carbon\Carbon::parse($child->tanggal_lahir)->diffInMonths(now()) : 0;
                            $latestRecord = $child->healthRecords->first();
                            $statusGizi = $latestRecord ? $latestRecord->status_gizi : '-';
                            $beratTerakhir = $latestRecord ? $latestRecord->berat . ' kg' : '-';
                            
                            // Status badge color
                            $statusBadge = 'bg-gray-500/20 text-gray-400 border-gray-500/30';
                            if ($statusGizi === 'Normal') {
                                $statusBadge = 'bg-green-500/20 text-green-400 border-green-500/30';
                            } elseif ($statusGizi === 'Gizi Kurang' || $statusGizi === 'Gizi Buruk') {
                                $statusBadge = 'bg-red-500/20 text-red-400 border-red-500/30';
                            } elseif ($statusGizi === 'Gizi Lebih') {
                                $statusBadge = 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30';
                            }
                        @endphp
                        <tr class="border-b border-gray-800 hover:bg-white/5">
                            <td class="py-4 text-white">{{ $index + 1 }}</td>
                            <td class="py-4">
                                <div class="text-white font-bold">{{ $child->nama }}</div>
                                <div class="text-gray-400 text-sm">{{ $child->tanggal_lahir ? \Carbon\Carbon::parse($child->tanggal_lahir)->format('d M Y') : '-' }}</div>
                            </td>
                            <td class="py-4 text-gray-300">{{ $child->nik ?? '-' }}</td>
                            <td class="py-4 text-gray-300">{{ $child->posyandu->nama_posyandu ?? '-' }}</td>
                            <td class="py-4 text-blue-400 font-bold">{{ $umurBulan }} bulan</td>
                            <td class="py-4 text-green-400 font-mono">{{ $beratTerakhir }}</td>
                            <td class="py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-bold border {{ $statusBadge }}">
                                    {{ $statusGizi }}
                                </span>
                            </td>
                            <td class="py-4">
                                <button type="button" 
                                    onclick="openKmsModal({{ $child->id }}, '{{ $child->nama }}', {{ $umurBulan }})"
                                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-bold text-sm transition-all">
                                    <i class="fas fa-edit mr-1"></i>Tambah
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">
                                <i class="fas fa-child-slash text-4xl mb-4"></i>
                                <p>Belum ada data anak</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $children->links() }}
        </div>
    </div>

    <!-- Modal Edit KMS Anak -->
    <div id="kmsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-gray-900 rounded-3xl p-8 border border-gray-700 max-w-lg w-full">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-child mr-3 text-blue-400"></i>Edit KMS Anak
                    </h3>
                    <button type="button" onclick="closeKmsModal()" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="kmsForm">
                    @csrf
                    <input type="hidden" name="child_id" id="modalChildId">
                    
                    <div class="mb-4">
                        <label class="text-white font-bold mb-2 block">Nama Anak:</label>
                        <div id="modalNamaAnak" class="text-blue-400 font-bold text-lg">-</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-white font-bold mb-2 block">Umur:</label>
                        <div id="modalUmurDisplay" class="text-blue-400 font-bold">-</div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-white font-bold mb-2 block">Berat Badan (kg):</label>
                            <input type="number" name="berat" id="modalBerat" 
                                step="0.1" min="0.5" max="100"
                                placeholder="Contoh: 12.5"
                                class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-white font-bold mb-2 block">Tinggi Badan (cm):</label>
                            <input type="number" name="tinggi" id="modalTinggi" 
                                step="0.1" min="20" max="150"
                                placeholder="Contoh: 85.5"
                                class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="text-white font-bold mb-2 block">Tanggal Pemeriksaan:</label>
                        <input type="date" name="tanggal" id="modalTanggal" 
                            value="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                    </div>
                    
                    <div class="mb-6">
                        <label class="text-white font-bold mb-2 block">Catatan:</label>
                        <textarea name="catatan" id="modalCatatan" rows="2"
                            placeholder="Catatan opsional..."
                            class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none"></textarea>
                    </div>
                    
                    <!-- Status Info -->
                    <div id="statusInfo" class="hidden mb-4 p-4 bg-blue-500/20 border border-blue-500/30 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-blue-400 text-sm">Status Gizi:</span>
                                <span id="statusGiziDisplay" class="text-white font-bold ml-2">-</span>
                            </div>
                            <div>
                                <span class="text-blue-400 text-sm">Stunting:</span>
                                <span id="statusStuntingDisplay" class="text-white font-bold ml-2">-</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="button" onclick="closeKmsModal()" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-3 rounded-xl font-bold">
                            Batal
                        </button>
                        <button type="button" onclick="saveKmsData()" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl font-bold">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentChildId = null;
        
        function openKmsModal(childId, namaAnak, umurBulan) {
            currentChildId = childId;
            
            document.getElementById('modalChildId').value = childId;
            document.getElementById('modalNamaAnak').textContent = namaAnak;
            document.getElementById('modalUmurDisplay').textContent = umurBulan + ' bulan';
            
            // Reset form
            document.getElementById('kmsForm').reset();
            document.getElementById('modalTanggal').value = new Date().toISOString().split('T')[0];
            document.getElementById('statusInfo').classList.add('hidden');
            
            // Fetch existing data for this child
            fetch("{{ route('admin.kms-anak.get-data') }}?child_id=" + childId, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const latestRecord = data.latest_record;
                    if (latestRecord) {
                        document.getElementById('modalBerat').value = latestRecord.berat || '';
                        document.getElementById('modalTinggi').value = latestRecord.tinggi || '';
                        document.getElementById('modalTanggal').value = latestRecord.tanggal || '';
                        document.getElementById('modalCatatan').value = latestRecord.catatan || '';
                        
                        // Show status info
                        document.getElementById('statusInfo').classList.remove('hidden');
                        document.getElementById('statusGiziDisplay').textContent = latestRecord.status_gizi || '-';
                        document.getElementById('statusStuntingDisplay').textContent = latestRecord.status_stunting || '-';
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
            currentChildId = null;
        }
        
        function saveKmsData() {
            if (!currentChildId) {
                alert('Data anak tidak valid');
                return;
            }
            
            const formData = new FormData(document.getElementById('kmsForm'));
            formData.append('child_id', currentChildId);
            
            // Show loading
            const saveBtn = document.querySelector('#kmsModal button[type="button"]:last-child');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            saveBtn.disabled = true;
            
            fetch("{{ route('admin.kms-anak.store') }}", {
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
                    alert('Data KMS anak berhasil disimpan!');
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
