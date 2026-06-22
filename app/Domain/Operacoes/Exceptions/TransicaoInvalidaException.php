<?php

namespace App\Domain\Operacoes\Exceptions;

use App\Domain\Operacoes\Enums\SituacaoQuarto;
use DomainException;

/**
 * Lançada quando se tenta mudar a situação de um quarto por uma transição
 * não permitida pela máquina de estados (SituacaoQuarto).
 */
class TransicaoInvalidaException extends DomainException
{
    public static function entre(SituacaoQuarto $origem, SituacaoQuarto $destino): self
    {
        return new self(sprintf(
            'Transição inválida: não é possível mudar de "%s" para "%s".',
            $origem->rotulo(),
            $destino->rotulo(),
        ));
    }
}
