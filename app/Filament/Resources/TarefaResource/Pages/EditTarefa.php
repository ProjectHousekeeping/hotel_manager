<?php

namespace App\Filament\Resources\TarefaResource\Pages;

use App\Filament\Resources\TarefaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTarefa extends EditRecord
{
    protected static string $resource = TarefaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
