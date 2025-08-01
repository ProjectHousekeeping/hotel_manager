<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelatorioResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RelatoriosResource extends Resource
{
    /**
     * Como este Resource é apenas um agrupador de relatórios,
     * não o associamos a nenhum Model.
     */
    protected static ?string $model = null;

    /**
     * Define o ícone que aparecerá no menu.
     */
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    /**
     * Define o nome que aparecerá no menu.
     */
    protected static ?string $navigationLabel = 'Relatórios';

    /**
     * Define o slug (parte da URL) para este recurso.
     */
    protected static ?string $slug = 'relatorios';


    /**
     * Este é o método mais importante aqui.
     * Ele registra sua página de relatório como a página principal ("index")
     * deste Resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\RelatorioFuncionarios::route('/'),
        ];
    }
}