<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotherHealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'mother_id',
        'bulan_ke',
        'berat_badan',
        'lila',
        'tekanan_darah',
        'catatan',
        'recorded_by',
        'recorded_at',
    ];

    protected $casts = [
        'bulan_ke' => 'integer',
        'berat_badan' => 'decimal:1',
        'lila' => 'decimal:1',
        'recorded_at' => 'date',
    ];

    /**
     * Relasi ke Mother (ibu)
     */
    public function mother(): BelongsTo
    {
        return $this->belongsTo(Mother::class);
    }

    /**
     * Relasi ke User (yang input data)
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Format tekanan darah untuk display
     */
    public function getTekananDarahDisplayAttribute(): string
    {
        if (!$this->tekanan_darah) {
            return '-';
        }
        return $this->tekanan_darah . ' mmHg';
    }

    /**
     * Cek status KEK berdasarkan LILA
     * LILA < 23.5 cm = risiko KEK (Kekurangan Energi Kronis)
     */
    public function getStatusKekAttribute(): string
    {
        if (!$this->lila) {
            return 'Belum Diperiksa';
        }
        
        if ($this->lila < 23.5) {
            return 'Risiko KEK';
        }
        
        return 'Normal';
    }

    /**
     * Cek apakah LILA termasuk risiko KEK
     */
    public function getIsRisikoKekAttribute(): bool
    {
        return $this->lila && $this->lila < 23.5;
    }

    /**
     * Scope untuk filter berdasarkan ibu
     */
    public function scopeForMother($query, int $motherId)
    {
        return $query->where('mother_id', $motherId);
    }

    /**
     * Scope untuk urut berdasarkan bulan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('bulan_ke', 'asc');
    }
}
