<?php

namespace App\Filament\Resources\TipoUrgenciaResource\Pages;

use App\Filament\Resources\TipoUrgenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoUrgencias extends ListRecords
{
    protected static string $resource = TipoUrgenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
