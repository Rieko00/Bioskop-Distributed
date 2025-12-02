<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksis';
    protected $primaryKey = 'id_detail_transaksi';
    public $timestamps = false; // Only has created_at in original schema

    protected $fillable = [
        'id_transaksi',
        'id_jadwal',
        'seat_id',
        'id_studio',
        'harga',
    ];

    protected $dates = [
        'created_at',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function jadwalTayang()
    {
        return $this->belongsTo(JadwalTayang::class, 'id_jadwal', 'id_jadwal');
    }

    public function seatMap()
    {
        return $this->belongsTo(SeatMap::class, 'seat_id', 'seat_id');
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class, 'id_studio', 'id_studio');
    }
}
