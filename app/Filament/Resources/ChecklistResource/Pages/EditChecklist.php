<?php

namespace App\Filament\Resources\ChecklistResource\Pages;

use App\Filament\Resources\ChecklistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChecklist extends EditRecord
{
    protected static string $resource = ChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
