<?php

namespace Tests\Support\Fakes;

use App\Domain\Operacoes\Repositories\ItemRepositoryInterface;
use App\Models\Item;

/**
 * Repositório de Item em memória, para testar a Action de consumo sem banco
 * de dados nem Filament.
 */
class FakeItemRepository implements ItemRepositoryInterface
{
    /** @var array<int, Item> */
    private array $porId = [];

    /** @var array<int, Item> Itens passados a salvar(), na ordem. */
    public array $salvos = [];

    /**
     * @param  array<int, Item>  $itens
     */
    public function __construct(array $itens = [])
    {
        foreach ($itens as $item) {
            if ($item->id !== null) {
                $this->porId[(int) $item->id] = $item;
            }
        }
    }

    public function buscarPorId(int $id): ?Item
    {
        return $this->porId[$id] ?? null;
    }

    public function salvar(Item $item): Item
    {
        $this->salvos[] = $item;

        if ($item->id !== null) {
            $this->porId[(int) $item->id] = $item;
        }

        return $item;
    }
}
