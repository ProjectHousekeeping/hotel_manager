<?php

namespace App\Filament\Resources\QuartoResource\Pages;

use App\Filament\Resources\QuartoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuarto extends EditRecord
{
    protected static string $resource = QuartoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
