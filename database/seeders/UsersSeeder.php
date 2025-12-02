<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@bioskop.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Kasir',
                'email' => 'kasir@bioskop.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'John Doe',
                'email' => 'john.doe@bioskop.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('password'),
                'role' => 'kasir',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
