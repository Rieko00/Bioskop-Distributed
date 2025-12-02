<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SeatMap;

class SeatMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate seats for each studio
        $studios = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        foreach ($studios as $studioId) {
            // Generate seats: 10 rows (A-J), 12 seats per row
            for ($row = 0; $row < 10; $row++) {
                $rowLetter = chr(65 + $row); // A, B, C, etc.

                for ($seat = 1; $seat <= 12; $seat++) {
                    $seatCode = $rowLetter . str_pad($seat, 2, '0', STR_PAD_LEFT);

                    SeatMap::create([
                        'id_studio' => $studioId,
                        'seat_code' => $seatCode,
                        'no_baris' => $rowLetter,
                        'no_kolom' => str_pad($seat, 2, '0', STR_PAD_LEFT),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
