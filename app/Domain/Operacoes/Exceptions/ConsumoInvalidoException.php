<?php

namespace App\Domain\Operacoes\Exceptions;

use DomainException;

/**
 * Lançada quando o registro de consumo de um item é inválido
 * (quantidade não positiva ou maior que o estoque disponível).
 */
class ConsumoInvalidoException extends DomainException
{
    public static function quantidadeNaoPositiva(int $quantidade): self
    {
        return new self(sprintf(
            'A quantidade consumida deve ser maior que zero; recebido %d.',
            $quantidade,
        ));
    }

    public static function estoqueInsuficiente(int $solicitado, int $disponivel): self
    {
        return new self(sprintf(
            'Consumo de %d excede o estoque disponível (%d).',
            $solicitado,
            $disponivel,
        ));
    }
}
