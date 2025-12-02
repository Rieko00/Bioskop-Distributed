<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggans = [
            [
                'nama' => 'Ahmad Wijaya',
                'email' => 'ahmad.wijaya@email.com',
                'telp' => '08123456789',
            ],
            [
                'nama' => 'Sari Dewi',
                'email' => 'sari.dewi@email.com',
                'telp' => '08234567890',
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'telp' => '08345678901',
            ],
            [
                'nama' => 'Maya Putri',
                'email' => 'maya.putri@email.com',
                'telp' => '08456789012',
            ],
            [
                'nama' => 'Rizki Pratama',
                'email' => 'rizki.pratama@email.com',
                'telp' => '08567890123',
            ],
            [
                'nama' => 'Indah Sari',
                'email' => 'indah.sari@email.com',
                'telp' => '08678901234',
            ],
            [
                'nama' => 'Dedi Kurniawan',
                'email' => 'dedi.kurniawan@email.com',
                'telp' => '08789012345',
            ],
            [
                'nama' => 'Lia Maharani',
                'email' => 'lia.maharani@email.com',
                'telp' => '08890123456',
            ],
        ];

        foreach ($pelanggans as $pelanggan) {
            Pelanggan::create($pelanggan);
        }
    }
}
