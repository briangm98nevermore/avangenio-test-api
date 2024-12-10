<?php

namespace Database\Seeders;

use App\Models\ToroVacaGame;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)
        ->has(ToroVacaGame::factory()->count(2), 'torovacagames') // 5 juegos por usuario
        ->create();
       // ToroVacaGame::factory(10)->create();
    }
}
