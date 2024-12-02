<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new User();
        $admin->username = 'admin';
        $admin->email = 'admin@localhost';
        $admin->password = Hash::make('password');
        $admin->role_id = 1;
        $admin->save();
    }
}
