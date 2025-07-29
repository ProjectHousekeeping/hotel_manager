<?php

namespace App\Filament\Resources\CargoResource\Pages;

use App\Filament\Resources\CargoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;

use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;

class ViewCargo extends ViewRecord
{
    use WithRecordNavigation;
    protected static string $resource = CargoResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label("Editar"),
            PreviousRecordAction::make(),
            NextRecordAction::make(),
            // ... your other actions
        ];
    }
}
