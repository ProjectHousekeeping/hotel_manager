<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cargo;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Garante que a tabela seja limpa antes de popular.
        // DB::table('cargos')->delete();

        Cargo::create(['nome' => 'Gerente']);
        Cargo::create(['nome' => 'Recepcionista']);
        Cargo::create(['nome' => 'Camareira']);
        Cargo::create(['nome' => 'Serviços Gerais']);
    }
}