<div class="space-y-6">
    <!-- Data Ibu Hamil -->
    <div class="bg-gray-800 rounded-xl p-4">
        <h4 class="text-lg font-bold text-white mb-3 border-b border-gray-700 pb-2">
            <i class="fas fa-female mr-2 text-pink-400"></i>Data Ibu Hamil
        </h4>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-400 text-sm">Nama Ibu</p>
                <p class="text-white font-bold">{{ $mother->nama_lengkap }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">NIK</p>
                <p class="text-white">{{ $mother->nik ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Tanggal Kehamilan</p>
                <p class="text-white">{{ $mother->tanggal_kehamiltonan ? \Carbon\Carbon::parse($mother->tanggal_kehamiltonan)->format('d M Y') : '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Posyandu</p>
                <p class="text-white">{{ $mother->posyandu->nama_posyandu ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Orang Tua</p>
                <p class="text-white">{{ $mother->user->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Status</p>
                <span class="px-3 py-1 rounded-full text-sm font-bold {{ $mother->status == 'AKTIF' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                    {{ $mother->status ?? 'AKTIF' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Riwayat Tablet Tambah Darah (TT) -->
    <div class="bg-gray-800 rounded-xl p-4">
        <h4 class="text-lg font-bold text-white mb-3 border-b border-gray-700 pb-2">
            <i class="fas fa-pills mr-2 text-red-400"></i>Riwayat Tablet Tambah Darah (TT)
        </h4>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($tt as $nama => $status)
            <div class="flex items-center justify-between bg-gray-700 rounded-lg p-3">
                <span class="text-white text-sm">{{ $nama }}</span>
                <span class="px-2 py-1 rounded text-xs font-bold {{ $status == 'Sudah' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                    {{ $status }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Perkembangan Kehamilan -->
    <div class="bg-gray-800 rounded-xl p-4">
        <h4 class="text-lg font-bold text-white mb-3 border-b border-gray-700 pb-2">
            <i class="fas fa-baby mr-2 text-purple-400"></i>Riwayat Pemeriksaan Kehamilan
        </h4>
        @if($healthRecords->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b border-gray-700">
                        <th class="py-2 pr-4">Bulan Ke</th>
                        <th class="py-2 pr-4">Berat Badan (kg)</th>
                        <th class="py-2 pr-4">LILA (cm)</th>
                        <th class="py-2 pr-4">Tekanan Darah</th>
                        <th class="py-2">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($healthRecords as $record)
                    <tr class="border-b border-gray-700">
                        <td class="py-2 text-white">{{ $record->bulan_ke }}</td>
                        <td class="py-2 text-white">{{ $record->berat_badan ?? '-' }}</td>
                        <td class="py-2 text-white">{{ $record->lila ?? '-' }}</td>
                        <td class="py-2 text-white">{{ $record->tekanan_darah ?? '-' }}</td>
                        <td class="py-2 text-white">{{ $record->catatan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-400 text-center py-4">Belum ada data riwayat pemeriksaan</p>
        @endif
    </div>

    <!-- Ringkasan Perkembangan Kandungan -->
    @if($healthRecords->count() > 0)
    <div class="bg-gray-800 rounded-xl p-4">
        <h4 class="text-lg font-bold text-white mb-3 border-b border-gray-700 pb-2">
            <i class="fas fa-chart-line mr-2 text-green-400"></i>Ringkasan Perkembangan
        </h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-700 rounded-lg p-3 text-center">
                <p class="text-gray-400 text-sm">Jumlah Pemeriksaan</p>
                <p class="text-white text-2xl font-bold">{{ $healthRecords->count() }}</p>
            </div>
            <div class="bg-gray-700 rounded-lg p-3 text-center">
                <p class="text-gray-400 text-sm">Berat Badan Terakhir</p>
<p class="text-white text-2xl font-bold">{{ $healthRecords->last()->berat_badan ?? '-' }}</p>
            </div>
            <div class="bg-gray-700 rounded-lg p-3 text-center">
                <p class="text-gray-400 text-sm">LILA Terakhir</p>
                <p class="text-white text-2xl font-bold">{{ $healthRecords->last()->lila ?? '-' }}</p>
            </div>
            <div class="bg-gray-700 rounded-lg p-3 text-center">
                <p class="text-gray-400 text-sm">TT Diberikan</p>
                @php
                $ttDiberikan = 0;
                foreach($tt as $s) { if($s == 'Sudah') $ttDiberikan++; }
                @endphp
                <p class="text-white text-2xl font-bold">{{ $ttDiberikan }}/5</p>
            </div>
        </div>
    </div>
    @endif
</div>
