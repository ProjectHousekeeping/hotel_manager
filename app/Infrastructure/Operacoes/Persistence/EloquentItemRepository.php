<?php

namespace App\Infrastructure\Operacoes\Persistence;

use App\Domain\Operacoes\Repositories\ItemRepositoryInterface;
use App\Models\Item;

/**
 * Implementação Eloquent da porta de Item (Fase 1 do Plano de Evolução).
 */
class EloquentItemRepository implements ItemRepositoryInterface
{
    public function buscarPorId(int $id): ?Item
    {
        return Item::find($id);
    }

    public function salvar(Item $item): Item
    {
        $item->save();

        return $item;
    }
}
