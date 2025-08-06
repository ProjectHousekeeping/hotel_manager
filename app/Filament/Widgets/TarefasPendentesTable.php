<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TarefaResource;
use App\Models\Tarefa;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TarefasPendentesTable extends BaseWidget
{
    protected static ?int $sort = 4;


        protected static ?string $maxHeight = '300px';


    public function table(Table $table): Table
    {
        return $table
            ->query(
                Tarefa::query()->whereNull('hora_fim')->latest('data')->limit(5)
            )
            ->heading('Últimas Tarefas Pendentes')
            ->columns([
                Tables\Columns\TextColumn::make('quarto.numero')->label('Quarto Nº'),
                Tables\Columns\TextColumn::make('user.name')->label('Atribuído a'),
                Tables\Columns\TextColumn::make('tipo_tarefa')->badge(),
                Tables\Columns\TextColumn::make('data')->date('d/m/Y'),
            ])
            ->actions([
                Tables\Actions\Action::make('Ver')
                    ->url(fn (Tarefa $record): string => TarefaResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-m-arrow-top-right-on-square'),
            ]);
    }
}