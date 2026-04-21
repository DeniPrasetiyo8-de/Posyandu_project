<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    public function user(){
    return $this->belongsTo(User::class);
}

public function posyandu(){
    return $this->belongsTo(Posyandu::class);
}

public function healthRecords(){
    return $this->hasMany(HealthRecord::class);
}
    protected $fillable = [
    'user_id',
    'posyandu_id',
    'nama',
    'tanggal_lahir',
    'jenis_kelamin'
];
}
