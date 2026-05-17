<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'kategori',
        'isi',
        'gambar',
    ];

    // Category constants
    const KATEGORI_GIZI_ANAK = 'gizi_anak';
    const KATEGORI_IBU_HAMIL = 'ibu_hamil';
    const KATEGORI_IMUNISASI = 'imunisasi';

    public static function getKategoriOptions()
    {
        return [
            self::KATEGORI_GIZI_ANAK => 'Gizi Anak',
            self::KATEGORI_IBU_HAMIL => 'Ibu Hamil',
            self::KATEGORI_IMUNISASI => 'Imunisasi',
        ];
    }

    public function getKategoriLabelAttribute()
    {
        $labels = [
            self::KATEGORI_GIZI_ANAK => 'Gizi Anak',
            self::KATEGORI_IBU_HAMIL => 'Ibu Hamil',
            self::KATEGORI_IMUNISASI => 'Imunisasi',
        ];
        return $labels[$this->kategori] ?? $this->kategori;
    }

    public function getTanggalAttribute()
    {
        return $this->created_at ? \Carbon\Carbon::parse($this->created_at)->format('d M Y') : '-';
    }
}
