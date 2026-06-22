<?php

namespace App\Filament\Resources\QuartoResource\RelationManagers;

use App\Domain\Operacoes\Actions\RegistrarConsumoItem;
use App\Domain\Operacoes\Exceptions\ConsumoInvalidoException;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
                // Fase 3 — thin wrapper: coleta a quantidade e delega o abate
                // de estoque e o cálculo do valor à Action de domínio.
                Tables\Actions\Action::make('registrarConsumo')
                    ->label('Registrar consumo')
                    ->icon('heroicon-o-shopping-cart')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('quantidade')
                            ->label('Quantidade consumida')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1),
                    ])
                    ->action(function (Item $record, array $data): void {
                        try {
                            $resultado = app(RegistrarConsumoItem::class)
                                ->executar($record, (int) $data['quantidade']);

                            Notification::make()
                                ->title('Consumo registrado')
                                ->body(sprintf(
                                    'Consumidas %d un. (R$ %.2f). Estoque restante: %d.',
                                    $resultado->quantidadeConsumida,
                                    $resultado->valorTotal,
                                    $resultado->quantidadeRestante,
                                ))
                                ->success()
                                ->send();
                        } catch (ConsumoInvalidoException $e) {
                            Notification::make()
                                ->title('Consumo inválido')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
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