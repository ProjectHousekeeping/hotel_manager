<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoTarefaResource\Pages;
use App\Models\TipoTarefa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\View;

class TipoTarefaResource extends Resource
{
    protected static ?string $model = TipoTarefa::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationGroup = 'Configurações';


//monta o formulário e cadastro do tipo de tarefa
    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('desc_tipo_tarefa')
                ->maxLength(255)
                ->label('Descrição tipo tarefa:')
                ->required(),

            Forms\Components\Select::make('checklist_id')
                ->label('Selecione o Checklist:')
                ->relationship('checklist', 'nome')
                ->searchable()
                ->preload()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                     $itens = \App\Models\Checklist::find($state)?->itensDoChecklist ?? collect();

                    $html = view('filament.partials.tipo-tarefa-itens', [
                        'itens' => $itens,
                    ])->render();

                        $set('itens_html', $html);
                }),

            Forms\Components\Select::make('status')
                ->label('Status:')
                ->options([
                    'ativo' => 'Ativo',
                    'inativo' => 'Inativo',
                ])
                ->default('ativo')
                ->required(),

Forms\Components\Section::make('Itens do Checklist')
    ->schema([
        Forms\Components\View::make('filament.partials.tipo-tarefa-itens')
            ->viewData(function (callable $get) {
                $checklistId = $get('checklist_id');

                if (! $checklistId) {
                    return ['itens' => collect()];
                }

                $checklist = \App\Models\Checklist::find($checklistId);

                return [
                    'itens' => $checklist?->itensDoChecklist ?? collect(),
                ];
            })
            ->columnSpan('full'),
    ])
                    ]);
    }

// Monta a tabela com a lista de Tipos de Tarefas já cadastradas no sistema
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('desc_tipo_tarefa')
                    ->label('Descrição')
                    ->searchable(),

                Tables\Columns\TextColumn::make('checklist.nome')
                    ->label('Checklist')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label("Excluir"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTipoTarefas::route('/'),
            'create' => Pages\CreateTipoTarefa::route('/create'),
            'edit' => Pages\EditTipoTarefa::route('/{record}/edit'),
        ];
    }
}
