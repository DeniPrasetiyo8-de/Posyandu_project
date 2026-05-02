<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'tanggal',
        'rw',
        'posyandu_id',
        'description',
    ];

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function getIconAttribute()
    {
        $nama = strtolower($this->nama_kegiatan);
        if (str_contains($nama, 'imunisasi')) return 'fa-syringe';
        if (str_contains($nama, 'vitamin') || str_contains($nama, 'capsule')) return 'fa-pills';
        if (str_contains($nama, 'timbang') || str_contains($nama, 'bb') || str_contains($nama, 'tb')) return 'fa-weight-hanging';
        if (str_contains($nama, 'gizi') || str_contains($nama, 'kms')) return 'fa-chart-line';
        return 'fa-calendar-check';
    }
}
