<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $articles = [
            [
                'judul' => 'Tahapan Gizi Bayi yang Tepat',
                'kategori' => 'gizi_anak',
                'isi' => 'Pemantauan pertumbuhan dan perkembangan bayi merupakan hal yang sangat penting dalam memastikan tumbuh kembang anak berjalan optimal. Gizi berperan krusial dalam mendukung pertumbuhan fisik dan perkembangan otak anak. Tahapan gizi bayi dimulai dari ASI eksklusif selama 6 bulan pertama, dilanjutkan dengan MPASI (Makanan Pendamping ASI) usia 6-12 bulan, hingga pola makan keluarga saat anak sudah berusia 1 tahun ke atas. Pastikan pemberian makanan sesuai dengan usia dan kebutuhan nutrisi anak.',
                'gambar' => 'G Tahapan gizi bayi.png',
            ],
            [
                'judul' => 'Gizi Seimbang untuk Keluarga',
                'kategori' => 'gizi_anak',
                'isi' => 'Gizi seimbang adalah susunan makanan harian yang mengandung nutrisi dalam jumlah dan proporsi yang tepat untuk menjaga kesehatan. Prinsip gizi seimbang mencakup variasi makanan dari berbagai kelompok seperti nasi dan umbi-umbian sebagai sumber energi, lauk-pauk sebagai sumber protein, sayuran dan buah-buahan sebagai sumber vitamin dan mineral, serta susu untuk calcium. Dengan menerapkan pola gizi seimbang dalam keluarga, kita dapat mencegah berbagai penyakit akibat malnutrition baik kekurangan maupun kelebihan nutrisi.',
                'gambar' => 'G Gizi seimbang.jpg',
            ],
            [
                'judul' => 'Jadwal Imunisasi Anak Lengkap',
                'kategori' => 'imunisasi',
                'isi' => 'Imunisasi merupakan upaya perlindungan tubuh untuk kebal terhadap penyakit tertentu melalui pemberian vaksin. Jadwal imunisasi anak (0 Bulan), Hepatitis B (0, 1, 6 Bulan), Polio (0, 2, 4 Bulan), DPT-HB-Hib (2, 4, 6 Bulan), Campak (9 Bulan), dan imunisasi lanjutan sesuai ketentuan. Penting untuk mengikuti jadwal imunisasi agar anak terlindungi dari penyakit berbahaya seperti campak, polio, dan difteri. Jangan lupa untuk membawa buku KIA saat imunisasi.',
                'gambar' => 'G Imunisasi anak.png',
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
