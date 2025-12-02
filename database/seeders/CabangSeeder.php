<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabangs = [
            [
                'nama_cabang' => 'Bioskop Central Plaza',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'kode_cabang_kota' => 'JKT1'
            ],
            [
                'nama_cabang' => 'Bioskop Mall Kelapa Gading',
                'alamat' => 'Jl. Kelapa Gading Boulevard, Jakarta Utara',
                'kode_cabang_kota' => 'JKT2'
            ],
            [
                'nama_cabang' => 'Bioskop Plaza Indonesia',
                'alamat' => 'Jl. M.H. Thamrin Kav. 28-30, Jakarta Pusat',
                'kode_cabang_kota' => 'JKT3'
            ],
            [
                'nama_cabang' => 'Bioskop Pondok Indah Mall',
                'alamat' => 'Jl. Metro Pondok Indah, Jakarta Selatan',
                'kode_cabang_kota' => 'JKT4'
            ],
            [
                'nama_cabang' => 'Bioskop Grand Indonesia',
                'alamat' => 'Jl. M.H. Thamrin No. 1, Jakarta Pusat',
                'kode_cabang_kota' => 'JKT5'
            ],
        ];

        foreach ($cabangs as $cabang) {
            Cabang::create($cabang);
        }
    }
}
