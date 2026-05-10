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
        'nama',
        'nama_lengkap',
        'jenis_kelamin',
        'tgl_hamil',
        'tanggal_kehamilan',
        'berat_badan',
        'foto',
        'posyandu_id',
        'trimester_status',
        'tt_status',
        'iron_status',
    ];

    protected $casts = [
        'tgl_hamil' => 'date',
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
        $tgl = $this->tgl_hamil ?? $this->tanggal_kehamiltonan;
        if (!$tgl) return 0;
        return \Carbon\Carbon::parse($tgl)->diffInMonths(now());
    }

    public function getNamaAttribute()
    {
        return $this->nama_lengkap ?? $this->attributes['nama'] ?? null;
    }

    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('images/mothers/' . $this->foto) : null;
    }

    public function getStatusKesehatanAttribute()
    {
        $tgl = $this->tgl_hamil ?? $this->tanggal_kehamiltonan;
        if (!$tgl) {
            return 'Perlu Pemeriksaan';
        }
        
        $mingguKehamiltonan = \Carbon\Carbon::parse($tgl)->diffInWeeks(now());
        $beratIdeal = 50 + ($mingguKehamiltonan * 0.5);
        
        if (!$this->berat_badan) {
            return 'Perlu Pemeriksaan';
        }
        
        $diff = abs($this->berat_badan - $beratIdeal) / $beratIdeal * 100;
        
        if ($diff <= 20 && $this->berat_badan > 40) {
            return 'Sehat';
        } elseif ($this->berat_badan < 35) {
            return 'Kurangan';
        } else {
            return 'Perlu Pemeriksaan';
        }
    }
    
    public static function getDaftarTT()
    {
        return array(
            'tt1' => 'TT 1',
            'tt2' => 'TT 2',
            'tt3' => 'TT 3',
            'tt4' => 'TT 4',
            'tt5' => 'TT 5',
        );
    }
    
    public function calculatePregnancyWeek()
    {
        $tgl = $this->tgl_hamil ?? $this->tanggal_kehamiltonan;
        if (!$tgl) return 0;
        return \Carbon\Carbon::parse($tgl)->diffInWeeks(now()) + 1;
    }
    
    public function calculateDueDate()
    {
        $tgl = $this->tgl_hamil ?? $this->tanggal_kehamiltonan;
        if (!$tgl) return null;
        return \Carbon\Carbon::parse($tgl)->addDays(280)->toDateString();
    }
    
    public function getPregnancyStatus($week)
    {
        if ($week <= 13) return 'Trimester 1';
        if ($week <= 26) return 'Trimester 2';
        return 'Trimester 3';
    }
}
