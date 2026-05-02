@extends('layouts.app')

@section('title', 'KMS - Kartu Menuju Sehat')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-chart-line mr-4 text-blue-400 text-3xl"></i>
                Kartu Menuju Sehat (KMS)
            </h1>
            <p class="text-xl text-slate-400">Pemantauan perkembangan berat badan, tinggi badan, dan status gizi anak secara otomatis dari data informasi anak</p>
        </div>
    </div>

    @if($children->isEmpty())
        <div class="text-center py-20 bg-slate-800/30 rounded-3xl border-2 border-dashed border-slate-600">
            <i class="fas fa-child text-7xl text-slate-500 mb-6"></i>
            <h3 class="text-2xl font-bold text-slate-300 mb-4">Belum Ada Data Anak</h3>
            <p class="text-slate-500 mb-8 max-w-lg mx-auto">Tambahkan data anak di menu <strong>Informasi Anak</strong> untuk melihat diagram KMS dan status gizi.</p>
            <a href="{{ route('dashboard.informasi-anak') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-12 py-4 rounded-2xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all inline-flex items-center space-x-3">
                <i class="fas fa-arrow-right"></i>
                <span>Kelola Data Anak</span>
            </a>
        </div>
    @else
        {{-- Child Selector --}}
        <div class="bg-slate-800/70 backdrop-blur-sm rounded-3xl p-8 border border-slate-700 mb-8">
            <label class="block text-white font-bold text-xl mb-4">Pilih Anak</label>
            <select id="childSelect" class="w-full md:w-96 p-4 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all">
                @foreach($children as $child)
                    <option value="{{ $child->id }}" data-nama="{{ $child->nama }}" data-berat="{{ $child->berat_badan ?? 0 }}" data-tinggi="{{ $child->tinggi_badan ?? 0 }}" data-health='{{ json_encode($child->healthRecords->toArray()) }}'>
                        {{ $child->nama }} ({{ $child->umur_bulan }} bulan{{ $child->berat_badan ? ', BB ' . $child->berat_badan . ' kg' : '' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-3xl p-8 border border-slate-700">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-chart-line mr-3 text-blue-400"></i>
                    Perkembangan BB & TB
                </h3>
                <canvas id="growthChart" height="400"></canvas>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm rounded-3xl p-8 border border-slate-700">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-chart-pie mr-3 text-green-400"></i>
                    Status Gizi
                </h3>
                <canvas id="giziChart" height="400"></canvas>
                <div id="giziStats" class="mt-6 space-y-3"></div>
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm rounded-3xl border border-slate-700 overflow-hidden">
            <div class="p-6 border-b border-slate-700 bg-gradient-to-r from-slate-900 to-transparent">
                <h3 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-table mr-3 text-purple-400"></i>
                    Riwayat Pemeriksaan Terbaru
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-900/50">
                        <tr>
                            <th class="p-6 text-left text-slate-300 font-bold text-lg">Tanggal</th>
                            <th class="p-6 text-left text-slate-300 font-bold text-lg">Berat (kg)</th>
                            <th class="p-6 text-left text-slate-300 font-bold text-lg">Tinggi (cm)</th>
                            <th class="p-6 text-left text-slate-300 font-bold text-lg">BB/U</th>
                            <th class="p-6 text-left text-slate-300 font-bold text-lg">Status</th>
                        </tr>
                    </thead>
                    <tbody id="recordsTableBody" class="divide-y divide-slate-700">
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let growthChart, giziChart;
        let currentChildData = null;

        document.getElementById('childSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            currentChildData = {
                id: selectedOption.value,
                nama: selectedOption.dataset.nama,
                berat: parseFloat(selectedOption.dataset.berat),
                tinggi: parseFloat(selectedOption.dataset.tinggi),
                healthRecords: JSON.parse(selectedOption.dataset.health)
            };
            updateCharts();
        });

        function updateCharts() {
            if (!currentChildData) return;

            // Labels for chart
            const labels = [...Array(currentChildData.healthRecords.length).keys()].map(i => `Pemeriksaan ${i+1}`).reverse();
            labels.push('Saat Ini');

            const beratData = currentChildData.healthRecords.slice().reverse().map(r => r.berat || currentChildData.berat);
            beratData.push(currentChildData.berat);

            const tinggiData = currentChildData.healthRecords.slice().reverse().map(r => r.tinggi || currentChildData.tinggi);
            tinggiData.push(currentChildData.tinggi);

            // Growth Chart
            const ctxGrowth = document.getElementById('growthChart').getContext('2d');
            if (growthChart) growthChart.destroy();
            growthChart = new Chart(ctxGrowth, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Berat Badan (kg)',
                        data: beratData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y'
                    }, {
                        label: 'Tinggi Badan (cm)',
                        data: tinggiData,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    interaction: {
                        intersect: false
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });

            // Gizi Status
            const allRecords = [...currentChildData.healthRecords, {berat: currentChildData.berat, tinggi: currentChildData.tinggi}];
            const statuses = allRecords.map(r => {
                const tinggiM = (r.tinggi || 50) / 100; // default 50cm
                const bbu = tinggiM > 0 ? (r.berat || 3) / (tinggiM * tinggiM) : 15; // default
                if (bbu < 14) return 'Gizi Buruk';
                if (bbu < 16) return 'Kurang';
                if (bbu > 20) return 'Lebih';
                return 'Baik';
            });

            const statusCount = {};
            statuses.forEach(s => statusCount[s] = (statusCount[s] || 0) + 1);

            const ctxGizi = document.getElementById('giziChart').getContext('2d');
            if (giziChart) giziChart.destroy();
            giziChart = new Chart(ctxGizi, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusCount),
                    datasets: [{
                        data: Object.values(statusCount),
                        backgroundColor: ['#ef4444', '#f97316', '#eab308', '#10b981']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Gizi Stats
            let statsHTML = '';
            Object.entries(statusCount).forEach(([status, count]) => {
                const color = status.includes('Buruk') ? 'text-red-400' : status.includes('Kurang') ? 'text-orange-400' : status.includes('Lebih') ? 'text-yellow-400' : 'text-green-400';
                statsHTML += `<div class="p-4 bg-slate-900/50 rounded-xl border-l-4 border-${color.split('-')[1]}-500">
                    <div class="text-2xl font-bold ${color}">${count}</div>
                    <div class="text-slate-400 capitalize">${status}</div>
                </div>`;
            });
            document.getElementById('giziStats').innerHTML = statsHTML;

            // Table
            const tbody = document.getElementById('recordsTableBody');
            tbody.innerHTML = allRecords.slice().reverse().map((r, i) => {
                const tinggiM = (r.tinggi || 50) / 100;
                const bbu = tinggiM > 0 ? (r.berat || 3) / (tinggiM * tinggiM) : 15;
                const statusHtml = calculateStatus(r.berat || 3, r.tinggi || 50);
                return `
                    <tr class="hover:bg-slate-800/50">
                        <td class="p-6">${['Pemeriksaan', 'Saat Ini'][i === allRecords.length - 1 ? 1 : 0]}</td>
                        <td class="p-6 font-mono text-lg font-bold text-green-400">${(r.berat || 3).toFixed(1)} kg</td>
                        <td class="p-6 font-mono text-lg font-bold text-blue-400">${(r.tinggi || 50).toFixed(1)} cm</td>
                        <td class="p-6 font-mono text-lg">${bbu.toFixed(1)}</td>
                        <td class="p-6">${statusHtml}</td>
                    </tr>
                `;
            }).join('');
        }

        function calculateStatus(bb, tb) {
            const tinggiM = tb / 100;
            const bbu = tinggiM > 0 ? bb / (tinggiM * tinggiM) : 15;
            if (bbu < 14) return '<span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full font-bold text-sm border border-red-500/30">Gizi Buruk</span>';
            if (bbu < 16) return '<span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full font-bold text-sm border border-orange-500/30">Kurang</span>';
            if (bbu > 20) return '<span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full font-bold text-sm border border-yellow-500/30">Lebih</span>';
            return '<span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full font-bold text-sm border border-green-500/30">Baik</span>';
        }

        // Auto load first child
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('childSelect');
            if (select && select.options.length > 0) {
                select.selectedIndex = 0;
                select.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection

