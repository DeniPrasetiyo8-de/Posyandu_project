<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    use HasFactory;

protected $fillable = ['nama_posyandu', 'nama_kader', 'alamat', 'rw', 'status_kehadiran', 'foto'];

    public function children()
    {
        return $this->hasMany(Child::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function kaders()
    {
        return $this->hasMany(Kader::class);
    }
}
