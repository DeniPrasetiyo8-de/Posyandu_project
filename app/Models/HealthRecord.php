<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'berat',
        'tinggi',
        'tanggal',
        'catatan',
        'status_gizi',
        'status_stunting',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke model Child
     */
    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Hitung umur bulan dari tanggal lahir anak
     */
    public function getUmurBulanAttribute()
    {
        if ($this->child && $this->child->tanggal_lahir) {
            return Carbon::parse($this->child->tanggal_lahir)->diffInMonths(Carbon::parse($this->tanggal));
        }
        return 0;
    }

    /**
     * Hitung status gizi berdasarkan BB/U (Berat Badan menurut Umur)
     * Menggunakan kurva WHO sederhana
     */
    public function calculateStatusGizi($berat, $umurBulan)
    {
        if (!$berat || !$umurBulan) {
            return null;
        }

        // BB ideal berdasarkan usia (rumus sederhana WHO)
        // Normal = median, Kurang = < -1 SD, Lebih = > +1 SD
        $bbNormal = $this->getBeratNormal($umurBulan);

        if (!$bbNormal) {
            return null;
        }

        $bbMinKurang = $bbNormal * 0.85; // Batas bawah gizi kurang
        $bbMinBuruk = $bbNormal * 0.70;   // Batas bawah gizi buruk
        $bbMaxLebih = $bbNormal * 1.15; // Batas atas overweight

        if ($berat < $bbMinBuruk) {
            return 'Gizi Buruk';
        } elseif ($berat < $bbMinKurang) {
            return 'Gizi Kurang';
        } elseif ($berat > $bbMaxLebih) {
            return 'Gizi Lebih';
        } else {
            return 'Normal';
        }
    }

    /**
     * Hitung status stunting berdasarkan TB/U (Tinggi Badan menurut Umur)
     */
    public function calculateStatusStunting($tinggi, $umurBulan)
    {
        if (!$tinggi || !$umurBulan) {
            return null;
        }

        // TB ideal berdasarkan usia
        $tbNormal = $this->getTinggiNormal($umurBulan);

        if (!$tbNormal) {
            return null;
        }

        $tbMinNormal = $tbNormal * 0.90; // Batas bawah normal (-2 SD)
        $tbMinStunting = $tbNormal * 0.85; // Batas stunting (-3 SD)

        if ($tinggi < $tbMinStunting) {
            return 'Stunting Berat';
        } elseif ($tinggi < $tbMinNormal) {
            return 'Stunting';
        } else {
            return 'Normal';
        }
    }

    /**
     * Ambil berat badan normal berdasarkan usia (rumus WHO sederhana)
     */
    private function getBeratNormal($umurBulan)
    {
        // Median BB/U menurut WHO (perempuan & laki-laki rata-rata)
        $bbTable = [
            0 => 3.3, 1 => 4.5, 2 => 5.6, 3 => 6.4, 4 => 7.0, 5 => 7.5,
            6 => 7.9, 7 => 8.3, 8 => 8.6, 9 => 8.9, 10 => 9.2, 11 => 9.4,
            12 => 9.6, 13 => 9.9, 14 => 10.1, 15 => 10.3, 16 => 10.5, 17 => 10.7,
            18 => 10.9, 19 => 11.2, 20 => 11.4, 21 => 11.6, 22 => 11.8, 23 => 12.1,
            24 => 12.3, 30 => 13.3, 36 => 14.3, 42 => 15.3, 48 => 16.3, 54 => 17.3,
            60 => 18.3
        ];

        if (isset($bbTable[$umurBulan])) {
            return $bbTable[$umurBulan];
        }

        // Interpolasi untuk bulan yang tidak ada
        if ($umurBulan > 0 && $umurBulan < 60) {
            return 3.3 + ($umurBulan * 0.25);
        }

        return 12.3 + (($umurBulan - 24) * 0.17);
    }

    /**
     * Ambil tinggi badan normal berdasarkan usia
     */
    private function getTinggiNormal($umurBulan)
    {
        // Median TB/U menurut WHO (cm)
        $tbTable = [
            0 => 49.9, 1 => 54.7, 2 => 58.4, 3 => 61.4, 4 => 63.9, 5 => 65.9,
            6 => 67.6, 7 => 69.2, 8 => 70.6, 9 => 72.0, 10 => 73.3, 11 => 74.5,
            12 => 75.7, 13 => 76.9, 14 => 78.0, 15 => 79.1, 16 => 80.2, 17 => 81.2,
            18 => 82.3, 19 => 83.2, 20 => 84.2, 21 => 85.1, 22 => 86.0, 23 => 86.9,
            24 => 87.8, 30 => 91.9, 36 => 95.9, 42 => 99.9, 48 => 103.9, 54 => 107.7,
            60 => 111.3
        ];

        if (isset($tbTable[$umurBulan])) {
            return $tbTable[$umurBulan];
        }

        // Interpolasi
        if ($umurBulan > 0 && $umurBulan < 60) {
            return 49.9 + ($umurBulan * 1.03);
        }

        return 87.8 + (($umurBulan - 24) * 0.78);
    }

    /**
     * Auto calculate dan simpan status ketika record dibuat/diupdate
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($record) {
            $umurBulan = $record->getUmurBulanAttribute();
            $record->status_gizi = $record->calculateStatusGizi($record->berat, $umurBulan);
            $record->status_stunting = $record->calculateStatusStunting($record->tinggi, $umurBulan);
        });
    }
}
