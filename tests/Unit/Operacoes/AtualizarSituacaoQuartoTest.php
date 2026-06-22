<?php

namespace Tests\Unit\Operacoes;

use App\Domain\Operacoes\Actions\AtualizarSituacaoQuarto;
use App\Domain\Operacoes\Enums\SituacaoQuarto;
use App\Domain\Operacoes\Exceptions\TransicaoInvalidaException;
use App\Models\Quarto;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Support\Fakes\FakeQuartoRepository;

/**
 * Cobre a Action de transição de situação sem Filament e sem banco (E5/E6).
 */
class AtualizarSituacaoQuartoTest extends TestCase
{
    private function quarto(string $situacao, int $id = 1): Quarto
    {
        $quarto = new Quarto(['numero' => 101, 'tipo' => 'Standard', 'situacao' => $situacao]);
        $quarto->id = $id;

        return $quarto;
    }

    public function test_transicao_valida_atualiza_e_persiste(): void
    {
        $quarto = $this->quarto('disponivel');
        $repo = new FakeQuartoRepository();
        $action = new AtualizarSituacaoQuarto($repo);

        $resultado = $action->executar($quarto, SituacaoQuarto::Ocupado);

        $this->assertSame('ocupado', $resultado->situacao);
        $this->assertCount(1, $repo->salvos);
        $this->assertSame($quarto, $repo->salvos[0]);
    }

    public function test_aceita_situacao_como_string(): void
    {
        $quarto = $this->quarto('disponivel');
        $action = new AtualizarSituacaoQuarto(new FakeQuartoRepository());

        $resultado = $action->executar($quarto, 'ocupado');

        $this->assertSame('ocupado', $resultado->situacao);
    }

    public function test_transicao_invalida_lanca_excecao_e_nao_persiste(): void
    {
        $quarto = $this->quarto('disponivel');
        $repo = new FakeQuartoRepository();
        $action = new AtualizarSituacaoQuarto($repo);

        try {
            $action->executar($quarto, SituacaoQuarto::Finalizada);
            $this->fail('Esperava TransicaoInvalidaException.');
        } catch (TransicaoInvalidaException $e) {
            $this->assertCount(0, $repo->salvos);
            $this->assertSame('disponivel', $quarto->situacao);
        }
    }

    public function test_permite_mesma_situacao_como_no_op(): void
    {
        $quarto = $this->quarto('ocupado');
        $repo = new FakeQuartoRepository();
        $action = new AtualizarSituacaoQuarto($repo);

        $resultado = $action->executar($quarto, SituacaoQuarto::Ocupado);

        $this->assertSame('ocupado', $resultado->situacao);
        $this->assertCount(1, $repo->salvos);
    }

    public function test_resolve_quarto_por_id(): void
    {
        $quarto = $this->quarto('disponivel', id: 7);
        $repo = new FakeQuartoRepository([$quarto]);
        $action = new AtualizarSituacaoQuarto($repo);

        $resultado = $action->executar(7, SituacaoQuarto::LimpezaEmAndamento);

        $this->assertSame('limpeza_em_andamento', $resultado->situacao);
    }

    public function test_id_inexistente_lanca_invalid_argument(): void
    {
        $action = new AtualizarSituacaoQuarto(new FakeQuartoRepository());

        $this->expectException(InvalidArgumentException::class);

        $action->executar(999, SituacaoQuarto::Ocupado);
    }

    public function test_situacao_invalida_lanca_invalid_argument(): void
    {
        $quarto = $this->quarto('disponivel');
        $action = new AtualizarSituacaoQuarto(new FakeQuartoRepository());

        $this->expectException(InvalidArgumentException::class);

        $action->executar($quarto, 'estado_que_nao_existe');
    }
}
