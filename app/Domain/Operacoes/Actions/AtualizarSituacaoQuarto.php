<?php

namespace App\Domain\Operacoes\Actions;

use App\Domain\Operacoes\Enums\SituacaoQuarto;
use App\Domain\Operacoes\Exceptions\TransicaoInvalidaException;
use App\Domain\Operacoes\Repositories\QuartoRepositoryInterface;
use App\Models\Quarto;

/**
 * P2 — Service/Action: transição de situação do quarto.
 *
 * Concentra a regra que antes estava implícita no formulário Filament
 * (qualquer situação podia ser escolhida livremente). Agora a mudança passa
 * pela máquina de estados SituacaoQuarto e é persistida via repositório,
 * ficando testável sem Filament (métricas E5/E6).
 */
class AtualizarSituacaoQuarto
{
    public function __construct(
        private readonly QuartoRepositoryInterface $quartos,
    ) {
    }

    /**
     * @param  Quarto|int  $quarto  Modelo ou id do quarto
     * @param  SituacaoQuarto|string  $novaSituacao
     *
     * @throws TransicaoInvalidaException quando a transição não é permitida
     * @throws \InvalidArgumentException quando o quarto ou a situação são inválidos
     */
    public function executar(Quarto|int $quarto, SituacaoQuarto|string $novaSituacao): Quarto
    {
        $quarto = $this->resolverQuarto($quarto);
        $destino = $this->resolverSituacao($novaSituacao);
        $origem = $this->resolverSituacao($quarto->situacao);

        if ($origem !== $destino && ! $origem->podeTransicionarPara($destino)) {
            throw TransicaoInvalidaException::entre($origem, $destino);
        }

        $quarto->situacao = $destino->value;

        return $this->quartos->salvar($quarto);
    }

    private function resolverQuarto(Quarto|int $quarto): Quarto
    {
        if ($quarto instanceof Quarto) {
            return $quarto;
        }

        $encontrado = $this->quartos->buscarPorId($quarto);

        if ($encontrado === null) {
            throw new \InvalidArgumentException("Quarto {$quarto} não encontrado.");
        }

        return $encontrado;
    }

    private function resolverSituacao(SituacaoQuarto|string $situacao): SituacaoQuarto
    {
        if ($situacao instanceof SituacaoQuarto) {
            return $situacao;
        }

        $resolvida = SituacaoQuarto::tryFrom($situacao);

        if ($resolvida === null) {
            throw new \InvalidArgumentException("Situação inválida: {$situacao}.");
        }

        return $resolvida;
    }
}
