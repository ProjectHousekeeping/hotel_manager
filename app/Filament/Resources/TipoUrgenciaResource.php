<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoUrgenciaResource\Pages;
use App\Filament\Resources\TipoUrgenciaResource\RelationManagers;
use App\Models\TipoUrgencia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoUrgenciaResource extends Resource
{
    protected static ?string $model = TipoUrgencia::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationGroup = 'Configurações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tipo_urgencia_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('classificacao_urgencia')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListTipoUrgencias::route('/'),
            'create' => Pages\CreateTipoUrgencia::route('/create'),
            'view' => Pages\ViewTipoUrgencia::route('/{record}'),
            'edit' => Pages\EditTipoUrgencia::route('/{record}/edit'),
        ];
    }
}
