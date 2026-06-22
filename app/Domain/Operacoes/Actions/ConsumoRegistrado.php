<?php

namespace App\Domain\Operacoes\Actions;

/**
 * Resultado imutável do registro de consumo de um item.
 */
final readonly class ConsumoRegistrado
{
    public function __construct(
        public int $itemId,
        public int $quantidadeConsumida,
        public int $quantidadeRestante,
        public float $valorTotal,
    ) {
    }
}
