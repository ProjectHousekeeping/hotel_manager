<?php

namespace App\Filament\Widgets;

use App\Models\Funcionario;
use App\Models\Quarto;
use App\Models\Tarefa;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

use App\Filament\Widgets\ManutencoesPorMesChart;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1; // Define a ordem no dashboard




    protected function getStats(): array
    {
        $quartosDisponiveis = Quarto::where('situacao', 'disponivel')->count();
        $totalQuartos = Quarto::count();
        $tarefasPendentes = Tarefa::whereNull('hora_fim')->count();

        // Ranking do quarto com mais manutenções
        $quartoTopManutencao = DB::table('tarefas as t')
            ->join('quartos as q', 't.quarto_id', '=', 'q.id')
            ->select('q.numero', DB::raw('COUNT(t.id) as total_manutencoes'))
            ->where('t.tipo_tarefa', 'Manutenção')
            ->groupBy('q.numero')
            ->orderByDesc('total_manutencoes')
            ->limit(1)
            ->first();

        // Manutenções por mês e ano
        $manutencoesPorMes = DB::table('tarefas as t')
            ->selectRaw('EXTRACT(YEAR FROM t.data) AS ano, EXTRACT(MONTH FROM t.data) AS mes, COUNT(t.id) AS total_manutencoes')
            ->where('t.tipo_tarefa', 'Manutenção')
            ->groupByRaw('EXTRACT(YEAR FROM t.data), EXTRACT(MONTH FROM t.data)')
            ->orderByRaw('ano, mes')
            ->get();


        // Pega o total do último mês com registro
        $ultimoRegistro = $manutencoesPorMes->last();
        $labelUltimoMes = $ultimoRegistro
            ? str_pad($ultimoRegistro->mes, 2, '0', STR_PAD_LEFT) . '/' . $ultimoRegistro->ano
            : 'Sem registros';

        $valorUltimoMes = $ultimoRegistro ? $ultimoRegistro->total_manutencoes : 0;

        // Funcionário que mais atendeu tarefas
        $topFuncionario = DB::table('tarefas as t')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->select('u.name', DB::raw('COUNT(t.id) as total'))
            ->whereNotNull('t.hora_fim')
            ->groupBy('u.name')
            ->orderByDesc('total')
            ->first();


        return [
            Stat::make('Quartos Disponíveis', "{$quartosDisponiveis} de {$totalQuartos}")
                ->description('Quartos livres para reserva')
                ->descriptionIcon('heroicon-m-key')
                ->color('success'),

            Stat::make('Tarefas Pendentes', $tarefasPendentes)
                ->description('Tarefas que precisam de atenção')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color($tarefasPendentes > 0 ? 'warning' : 'gray'),

            Stat::make('Funcionários Ativos', User::where('situacao', 'disponivel')->count())
                ->description('Equipe disponível para trabalho')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make(
                'Quarto com Mais Manutenções',
                $quartoTopManutencao
                ? "Quarto {$quartoTopManutencao->numero} ({$quartoTopManutencao->total_manutencoes})"
                : 'Nenhum registro'
            )
                ->description('Mais demandado para manutenção')
                ->descriptionIcon('heroicon-m-wrench')
                ->color('danger'),

            Stat::make("Manutenções em {$labelUltimoMes}", $valorUltimoMes)
                ->description('Total de manutenções registradas')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('amber'),

            Stat::make(
                'Top Atendente',
                $topFuncionario
                ? "{$topFuncionario->name} ({$topFuncionario->total} atendimentos)"
                : 'Nenhum registro'
            )
                ->description('Funcionário com mais tarefas finalizadas')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('purple'),

        ];
    }

}