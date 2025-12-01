<?php

namespace App\Filament\Resources\ItemChecklistResource\Pages;

use App\Filament\Resources\ItemChecklistResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemChecklists extends ListRecords
{
    protected static string $resource = ItemChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
