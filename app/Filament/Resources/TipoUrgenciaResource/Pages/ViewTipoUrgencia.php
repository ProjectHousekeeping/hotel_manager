<?php

namespace App\Filament\Resources\TipoUrgenciaResource\Pages;

use App\Filament\Resources\TipoUrgenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTipoUrgencia extends ViewRecord
{
    protected static string $resource = TipoUrgenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
