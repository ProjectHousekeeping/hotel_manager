<?php

namespace App\Filament\Resources\QuartoResource\Pages;

use App\Filament\Resources\QuartoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;

use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;


class ViewQuarto extends ViewRecord
{
    use WithRecordNavigation;
    protected static string $resource = QuartoResource::class;

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
