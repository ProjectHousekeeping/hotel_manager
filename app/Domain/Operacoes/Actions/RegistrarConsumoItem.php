<?php

namespace App\Domain\Operacoes\Actions;

use App\Domain\Operacoes\Exceptions\ConsumoInvalidoException;
use App\Domain\Operacoes\Repositories\ItemRepositoryInterface;
use App\Models\Item;

/**
 * P2 — Service/Action: registro de consumo de um item (frigobar/consumo).
 *
 * Antes inexistente como regra de domínio: o ItensRelationManager apenas
 * editava a quantidade livremente. Agora o consumo é validado (quantidade
 * positiva e dentro do estoque), abate o estoque e calcula o valor total,
 * de forma testável sem Filament (métricas E5/E6/F7).
 */
class RegistrarConsumoItem
{
    public function __construct(
        private readonly ItemRepositoryInterface $itens,
    ) {
    }

    /**
     * @param  Item|int  $item  Modelo ou id do item
     * @param  int  $quantidade  Quantidade consumida (> 0)
     *
     * @throws ConsumoInvalidoException
     * @throws \InvalidArgumentException quando o item não é encontrado
     */
    public function executar(Item|int $item, int $quantidade): ConsumoRegistrado
    {
        $item = $this->resolverItem($item);

        if ($quantidade <= 0) {
            throw ConsumoInvalidoException::quantidadeNaoPositiva($quantidade);
        }

        $disponivel = (int) $item->quantidade;

        if ($quantidade > $disponivel) {
            throw ConsumoInvalidoException::estoqueInsuficiente($quantidade, $disponivel);
        }

        $restante = $disponivel - $quantidade;
        $item->quantidade = $restante;
        $this->itens->salvar($item);

        return new ConsumoRegistrado(
            itemId: (int) $item->id,
            quantidadeConsumida: $quantidade,
            quantidadeRestante: $restante,
            valorTotal: round((float) $item->preco * $quantidade, 2),
        );
    }

    private function resolverItem(Item|int $item): Item
    {
        if ($item instanceof Item) {
            return $item;
        }

        $encontrado = $this->itens->buscarPorId($item);

        if ($encontrado === null) {
            throw new \InvalidArgumentException("Item {$item} não encontrado.");
        }

        return $encontrado;
    }
}
