<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KepalaDesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan hanya ada 1 kades untuk menghindari duplikasi
        User::updateOrCreate(
            ['email' => 'kades@desa.com'],
            [
                'name' => 'Kepala Desa Karombo',
                'password' => Hash::make('password123'),
                'role' => 'kepala_desa',
            ]
        );
    }
}
