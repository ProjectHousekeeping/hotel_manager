<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;


use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;

use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;
class ViewItem extends ViewRecord
{

    use WithRecordNavigation;

    protected static string $resource = ItemResource::class;

        protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label("Editar"),
            PreviousRecordAction::make(),
            NextRecordAction::make(),
        ];
    }
}
