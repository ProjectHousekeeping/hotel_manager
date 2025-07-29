<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

use Nben\FilamentRecordNav\Actions\NextRecordAction;
use Nben\FilamentRecordNav\Actions\PreviousRecordAction;

use Nben\FilamentRecordNav\Concerns\WithRecordNavigation;

class ViewUser extends ViewRecord
{

    use WithRecordNavigation;
    protected static string $resource = UserResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label("Editar"),
            PreviousRecordAction::make(),
            NextRecordAction::make(),
        ];
    }
}
