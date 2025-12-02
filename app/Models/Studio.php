<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    use HasFactory;

    protected $table = 'studios';
    protected $primaryKey = 'id_studio';

    protected $fillable = [
        'id_cabang',
        'nama_studio',
        'tipe_studio',
        'kapasitas',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }

    public function seatMaps()
    {
        return $this->hasMany(SeatMap::class, 'id_studio', 'id_studio');
    }

    public function jadwalTayangs()
    {
        return $this->hasMany(JadwalTayang::class, 'id_studio', 'id_studio');
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_studio', 'id_studio');
    }
}
