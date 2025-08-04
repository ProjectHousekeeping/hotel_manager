<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ManutencoesPorMesChart extends ChartWidget
{
    protected static ?string $heading = 'Manutenções por Mês';

    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '300px';
    protected static string $color = 'warning';

    protected function getData(): array
    {
        $dados = DB::table('tarefas as t')
            ->selectRaw("
                EXTRACT(YEAR FROM t.data) AS ano,
                EXTRACT(MONTH FROM t.data) AS mes,
                COUNT(*) as total
            ")
            ->where('t.tipo_tarefa', 'Manutenção')
            ->groupByRaw('EXTRACT(YEAR FROM t.data), EXTRACT(MONTH FROM t.data)')
            ->orderByRaw('ano, mes')
            ->get();

        $labels = [];
        $values = [];

        foreach ($dados as $registro) {
            $labels[] = str_pad($registro->mes, 2, '0', STR_PAD_LEFT) . '/' . $registro->ano;
            $values[] = $registro->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total de Manutenções',
                    'data' => $values,
                    'backgroundColor' => '#f59e0b', // cor amber
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
