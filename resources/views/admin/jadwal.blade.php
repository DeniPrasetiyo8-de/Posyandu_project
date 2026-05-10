@extends('layouts.app')

@section('title', 'Kelola Jadwal - Admin')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">Kelola Jadwal Kegiatan</h1>
            <p class="text-gray-300">Tambah, edit, atau hapus jadwal kegiatan posyandu</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Add Jadwal Form -->
    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20 mb-8">
        <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-plus mr-3 text-green-400"></i>Tambah Jadwal Baru
        </h3>
        
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @csrf
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Nama Kegiatan</label>
<input type="text" name="kegiatan" placeholder="Contoh: Imunisasi DPT-HB-HiB" 
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none"
                    required>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Tanggal</label>
                <input type="date" name="tanggal" 
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none"
                    required>
                    <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Lokasi</label>
<input type="text" name="lokasi" placeholder="Contoh: Balai RW 05"
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none"
                    required>
            </div>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Posyandu</label>
                <select name="posyandu_id" class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                   
                    @foreach($posyandus as $posyandu)
                        <option value="{{ $posyandu->id }}">{{ $posyandu->nama_posyandu }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Deskripsi</label>
                <input type="text" name="deskripsi" placeholder="Deskripsi kegiatan" 
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-2 lg:col-span-4">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-xl font-bold">
                    <i class="fas fa-save mr-2"></i>Simpan Jadwal
                </button>
            </div>
        </form>
    </div>

    <!-- Jadwal List -->
    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
        <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-calendar mr-3 text-yellow-400"></i>Daftar Jadwal Kegiatan
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-300 border-b border-gray-700">
                        <th class="pb-4 font-bold">#</th>
                        <th class="pb-4 font-bold">Nama Kegiatan</th>
                        <th class="pb-4 font-bold">Tanggal</th>
                        <th class="pb-4 font-bold">Posyandu</th>
                        <th class="pb-4 font-bold">Deskripsi</th>
                        <th class="pb-4 font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $index => $jadwal)
                        <tr class="border-b border-gray-800 hover:bg-white/5">
                            <td class="py-4 text-gray-300">{{ $index + 1 }}</td>
<td class="py-4">
                                <span class="text-white font-bold">{{ $jadwal->kegiatan }}</span>
                            </td>
                            <td class="py-4 text-gray-300">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                            </td>
                            <td class="py-4 text-gray-300">
                                {{ $jadwal->posyandu->nama_posyandu ?? 'Posyandu 1' }}
                            </td>
                            <td class="py-4 text-gray-300 max-w-xs">
                                {{ Str::limit($jadwal->deskripsi, 50) }}
                            </td>
                            <td class="py-4">
                                <div class="flex gap-2">
<button onclick="editJadwal({{ $jadwal->id }}, '{{ $jadwal->kegiatan }}', '{{ $jadwal->tanggal }}', '{{ $jadwal->deskripsi }}')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm"
                                            onclick="return confirm('Yakin hapus jadwal ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">
                                <i class="fas fa-calendar-times text-4xl mb-4 block"></i>
                                Belum ada jadwal kegiatan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $jadwals->links() }}
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-gray-900 rounded-3xl p-8 max-w-lg w-full mx-4 border border-gray-700">
            <h3 class="text-2xl font-bold text-white mb-6">Edit Jadwal</h3>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-gray-300 text-sm font-bold mb-2">Nama Kegiatan</label>
<input type="text" name="kegiatan" id="editNama" 
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white" required>
                </div>
                <div>
                    <label class="block text-gray-300 text-sm font-bold mb-2">Tanggal</label>
                    <input type="date" name="tanggal" id="editTanggal" 
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white" required>
                </div>
                <div>
                    <label class="block text-gray-300 text-sm font-bold mb-2">Deskripsi</label>
                    <input type="text" name="deskripsi" id="editDeskripsi" 
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white">
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold flex-1">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="bg-gray-600 hover:bg-gray-500 text-white px-6 py-3 rounded-xl font-bold">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editJadwal(id, nama, tanggal, deskripsi) {
    document.getElementById('editForm').action = '/admin/jadwal/' + id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editTanggal').value = tanggal;
    document.getElementById('editDeskripsi').value = deskripsi || '';
    document.getElementById('editModal').class.remove('hidden');
    document.getElementById('editModal').class.add('flex');
}

function closeModal() {
    document.getElementById('editModal').class.add('hidden');
    document.getElementById('editModal').class.remove('flex');
}
</script>

<style>
.bg-gray-50 { background-color: #1f2937; }
</style>
@endsection
