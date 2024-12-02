<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create 11 mahasiswa
        for ($i = 11; $i <= 100; $i++) {
            $mahasiswa = Mahasiswa::create([
                'nim' => '2024' . $i,
                'nama_mahasiswa' => 'acikiwir' . $i,
            ]);
        }
    }
}
