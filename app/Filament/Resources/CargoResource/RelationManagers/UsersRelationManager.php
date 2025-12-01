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

    //incluido para arrumar o titulo da tabela usuários
    protected static ?string $title = 'Usuários vinculados ao cargo';

    // Formulário para criar/editar um funcionário a partir desta relação
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
                    ->label('Senha:') // Adicione um label mais claro
                    ->password()
                    ->dehydrated(fn (string $context): bool => $context === 'create' || !empty($state))
                    ->required(fn (string $context): bool => $context === 'create'),
            ]);
    }

    // Monta a tabela que lista os usuários associados ao cargo
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
                Tables\Columns\BadgeColumn::make('situacao')
                    ->label('Situação')
                    ->colors([
                        'success' => 'Disponível',
                        'danger' => 'Afastado',
                        'warning' => 'Férias',
                    ]),
            ])
            ->filters([
                // Pode adicionar filtros aqui se desejar
            ])
            ->headerActions([
                // Botão para criar um novo funcionário já associado a este cargo
                Tables\Actions\CreateAction::make()
                    ->label('Novo Usuário')
                    ->modalHeading('Criar Usuário')    // título do modal
                    ->modalButton('Salvar Usuário'),   // texto do botão de confirmação,
            ])
            ->actions([
                // Ações para cada linha da tabela
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