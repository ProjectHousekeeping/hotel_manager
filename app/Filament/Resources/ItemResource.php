<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use BackedEnum;
use UnitEnum;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube'; // Ícone de cubo

    protected static ?string  $navigationGroup = 'Inventário'; // Grupo no menu lateral

    protected static ?string $modelLabel = 'Item';

    protected static ?string $pluralModelLabel = 'Itens';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('quarto_id')
                    ->relationship('quarto', 'numero') // Relaciona com o quarto pelo número
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('marca')
                    ->maxLength(255),
                Forms\Components\TextInput::make('preco')
                    ->required()
                    ->numeric()
                    ->prefix('R$'),
                Forms\Components\TextInput::make('quantidade')
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quarto.numero') // Exibe o número do quarto
                    ->label('Quarto Nº')
                    ->sortable(),
                Tables\Columns\TextColumn::make('preco')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantidade'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label("Visualizar"),
                Tables\Actions\EditAction::make()->label("Editar"),
                Tables\Actions\DeleteAction::make()->label("Excluir"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label("Excluir"),
                ]),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Detalhes do Item')
                ->schema([
                    Infolists\Components\Grid::make(3)
                        ->schema([
                            Infolists\Components\TextEntry::make('quarto.numero')->label('Localizado no Quarto'),
                            Infolists\Components\TextEntry::make('nome'),
                            Infolists\Components\TextEntry::make('marca'),
                            Infolists\Components\TextEntry::make('quantidade'),
                            Infolists\Components\TextEntry::make('preco')->money('BRL'),
                        ])
                ])
        ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
            'view' => Pages\ViewItem::route('/{record}/view'),
        ];
    }
}
