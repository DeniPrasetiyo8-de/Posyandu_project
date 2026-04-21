<?php

namespace Database\Seeders;

use App\Models\Posyandu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PosyanduSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Posyandu::create(['nama_posyandu'=>'Posyandu Mawar','rw'=>'01']);
Posyandu::create(['nama_posyandu'=>'Posyandu Melati','rw'=>'02']);
Posyandu::create(['nama_posyandu'=>'Posyandu Anggrek','rw'=>'03']);
Posyandu::create(['nama_posyandu'=>'Posyandu Dahlia','rw'=>'04']);
Posyandu::create(['nama_posyandu'=>'Posyandu Sakura','rw'=>'05']);
Posyandu::create(['nama_posyandu'=>'Posyandu Teratai','rw'=>'06']);
    }
}
