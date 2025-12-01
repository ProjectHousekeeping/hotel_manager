<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoTarefaResource\Pages;
use App\Models\TipoTarefa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\View;

class TipoTarefaResource extends Resource
{
    protected static ?string $model = TipoTarefa::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationGroup = 'Configurações';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('desc_tipo_tarefa')
                ->maxLength(255)
                ->label('Descrição tipo tarefa:')
                ->required()
                ->default(null),

            Forms\Components\Select::make('checklist_id')
                ->label('Selecione o Checklist:')
                ->relationship('checklist', 'nome')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Status:')
                ->options([
                    'ativo' => 'Ativo',
                    'inativo' => 'Inativo',
                ])
                ->default('ativo')
                ->required(),

            // 👇 Aqui vem a seção que lista os itens do checklist
            Forms\Components\Section::make('Itens do Checklist')
                ->description('Itens vinculados ao checklist selecionado.')
                ->schema(function (?Model $record) {
                    if (! $record || ! $record->checklist) {
                        return [
                            Forms\Components\View::make('filament.partials.tipo-tarefa-itens')
                                ->viewData(['itens' => collect()]),
                        ];
                    }

                    return [
                        Forms\Components\View::make('filament.partials.tipo-tarefa-itens')
                            ->viewData([
                                'itens' => $record->checklist->itensDoChecklist,
                            ]),
                    ];
                })
                ->hidden(fn (?Model $record) => $record === null),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('desc_tipo_tarefa')
                    ->label('Descrição')
                    ->searchable(),

                Tables\Columns\TextColumn::make('checklist.nome')
                    ->label('Checklist')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
