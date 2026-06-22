<?php

namespace Tests\Support\Fakes;

use App\Domain\Operacoes\Repositories\QuartoRepositoryInterface;
use App\Models\Quarto;

/**
 * Repositório de Quarto em memória, para testar as Actions de domínio sem
 * banco de dados nem Filament (evidência das métricas E6 — testável sem Filament).
 */
class FakeQuartoRepository implements QuartoRepositoryInterface
{
    /** @var array<int, Quarto> */
    private array $porId = [];

    /** @var array<int, Quarto> Quartos passados a salvar(), na ordem. */
    public array $salvos = [];

    /**
     * @param  array<int, Quarto>  $quartos
     */
    public function __construct(array $quartos = [])
    {
        foreach ($quartos as $quarto) {
            if ($quarto->id !== null) {
                $this->porId[(int) $quarto->id] = $quarto;
            }
        }
    }

    public function buscarPorId(int $id): ?Quarto
    {
        return $this->porId[$id] ?? null;
    }

    public function salvar(Quarto $quarto): Quarto
    {
        $this->salvos[] = $quarto;

        if ($quarto->id !== null) {
            $this->porId[(int) $quarto->id] = $quarto;
        }

        return $quarto;
    }
}
