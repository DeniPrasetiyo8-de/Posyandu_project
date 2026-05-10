<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kader extends Model
{
    use HasFactory;

protected $fillable = [
        'nama_kader',
        'posyandu_id',
        'alamat',
        'rw',
        'no_hp',
        'status_kehadiran',
        'foto',
    ];

    protected $casts = [
        'status_kehadiran' => 'string',
    ];

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }
}
