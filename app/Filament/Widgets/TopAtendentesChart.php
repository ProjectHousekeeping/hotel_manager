<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopAtendentesChart extends ChartWidget
{
    protected static ?string $heading = 'Funcionários que Mais Atenderam Tarefas';
    protected static ?int $sort = 3; // ordenação no painel
    protected static ?string $maxHeight = '300px';


    protected function getData(): array
    {
        $dados = DB::table('tarefas as t')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->select('u.name', DB::raw('COUNT(t.id) as total'))
            ->whereNotNull('t.hora_fim')
            ->groupBy('u.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $labels = $dados->pluck('name')->toArray();
        $valores = $dados->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Tarefas Finalizadas',
                    'data' => $valores,
                    'backgroundColor' => '#6366f1', // roxo
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // ou 'horizontalBar' para barras horizontais
    }
}
