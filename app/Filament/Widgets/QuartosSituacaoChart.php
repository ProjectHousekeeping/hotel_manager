<?php

namespace App\Filament\Widgets;

use App\Models\Quarto;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class QuartosSituacaoChart extends ChartWidget
{
    protected static ?string $heading = 'Situação dos Quartos';


    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $situacaoInfo = [
            'disponivel' => ['label' => 'Disponível', 'color' => '#22c55e'],
            'ocupado' => ['label' => 'Ocupado', 'color' => '#ef4444'],
            'limpeza_em_andamento' => ['label' => 'Em Limpeza', 'color' => '#f59e0b'],
            'manutencao_em_andamento' => ['label' => 'Em Manutenção', 'color' => '#f97316'],
            'finalizada' => ['label' => 'Finalizada', 'color' => '#6b7280'],
            'pedido_encaminhado' => ['label' => 'Pedido Encaminhado', 'color' => '#3b82f6'],
        ];

        $dados = Quarto::query()
            ->select('situacao', DB::raw('count(*) as total'))
            ->groupBy('situacao')
            ->get();

        $labels = [];
        $data = [];
        $colors = [];

        foreach ($dados as $item) {
            $situacao = $item->situacao;
            if (isset($situacaoInfo[$situacao])) {
                $labels[] = $situacaoInfo[$situacao]['label'];
                $data[] = $item->total;
                $colors[] = $situacaoInfo[$situacao]['color'];
            }
        }

        return [
            'datasets' => [['label' => 'Quartos', 'data' => $data, 'backgroundColor' => $colors]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

}