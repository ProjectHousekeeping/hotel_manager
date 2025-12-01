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

    // retorno o formulario de cadastro do quarto
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('numero')
                    ->required()
                    ->label('Número:')
                    ->numeric()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('tipo')
                    ->required()
                    ->label('Tipo:')
                    ->maxLength(255)
                    ->datalist(['Standard', 'Deluxe', 'Suíte']), // Sugestões de tipo
                Forms\Components\TextInput::make('valor_diaria')
                    ->label('Valor Diária:')
                    ->required()
                    ->numeric()
                    ->prefix('R$'),
                Forms\Components\Select::make('situacao')
                    ->label('Situação:')
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

    //monta a tabela com a lista de quartos
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')
                    ->label('Número')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('valor_diaria')
                    ->label('R$ Diária')
                    ->money('BRL') // Formata como moeda brasileira
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('situacao') // Badge é mais visual
                    ->label('Situação')
                    ->colors([
                        'success' => 'Disponível',
                        'danger' => 'Ocupado',
                        'warning' => fn($state) => in_array($state, ['limpeza_em_andamento', 'manutencao_em_andamento']),
                        'gray' => 'Finalizada',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label("Visualizar"),
                Tables\Actions\EditAction::make()
                    ->label("Editar"),
                Tables\Actions\DeleteAction::make()
                    ->label("Excluir"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label("Excluir"),
                ]),
            ]);
    }

    // monta a interface com os dados do quarto (visualizar)
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detalhes do Quarto')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('numero')
                                    ->label('Número'),
                                Infolists\Components\TextEntry::make('tipo'),
                                Infolists\Components\TextEntry::make('valor_diaria')
                                    ->label('R$ Diária')
                                    ->money('BRL'),
                            ]),
                        Infolists\Components\TextEntry::make('situacao')
                            ->label('Situação')
                            ->badge()
                            ->colors([
                                'success' => 'Disponível',
                                'danger' => 'Ocupado',
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
