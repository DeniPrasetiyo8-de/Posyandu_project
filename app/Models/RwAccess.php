<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RwAccess extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rw',
        'kode_akses',
        'nama_posyandu',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Scope to get only active access codes.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Find by kode akses.
     */
    public static function findByKode($kode)
    {
        return static::where('kode_akses', strtoupper($kode))->first();
    }

    /**
     * Check if kode akses is valid and active.
     */
    public static function isValid($kode)
    {
        $access = static::findByKode($kode);
        return $access && $access->status;
    }
}
