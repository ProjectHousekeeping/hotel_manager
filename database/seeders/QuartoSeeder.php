<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quarto;
use Illuminate\Support\Facades\DB;
class QuartoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
    {
        // Opcional: Limpa a tabela antes de popular para evitar quartos duplicados
        // se você rodar o seeder mais de uma vez.
        // Se não quiser apagar os quartos existentes, remova a linha abaixo.
        DB::table('quartos')->delete();

        $tiposDeQuarto = ['Solteiro', 'Casal', 'Suíte', 'Familiar'];
        $situacoesPossiveis = [
                'finalizada',
                'limpeza_em_andamento',
                'manutencao_em_andamento',
                'pedido_encaminhado',
                'disponivel', // Adicionando outras situações comuns
                'ocupado'
            ];

        for ($i = 1; $i <= 10; $i++) {
            // Gera um número de quarto sequencial, por exemplo: 101, 102, 201, 202, etc.
            $andar = fake()->numberBetween(1, 4); // Andares de 1 a 4
            $numeroNoAndar = fake()->unique()->numberBetween(1, 20); // Número do quarto no andar
            $numeroFinal = $andar . str_pad($numeroNoAndar, 2, '0', STR_PAD_LEFT);


            Quarto::create([
                'numero' => $numeroFinal,
                'tipo' => $tiposDeQuarto[array_rand($tiposDeQuarto)],
                'valor_diaria' => fake()->randomFloat(2, 180, 650), // Gera valor entre 180.00 e 650.00
                'situacao' => $situacoesPossiveis[array_rand($situacoesPossiveis)],
            ]);
        }
    }
}
