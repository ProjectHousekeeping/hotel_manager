<?php

namespace App\Infrastructure\Operacoes\Persistence;

use App\Domain\Operacoes\Repositories\QuartoRepositoryInterface;
use App\Models\Quarto;

/**
 * Implementação Eloquent da porta de Quarto (Fase 1 do Plano de Evolução).
 */
class EloquentQuartoRepository implements QuartoRepositoryInterface
{
    public function buscarPorId(int $id): ?Quarto
    {
        return Quarto::find($id);
    }

    public function salvar(Quarto $quarto): Quarto
    {
        $quarto->save();

        return $quarto;
    }
}
