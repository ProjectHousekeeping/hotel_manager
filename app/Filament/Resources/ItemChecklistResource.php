<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemChecklistResource\Pages;
use App\Filament\Resources\ItemChecklistResource\RelationManagers;
use App\Models\ItemChecklist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemChecklistResource extends Resource
{
    protected static ?string $model = ItemChecklist::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string  $navigationGroup = 'Configurações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descricao')
                    ->label('Descrição do Item:')
                    ->required()
                    ->maxLength(255)
                    ->default(null),

                 Forms\Components\Select::make('checklist_id')
                    ->label('Selecione o Checklist:')
                    ->relationship('checklist', 'nome') // Mostra o campo "nome" do model Checklist
                    ->searchable()
                    ->required(),

 //               Forms\Components\TextInput::make('checklist_id')
 //                   ->required()
 //                   ->numeric()
 //                   ->default(null),

            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descricao')
                    ->searchable()
                    ->label('Descrição'),

                Tables\Columns\TextColumn::make('checklist.nome')
                    ->label('Checklist')
                    ->sortable()
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
            'index' => Pages\ListItemChecklists::route('/'),
            'create' => Pages\CreateItemChecklist::route('/create'),
            'edit' => Pages\EditItemChecklist::route('/{record}/edit'),
        ];
    }
}
