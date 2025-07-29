<?php

namespace App\Filament\Resources\QuartoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TarefasRelationManager extends RelationManager
{
    protected static string $relationship = 'tarefas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('funcionario_id')
                    ->relationship('funcionario', 'nome')
                    ->required(),
                Forms\Components\Select::make('tipo_tarefa')
                    ->options([
                        'Limpeza' => 'Limpeza',
                        'Manutenção' => 'Manutenção',
                    ])->required(),
                Forms\Components\DatePicker::make('data')->required()->default(now()),
                Forms\Components\TimePicker::make('hora_inicio')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tipo_tarefa')
            ->columns([
                Tables\Columns\TextColumn::make('funcionario.nome'),
                Tables\Columns\BadgeColumn::make('tipo_tarefa'),
                Tables\Columns\TextColumn::make('data')->date('d/m/Y'),
                Tables\Columns\IconColumn::make('hora_fim')->boolean()->label('Finalizada'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}