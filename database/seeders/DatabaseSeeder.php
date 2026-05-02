<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PosyanduSeeder::class,
        ]);

        // Buat admin user fixed
        User::updateOrCreate(
            ['email' => 'sipandu@gmail.com'],
            [
                'name' => 'Admin SIPANDU',
                'phone' => '08123456789',
                'address' => 'Kantor Pusat Posyandu',
                'rt' => '000',
                'rw' => '0',
                'password' => Hash::make('sipandu6'),
                'role' => 'admin',
            ]
        );

        $this->call([
            ScheduleSeeder::class,
            HealthRecordSeeder::class,
        ]);
    }
}