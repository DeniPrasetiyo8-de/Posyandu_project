<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mother extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_kehamilan',
        'berat_badan',
        'foto',
        'posyandu_id'
    ];

    protected $casts = [
        'tanggal_kehamilan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function getUmurKehamilanAttribute()
    {
        return $this->tanggal_kehamilan->diffInMonths(now());
    }

    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('images/mothers/' . $this->foto) : null;
    }

    // Calculate status kesehatan based on berat badan and tanggal kehamilan
    public function getStatusKesehatanAttribute()
    {
        $mingguKehamilan = \Carbon\Carbon::parse($this->tanggal_kehamilan)->diffInWeeks(now());
        
        // Berat badan normal berdasarkan minggu kehamilan (estimasi)
        // Normal: 0.5kg/minggu (40 minggu = +20kg total)
        $beratIdeal = 50 + ($mingguKehamilan * 0.5); // base 50kg + pertambahan
        
        if (!$this->berat_badan) {
            return 'Perlu Pemeriksaan';
        }
        
        // Jika berat badan dalam range +-20% dari ideal
        $diff = abs($this->berat_badan - $beratIdeal) / $beratIdeal * 100;
        
        if ($diff <= 20 && $this->berat_badan > 40) {
            return 'Sehat';
        } elseif ($this->berat_badan < 35) {
            return 'Kurangan';
        } else {
            return 'Perlu Pemeriksaan';
        }
    }
}
