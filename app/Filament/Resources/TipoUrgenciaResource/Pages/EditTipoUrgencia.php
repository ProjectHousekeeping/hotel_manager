<?php

namespace App\Filament\Resources\TipoUrgenciaResource\Pages;

use App\Filament\Resources\TipoUrgenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoUrgencia extends EditRecord
{
    protected static string $resource = TipoUrgenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
