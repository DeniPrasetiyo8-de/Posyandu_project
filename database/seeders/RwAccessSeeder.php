<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RwAccess;

class RwAccessSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        RwAccess::truncate();

        // Seed RW access codes
        $accessCodes = [
            [
                'rw' => '1',
                'kode_akses' => 'RW01',
                'nama_posyandu' => 'Posyandu Mawar',
                'status' => true,
            ],
            [
                'rw' => '2',
                'kode_akses' => 'RW02',
                'nama_posyandu' => 'Posyandu Melati',
                'status' => true,
            ],
            [
                'rw' => '3',
                'kode_akses' => 'RW03',
                'nama_posyandu' => 'Posyandu Anggrek',
                'status' => true,
            ],
            [
                'rw' => '4',
                'kode_akses' => 'RW04',
                'nama_posyandu' => 'Posyandu Dahlia',
                'status' => true,
            ],
            [
                'rw' => '5',
                'kode_akses' => 'RW05',
                'nama_posyandu' => 'Posyandu Sakura',
                'status' => true,
            ],
            [
                'rw' => '6',
                'kode_akses' => 'RW06',
                'nama_posyandu' => 'Posyandu Teratai',
                'status' => true,
            ],
            [
                'rw' => null,
                'kode_akses' => 'SIPANDU',
                'status' => true,
            ],
        ];

        foreach ($accessCodes as $code) {
            RwAccess::create($code);
        }
    }
}
