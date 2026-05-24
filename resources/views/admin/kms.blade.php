@extends('layouts.admin')

@section('title', 'KMS Analytics - Admin')
@section('page_title', 'KMS Analytics')
@section('page_description', 'Analisis status gizi anak.')

@section('admin_content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

<!-- Filter Posyandu -->
    <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700 mb-8">
        <form action="{{ route('admin.kms') }}" method="GET" class="flex items-center gap-4">
            <label class="text-white font-bold">Filter Posyandu:</label>
            <select name="posyandu_id" onchange="this.form.submit()" class="px-4 py-2 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                <option value="">Semua Posyandu</option>
                @foreach($posyandus as $posyandu)
                    <option value="{{ $posyandu->id }}" {{ $posyanduId == $posyandu->id ? 'selected' : '' }}>
                        {{ $posyandu->nama_posyandu }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

<!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-800 rounded-xl">
                    <i class="fas fa-child text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-white">{{ $stats['total_anak'] }}</h3>
<p class="text-black">Total Anak</p>
        </div>

        <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-800 rounded-xl">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-white">{{ $stats['normal'] ?? 0 }}</h3>
            <p class="text-black">Status Gizi Baik</p>
        </div>

        <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-800 rounded-xl">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-white">{{ $stats['underweight'] ?? 0 }}</h3>
            <p class="text-black">Gizi Kurang</p>
        </div>

        <div class="bg-gray-900 rounded-3xl p-6 border border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-gray-800 rounded-xl">
                    <i class="fas fa-user-clock text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-white">{{ $stats['stunting'] ?? 0 }}</h3>
            <p class="text-black">Stunting</p>
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

    <!-- Recent Children with Health Issues -->
    <div class="bg-gray-900 rounded-3xl p-8 border border-gray-700">
        <h3 class="text-xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-red-400"></i>Anak dengan Gizi Kurang
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
<tr class="text-left text-black border-b border-gray-700">
                        <th class="pb-4 font-bold">Nama</th>
                        <th class="pb-4 font-bold">Umur</th>
                        <th class="pb-4 font-bold">Berat</th>
                        <th class="pb-4 font-bold">Posyandu</th>
                        <th class="pb-4 font-bold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $problemChildren = \App\Models\Child::whereHas('healthRecords', function($q) {
                            $q->where('berat_badan', '<', 10);
                        })->with('healthRecords', 'posyandu')->take(10)->get();
                    @endphp
                    @forelse($problemChildren as $child)
                        <tr class="border-b border-gray-800 hover:bg-white/5">
                            <td class="py-4">
                                <span class="text-white font-bold">{{ $child->nama }}</span>
                            </td>
<td class="py-4 text-black">{{ $child->umur_bulan ?? 0 }} bulan</td>
                            <td class="py-4 text-black">
                                {{ $child->healthRecords->first()->berat_badan ?? '-' }} kg
                            </td>
                            <td class="py-4 text-black">
                                {{ $child->posyandu->nama_posyandu ?? 'N/A' }}
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-sm font-bold">
                                    Gizi Kurang
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
<td colspan="5" class="py-8 text-center text-black">
                                <i class="fas fa-check-circle text-4xl mb-4 text-green-400"></i>
                                <p>Semua anak memiliki status gizi baik</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gizi Distribution Chart
    const ctxGizi = document.getElementById('giziChart').getContext('2d');
    new Chart(ctxGizi, {
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

    // Status Comparison Chart
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
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
</script>
@endsection
