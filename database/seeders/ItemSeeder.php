<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quarto;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opcional: Limpa a tabela de itens antes de popular.
        // CUIDADO: Isso apagará TODOS os itens existentes.
        // Se não quiser esse comportamento, remova a linha abaixo.
        DB::table('items')->delete();

        // Pega todos os quartos cadastrados no banco de dados.
        $quartos = Quarto::all();

        if ($quartos->isEmpty()) {
            $this->command->info('Nenhum quarto encontrado. Rode o seeder de quartos primeiro.');
            return;
        }

        // Define a lista de itens padrão que cada quarto deve ter.
        $itensPadrao = [
            ['nome' => 'Televisão', 'classificacao' => 'Eletrônico', 'preco' => 1200.00, 'quantidade' => 1],
            ['nome' => 'Ar Condicionado', 'classificacao' => 'Eletrodoméstico', 'preco' => 1800.00, 'quantidade' => 1],
            ['nome' => 'Controle Remoto', 'classificacao' => 'Acessório', 'preco' => 50.00, 'quantidade' => 1],
            ['nome' => 'Cama de Casal', 'classificacao' => 'Móvel', 'preco' => 1500.00, 'quantidade' => 1],
            ['nome' => 'Geladeira (Frigobar)', 'classificacao' => 'Eletrodoméstico', 'preco' => 750.00, 'quantidade' => 1],
            ['nome' => 'Espelho de Parede', 'classificacao' => 'Decoração', 'preco' => 250.00, 'quantidade' => 1],
            ['nome' => 'Travesseiro', 'classificacao' => 'Enxoval', 'preco' => 60.00, 'quantidade' => 2],
            ['nome' => 'Toalha de Banho', 'classificacao' => 'Enxoval', 'preco' => 45.00, 'quantidade' => 2],
            ['nome' => 'Conjunto de Cama', 'classificacao' => 'Enxoval', 'preco' => 200.00, 'quantidade' => 1],
        ];

        // Itera sobre cada quarto encontrado.
        foreach ($quartos as $quarto) {
            // Itera sobre a lista de itens padrão e cria um para o quarto atual.
            foreach ($itensPadrao as $itemData) {
                Item::create([
                    'quarto_id' => $quarto->id,
                    'nome' => $itemData['nome'],
                    'classificacao' => $itemData['classificacao'],
                    'marca' => 'Marca Padrão', // Você pode ajustar se necessário
                    'preco' => $itemData['preco'],
                    'quantidade' => $itemData['quantidade'],
                ]);
            }
        }
    }
}
