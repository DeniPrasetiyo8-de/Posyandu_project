@extends('layouts.app')

@section('title', 'Artikel Kesehatan')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-16 h-16 bg-gradient-to-r from-orange-400 to-pink-500 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg">
            <i class="fas fa-newspaper"></i>
        </div>
        <div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Artikel Kesehatan</h1>
            <p class="text-xl text-gray-500">Tips kesehatan ibu, anak, dan gizi seimbang dari ahli posyandu</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative max-w-md w-full">

            </div>
            <div class="flex flex-wrap gap-2">
                <button class="px-6 py-2 bg-gray-800 text-white rounded-full font-semibold transition-all">Semua</button>
                <button class="px-6 py-2 bg-green-500 text-white rounded-full font-semibold hover:bg-green-600 transition-all">
                    <i class="fas fa-leaf mr-2"></i>Gizi Anak
                </button>
                <button class="px-6 py-2 bg-pink-500 text-white rounded-full font-semibold hover:bg-pink-600 transition-all">
                    <i class="fas fa-female mr-2"></i>Ibu Hamil
                </button>
                <button class="px-6 py-2 bg-purple-500 text-white rounded-full font-semibold hover:bg-purple-600 transition-all">
                    <i class="fas fa-syringe mr-2"></i>Imunisasi
                </button>
            </div>
        </div>
    </div>

    <!-- Artikel Cards -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($artikels as $artikel)
        <div class="group bg-white rounded-2xl p-6 border border-gray-100 shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all duration-300 overflow-hidden h-full">
            <!-- Category Badge -->
            @php
                $badgeColors = [
                    'Gizi Anak' => 'bg-green-100 text-green-600',
                    'Ibu Hamil' => 'bg-pink-100 text-pink-600',
                    'Imunisasi' => 'bg-purple-100 text-purple-600'
                ];
                $badgeColor = $badgeColors[$artikel['kategori']] ?? 'bg-gray-100 text-gray-600';
            @endphp
            <span class="inline-block px-3 py-1 {{ $badgeColor }} text-xs font-semibold rounded-full mb-4">
                {{ $artikel['kategori'] }}
            </span>
            
            <!-- Thumbnail with Image -->
            <div class="w-full h-40 bg-gradient-to-br from-orange-100 to-pink-100 rounded-xl mb-4 group-hover:scale-105 transition-transform flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/' . $artikel['gambar']) }}" alt="{{ $artikel['judul'] }}" class="w-full h-full object-cover rounded-xl">
            </div>
            
            <div class="space-y-3">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 leading-tight line-clamp-2 group-hover:text-orange-600 transition-colors mb-2">
                        {{ $artikel['judul'] }}
                    </h3>
<p class="text-gray-500 text-sm leading-relaxed line-clamp-3">
                        {{ \Illuminate\Support\Str::limit($artikel['isi'], 100) }}
                    </p>
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center text-xs text-gray-400 space-x-3">
                        <span><i class="fas fa-calendar mr-1"></i>{{ $artikel['tanggal'] }}</span>
                    </div>
                    <button onclick="openModal({{ $artikel['id'] }})" class="flex items-center space-x-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-all text-sm">
                        <span>Baca</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination & CTA -->
    <div class="flex flex-col md:flex-row gap-6 items-center justify-between pt-8 border-t border-gray-200">
        <div class="text-sm text-gray-500">
          
        </div>
        
        </div>
    </div>
</div>

<!-- Modal for Article Detail -->
<div id="articleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-800"></h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <img id="modalImage" src="" alt="" class="w-full h-64 object-cover rounded-xl mb-4">
            <div id="modalContent" class="text-gray-600 leading-relaxed text-justify"></div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal()" class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const articles = @json($artikels);

function openModal(id) {
    const article = articles.find(a => a.id === id);
    if (article) {
        document.getElementById('modalTitle').textContent = article.judul;
        document.getElementById('modalImage').src = '/images/' + article.gambar;
        document.getElementById('modalContent').textContent = article.isi;
        document.getElementById('articleModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal() {
    document.getElementById('articleModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('articleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
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
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
