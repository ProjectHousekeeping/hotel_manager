<?php

namespace App\Filament\Resources\CargoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Usuários vinculados ao cargo';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nome:')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail:')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cpf')
                    ->label('CPF:')
                    ->mask('999.999.999-99')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('Senha:')
                    ->password()
                    ->dehydrated(fn ($state, string $context): bool => $context === 'create' || filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('situacao')
                    ->label('Situação')
                    ->color(fn (string $state): string => match ($state) {
                        'disponivel' => 'success',
                        'ocupado' => 'warning',
                        'ferias' => 'info',
                        'afastado' => 'danger',
                        'inativo' => 'gray',
                        default => 'secondary',
                    })
                    ->badge(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Novo Usuário')
                    ->modalHeading('Criar Usuário')
                    ->modalButton('Salvar Usuário'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}