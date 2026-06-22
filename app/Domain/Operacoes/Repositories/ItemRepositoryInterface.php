<?php

namespace App\Domain\Operacoes\Repositories;

use App\Models\Item;

/**
 * Porta de acesso a dados de Item (P1 — Repository).
 *
 * Isola as queries Eloquent de itens de frigobar/consumo que antes viviam
 * acopladas ao ItensRelationManager Filament (smell CLS-03).
 */
interface ItemRepositoryInterface
{
    public function buscarPorId(int $id): ?Item;

    /**
     * Persiste o estado atual do item.
     */
    public function salvar(Item $item): Item;
}
