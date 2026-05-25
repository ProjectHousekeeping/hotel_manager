<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuartoResource\Pages;
use App\Filament\Resources\QuartoResource\RelationManagers;
use App\Models\Quarto;
use DomainException;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class QuartoResource extends Resource
{
    protected static ?string $model = Quarto::class;

    protected static ?string  $navigationIcon = 'heroicon-o-key';

    protected static ?string  $navigationGroup = 'Gerenciamento';

    protected static ?string $modelLabel = 'Quarto';

    protected static ?string $pluralModelLabel = 'Quartos';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        if ($user?->isOperacional()) {
            $query->whereHas('tarefas', fn (Builder $q) => $q->where('user_id', $user->id));
        }

        return $query;
    }

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
                    ->datalist(['Standard', 'Deluxe', 'Suíte']),
                Forms\Components\TextInput::make('valor_diaria')
                    ->label('Valor Diária:')
                    ->required()
                    ->numeric()
                    ->prefix('R$'),
                Forms\Components\Select::make('situacao')
                    ->label('Situação:')
                    ->required()
                    ->options(Quarto::SITUACOES)
                    ->default(Quarto::SITUACAO_DISPONIVEL)
                    ->native(false),
            ]);
    }

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
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('situacao')
                    ->label('Situação')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Quarto::SITUACOES[$state] ?? $state)
                    ->color(fn (string $state): string => self::corDaSituacao($state)),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('situacao')
                    ->label('Situação')
                    ->options(Quarto::SITUACOES),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make(
                    self::acoesDeTransicao()
                )
                    ->label('Alterar situação')
                    ->icon('heroicon-o-arrow-path')
                    ->button()
                    ->color('warning'),
                Tables\Actions\ViewAction::make()->label('Visualizar'),
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
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
                            ->formatStateUsing(fn (string $state): string => Quarto::SITUACOES[$state] ?? $state)
                            ->color(fn (string $state): string => self::corDaSituacao($state)),
                    ]),
            ]);
    }

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

    protected static function corDaSituacao(string $situacao): string
    {
        return match ($situacao) {
            Quarto::SITUACAO_DISPONIVEL => 'success',
            Quarto::SITUACAO_OCUPADO, Quarto::SITUACAO_FECHADO => 'danger',
            Quarto::SITUACAO_LIMPEZA_PENDENTE, Quarto::SITUACAO_MANUTENCAO_PENDENTE => 'warning',
            Quarto::SITUACAO_LIMPEZA_EM_ANDAMENTO, Quarto::SITUACAO_MANUTENCAO_EM_ANDAMENTO => 'info',
            default => 'gray',
        };
    }

    /**
     * @return array<int, Action>
     */
    protected static function acoesDeTransicao(): array
    {
        $labels = [
            Quarto::SITUACAO_OCUPADO => ['Marcar como ocupado', 'heroicon-o-user', 'danger'],
            Quarto::SITUACAO_DISPONIVEL => ['Liberar quarto', 'heroicon-o-check-circle', 'success'],
            Quarto::SITUACAO_LIMPEZA_PENDENTE => ['Abrir para limpeza', 'heroicon-o-sparkles', 'warning'],
            Quarto::SITUACAO_LIMPEZA_EM_ANDAMENTO => ['Iniciar limpeza', 'heroicon-o-play', 'info'],
            Quarto::SITUACAO_MANUTENCAO_PENDENTE => ['Abrir manutenção', 'heroicon-o-wrench-screwdriver', 'warning'],
            Quarto::SITUACAO_MANUTENCAO_EM_ANDAMENTO => ['Iniciar manutenção', 'heroicon-o-cog-6-tooth', 'info'],
            Quarto::SITUACAO_FECHADO => ['Fechar quarto', 'heroicon-o-lock-closed', 'danger'],
        ];

        $acoes = [];

        foreach ($labels as $destino => [$label, $icon, $color]) {
            $acoes[] = Action::make("transitar_{$destino}")
                ->label($label)
                ->icon($icon)
                ->color($color)
                ->requiresConfirmation()
                ->modalHeading($label)
                ->modalDescription(fn (Quarto $record): string =>
                    "Mudar situação do quarto {$record->numero} para '" . (Quarto::SITUACOES[$destino] ?? $destino) . "'?"
                )
                ->visible(fn (Quarto $record): bool => $record->podeTransitarPara($destino))
                ->action(function (Quarto $record) use ($destino, $label): void {
                    try {
                        $record->transitarPara($destino);
                        Notification::make()
                            ->title("Quarto {$record->numero}: {$label}")
                            ->success()
                            ->send();
                    } catch (DomainException $e) {
                        Notification::make()
                            ->title('Transição inválida')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                });
        }

        return $acoes;
    }
}
