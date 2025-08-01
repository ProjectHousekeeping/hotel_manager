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
use Illuminate\Support\Facades\DB; // Importar DB Facade

class RelatorioFuncionarios extends Page implements HasTable
{
    use InteractsWithTable; // Usar o trait

    protected static string $resource = UserResource::class;

    // protected static string $view = 'filament.resources.user-resource.pages.relatorio-Funcionarios';
    // CORRETO
    protected static string $view = 'filament.resources.relatorios-resource.pages.relatorio-funcionarios';

    // Título da Página
    protected static ?string $title = 'Relatório de Funcionarios';

    // Ícone da Navegação
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    // Label da Navegação
    // protected string | \Illuminate\Contracts\Support\Htmlable $navigationLabel = 'Relatório de Desempenho';

    // CORRETO
    protected static ?string $navigationLabel = 'Relatório de Funcionarios';

    // Ordem no menu
    protected static ?int $navigationSort = 3;


    /**
     * Define a query que alimenta a tabela.
     * Aqui é onde a "mágica" da compilação de dados acontece.
     */
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return User::select([
            'users.id',
            'users.name'
        ])
            ->selectRaw('(select count(*) from tarefas where users.id = tarefas.user_id) as tarefas_realizadas_count')
            // ->selectRaw('(SELECT AVG(strftime(\'%s\', tarefas.hora_fim) - strftime(\'%s\', tarefas.hora_inicio)) / 60 FROM tarefas WHERE tarefas.user_id = users.id) as media_tempo_minutos')
            ->selectRaw('(SELECT 
                CAST((AVG(strftime(\'%s\', tarefas.hora_fim) - strftime(\'%s\', tarefas.hora_inicio)) / 3600) AS INTEGER) || \'h \' ||
                CAST(((AVG(strftime(\'%s\', tarefas.hora_fim) - strftime(\'%s\', tarefas.hora_inicio)) % 3600) / 60) AS INTEGER) || \'m\'
                FROM tarefas WHERE tarefas.user_id = users.id) as media_tempo_formatado')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('tarefas')
                    ->whereRaw('users.id = tarefas.user_id');
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
            ->paginated(false); // Relatórios geralmente não são paginados
    }
}