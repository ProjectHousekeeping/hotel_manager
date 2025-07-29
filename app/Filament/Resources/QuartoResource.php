<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuartoResource\Pages;
use App\Filament\Resources\QuartoResource\RelationManagers;
use App\Models\Quarto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;


class QuartoResource extends Resource
{
    protected static ?string $model = Quarto::class;

    protected static ?string  $navigationIcon = 'heroicon-o-key'; // Ícone de chave

    protected static ?string  $navigationGroup = 'Gerenciamento'; // Grupo no menu lateral

    protected static ?string $modelLabel = 'Quarto';

    protected static ?string $pluralModelLabel = 'Quartos';


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('numero')
                    ->required()
                    ->numeric()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('tipo')
                    ->required()
                    ->maxLength(255)
                    ->datalist(['Standard', 'Deluxe', 'Suíte']), // Sugestões de tipo
                Forms\Components\TextInput::make('valor_diaria')
                    ->required()
                    ->numeric()
                    ->prefix('R$'),
                Forms\Components\Select::make('situacao')
                    ->required()
                    ->options([ // Opções baseadas na migration
                        'disponivel' => 'Disponível',
                        'ocupado' => 'Ocupado',
                        'limpeza_em_andamento' => 'Em Limpeza',
                        'manutencao_em_andamento' => 'Em Manutenção',
                        'finalizada' => 'Finalizada',
                        'pedido_encaminhado' => 'Pedido Encaminhado',
                    ])
                    ->native(false), // Para melhor visual
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')->sortable(),
                Tables\Columns\TextColumn::make('tipo')->searchable(),
                Tables\Columns\TextColumn::make('valor_diaria')
                    ->money('BRL') // Formata como moeda brasileira
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('situacao') // Badge é mais visual
                    ->colors([
                        'success' => 'disponivel',
                        'danger' => 'ocupado',
                        'warning' => fn($state) => in_array($state, ['limpeza_em_andamento', 'manutencao_em_andamento']),
                        'gray' => 'finalizada',
                    ]),
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
                Infolists\Components\Section::make('Detalhes do Quarto')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('numero'),
                                Infolists\Components\TextEntry::make('tipo'),
                                Infolists\Components\TextEntry::make('valor_diaria')
                                    ->money('BRL'),
                            ]),
                        Infolists\Components\TextEntry::make('situacao')
                            ->badge()
                            ->colors([
                                'success' => 'disponivel',
                                'danger' => 'ocupado',
                                'warning' => fn($state) => in_array($state, ['limpeza_em_andamento', 'manutencao_em_andamento']),
                            ]),
                    ]),
            ]);
    }

    // Adiciona abas para os relacionamentos
    public static function getRelations(): array
    {
        return [
            RelationManagers\ItensRelationManager::class,
            RelationManagers\TarefasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuartos::route('/'),
            'create' => Pages\CreateQuarto::route('/create'),
            'edit' => Pages\EditQuarto::route('/{record}/edit'),
            'view' => Pages\ViewQuarto::route('/{record}/view'),
        ];
    }
}
