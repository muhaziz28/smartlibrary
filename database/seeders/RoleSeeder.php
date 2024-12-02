<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'role_name' => 'admin',
            'role_description' => 'Administrator',
        ]);

        Role::create([
            'role_name' => 'mahasiswa',
            'role_description' => 'Mahasiswa',
        ]);

        Role::create([
            'role_name' => 'dosen',
            'role_description' => 'Dosen',
        ]);
    }
}
