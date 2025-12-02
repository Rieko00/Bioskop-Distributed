<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatMap extends Model
{
    use HasFactory;

    protected $table = 'seat_maps';
    protected $primaryKey = 'seat_id';
    public $timestamps = false; // Only has updated_at in original schema

    protected $fillable = [
        'id_studio',
        'seat_code',
        'no_baris',
        'no_kolom',
        'tipe',
    ];

    protected $dates = [
        'updated_at',
    ];

    public function studio()
    {
        return $this->belongsTo(Studio::class, 'id_studio', 'id_studio');
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'seat_id', 'seat_id');
    }
}
