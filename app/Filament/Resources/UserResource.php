<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string  $navigationGroup = 'Configurações';

    protected static ?string  $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Usuário';

    protected static ?string $pluralModelLabel = 'Usuários';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identificação')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome Completo')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                         Forms\Components\TextInput::make('password')
                            ->label('Senha') // Adicione um label mais claro
                            ->password()
                            ->dehydrated(fn (string $context): bool => $context === 'create' || !empty($state)) 
                            ->required(fn (string $context): bool => $context === 'create'), 
                        Forms\Components\TextInput::make('cpf')->required(),
                        Forms\Components\TextInput::make('telefone'),
                    ])->columns(2),

                Forms\Components\Section::make('Função e Hierarquia')
                    ->schema([
                        Forms\Components\Select::make('cargo_id')
                            ->relationship('cargo', 'nome')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('gerente_id')
                            ->relationship('gerente', 'name') // Agora relaciona com outros usuários
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('situacao')
                            ->options([
                                'disponivel' => 'Disponível',
                                'ocupado' => 'Ocupado',
                                'ferias' => 'Férias',
                                'afastado' => 'Afastado',
                            ])->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('cargo.nome')->sortable(),
                Tables\Columns\TextColumn::make('situacao')
                    ->colors([
                        'success' => 'disponivel',
                        'danger' => 'afastado',
                        'warning' => 'ferias',
                    ])->badge(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y')->toggleable(isToggledHiddenByDefault: true),
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
                Infolists\Components\Section::make('Informações do Usuário')
                    ->schema([
                        Infolists\Components\Grid::make(2) // Organiza os campos em 2 colunas
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Nome'), // Customiza o rótulo do campo

                                Infolists\Components\TextEntry::make('email'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Metadados')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\IconEntry::make('email_verified_at')
                                    ->label('Email Verificado')
                                    ->boolean() // Mostra um ícone de certo/errado
                                    ->trueIcon('heroicon-o-check-badge')
                                    ->falseIcon('heroicon-o-x-circle'),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Data de Criação')
                                    ->dateTime('d/m/Y H:i:s'), // Formata a data e hora
                            ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}/view'),
        ];
    }
}
