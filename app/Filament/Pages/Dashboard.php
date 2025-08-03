<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\QuartosSituacaoChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TarefasPendentesTable;
use App\Filament\Widgets\ManutencoesPorMesChart;
use App\Filament\Widgets\TopAtendentesChart;

use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    public function getWidgets(): array
    {
        return [
            'stats_overview' => StatsOverview::class,
            'quartos_situacao_chart' => QuartosSituacaoChart::class,
            'tarefas_pendentes_table' => TarefasPendentesTable::class,
            'manutencoes_por_mes_chart' => ManutencoesPorMesChart::class,
            'top_atendentes_chart' => TopAtendentesChart::class,
        ];
    }
}