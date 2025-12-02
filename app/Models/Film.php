<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $table = 'films';
    protected $primaryKey = 'id_film';

    protected $fillable = [
        'judul',
        'sinopsis',
        'durasi_menit',
        'rating_usia',
        'harga_film',
    ];

    public function jadwalTayangs()
    {
        return $this->hasMany(JadwalTayang::class, 'id_film', 'id_film');
    }
}
