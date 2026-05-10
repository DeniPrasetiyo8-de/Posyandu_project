@extends('layouts.app')

@section('title', 'Kelola Kader - Admin')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-black mb-2">Kelola Kader</h1>
            <p class="text-gray-600">Kelola data kader dan status kehadiran</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Search & Filter Form -->
    <div class="bg-white rounded-3xl p-6 border border-gray-200 mb-8 shadow">
        <form action="{{ route('admin.kader') }}" method="GET" class="flex items-center gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama kader..." 
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
            </div>
            <div>
                <select name="posyandu_id" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
                    <option value="">Semua Posyandu</option>
                    @foreach($posyandus as $pos)
                        <option value="{{ $pos->id }}" {{ ($posyanduId ?? '') == $pos->id ? 'selected' : '' }}>
                            {{ $pos->nama_posyandu }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </form>
    </div>

    <!-- Add New Kader Button -->
    <div class="mb-6">
        <button onclick="openAddModal()" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-bold">
            <i class="fas fa-plus mr-2"></i>Tambah Kader Baru
        </button>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-500 text-green-700 px-6 py-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Kader Table -->
    <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
<tr class="text-left text-gray-600 border-b border-gray-300">
                        <th class="pb-4 font-bold">#</th>
                        <th class="pb-4 font-bold">Nama Kader</th>
                        <th class="pb-4 font-bold">Posyandu</th>
                        <th class="pb-4 font-bold">No. HP</th>
                        <th class="pb-4 font-bold">Alamat</th>
                        <th class="pb-4 font-bold">RW</th>
                        <th class="pb-4 font-bold">Status Kehadiran</th>
                        <th class="pb-4 font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kaders as $index => $kader)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-4 text-gray-600">{{ $index + 1 }}</td>
                            <td class="py-4">
                                <span class="text-black font-bold">{{ $kader->nama_kader }}</span>
                            </td>
<td class="py-4">
                                <span class="text-black">{{ $kader->posyandu->nama_posyandu }}</span>
                            </td>
                            <td class="py-4 text-gray-600">{{ $kader->no_hp ?? '-' }}</td>
                            <td class="py-4 text-gray-600">{{ $kader->alamat ?? '-' }}</td>
                            <td class="py-4 text-gray-600">{{ $kader->rw ?? '-' }}</td>
                            <td class="py-4">
                                {{-- Form Status Kehadiran --}}
                                <form id="statusForm{{ $kader->id }}" 
                                      action="{{ route('admin.kader.updateStatus', $kader->id) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status_kehadiran" 
                                            onchange="updateStatus({{ $kader->id }}, this.value)"
                                            class="px-3 py-1 rounded-lg text-sm font-bold border-0 cursor-pointer transition-all duration-200
                                            {{ $kader->status_kehadiran === 'hadir' ? 'bg-green-100 text-green-700 ring-2 ring-green-300' : 'bg-red-100 text-red-700 ring-2 ring-red-300' }}">
                                        <option value="hadir" {{ $kader->status_kehadiran === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="tidak_hadir" {{ $kader->status_kehadiran === 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-4">
<button onclick="openEditModal({{ $kader->id }}, '{{ addslashes($kader->nama_kader) }}', '{{ $kader->posyandu->id }}', '{{ addslashes($kader->alamat ?? '') }}', '{{ addslashes($kader->no_hp ?? '') }}', '{{ $kader->rw ?? '' }}', '{{ $kader->status_kehadiran }}')" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm mr-2 transition-all duration-200">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                {{-- Tombol Delete --}}
                                <form id="deleteForm{{ $kader->id }}" 
                                      action="{{ route('admin.kader.destroy', $kader->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus kader \"{{ addslashes($kader->nama_kader) }}\"?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm transition-all duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500">
                                <i class="fas fa-user-md text-4xl mb-4 block"></i>
                                <p>Belum ada data kader</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($kaders->hasPages())
        <div class="mt-6">
            {{ $kaders->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md border border-gray-300">
        <h3 class="text-2xl font-bold text-black mb-6">Tambah Kader Baru</h3>
        <form id="addForm" method="POST" action="{{ route('admin.kader.store') }}">
            @csrf
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kader</label>
                <input type="text" name="nama_kader" required 
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Posyandu</label>
                <select name="posyandu_id" required 
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
                    <option value="">Pilih Posyandu</option>
                    @foreach($posyandus as $pos)
                        <option value="{{ $pos->id }}">{{ $pos->nama_posyandu }}</option>
                    @endforeach
                </select>
            </div>

<div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
                <input type="text" name="alamat" 
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">No. HP</label>
                <input type="text" name="no_hp" placeholder=" Contoh: 6289512345678" 
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">RW</label>
                <select name="rw" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
                    <option value="">Pilih RW</option>
                    <option value="01">RW 01</option>
                    <option value="02">RW 02</option>
                    <option value="03">RW 03</option>
                    <option value="04">RW 04</option>
                    <option value="05">RW 05</option>
                    <option value="06">RW 06</option>
                </select>
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeAddModal()" 
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold">
                    Batal
                </button>
                <button type="submit" 
                    class="flex-1 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-bold">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md border border-gray-300">
        <h3 class="text-2xl font-bold text-black mb-6">Edit Kader</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kader</label>
                <input type="text" id="editNamaKader" name="nama_kader" required 
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Posyandu</label>
                <select id="editPosyandu" name="posyandu_id" required 
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
                    @foreach($posyandus as $pos)
                        <option value="{{ $pos->id }}">{{ $pos->nama_posyandu }}</option>
                    @endforeach
                </select>
            </div>

<div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
                <input type="text" id="editAlamat" name="alamat" 
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">No. HP</label>
                <input type="text" id="editNoHp" name="no_hp" placeholder=" Contoh: 6289512345678" 
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">RW</label>
                <select id="editRw" name="rw" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
                    <option value="01">RW 01</option>
                    <option value="02">RW 02</option>
                    <option value="03">RW 03</option>
                    <option value="04">RW 04</option>
                    <option value="05">RW 05</option>
                    <option value="06">RW 06</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status Kehadiran</label>
                <select id="editStatus" name="status_kehadiran" 
                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-black focus:border-blue-500 focus:outline-none">
                    <option value="hadir">Hadir</option>
                    <option value="tidak_hadir">Tidak Hadir</option>
                </select>
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeEditModal()" 
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold">
                    Batal
                </button>
                <button type="submit" 
                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Function untuk update status kehadiran
    function updateStatus(kaderId, status) {
        const form = document.getElementById('statusForm' + kaderId);
        
        if (form) {
            form.submit();
        }
    }

    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.getElementById('addModal').classList.add('flex');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('addModal').classList.remove('flex');
    }

function openEditModal(id, namaKader, posyanduId, alamat, noHp, rw, status) {
        document.getElementById('editForm').action = '/admin/kader/' + id;
        document.getElementById('editNamaKader').value = namaKader;
        document.getElementById('editPosyandu').value = posyanduId;
        document.getElementById('editAlamat').value = alamat;
        document.getElementById('editNoHp').value = noHp;
        document.getElementById('editRw').value = rw;
        document.getElementById('editStatus').value = status;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    // Close modal when clicking outside
    document.querySelectorAll('.fixed').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddModal();
                closeEditModal();
            }
        });
    });
</script>
@endsection
