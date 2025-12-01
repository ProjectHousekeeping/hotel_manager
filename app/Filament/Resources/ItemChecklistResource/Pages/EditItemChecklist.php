<?php

namespace App\Filament\Resources\ItemChecklistResource\Pages;

use App\Filament\Resources\ItemChecklistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemChecklist extends EditRecord
{
    protected static string $resource = ItemChecklistResource::class;

    /**
     * Título personalizado da página
     */
 //   protected function getTitle(): string
   // {
     //   return 'Editar Item do Checklist';
   // }
    
    /**
     * Ações do header
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

}
