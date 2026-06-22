<?php

namespace App\Domain\Operacoes\Repositories;

use App\Models\Quarto;

/**
 * Porta de acesso a dados de Quarto (P1 — Repository).
 *
 * Isola as queries Eloquent que antes eram acessadas diretamente nos
 * Resources/RelationManagers Filament (smell CLS-03), tornando o acesso a
 * dados injetável e substituível (por fakes em teste, por outra persistência
 * no futuro).
 */
interface QuartoRepositoryInterface
{
    public function buscarPorId(int $id): ?Quarto;

    /**
     * Persiste o estado atual do quarto.
     */
    public function salvar(Quarto $quarto): Quarto;
}
