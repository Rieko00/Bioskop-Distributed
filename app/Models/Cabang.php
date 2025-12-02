<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabangs';
    protected $primaryKey = 'id_cabang';

    protected $fillable = [
        'nama_cabang',
        'alamat',
    ];

    public function studios()
    {
        return $this->hasMany(Studio::class, 'id_cabang', 'id_cabang');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_cabang', 'id_cabang');
    }
}
