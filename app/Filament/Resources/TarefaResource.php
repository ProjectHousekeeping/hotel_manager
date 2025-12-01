<?php

namespace App\Filament\Resources;

use App\Filament\Enums\NavigationGroup;
use App\Filament\Resources\TarefaResource\Pages;
use App\Models\Tarefa;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use BackedEnum;
use Filament\Forms\Components\Tabs\Tab;
use UnitEnum;

class TarefaResource extends Resource
{
    protected static ?string $model = Tarefa::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = "Operações";

    protected static ?string $modelLabel = 'Tarefa';

    protected static ?string $pluralModelLabel = 'Tarefas';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('quarto_id')
                    ->relationship('quarto', 'numero')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Atribuído a')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('tipo_tarefa')
                    ->options([
                        'Limpeza' => 'Limpeza',
                        'Manutenção' => 'Manutenção',
                        'Vistoria' => 'Vistoria',
                    ])
                    ->required(),

                Forms\Components\Select::make('tipo_tarefa_id')
                    ->label('Tipo de Tarefa')
                    ->relationship('tipoTarefa', 'desc_tipo_tarefa')
                    ->searchable(),
                //->required(),
                Forms\Components\Select::make('tipo_urgencia_id')
                    ->label('Tipo Urgência')
                    ->relationship('tipourgencia', 'name')
                    ->preload()
                    ->searchable(),
                Forms\Components\DatePicker::make('data')
                    ->required(),
                Forms\Components\TimePicker::make('hora_inicio')
                    ->required(),
                Forms\Components\TimePicker::make('hora_fim'),
                Forms\Components\Textarea::make('descricao')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quarto.numero')
                    ->label('Quarto Nº')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name') // user é o nome da relação, name é a coluna do modelo User
                    ->label('Atribuído a')
                    ->searchable()
                    ->sortable(), // Permite ordenar por nome do usuário
                Tables\Columns\TextColumn::make('tipo_tarefa')->badge(),

                Tables\Columns\TextColumn::make('tipoTarefa.desc_tipo_tarefa')
                    ->label('Tipo de Tarefa')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipoUrgencia.name')
                    ->label('Tipo Urgência')
                    ->searchable()
                    ->sortable(),


                Tables\Columns\TextColumn::make('data')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('hora_inicio')
                    ->label('Início')
                    ->sortable(),
                Tables\Columns\IconColumn::make('hora_fim')
                    ->label('Finalizada')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('updated_at') // updated_at é o nome da coluna no banco de dados q mantém a ultima atualização do registro
                    ->label('Última Atualização') // Label da coluna q será exibida para o usuario na tabela
                    ->date('d/m/Y H:i') // Formato da data / data e hora
                    ->sortable() // Permite ordenar por data de atualização
                    ->toggleable() // Permite alternar a visibilidade da coluna
            ])
            ->filters([
                //
            ])
            ->actions([
                // AÇÃO PARA CONCLUIR A TAREFA
                Action::make('concluir')
                    ->label('Concluir') // O texto que aparece no botão
                    ->icon('heroicon-o-check-circle') // Ícone de check
                    ->color('success') // Cor verde
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-exclamation-triangle') // Ícone de aviso
                    ->modalIconColor('warning') // Cor do ícone (amarelo)
                    ->modalHeading('Confirmar Término da Tarefa') // Título do pop-up
                    ->modalDescription('Deseja confirmar o término da tarefa? Esta ação registrará o horário atual e não poderá ser desfeita.') // A sua mensagem
                    ->modalSubmitActionLabel('Sim, confirmar') // Texto do botão de confirmação // Pede confirmação antes de executar
                    ->action(function ($record) {
                        // A mágica acontece aqui: atualiza o campo com a hora atual
                        $record->update([
                            'hora_fim' => Carbon::now()
                        ]);
                    })
                    // O botão só aparece se a tarefa ainda não foi concluída
                    ->visible(fn($record) => is_null($record->hora_fim)),
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
                Infolists\Components\Section::make('Detalhes da Tarefa')
                    ->schema([
                        Infolists\Components\Grid::make(2)->schema([
                            Infolists\Components\TextEntry::make('quarto.numero')->label('Quarto'),

                            Infolists\Components\TextEntry::make('user.name')->label('Funcionário Responsável'),

                            Infolists\Components\TextEntry::make('data')->date('d/m/Y'),
                            Infolists\Components\TextEntry::make('tipo_tarefa')->badge(),
                            Infolists\Components\IconEntry::make('hora_fim')
                                ->label('Status')
                                ->boolean()
                                ->trueIcon('heroicon-o-check-badge')
                                ->trueColor('success')
                                ->falseIcon('heroicon-o-x-circle')
                                ->falseColor('warning'),
                            Infolists\Components\TextEntry::make('hora_fim')->time('H:i')->label('Horário de Conclusão'),
                        ]),
                        Infolists\Components\TextEntry::make('descricao')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTarefas::route('/'),
            'create' => Pages\CreateTarefa::route('/create'),
            'edit' => Pages\EditTarefa::route('/{record}/edit'),
            'view' => Pages\ViewTarefa::route('/{record}/view'),
        ];
    }
}
