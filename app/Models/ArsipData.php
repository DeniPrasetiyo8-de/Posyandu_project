<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipData extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_data',
        'data_id',
        'posyandu_id',
        'user_id',
        'data_json',
        'arsip_tahun',
        'arsip_bulan',
        'catatan',
    ];

    protected $casts = [
        'data_json' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAnak($query)
    {
        return $query->where('jenis_data', 'anak');
    }

    public function scopeIbuHamil($query)
    {
        return $query->where('jenis_data', 'ibu_hamil');
    }

    public function scopeKader($query)
    {
        return $query->where('jenis_data', 'kader');
    }

    public function scopeJadwal($query)
    {
        return $query->where('jenis_data', 'jadwal');
    }

    public function scopeKms($query)
    {
        return $query->where('jenis_data', 'kms');
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->where('arsip_tahun', $tahun);
    }

    public static function getJenisDataOptions()
    {
        return [
            'anak' => 'Data Anak',
            'ibu_hamil' => 'Data Ibu Hamil',
            'kader' => 'Data Kader',
            'jadwal' => 'Data Jadwal',
            'kms' => 'Data KMS',
        ];
    }
}
