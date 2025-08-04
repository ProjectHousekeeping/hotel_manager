<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cargo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
// Importe a classe Factory para usar o Faker
use Illuminate\Database\Eloquent\Factories\Factory;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Garante que a tabela seja limpa antes de popular.
        // DB::table('users')->delete();

        $cargos = Cargo::all();

        if ($cargos->isEmpty()) {
            $this->command->info('Nenhum cargo encontrado. Rode o seeder de cargos primeiro.');
            return;
        }

        // Pega o cargo de Diretor para ser o gerente
        $cargoDiretor = $cargos->firstWhere('nome', 'Diretor Executivo');

        // Cria o Diretor primeiro para que ele possa ser o gerente dos outros
        $gerente = User::factory()->create([
            'name' => 'João da Silva (Diretor)',
            'email' => 'diretor@exemplo.com',
            'password' => Hash::make('senha123'),
            'cpf' => '123.456.789-00',
            'telefone' => '(11) 98888-7777',
            'situacao' => 'ativo',
            'cargo_id' => $cargoDiretor->id,
            'gerente_id' => null, // O diretor não tem gerente
        ]);


        // Filtra os outros cargos para distribuir entre os funcionários
        $outrosCargos = $cargos->where('nome', '!=', 'Diretor Executivo')->where('nome', '!=', 'admin');

        // Cria os outros 19 funcionários
        for ($i = 0; $i < 19; $i++) {
            User::factory()->create([
                // 'name' e 'email' serão gerados pelo Factory
                'password' => Hash::make('senha123'),
                'cpf' => fake()->unique()->numerify('###.###.###-##'),
                'telefone' => fake()->numerify('(##) 9####-####'),
                'situacao' => 'ativo',
                // Pega um cargo aleatório da coleção de "outrosCargos"
                'cargo_id' => $outrosCargos->random()->id,
                'gerente_id' => $gerente->id, // Define o Diretor como gerente
            ]);
        }
    }
}