<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoTarefaResource\Pages;
use App\Filament\Resources\TipoTarefaResource\RelationManagers;
use App\Models\TipoTarefa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoTarefaResource extends Resource
{
    protected static ?string $model = TipoTarefa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('desc_tipo_tarefa')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('checklist_id')
                    ->label('Selecione o Checklist:')
                    ->relationship('checklist', 'nome') // Mostra o campo "nome" do model Checklist
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'ativo' => 'Ativo',
                        'inativo' => 'Inativo',
                                            ])
                    ->default('ativo')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('desc_tipo_tarefa')
                    ->searchable(),
                //Tables\Columns\TextColumn::make('checklist_id')
                  //  ->numeric()
                   // ->sortable(),
                Tables\Columns\TextColumn::make('checklist.nome')
                    ->label('Checklist')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTipoTarefas::route('/'),
            'create' => Pages\CreateTipoTarefa::route('/create'),
            'edit' => Pages\EditTipoTarefa::route('/{record}/edit'),
        ];
    }
}
