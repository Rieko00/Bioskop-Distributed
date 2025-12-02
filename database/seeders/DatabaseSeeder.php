<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed cinema data in correct order based on foreign key dependencies
        $this->call([
            UsersSeeder::class,
            CabangSeeder::class,
            FilmSeeder::class,
            PelangganSeeder::class,
            StudioSeeder::class,
            SeatMapSeeder::class,
            JadwalTayangSeeder::class,
            TransaksiSeeder::class,
            DetailTransaksiSeeder::class,

        ]);
    }
}
