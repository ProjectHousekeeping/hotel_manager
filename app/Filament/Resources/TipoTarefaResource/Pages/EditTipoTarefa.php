<?php

namespace App\Filament\Resources\TipoTarefaResource\Pages;

use App\Filament\Resources\TipoTarefaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoTarefa extends EditRecord
{
    protected static string $resource = TipoTarefaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
