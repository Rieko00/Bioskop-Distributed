<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false; // No timestamps in original schema

    protected $fillable = [
        'id_pelanggan',
        'id_cabang',
        'waktu_transaksi',
        'metode_pembayaran',
        'total_bayar',
    ];

    protected $casts = [
        'waktu_transaksi' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}
