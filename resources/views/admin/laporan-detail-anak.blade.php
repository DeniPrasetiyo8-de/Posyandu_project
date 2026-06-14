<div class="space-y-6">
    <!-- Data Anak -->
    <div class="bg-gray-800 rounded-xl p-4">
        <h4 class="text-lg font-bold text-white mb-3 border-b border-gray-700 pb-2">
            <i class="fas fa-baby mr-2 text-pink-400"></i>Data Anak
        </h4>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-400 text-sm">Nama Anak</p>
                <p class="text-white font-bold">{{ $child->nama }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">NIK</p>
                <p class="text-white">{{ $child->nik ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Tanggal Lahir</p>
                <p class="text-white">{{ $child->tanggal_lahir ? \Carbon\Carbon::parse($child->tanggal_lahir)->format('d M Y') : '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Posyandu</p>
                <p class="text-white">{{ $child->posyandu->nama_posyandu ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Orang Tua</p>
                <p class="text-white">{{ $child->user->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Status</p>
                <span class="px-3 py-1 rounded-full text-sm font-bold {{ $child->status == 'AKTIF' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                    {{ $child->status ?? 'AKTIF' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Riwayat Imunisasi -->
    <div class="bg-gray-800 rounded-xl p-4">
        <h4 class="text-lg font-bold text-white mb-3 border-b border-gray-700 pb-2">
            <i class="fas fa-syringe mr-2 text-blue-400"></i>Riwayat Imunisasi
        </h4>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($imunisasi as $nama => $status)
            <div class="flex items-center justify-between bg-gray-700 rounded-lg p-3">
                <span class="text-white text-sm">{{ $nama }}</span>
                <span class="px-2 py-1 rounded text-xs font-bold {{ $status == 'Sudah' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                    {{ $status }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Riwayat Vitamin -->
    <div class="bg-gray-800 rounded-xl p-4">
        <h4 class="text-lg font-bold text-white mb-3 border-b border-gray-700 pb-2">
            <i class="fas fa-pills mr-2 text-yellow-400"></i>Riwayat Vitamin
        </h4>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($vitamin as $nama => $status)
            <div class="flex items-center justify-between bg-gray-700 rounded-lg p-3">
                <span class="text-white text-sm">{{ $nama }}</span>
                <span class="px-2 py-1 rounded text-xs font-bold {{ $status == 'Sudah' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                    {{ $status }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Riwayat Berat Badan (KMS) -->
    <div class="bg-gray-800 rounded-xl p-4">
        <h4 class="text-lg font-bold text-white mb-3 border-b border-gray-700 pb-2">
            <i class="fas fa-weight mr-2 text-green-400"></i>Riwayat Berat Badan (KMS)
        </h4>
        @if($healthRecords->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b border-gray-700">
                        <th class="py-2 pr-4">Tanggal</th>
                        <th class="py-2 pr-4">Berat (kg)</th>
                        <th class="py-2 pr-4">Tinggi (cm)</th>
                        <th class="py-2 pr-4">Status Gizi</th>
                        <th class="py-2">Status Stunting</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($healthRecords as $record)
                    <tr class="border-b border-gray-700">
                        <td class="py-2 text-white">{{ \Carbon\Carbon::parse($record->tanggal)->format('d M Y') }}</td>
                        <td class="py-2 text-white">{{ $record->berat }}</td>
                        <td class="py-2 text-white">{{ $record->tinggi ?? '-' }}</td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded text-xs font-bold 
                                {{ $record->status_gizi == 'Normal' ? 'bg-green-500 text-white' : 
                                   ($record->status_gizi == 'Kurus' ? 'bg-yellow-500 text-black' : 'bg-red-500 text-white') }}">
                                {{ $record->status_gizi ?? '-' }}
                            </span>
                        </td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $record->status_stunting == 'Normal' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ $record->status_stunting ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-400 text-center py-4">Belum ada data riwayat pemeriksaan</p>
        @endif
    </div>
</div>
