<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalTayang extends Model
{
    use HasFactory;

    protected $table = 'jadwal_tayangs';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_studio',
        'id_film',
        'waktu_mulai',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime:H:i',
    ];

    public function studio()
    {
        return $this->belongsTo(Studio::class, 'id_studio', 'id_studio');
    }

    public function film()
    {
        return $this->belongsTo(Film::class, 'id_film', 'id_film');
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_jadwal', 'id_jadwal');
    }
}
