<?php

namespace App\Filament\Resources\QuartoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItensRelationManager extends RelationManager
{
    protected static string $relationship = 'itens';
        
    // Monta o Formulário para criar/editar um item DENTRO do quarto
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->label('Nome:')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('preco')
                    ->label('Valor:')
                    ->required()
                    ->numeric()
                    ->prefix('R$'),
                Forms\Components\TextInput::make('quantidade')
                    ->label('Quantidade:')
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }

    // Monta a Tabela que lista os itens do quarto selecionado
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nome')
            ->columns([
                Tables\Columns\TextColumn::make('nome'),
                Tables\Columns\TextColumn::make('preco')
                    ->label('Valor')
                    ->money('BRL'),
                Tables\Columns\TextColumn::make('quantidade'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(), // Botão "+ Novo Item"
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