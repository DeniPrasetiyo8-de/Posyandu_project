@extends('layouts.admin')

@section('title', 'Laporan Posyandu - Admin')
@section('page_title', 'Laporan Posyandu')
@section('page_description', 'Kelola dan unduh data laporan posyandu.')

@section('admin_content')
<!-- Stats Cards - 4 Kotak seperti Dashboard Admin -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
<div class="bg-gray-900 rounded-3xl p-6 border border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-blue-600 rounded-xl">
                <i class="fas fa-child text-white text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-white">{{ $stats['total_anak'] ?? 0 }}</h3>
        <p class="text-gray-400">Total Anak</p>
    </div>

    <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-green-600 rounded-xl">
                <i class="fas fa-calendar-check text-white text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-white">{{ $stats['total_jadwal'] ?? 0 }}</h3>
        <p class="text-gray-400">Jadwal Kegiatan</p>
    </div>

    <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-purple-600 rounded-xl">
                <i class="fas fa-map-marker-alt text-white text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-white">{{ $stats['total_posyandu'] ?? 0 }}</h3>
        <p class="text-gray-400">Posyandu</p>
    </div>

    <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-pink-600 rounded-xl">
                <i class="fas fa-chart-line text-white text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-white">{{ $stats['total_kms'] ?? 0 }}</h3>
        <p class="text-gray-400">Rekam Medis KMS</p>
    </div>
</div>

<!-- Additional Stats Cards for Nutrition Status -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-green-600 rounded-xl">
                <i class="fas fa-check-circle text-white text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-white">{{ $stats['normal'] ?? 0 }}</h3>
        <p class="text-gray-400">Status Gizi Baik</p>
    </div>

    <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-red-600 rounded-xl">
                <i class="fas fa-exclamation-triangle text-white text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-white">{{ $stats['underweight'] ?? 0 }}</h3>
        <p class="text-gray-400">Gizi Kurang</p>
    </div>

    <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-yellow-600 rounded-xl">
                <i class="fas fa-user-clock text-white text-xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold text-white">{{ $stats['stunting'] ?? 0 }}</h3>
        <p class="text-gray-400">Stunting</p>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-gray-900 rounded-3xl p-8 border border-gray-700">
        <h3 class="text-xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-chart-pie mr-3 text-blue-400"></i>Distribusi Status Gizi
        </h3>
        <canvas id="giziChart"></canvas>
    </div>

    <div class="bg-gray-900 rounded-3xl p-8 border border-gray-700">
        <h3 class="text-xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-chart-bar mr-3 text-green-400"></i>Perbandingan Status
        </h3>
        <canvas id="statusChart"></canvas>
    </div>
</div>
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <a href="{{ route('admin.dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
    <div class="flex gap-3">
        <button onclick="exportData('Pdf')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-bold">
            <i class="fas fa-file-Pdf mr-2"></i>PDF
        </button>
        <button onclick="exportData('Excel')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl font-bold">
            <i class="fas fa-file-excel mr-2"></i>Excel
        </button>
        <button onclick="exportData('csv')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold">
            <i class="fas fa-file-csv mr-2"></i>CSV
        </button>
    </div>
</div>

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

