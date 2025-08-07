<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Quarto;
use App\Models\Tarefa;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TarefaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opcional: Limpa a tabela de tarefas antes de popular.
        DB::table('tarefas')->delete();

        // Pega todos os usuários que não são gerentes, carregando a relação com o cargo.
        // Ajuste 'Gerente' se o cargo do seu gerente tiver outro nome, como 'Diretor Executivo'.
        $funcionarios = User::with('cargo')->whereHas('cargo', function ($query) {
            $query->where('nome', '!=', 'Gerente');
        })->get();

        // Pega todos os quartos para associar às tarefas.
        $quartos = Quarto::all();

        if ($funcionarios->isEmpty()) {
            $this->command->info('Nenhum funcionário (não-gerente) encontrado. Rode o seeder de usuários primeiro.');
            return;
        }

        if ($quartos->isEmpty()) {
            $this->command->info('Nenhum quarto encontrado. Rode o seeder de quartos primeiro.');
            return;
        }

        // Define os tipos de tarefas para cada função.
        $tarefasPorFuncao = [
            'Recepcionista' => ['Reserva'],
            'Camareira' => ['Limpeza', 'Vistoria'],
            'Serviços Gerais' => ['Manutenção'],
        ];

        foreach ($funcionarios as $funcionario) {
            $cargoNome = $funcionario->cargo->nome;

            // Pula para o próximo funcionário se o cargo dele não estiver na lista de tarefas.
            if (!isset($tarefasPorFuncao[$cargoNome])) {
                continue;
            }

            // Define um número aleatório de tarefas para cada funcionário (entre 1 e 15).
            $numeroDeTarefas = rand(1, 15);

            for ($i = 0; $i < $numeroDeTarefas; $i++) {
                // Seleciona um tipo de tarefa aleatório baseado na função do funcionário.
                $tiposDeTarefaPossiveis = $tarefasPorFuncao[$cargoNome];
                $tipoTarefaSelecionada = $tiposDeTarefaPossiveis[array_rand($tiposDeTarefaPossiveis)];

                // Pega um quarto aleatório.
                $quartoAleatorio = $quartos->random();

                // Gera dados de data e hora.
                $dataTarefa = fake()->dateTimeBetween('-1 month', 'now');
                $horaInicio = Carbon::instance($dataTarefa)->setTime(rand(8, 17), rand(0, 59));
                $horaFim = (rand(0, 1) == 1) ? $horaInicio->copy()->addHours(rand(1, 2)) : null;

                Tarefa::create([
                    'user_id' => $funcionario->id,
                    'quarto_id' => $quartoAleatorio->id,
                    'tipo_tarefa' => $tipoTarefaSelecionada,
                    'data' => $dataTarefa->format('Y-m-d'),
                    'hora_inicio' => $horaInicio->format('H:i:s'),
                    'hora_fim' => $horaFim ? $horaFim->format('H:i:s') : null,
                    'descricao' => "{$tipoTarefaSelecionada} a ser realizada no quarto {$quartoAleatorio->numero} pelo funcionário {$funcionario->name}.",
                ]);
            }
        }
    }
}
