<?php

namespace App\Filament\Resources;

use App\Filament\Enums\NavigationGroup;
use App\Filament\Resources\CargoResource\Pages;
use App\Models\Cargo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use App\Filament\Resources\CargoResource\RelationManagers;


class CargoResource extends Resource
{
    protected static ?string $model = Cargo::class;

    protected static ?string  $navigationGroup = 'Configurações'; // Agrupa no menu lateral

    protected static ?string  $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string  $activeNavigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Cargo';

    protected static ?string $modelLabel = 'Cargo';

    protected static ?string $pluralModelLabel = 'Cargos';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->label('Nome:')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true), // Garante que o nome seja único
            ]);
    }

    public static function table(Table $table): Table
    {

        $livewire = $table->getLivewire();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable() // Permite buscar pelo nome
                    ->sortable(), // Permite ordenar
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Coluna oculta por padrão
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
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informações do Cargo')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('nome'),
                                Infolists\Components\TextEntry::make('created_at')->dateTime('d/m/Y H:i')->label('Criado em'),
                            ])
                    ])
            ]);
    }

    public static function getRelations(): array
{
    return [
        // Adicione esta linha para registar o seu novo gestor
        RelationManagers\UsersRelationManager::class,
    ];
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCargos::route('/'),
            'create' => Pages\CreateCargo::route('/create'),
            'edit' => Pages\EditCargo::route('/{record}/edit'),
            'view' => Pages\ViewCargo::route('/{record}/view'),

        ];
    }
}
