<?php

namespace App\Filament\Widgets;

use App\Models\Funcionario;
use App\Models\Quarto;
use App\Models\Tarefa;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1; // Define a ordem no dashboard

    
    

    protected function getStats(): array
    {
        $quartosDisponiveis = Quarto::where('situacao', 'disponivel')->count();
        $totalQuartos = Quarto::count();
        $tarefasPendentes = Tarefa::whereNull('hora_fim')->count();

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
        ];
    }

}