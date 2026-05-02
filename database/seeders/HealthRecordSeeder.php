<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HealthRecord;
use App\Models\Child;
use App\Models\User;
use Carbon\Carbon;

class HealthRecordSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role', 'orang_tua')->limit(3)->get();
        $children = Child::inRandomOrder()->limit(6)->get();

        $records = [
            [
                'berat_badan' => 8.5,
                'tinggi_badan' => 70.2,
                'status_gizi' => 'Normal',
                'tanggal_pemeriksaan' => Carbon::now()->subMonths(1),
            ],
            [
                'berat_badan' => 9.2,
                'tinggi_badan' => 72.5,
                'status_gizi' => 'Normal',
                'tanggal_pemeriksaan' => Carbon::now()->subWeeks(2),
            ],
            [
                'berat_badan' => 7.8,
                'tinggi_badan' => 68.0,
                'status_gizi' => 'Kurang',
                'tanggal_pemeriksaan' => Carbon::now()->subMonths(2),
            ],
            [
                'berat_badan' => 10.1,
                'tinggi_badan' => 75.3,
                'status_gizi' => 'Lebih',
                'tanggal_pemeriksaan' => Carbon::now()->subDays(10),
            ],
            [
                'berat_badan' => 6.5,
                'tinggi_badan' => 65.1,
                'status_gizi' => 'Gizi Buruk',
                'tanggal_pemeriksaan' => Carbon::now()->subMonths(3),
            ],
            [
                'berat_badan' => 9.8,
                'tinggi_badan' => 73.8,
                'status_gizi' => 'Normal',
                'tanggal_pemeriksaan' => Carbon::now()->subMonth(),
            ],
        ];

        foreach($records as $index => $record) {
            if (isset($children[$index])) {

            HealthRecord::create([
                    'child_id' => $children[$index]->id,
                    'berat' => $record['berat_badan'],
                    'tinggi' => $record['tinggi_badan'],
                    'tanggal' => $record['tanggal_pemeriksaan'],
                ]);

            }
        }
    }
}

