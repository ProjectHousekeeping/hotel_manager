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
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoUrgenciaResource extends Resource
{
    protected static ?string $model = TipoUrgencia::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Tipo Urgência';

    protected static ?string $modelLabel = 'Tipo Urgência';
    protected static ?string $navigationGroup = 'Configurações';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tipo_urgencia_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('tarefa_id')
                    ->relationship('tarefa', 'tipo_tarefa')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Visualizar'),
                Tables\Actions\EditAction::make()->label('Editar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Excluir'),
                ]),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informações tipo urgência')
                    ->schema([
                        TextEntry::make('id'),
                        TextEntry::make('name')->label('Nome classificação'),
                    ])->columns(2)
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
