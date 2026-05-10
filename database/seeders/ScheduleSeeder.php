<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Posyandu;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        $posyandus = Posyandu::all();
        
        $kegiatans = [
            [
                'kegiatan' => 'Imunisasi DPT-HB-HiB',
                'deskripsi' => 'Imunisasi rutin untuk bayi usia 2, 3, 4 bulan. Wajib bagi semua balita.',
                'tanggal' => Carbon::now()->addDays(3),
            ],
            [
                'kegiatan' => 'Pemantauan BB/PB & Vitamin A',
                'deskripsi' => 'Pemeriksaan berat badan, tinggi badan, dan pemberian Vitamin A dosis bulanan.',
                'tanggal' => Carbon::now()->addDays(7),
            ],
            [
                'kegiatan' => 'Konseling MP-ASI',
                'deskripsi' => 'Pelatihan pengenalan makanan pendamping ASI untuk bayi 6 bulan pertama.',
                'tanggal' => Carbon::now()->addDays(10),
            ],
            [
                'kegiatan' => 'Imunisasi Campak',
                'deskripsi' => 'Imunisasi campak untuk anak usia 9 dan 18 bulan. Gratis di semua posyandu.',
                'tanggal' => Carbon::now()->addDays(14),
            ],
            [
                'kegiatan' => 'Pemeriksaan Ibu Hamil',
                'deskripsi' => 'Konsultasi KIA, tablet Fe, dan pemeriksaan USG gratis untuk ibu hamil.',
                'tanggal' => Carbon::now()->addDays(20),
            ],
        ];

        foreach($kegiatans as $kegiatan) {
            Schedule::create([
                'posyandu_id' => $posyandus->random()->id,
                'kegiatan' => $kegiatan['kegiatan'],
                'deskripsi' => $kegiatan['deskripsi'],
                'tanggal' => $kegiatan['tanggal'],
            ]);
        }
    }
}

