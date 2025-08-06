<?php

// namespace App\Filament\Resources\UserResource\Pages;
namespace App\Filament\Resources\RelatorioResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User; // Importar o model User
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable; // Importar trait
use Filament\Tables\Contracts\HasTable; // Importar contrato
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\DB; // Importar DB Facade
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class RelatorioFuncionarios extends Page implements HasTable
{
    use InteractsWithTable; // Usar o trait

    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.relatorios-resource.pages.relatorio-funcionarios';

    // Título da Página
    protected static ?string $title = 'Relatório de Funcionarios';

    // Ícone da Navegação
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    // Label da Navegação
    protected static ?string $navigationLabel = 'Relatório de Funcionarios';

    // Ordem no menu
    protected static ?int $navigationSort = 3;

    /**
     * Define a query que alimenta a tabela.
     * Aqui é onde a "mágica" da compilação de dados acontece.
     */
protected function getTableQuery(): Builder
{
    // Obtém as datas do filtro para evitar repetição
    $dataInicio = $this->getTableFilterState('periodo')['data_inicio'] ?? '1900-01-01';
    $dataFim = $this->getTableFilterState('periodo')['data_fim'] ?? now()->format('Y-m-d');

    return User::select([
            'users.id',
            'users.name'
        ])
        ->selectRaw('(SELECT count(*) FROM tarefas WHERE users.id = tarefas.user_id AND tarefas.data >= ? AND tarefas.data <= ?) as tarefas_realizadas_count', [
            $dataInicio,
            $dataFim
        ])
        // 👇--- SEÇÃO ALTERADA PARA MYSQL ---👇
        ->selectRaw(
            '(SELECT
                COALESCE(
                    CONCAT(
                        FLOOR(AVG(TIMESTAMPDIFF(SECOND, tarefas.hora_inicio, tarefas.hora_fim)) / 3600),
                        \'h \',
                        FLOOR((AVG(TIMESTAMPDIFF(SECOND, tarefas.hora_inicio, tarefas.hora_fim)) % 3600) / 60),
                        \'m\'
                    ),
                    \'0h 0m\'
                )
             FROM tarefas
             WHERE tarefas.user_id = users.id
             AND tarefas.data >= ?
             AND tarefas.data <= ?
            ) as media_tempo_formatado',
            [
                $dataInicio,
                $dataFim
            ]
        )
        // 👆--- FIM DA SEÇÃO ALTERADA ---👆
        ->whereExists(function ($query) use ($dataInicio, $dataFim) {
            $query->select(DB::raw(1))
                ->from('tarefas')
                ->whereRaw('users.id = tarefas.user_id')
                ->where('tarefas.data', '>=', $dataInicio)
                ->where('tarefas.data', '<=', $dataFim);
        })
        ->whereNull('deleted_at')
        ->orderBy('id');
}

    /**
     * Define a estrutura da nossa tabela de relatório.
     */
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery()) // Usa a query que definimos acima
            ->columns([
                TextColumn::make('name')
                    ->label('Nome do Colaborador')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tarefas_realizadas_count')
                    ->label('Quantidade de Tarefas')
                    ->sortable(),
                   
                TextColumn::make('media_tempo_formatado')
                    ->label('Média de Tempo por Tarefa')
                    ->sortable(),
            ])
            ->filters([
                // Filtro de Período Personalizado
                Filter::make('periodo')
                    ->form([
                        DatePicker::make('data_inicio')
                            ->label('Data Início')
                            ->default(now()->subDays(30))
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('data_fim')
                            ->label('Data Fim')
                            ->default(now())
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query; // A lógica de filtro já está no getTableQuery()
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['data_inicio'] && !$data['data_fim']) {
                            return null;
                        }

                        $inicio = $data['data_inicio'] ? Carbon::parse($data['data_inicio'])->format('d/m/Y') : 'Início';
                        $fim = $data['data_fim'] ? Carbon::parse($data['data_fim'])->format('d/m/Y') : 'Hoje';

                        return "Período: {$inicio} até {$fim}";
                    }),

                // Filtros Rápidos
                SelectFilter::make('periodo_rapido')
                    ->label('Período Rápido')
                    ->options([
                        '7' => 'Últimos 7 dias',
                        '15' => 'Últimos 15 dias',
                        '30' => 'Últimos 30 dias',
                        '60' => 'Últimos 60 dias',
                        '90' => 'Últimos 90 dias',
                        'mes_atual' => 'Mês atual',
                        'mes_passado' => 'Mês passado',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!$data['value']) {
                            return $query;
                        }

                        $periodo = $data['value'];
                        
                        switch ($periodo) {
                            case '7':
                            case '15':
                            case '30':
                            case '60':
                            case '90':
                                $dataInicio = now()->subDays((int)$periodo)->format('Y-m-d');
                                $dataFim = now()->format('Y-m-d');
                                break;
                            case 'mes_atual':
                                $dataInicio = now()->startOfMonth()->format('Y-m-d');
                                $dataFim = now()->endOfMonth()->format('Y-m-d');
                                break;
                            case 'mes_passado':
                                $dataInicio = now()->subMonth()->startOfMonth()->format('Y-m-d');
                                $dataFim = now()->subMonth()->endOfMonth()->format('Y-m-d');
                                break;
                            default:
                                return $query;
                        }

                        // Atualiza o filtro de período personalizado
                        $this->tableFilters['periodo'] = [
                            'data_inicio' => $dataInicio,
                            'data_fim' => $dataFim,
                        ];

                        return $query;
                    }),
            ])
            ->defaultSort('tarefas_realizadas_count', 'desc')
            ->paginated(false); // Relatórios geralmente não são paginados
    }
}