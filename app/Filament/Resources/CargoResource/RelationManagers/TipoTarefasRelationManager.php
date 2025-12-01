<?php

namespace App\Filament\Resources\CargoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoTarefasRelationManager extends RelationManager
{
    protected static string $relationship = 'tipoTarefas';
    //public function tipoTarefas() Model Cargo
    protected static ?string $title = 'Tipos de Tarefa';
    protected static ?string $recordTitleAttribute = 'desc_tipo_tarefa';


    /*
    * Monta o formulário de cadastro
    */
    public function form(Form $form): Form
    {
        return $form
            
            ->schema([
            
            ]);
    }

    /*
    * Monta o tabela com a lista de Tarefas vinculadas ao cargo
    */
    public function table(Table $table): Table
    {
        return $table
           
             ->columns([
                Tables\Columns\TextColumn::make('desc_tipo_tarefa')
                    ->label('Descrição'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                 Tables\Actions\AttachAction::make()
                    ->label('Vincular Tipo de Tarefa')
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(function ($query) {
                        return $query->orderBy('desc_tipo_tarefa');// importante
                    })
            ])
            ->actions([
                 Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
