<?php

namespace App\Filament\Resources\QuartoResource\Pages;

use App\Filament\Resources\QuartoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuartos extends ListRecords
{
    protected static string $resource = QuartoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Adicionar"),
        ];
    }
}
