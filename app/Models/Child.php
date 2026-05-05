<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posyandu(){
        return $this->belongsTo(Posyandu::class);
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }
    
    // Daftar Imunisasi
    public static function getDaftarImunisasi() {
        return [
            'imunisasi_hb0' => 'Hepatitis B (0-7 hari)',
            'imunisasi_bcg' => 'BCG (1 bulan)',
            'imunisasi_polio1' => 'Polio 1 (2 bulan)',
            'imunisasi_polio2' => 'Polio 2 (3 bulan)',
            'imunisasi_polio3' => 'Polio 3 (4 bulan)',
            'imunisasi_polio4' => 'Polio 4 (6 bulan)',
            'imunisasi_dpt_hb_hib1' => 'DPT-HB-Hib 1 (2 bulan)',
            'imunisasi_dpt_hb_hib2' => 'DPT-HB-Hib 2 (3 bulan)',
            'imunisasi_dpt_hb_hib3' => 'DPT-HB-Hib 3 (4 bulan)',
            'imunisasi_campak' => 'Campak-Rubella (9 bulan)',
        ];
    }
    
    // Daftar Vitamin
    public static function getDaftarVitamin() {
        return [
            'vitamin_a_6_11' => 'Vitamin A (6-11 bulan)',
            'vitamin_a_12_59' => 'Vitamin A (12-59 bulan)',
        ];
    }
    
    protected $fillable = [
        'user_id',
        'posyandu_id',
        'nama',
        'tanggal_lahir',
        'jenis_kelamin',
        'berat_badan',
        'tinggi_badan',
        'foto'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function getUmurBulanAttribute()
    {
        return $this->tanggal_lahir->diffInMonths(now());
    }

    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('images/children/' . $this->foto) : null;
    }
}
