{{-- resources/views/kms/index.blade.php --}}

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
            <p class="text-xl text-slate-400">Pemantauan perkembangan berat badan, tinggi badan, dan status gizi secara otomatis</p>
        </div>
    </div>

    @if($children->isEmpty() && (isset($mothers) && $mothers->isEmpty()))
        <div class="text-center py-20 bg-slate-800/30 rounded-3xl border-2 border-dashed border-slate-600">
            <i class="fas fa-child text-7xl text-slate-500 mb-6"></i>
            <h3 class="text-2xl font-bold text-slate-300 mb-4">Belum Ada Data</h3>
            <p class="text-slate-500 mb-8 max-w-lg mx-auto">Tambahkan data anak atau ibu di menu <strong>Informasi</strong> untuk melihat diagram KMS.</p>
            <a href="{{ route('dashboard.informasi.anak') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-12 py-4 rounded-2xl font-bold text-xl shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all inline-flex items-center space-x-3">
                <i class="fas fa-arrow-right"></i>
                <span>Kelola Data</span>
            </a>
        </div>
    @else
        {{-- Type Selector: Ibu or Anak --}}
        <div class="bg-slate-800/70 backdrop-blur-sm rounded-3xl p-8 border border-slate-700 mb-8">
            <label class="block text-white font-bold text-xl mb-4">Pilih Tipe</label>
            <div class="flex gap-4">
                <button id="btnAnak" onclick="showKms('anak')" class="flex-1 p-4 rounded-2xl font-bold text-lg transition-all bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                    <i class="fas fa-child mr-2"></i>Anak
                </button>
                <button id="btnIbu" onclick="showKms('ibu')" class="flex-1 p-4 rounded-2xl font-bold text-lg transition-all bg-slate-700 text-slate-300 hover:from-pink-500 hover:to-purple-600 hover:text-white">
                    <i class="fas fa-user-pregnant mr-2"></i>Ibu
                </button>
            </div>
        </div>

        {{-- Child Selector --}}
        <div id="childSelectorSection" class="bg-slate-800/70 backdrop-blur-sm rounded-3xl p-8 border border-slate-700 mb-8">
            <label class="block text-white font-bold text-xl mb-4">Pilih Anak</label>
            <select id="childSelect" class="w-full md:w-96 p-4 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all">
                @forelse($children as $child)
                    <option value="{{ $child->id }}" 
                            data-nama="{{ $child->nama }}" 
                            data-berat="{{ $child->berat_badan ?? 0 }}" 
                            data-tinggi="{{ $child->tinggi_badan ?? 0 }}"
                            data-umur="{{ $child->umur_bulan ?? 0 }}"
                            data-health='{{ json_encode($child->healthRecords->toArray()) }}'>
                        {{ $child->nama }} ({{ $child->umur_bulan }} bulan{{ $child->berat_badan ? ', BB ' . $child->berat_badan . ' kg' : '' }})
                    </option>
                @empty
                    <option value="">Belum ada data anak</option>
                @endforelse
            </select>
        </div>

        {{-- Mother Selector --}}
        <div id="motherSelectorSection" class="bg-slate-800/70 backdrop-blur-sm rounded-3xl p-8 border border-slate-700 mb-8" style="display: none;">
            <label class="block text-white font-bold text-xl mb-4">Pilih Ibu</label>
            <select id="motherSelect" class="w-full md:w-96 p-4 rounded-2xl bg-slate-700/50 border-2 border-slate-600 text-white text-lg focus:border-pink-500 focus:ring-4 focus:ring-pink-500/20 transition-all">
                @forelse($mothers as $mother)
                    <option value="{{ $mother->id }}" 
                            data-nama="{{ $mother->nama }}"
                            data-tanggal="{{ $mother->tanggal_kehamilan ?? now()->format('Y-m-d') }}"
                            data-berat="{{ $mother->berat_badan ?? 50 }}"
                            data-berat-awal="{{ $mother->berat_badan_awal ?? null }}">
                        {{ $mother->nama }} (Berat: {{ $mother->berat_badan ?? 50 }} kg)
                    </option>
                @empty
                    <option value="">Belum ada data ibu</option>
                @endforelse
            </select>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-3xl p-8 border border-slate-700">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-chart-line mr-3 text-blue-400"></i>
                    Perkembangan
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

        {{-- KMS Result Card for Ibu --}}
        <div id="kmsIbuCard" class="hidden">
            <div id="kmsResultContainer"></div>
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
        // ============================================
        // FUNGSI CALCULATE KMS IBU
        // ============================================
        
        /**
         * Calculate KMS status for pregnant mother
         * Based on weight gain and pregnancy week
         * 
         * @param {number} beratBadan - Current weight in kg
         * @param {string} tanggalKehamilton - Pregnancy start date (Y-m-d)
         * @param {number|null} beratBadanAwal - Initial pregnancy weight (optional)
         * @returns {object} KMS calculation result
         */
        function calculateIbuKMS(beratBadan, tanggalKehamilton, beratBadanAwal = null) {
            const startDate = new Date(tanggalKehamilton);
            const now = new Date();
            
            const diffTime = now - startDate;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            const mingguKehamilton = Math.max(1, Math.floor(diffDays / 7));
            
            let trimester = 1;
            if (mingguKehamilton > 12 && mingguKehamilton <= 26) trimester = 2;
            else if (mingguKehamilton > 26) trimester = 3;
            
            const beratBase = beratBadanAwal || 50;
            
            // Calculate ideal weight based on trimester
            let totalGainIdeal;
            if (mingguKehamilton <= 12) {
                // Trimester 1: ~0.1 kg/week
                totalGainIdeal = mingguKehamilton * 0.1;
            } else if (mingguKehamilton <= 26) {
                // Trimester 2: ~0.5 kg/week
                totalGainIdeal = 1.2 + ((mingguKehamilton - 12) * 0.5);
            } else {
                // Trimester 3: ~0.5-0.8 kg/week
                totalGainIdeal = 1.2 + 7 + ((mingguKehamilton - 26) * 0.5);
            }
            
            const beratIdeal = beratBase + totalGainIdeal;
            const totalBeratNaik = beratBadanAwal ? (beratBadan - beratBadanAwal) : (beratBadan - beratBase);
            const selisihBerat = beratBadan - beratIdeal;
            const selisihPersen = (selisihBerat / beratIdeal) * 100;
            
            // Weight gain ranges by week (based on WHO/standard guidelines)
            const weekRanges = {
                12: { min: 0, max: 2 },
                20: { min: 3, max: 5 },
                28: { min: 6, max: 10 },
                36: { min: 8, max: 15 },
                40: { min: 10, max: 18 }
            };
            
            let minIdeal = 0, maxIdeal = 18;
            const sortedWeeks = Object.keys(weekRanges).map(Number).sort((a, b) => a - b);
            for (let i = 0; i < sortedWeeks.length; i++) {
                if (mingguKehamilton <= sortedWeeks[i]) {
                    minIdeal = weekRanges[sortedWeeks[i]].min;
                    maxIdeal = weekRanges[sortedWeeks[i]].max;
                    break;
                }
            }
            
            // Determine status
            let status, keterangan, statusType;
            if (totalBeratNaik < minIdeal) {
                status = 'Kurang';
                keterangan = 'Periksa';
                statusType = 'danger';
            } else if (totalBeratNaik > maxIdeal) {
                status = 'Berlebih';
                keterangan = 'Periksa';
                statusType = 'warning';
            } else {
                status = 'Baik';
                keterangan = 'Baik';
                statusType = 'success';
            }
            
            // Generate recommendation
            let rekomendasi = '';
            if (statusType === 'danger') {
                rekomendasi = 'Berat badan kurang dari ideal. Segera konsultasikan ke bidan/tenaga kesehatan untuk evaluasi nutrisi.';
            } else if (statusType === 'warning') {
                rekomendasi = 'Pertambahan berat badan berlebihan. Perlu kontrol pola makan dan aktivitas fisik.';
            } else {
                rekomendasi = 'Berat badan naik sesuai dengan kurva normal kehamilan. Lanjutkan pola makan seimbang.';
            }
            
            return {
                mingguKehamilton,
                trimester,
                tanggalKehamilton,
                beratBadanAwal: beratBase,
                beratBadanSekarang: beratBadan,
                beratIdeal: Math.round(beratIdeal * 10) / 10,
                totalBeratNaikIdeal: Math.round(minIdeal * 10) / 10,
                totalBeratNaikMaks: Math.round(maxIdeal * 10) / 10,
                totalBeratNaik: Math.round(totalBeratNaik * 10) / 10,
                selisihBerat: Math.round(selisihBerat * 10) / 10,
                selisihPersen: Math.round(selisihPersen * 10) / 10,
                status,
                keterangan,
                statusType,
                rekomendasi
            };
        }
        
        /**
         * Get status badge HTML
         */
        function getIbuStatusBadge(result) {
            const colors = {
                success: { bg: 'bg-green-500/20', text: 'text-green-400', border: 'border-green-500/30', icon: 'check' },
                danger: { bg: 'bg-red-500/20', text: 'text-red-400', border: 'border-red-500/30', icon: 'exclamation' },
                warning: { bg: 'bg-yellow-500/20', text: 'text-yellow-400', border: 'border-yellow-500/30', icon: 'exclamation' }
            };
            const color = colors[result.statusType];
            
            return `<span class="px-4 py-2 ${color.bg} ${color.text} rounded-full font-bold text-sm border ${color.border}">
                <i class="fas fa-${color.icon}-circle mr-1"></i>
                ${result.keterangan}
            </span>`;
        }
        
        /**
         * Get detailed KMS card HTML
         */
        function getIbuKMSCard(result, namaIbu) {
            const statusColor = result.statusType === 'success' ? 'green' : result.statusType === 'danger' ? 'red' : 'yellow';
            
            return `
            <div class="bg-slate-800/70 rounded-2xl p-6 border border-slate-700">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-xl font-bold text-white">📋 Hasil Pemeriksaan KMS - ${namaIbu}</h4>
                    ${getIbuStatusBadge(result)}
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-slate-900/50 rounded-xl p-4 text-center">
                        <div class="text-3xl font-bold text-pink-400">${result.mingguKehamilton}</div>
                        <div class="text-slate-400 text-sm">Minggu Kehamilan</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-4 text-center">
                        <div class="text-3xl font-bold text-blue-400">${result.trimester}</div>
                        <div class="text-slate-400 text-sm">Trimester</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-4 text-center">
                        <div class="text-3xl font-bold text-green-400">${result.beratBadanSekarang} kg</div>
                        <div class="text-slate-400 text-sm">Berat Saat Ini</div>
                    </div>
                    <div class="bg-slate-900/50 rounded-xl p-4 text-center">
                        <div class="text-3xl font-bold text-purple-400">${result.totalBeratNaik} kg</div>
                        <div class="text-slate-400 text-sm">Total Naik</div>
                    </div>
                </div>
                
                <div class="bg-slate-900/50 rounded-xl p-4 mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-slate-400">Berat Badan Ideal:</span>
                        <span class="text-white font-bold">${result.beratIdeal} kg</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-slate-400">Selisih Berat:</span>
                                                <span class="text-${statusColor}-400 font-bold">${result.selisihBerat > 0 ? '+' : ''}${result.selisihBerat} kg</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Kisaran Normal:</span>
                        <span class="text-white font-bold">${result.totalBeratNaikIdeal} - ${result.totalBeratNaikMaks} kg</span>
                    </div>
                </div>
                
                <div class="bg-${statusColor}-500/10 border border-${statusColor}-500/30 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-${statusColor}-400 mt-1 mr-3"></i>
                        <div>
                            <h5 class="font-bold text-${statusColor}-400 mb-1">Rekomendasi</h5>
                            <p class="text-slate-300 text-sm">${result.rekomendasi}</p>
                        </div>
                    </div>
                </div>
            </div>
            `;
        }

        // ============================================
        // VARIABEL GLOBAL
        // ============================================
        
        let growthChart, giziChart;
        let currentChildData = null;
        let currentKmsType = 'anak';
        const mothers = @json($mothers ?? collect([]));

        // ============================================
        // EVENT LISTENERS
        // ============================================
        
        document.getElementById('childSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            currentChildData = {
                id: selectedOption.value,
                nama: selectedOption.dataset.nama,
                berat: parseFloat(selectedOption.dataset.berat),
                tinggi: parseFloat(selectedOption.dataset.tinggi),
                umur: parseFloat(selectedOption.dataset.umur),
                healthRecords: JSON.parse(selectedOption.dataset.health || '[]')
            };
            updateAnakCharts();
        });

        document.getElementById('motherSelect').addEventListener('change', function() {
            showIbuCharts();
        });

        // ============================================
        // FUNGSI TAMPILKAN KMS (SWITCH IBU/ANAK)
        // ============================================
        
        function showKms(type) {
            currentKmsType = type;
            const btnAnak = document.getElementById('btnAnak');
            const btnIbu = document.getElementById('btnIbu');
            const childSection = document.getElementById('childSelectorSection');
            const motherSection = document.getElementById('motherSelectorSection');
            const kmsIbuCard = document.getElementById('kmsIbuCard');
            
            if (type === 'anak') {
                btnAnak.className = 'flex-1 p-4 rounded-2xl font-bold text-lg transition-all bg-gradient-to-r from-blue-500 to-indigo-600 text-white';
                btnIbu.className = 'flex-1 p-4 rounded-2xl font-bold text-lg transition-all bg-slate-700 text-slate-300 hover:from-pink-500 hover:to-purple-600 hover:text-white';
                childSection.style.display = 'block';
                motherSection.style.display = 'none';
                kmsIbuCard.classList.add('hidden');
                
                // Update chart title
                document.querySelector('.grid.lg\\:grid-cols-2 h3:first-child').innerHTML = '<i class="fas fa-chart-line mr-3 text-blue-400"></i>Perkembangan BB & TB';
                
                updateAnakCharts();
            } else {
                btnAnak.className = 'flex-1 p-4 rounded-2xl font-bold text-lg transition-all bg-slate-700 text-slate-300 hover:from-blue-500 hover:to-indigo-600 hover:text-white';
                btnIbu.className = 'flex-1 p-4 rounded-2xl font-bold text-lg transition-all bg-gradient-to-r from-pink-500 to-purple-600 text-white';
                childSection.style.display = 'none';
                motherSection.style.display = 'block';
                kmsIbuCard.classList.remove('hidden');
                
                // Update chart title
                document.querySelector('.grid.lg\\:grid-cols-2 h3:first-child').innerHTML = '<i class="fas fa-chart-line mr-3 text-pink-400"></i>Perkembangan Kehamilan';
                
                showIbuCharts();
            }
        }

        // ============================================
        // FUNGSI CHART UNTUK ANAK
        // ============================================
        
        function updateAnakCharts() {
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
                const tinggiM = (r.tinggi || 50) / 100;
                const bbu = tinggiM > 0 ? (r.berat || 3) / (tinggiM * tinggiM) : 15;
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
                            position: 'bottom',
                            labels: {
                                color: '#e2e8f0'
                            }
                        }
                    }
                }
            });

            // Gizi Stats
            let statsHTML = '';
            Object.entries(statusCount).forEach(([status, count]) => {
                const color = status.includes('Buruk') ? 'text-red-400' : status.includes('Kurang') ? 'text-orange-400' : status.includes('Lebih') ? 'text-yellow-400' : 'text-green-400';
                const borderColor = status.includes('Buruk') ? 'border-red-500' : status.includes('Kurang') ? 'border-orange-500' : status.includes('Lebih') ? 'border-yellow-500' : 'border-green-500';
                statsHTML += `<div class="p-4 bg-slate-900/50 rounded-xl border-l-4 ${borderColor}">
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
                const statusHtml = calculateAnakStatus(r.berat || 3, r.tinggi || 50);
                return `
                    <tr class="hover:bg-slate-800/50">
                        <td class="p-6">${i === allRecords.length - 1 ? 'Saat Ini' : 'Pemeriksaan ' + (allRecords.length - i - 1)}</td>
                        <td class="p-6 font-mono text-lg font-bold text-green-400">${(r.berat || 3).toFixed(1)} kg</td>
                        <td class="p-6 font-mono text-lg font-bold text-blue-400">${(r.tinggi || 50).toFixed(1)} cm</td>
                        <td class="p-6 font-mono text-lg">${bbu.toFixed(1)}</td>
                        <td class="p-6">${statusHtml}</td>
                    </tr>
                `;
            }).join('');
        }

        function calculateAnakStatus(bb, tb) {
            const tinggiM = tb / 100;
            const bbu = tinggiM > 0 ? bb / (tinggiM * tinggiM) : 15;
            if (bbu < 14) return '<span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full font-bold text-sm border border-red-500/30">Gizi Buruk</span>';
            if (bbu < 16) return '<span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full font-bold text-sm border border-orange-500/30">Kurang</span>';
            if (bbu > 20) return '<span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full font-bold text-sm border border-yellow-500/30">Lebih</span>';
            return '<span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full font-bold text-sm border border-green-500/30">Baik</span>';
        }

        // ============================================
        // FUNGSI CHART UNTUK IBU (DENGAN CALCULATE KMS)
        // ============================================
        
        function showIbuCharts() {
            const select = document.getElementById('motherSelect');
            if (!select || select.options.length === 0 || !select.options[select.selectedIndex].value) {
                // Tampilkan pesan jika tidak ada data
                document.getElementById('growthChart').style.display = 'none';
                document.getElementById('giziChart').style.display = 'none';
                document.getElementById('giziStats').innerHTML = `
                    <div class="text-center py-8 text-slate-400">
                        <i class="fas fa-exclamation-triangle text-4xl mb-4 text-yellow-400"></i>
                        <p>Belum ada data ibu. Silakan tambah data ibu terlebih dahulu.</p>
                    </div>
                `;
                document.getElementById('recordsTableBody').innerHTML = '';
                document.getElementById('kmsResultContainer').innerHTML = '';
                return;
            }
            
            // Tampilkan kembali chart
            document.getElementById('growthChart').style.display = 'block';
            document.getElementById('giziChart').style.display = 'block';
            
            const selectedOption = select.options[select.selectedIndex];
            const namaIbu = selectedOption.dataset.nama;
            const tanggalKehamilton = selectedOption.dataset.tanggal;
            const beratBadan = parseFloat(selectedOption.dataset.berat) || 50;
            const beratBadanAwal = selectedOption.dataset.beratAwal ? parseFloat(selectedOption.dataset.beratAwal) : null;
            
            // Calculate KMS using the calculateIbuKMS function
            const kmsResult = calculateIbuKMS(beratBadan, tanggalKehamilton, beratBadanAwal);
            
            // Generate labels untuk chart (minggu ke-0 sampai minggu sekarang)
            const labels = [];
            for (let i = 0; i <= Math.min(kmsResult.mingguKehamilton, 40); i++) {
                labels.push('Mg ' + i);
            }
            
            // Generate berat ideal untuk setiap minggu
            const beratIdealData = [];
            for (let i = 0; i <= Math.min(kmsResult.mingguKehamilton, 40); i++) {
                let gain;
                if (i <= 12) {
                    gain = i * 0.1;
                } else if (i <= 26) {
                    gain = 1.2 + ((i - 12) * 0.5);
                } else {
                    gain = 1.2 + 7 + ((i - 26) * 0.5);
                }
                beratIdealData.push(kmsResult.beratBadanAwal + gain);
            }
            
            // Growth Chart untuk Ibu
            const ctxGrowth = document.getElementById('growthChart').getContext('2d');
            if (growthChart) growthChart.destroy();
            
            growthChart = new Chart(ctxGrowth, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Berat Badan Aktual (kg)',
                        data: Array(labels.length).fill(beratBadan),
                        borderColor: '#ec4899',
                        backgroundColor: 'rgba(236, 72, 153, 0.1)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 3
                    }, {
                        label: 'Berat Badan Ideal (kg)',
                        data: beratIdealData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.3,
                        fill: false,
                        borderDash: [5, 5],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: { 
                            beginAtZero: false,
                            min: Math.max(40, beratBadan - 10),
                            title: {
                                display: true,
                                text: 'Berat Badan (kg)',
                                color: '#94a3b8'
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Minggu Kehamilan',
                                color: '#94a3b8'
                            },
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Perkembangan Kehamilan: Minggu ke-0 sampai Minggu ke-' + kmsResult.mingguKehamilton,
                            color: '#e2e8f0',
                            font: {
                                size: 14
                            }
                        },
                        legend: {
                            labels: {
                                color: '#e2e8f0'
                            }
                        }
                    }
                }
            });

            // Status Pie/Doughnut Chart untuk Ibu
            const ctxGizi = document.getElementById('giziChart').getContext('2d');
            if (giziChart) giziChart.destroy();
            
            const statusLabel = kmsResult.statusType === 'success' ? 'Baik' : 
                               kmsResult.statusType === 'danger' ? 'Kurang' : 'Berlebih';
            const statusColor = kmsResult.statusType === 'success' ? '#10b981' : 
                               kmsResult.statusType === 'danger' ? '#ef4444' : '#f59e0b';
            
            giziChart = new Chart(ctxGizi, {
                type: 'doughnut',
                data: {
                    labels: [statusLabel, 'Status Lain'],
                    datasets: [{
                        data: [1, 0],
                        backgroundColor: [statusColor, '#374151'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#e2e8f0'
                            }
                        },
                        title: {
                            display: true,
                            text: 'Status Gizi Ibu Hamil',
                            color: '#e2e8f0'
                        }
                    }
                }
            });

            // Gizi Stats dengan hasil calculate
            const statusColorClass = kmsResult.statusType === 'success' ? 'green' : 
                                    kmsResult.statusType === 'danger' ? 'red' : 'yellow';
                        const borderColorClass = kmsResult.statusType === 'success' ? 'border-green-500' : 
                                    kmsResult.statusType === 'danger' ? 'border-red-500' : 'border-yellow-500';
            
            document.getElementById('giziStats').innerHTML = `
                <div class="p-4 bg-slate-900/50 rounded-xl border-l-4 ${borderColorClass}">
                    <div class="text-2xl font-bold text-${statusColorClass}-400">${kmsResult.keterangan}</div>
                    <div class="text-slate-400">${kmsResult.status}</div>
                </div>
                <div class="p-4 bg-slate-900/50 rounded-xl">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Berat Ideal:</span>
                        <span class="text-white font-bold">${kmsResult.beratIdeal} kg</span>
                    </div>
                </div>
                <div class="p-4 bg-slate-900/50 rounded-xl">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400">Kisaran Normal:</span>
                        <span class="text-white font-bold">${kmsResult.totalBeratNaikIdeal} - ${kmsResult.totalBeratNaikMaks} kg</span>
                    </div>
                </div>
            `;

            // Tampilkan KMS Card untuk Ibu
            document.getElementById('kmsResultContainer').innerHTML = getIbuKMSCard(kmsResult, namaIbu);

            // Update table dengan perkembangan kehamilan
            let tableRows = '';
            
            // Header info
            tableRows += `
                <tr class="bg-slate-900/30">
                    <td colspan="5" class="p-4 text-center text-lg font-bold text-pink-400">
                        <i class="fas fa-user-pregnant mr-2"></i>
                        ${namaIbu} - Minggu Ke-${kmsResult.mingguKehamilton} (Trimester ${kmsResult.trimester})
                    </td>
                </tr>
            `;
            
            // Data rows per minggu
            for (let i = 0; i <= Math.min(kmsResult.mingguKehamilton, 40); i++) {
                const isCurrentWeek = i === kmsResult.mingguKehamilton;
                
                // Calculate ideal weight for this week
                let gain, beratIdealMinggu;
                if (i <= 12) {
                    gain = i * 0.1;
                } else if (i <= 26) {
                    gain = 1.2 + ((i - 12) * 0.5);
                } else {
                    gain = 1.2 + 7 + ((i - 26) * 0.5);
                }
                beratIdealMinggu = kmsResult.beratBadanAwal + gain;
                
                // Determine status for this week
                let statusBadge = '-';
                if (isCurrentWeek) {
                    if (kmsResult.statusType === 'success') {
                        statusBadge = '<span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full font-bold text-sm border border-green-500/30">Baik</span>';
                    } else if (kmsResult.statusType === 'danger') {
                        statusBadge = '<span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full font-bold text-sm border border-red-500/30">Periksa</span>';
                    } else {
                        statusBadge = '<span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full font-bold text-sm border border-yellow-500/30">Periksa</span>';
                    }
                }
                
                tableRows += `
                    <tr class="hover:bg-slate-800/50 ${isCurrentWeek ? 'bg-pink-500/10' : ''}">
                        <td class="p-4 ${isCurrentWeek ? 'text-pink-400 font-bold' : 'text-slate-300'}">
                            ${isCurrentWeek ? '<i class="fas fa-star mr-1"></i>' : ''}Minggu ke-${i}
                        </td>
                        <td class="p-4 font-mono text-lg font-bold text-green-400">${beratIdealMinggu.toFixed(1)} kg</td>
                        <td class="p-4 font-mono text-blue-400">-</td>
                        <td class="p-4 font-mono ${isCurrentWeek ? 'text-pink-400 font-bold' : 'text-slate-400'}">
                            ${isCurrentWeek ? beratBadan : '-'}
                        </td>
                        <td class="p-4">${statusBadge}</td>
                    </tr>
                `;
            }
            
            document.getElementById('recordsTableBody').innerHTML = tableRows;
        }

        // ============================================
        // INISIALISASI AWAL
        // ============================================
        
        document.addEventListener('DOMContentLoaded', function() {
            // Check default type
            @if(!$children->isEmpty())
                showKms('anak');
                // Load first child data
                const childSelect = document.getElementById('childSelect');
                if (childSelect && childSelect.options.length > 0) {
                    childSelect.selectedIndex = 0;
                    childSelect.dispatchEvent(new Event('change'));
                }
            @elseif(isset($mothers) && !$mothers->isEmpty())
                showKms('ibu');
            @else
                // No data available
                showKms('anak');
            @endif
        });

        // ============================================
        // FUNGSI HELPER UNTUK UPDATE REAL-TIME
        // ============================================
        
        /**
         * Update chart when mother data changes from another source
         * Can be called via AJAX or WebSocket update
         */
        function refreshMotherData() {
            if (currentKmsType === 'ibu') {
                showIbuCharts();
            }
        }
        
        /**
         * Update chart when child data changes
         */
        function refreshChildData() {
            if (currentKmsType === 'anak') {
                const childSelect = document.getElementById('childSelect');
                if (childSelect) {
                    childSelect.dispatchEvent(new Event('change'));
                }
            }
        }
        
        /**
         * Get quick status without full card (for notifications/alerts)
         */
        function getQuickStatusIbu(beratBadan, tanggalKehamilton, beratBadanAwal = null) {
            const result = calculateIbuKMS(beratBadan, tanggalKehamilton, beratBadanAwal);
            return {
                status: result.status,
                keterangan: result.keterangan,
                type: result.statusType,
                minggu: result.mingguKehamilton,
                beratIdeal: result.beratIdeal,
                totalNaik: result.totalBeratNaik
            };
        }
        
        /**
         * Validate KMS data before saving
         */
        function validateKMSData(type, data) {
            const errors = [];
            
            if (type === 'ibu') {
                if (!data.berat_badan || data.berat_badan < 30 || data.berat_badan > 200) {
                    errors.push('Berat badan harus antara 30-200 kg');
                }
                if (!data.tanggal_kehamilton) {
                    errors.push('Tanggal kehamilan harus diisi');
                }
                const startDate = new Date(data.tanggal_kehamilton);
                const now = new Date();
                if (startDate > now) {
                    errors.push('Tanggal kehamilan tidak boleh di masa depan');
                }
            } else if (type === 'anak') {
                if (!data.berat_badan || data.berat_badan < 0.5 || data.berat_badan > 100) {
                    errors.push('Berat badan harus antara 0.5-100 kg');
                }
                if (!data.tinggi_badan || data.tinggi_badan < 20 || data.tinggi_badan > 150) {
                    errors.push('Tinggi badan harus antara 20-150 cm');
                }
            }
            
            return {
                valid: errors.length === 0,
                errors: errors
            };
        }
    </script>
@endsection