<!-- Load Chart.js for charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Search and Filter Form -->
<div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 mb-8">
    <form action="{{ route('admin.laporan') }}" method="GET" class="space-y-6">
        <div class="grid md:grid-cols-5 gap-6">
            <div>
                <label class="block text-black text-sm font-bold mb-2">Kode Akses</label>
                <input type="text" name="kode_akses" value="{{ session('kode_akses') }}" placeholder="Isi Disini" 
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                <p class="text-xs text-gray-400 mt-1">Isi: Kode Sesuai Posyandu</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-black text-sm font-bold mb-2">Cari (Nama atau NIK)</label>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Ketik nama atau NIK..." 
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-black text-sm font-bold mb-2">Tipe Data</label>
                <select name="type" id="typeFilter" onchange="this.form.submit()" class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                    <option value="anak" {{ ($type ?? 'anak') == 'anak' ? 'selected' : '' }}>Data Anak</option>
                    <option value="ibu" {{ ($type ?? 'anak') == 'ibu' ? 'selected' : '' }}>Data Ibu Hamil</option>
                </select>
            </div>
            <div>
                <label class="block text-black text-sm font-bold mb-2">Filter Status</label>
                <select name="status" onchange="this.form.submit()" class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="AKTIF" {{ ($status ?? '') == 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                    <option value="NONAKTIF" {{ ($status ?? '') == 'NONAKTIF' ? 'selected' : '' }}>NONAKTIF</option>
                </select>
            </div>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div>
                <label class="block text-black text-sm font-bold mb-2">Filter RW</label>
                <select name="rw" onchange="this.form.submit()" class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                    <option value="">Semua RW</option>
                    <option value="01" {{ ($rw ?? '') == '01' ? 'selected' : '' }}>RW 01</option>
                    <option value="02" {{ ($rw ?? '') == '02' ? 'selected' : '' }}>RW 02</option>
                    <option value="03" {{ ($rw ?? '') == '03' ? 'selected' : '' }}>RW 03</option>
                    <option value="04" {{ ($rw ?? '') == '04' ? 'selected' : '' }}>RW 04</option>
                    <option value="05" {{ ($rw ?? '') == '05' ? 'selected' : '' }}>RW 05</option>
                    <option value="06" {{ ($rw ?? '') == '06' ? 'selected' : '' }}>RW 06</option>
                </select>
            </div>
            <div class="md:col-span-2 flex items-end gap-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-xl font-bold">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <label class="flex items-center cursor-pointer bg-gray-700 px-4 py-3 rounded-xl">
                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="mr-2 w-5 h-5">
                    <span class="text-white font-bold">Pilih Semua</span>
                </label>
            </div>
        </div>
    </form>
</div>

<!-- Results Table -->
<div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
    <h3 class="text-2xl font-bold text-black mb-6 flex items-center">
        @if(($type ?? 'anak') == 'anak')
            <i class="fas fa-baby mr-3 text-pink-400"></i>Daftar Laporan Data Anak
        @else
            <i class="fas fa-female mr-3 text-pink-400"></i>Daftar Laporan Data Ibu Hamil
        @endif
        <span class="ml-4 text-lg font-normal text-gray-400">({{ $results->total() }} data)</span>
    </h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-black border-b border-black-700">
                    <th class="pb-4 font-bold w-10">#</th>
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
                        <td class="py-4">
                            <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" class="w-5 h-5 record-checkbox">
                        </td>
                        
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
                            <button onclick="showDetail('{{ $item->id }}', '{{ $type ?? 'anak' }}')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm font-bold inline-block">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-black">
                            <i class="fas fa-file-alt text-4xl mb-4 block"></i>
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

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-gray-900 rounded-3xl p-8 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-white" id="modalTitle">Detail Riwayat</h3>
            <button onclick="closeDetail()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div id="modalContent" class="text-white">
            <div class="flex justify-center">
                <i class="fas fa-circle-notch fa-spin text-4xl text-blue-500"></i>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Form for Export -->
<form id="exportForm" method="POST" action="{{ route('admin.laporan.export') }}" style="display: none;">
    @csrf
    <input type="hidden" name="selected_ids" id="exportSelectedIds">
    <input type="hidden" name="export_type" id="exportType">
    <input type="hidden" name="type" value="{{ $type ?? 'anak' }}">
    <input type="hidden" name="search" value="{{ $search ?? '' }}">
    <input type="hidden" name="status" value="{{ $status ?? '' }}">
    <input type="hidden" name="rw" value="{{ $rw ?? '' }}">
</form>

<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.record-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = selectAll.checked;
    });
}

function showDetail(id, type) {
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('modalContent').innerHTML = '<div class="flex justify-center py-8"><i class="fas fa-circle-notch fa-spin text-4xl text-blue-500"></i></div>';
    
    fetch('{{ route("admin.laporan.get-detail") }}?id=' + id + '&type=' + type)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalContent').innerHTML = data.html;
            } else {
                document.getElementById('modalContent').innerHTML = '<p class="text-red-400">Gagal memuat data</p>';
            }
        })
        .catch(error => {
            document.getElementById('modalContent').innerHTML = '<p class="text-red-400">Error: ' + error.message + '</p>';
        });
}

function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
}

function exportData(type) {
    const checkboxes = document.querySelectorAll('.record-checkbox:checked');
    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    
    document.getElementById('exportSelectedIds').value = selectedIds.join(',');
    document.getElementById('exportType').value = type;
    
    if (selectedIds.length === 0) {
        alert('Pilih minimal satu data untuk diunduh!');
        return;
    }
    
    document.getElementById('exportForm').submit();
}

// Close modal when clicking outside
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetail();
    }
});
// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // Gizi Distribution Chart (Doughnut)
    const ctxGizi = document.getElementById('giziChart');
    if (ctxGizi) {
        new Chart(ctxGizi.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Baik', 'Kurang', 'Stunting'],
                datasets: [{
                    data: [
                        {{ $stats['normal'] ?? 0 }},
                        {{ $stats['underweight'] ?? 0 }},
                        {{ $stats['stunting'] ?? 0 }}
                    ],
                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#e2e8f0' }
                    }
                }
            }
        });
    }

    // Status Comparison Chart (Bar)
    const ctxStatus = document.getElementById('statusChart');
    if (ctxStatus) {
        new Chart(ctxStatus.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Baik', 'Gizi Kurang', 'Stunting'],
                datasets: [{
                    label: 'Jumlah Anak',
                    data: [
                        {{ $stats['normal'] ?? 0 }},
                        {{ $stats['underweight'] ?? 0 }},
                        {{ $stats['stunting'] ?? 0 }}
                    ],
                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b']
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#e2e8f0' },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    },
                    x: {
                        ticks: { color: '#e2e8f0' },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>

<style>
.bg-gray-50 { background-color: #1f2937; }
@endsection
