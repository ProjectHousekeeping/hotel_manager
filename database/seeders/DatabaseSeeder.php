<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CargoSeeder::class,
            UserSeeder::class,
            QuartoSeeder::class,
            ItemSeeder::class,
            TarefaSeeder::class,
        ]);
    }
}