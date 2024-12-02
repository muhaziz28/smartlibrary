<?php

namespace Database\Seeders;

use App\Models\JamPerkuliahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JamPerkuliahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = JamPerkuliahan::create([
            'start' => '07:00',
            'end'   => '07:50'
        ]);
    }
}
