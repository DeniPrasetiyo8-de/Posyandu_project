<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'kategori',
        'posyandu_id',
        'user_id',
        'konten',
        'data_json',
        'bulan',
        'tahun',
        'status',
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
        return $query->where('kategori', 'anak');
    }

    public function scopeIbuHamil($query)
    {
        return $query->where('kategori', 'ibu_hamil');
    }

    public function scopeImunisasi($query)
    {
        return $query->where('kategori', 'imunisasi');
    }

public function scopeGizi($query)
    {
        return $query->where('kategori', 'gizi');
    }

    public function scopeStunting($query)
    {
        return $query->where('kategori', 'stunting');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Get available report categories
     */
    public static function getKategoriOptions()
    {
        return [
            'anak' => 'Laporan Anak',
            'ibu_hamil' => 'Laporan Ibu Hamil',
            'kegatan' => 'Laporan Kegiat Posyandu',
            'imunisasi' => 'Rekap Imunisasi',
            'gizi' => 'Statistik Gizi',
            'stunting' => 'Statistik Stunting',
            'umum' => 'Laporan Umum',
        ];
    }

    /**
     * Get month options for reports
     */
    public static function getBulanOptions()
    {
        return [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'Nopember',
            '12' => 'Desember',
        ];
    }

    /**
     * Get available years (last 5 years)
     */
    public static function getTahunOptions()
    {
        $currentYear = date('Y');
        $years = [];
        for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
            $years[$i] = (string) $i;
        }
        return $years;
    }
}
