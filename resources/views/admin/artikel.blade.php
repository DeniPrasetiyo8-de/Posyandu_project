@extends('layouts.admin')

@section('title', 'Kelola Artikel - Admin')
@section('page_title', 'Kelola Artikel')
@section('page_description', 'CRUD artikel kesehatan posyandu.')

@section('admin_content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-bold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-xl mb-6">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    <!-- Search & Filter -->
    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-6 border border-white/20 mb-8">
        <form action="{{ route('admin.artikel') }}" method="GET" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari artikel..." 
                        class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                </div>
                <div class="md:w-48">
                    <select name="kategori" class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriOptions as $key => $label)
                            <option value="{{ $key }}" {{ ($kategori ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Add Button & Table -->
    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-6 border border-white/20">
        <div class="flex items-center justify-between mb-6">
<h3 class="text-2xl font-bold text-black">Daftar Artikel</h3>
            <button onclick="toggleModal('addModal')" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-bold">
                <i class="fas fa-plus mr-2"></i>Tambah Artikel
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
<tr class="text-left text-black border-b border-black-700">
                        <th class="pb-4 font-bold">#</th>
                        <th class="pb-4 font-bold">Judul</th>
                        <th class="pb-4 font-bold">Kategori</th>
                        <th class="pb-4 font-bold">Gambar</th>
                        <th class="pb-4 font-bold">Tanggal</th>
                        <th class="pb-4 font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($artikels as $index => $artikel)
                        <tr class="border-b border-gray-800 hover:bg-white/5">
                            <td class="py-4 text-black-300">{{ $index + 1 }}</td>
                            <td class="py-4">
                                <span class="text-white font-semibold">{{ $artikel->judul }}</span>
                                <p class="text-black-400 text-sm truncate max-w-xs">{{ \Illuminate\Support\Str::limit($artikel->isi, 50) }}</p>
                            </td>
                            <td class="py-4">
                                @php
                                    $badgeColors = [
                                        'gizi_anak' => 'bg-green-500/20 text-green-300 border-green-500',
                                        'ibu_hamil' => 'bg-pink-500/20 text-pink-300 border-pink-500',
                                        'imunisasi' => 'bg-purple-500/20 text-purple-300 border-purple-500',
                                    ];
                                    $badgeColor = $badgeColors[$artikel->kategori] ?? 'bg-gray-500/20 text-black-300';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $badgeColor }}">
                                    {{ $kategoriOptions[$artikel->kategori] ?? $artikel->kategori }}
                                </span>
                            </td>
                            <td class="py-4">
                                @if($artikel->gambar)
                                    <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <span class="text-black-500">-</span>
                                @endif
                            </td>
                            <td class="py-4 text-black-300">
                                {{ $artikel->created_at ? \Carbon\Carbon::parse($artikel->created_at)->format('d M Y') : '-' }}
                            </td>
                            <td class="py-4">
                                <div class="flex gap-2">
                                    <button onclick="editArtikel({{ $artikel->id }}, '{{ $artikel->judul }}', '{{ $artikel->kategori }}', `{{ addslashes($artikel->isi) }}`)" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteArtikel({{ $artikel->id }})" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-black-400">
                                <i class="fas fa-newspaper text-4xl mb-4 block"></i>
                                Belum ada artikel. Klik "Tambah Artikel" untuk membuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($artikels->hasPages())
        <div class="mt-6">
            {{ $artikels->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold text-white">Tambah Artikel</h2>
        </div>
        <form action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Judul Artikel</label>
                <input type="text" name="judul" required placeholder="Judul artikel..." 
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Kategori</label>
                <select name="kategori" required class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                    <option value="">Pilih Kategori</option>
                    @foreach($kategoriOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Isi Artikel</label>
                <textarea name="isi" required rows="6" placeholder="Isi artikel..." 
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none"></textarea>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Gambar (Opsional)</label>
                <input type="file" name="gambar" accept="image/*" 
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                <p class="text-gray-400 text-sm mt-1">Max 2MB. Format: JPG, PNG, GIF</p>
            </div>
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" onclick="toggleModal('addModal')" class="px-6 py-2 bg-gray-600 text-white rounded-xl hover:bg-gray-500">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold text-white">Edit Artikel</h2>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editId">
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Judul Artikel</label>
                <input type="text" name="judul" id="editJudul" required placeholder="Judul artikel..." 
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Kategori</label>
                <select name="kategori" id="editKategori" required class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                    @foreach($kategoriOptions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Isi Artikel</label>
                <textarea name="isi" id="editIsi" required rows="6" placeholder="Isi artikel..." 
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none"></textarea>
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Gambar (Opsional)</label>
                <input type="file" name="gambar" accept="image/*" 
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white focus:border-blue-500 focus:outline-none">
                <p class="text-gray-400 text-sm mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
            </div>
            <div class="flex justify-end gap-4 pt-4">
                <button type="button" onclick="toggleModal('editModal')" class="px-6 py-2 bg-gray-600 text-white rounded-xl hover:bg-gray-500">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-xl hover:bg-blue-600">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-2xl max-w-md w-full">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold text-white">Hapus Artikel</h2>
        </div>
        <div class="p-6">
            <p class="text-gray-300 mb-6">Apakah Anda yakin ingin menghapus artikel ini?</p>
            <form id="deleteForm" method="POST" class="flex justify-end gap-4">
                @csrf
                @method('DELETE')
                <button type="button" onclick="toggleModal('deleteModal')" class="px-6 py-2 bg-gray-600 text-white rounded-xl hover:bg-gray-500">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
    } else {
        modal.classList.add('hidden');
    }
}

function editArtikel(id, judul, kategori, isi) {
    document.getElementById('editId').value = id;
    document.getElementById('editJudul').value = judul;
    document.getElementById('editKategori').value = kategori;
    document.getElementById('editIsi').value = isi;
    document.getElementById('editForm').action = '/admin/artikel/' + id;
    toggleModal('editModal');
}

function deleteArtikel(id) {
    document.getElementById('deleteForm').action = '/admin/artikel/' + id;
    toggleModal('deleteModal');
}

// Close modal on outside click
document.querySelectorAll('.fixed').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.fixed').forEach(modal => {
            modal.classList.add('hidden');
        });
    }
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
