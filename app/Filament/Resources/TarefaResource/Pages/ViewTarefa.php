<?php

namespace App\Filament\Resources\TarefaResource\Pages;

use App\Filament\Resources\TarefaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;

use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;

class ViewTarefa extends ViewRecord
{
    use WithRecordNavigation;
    protected static string $resource = TarefaResource::class;

        protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label("Editar"),
            PreviousRecordAction::make(),
            NextRecordAction::make(),
        ];
    }
}
