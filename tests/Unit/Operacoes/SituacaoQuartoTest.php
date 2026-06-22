<?php

namespace Tests\Unit\Operacoes;

use App\Domain\Operacoes\Enums\SituacaoQuarto;
use PHPUnit\Framework\TestCase;

/**
 * Cobre a máquina de estados da situação do quarto sem Filament (E6) e
 * exercita todas as situações válidas (E9).
 */
class SituacaoQuartoTest extends TestCase
{
    public function test_todo_estado_possui_pelo_menos_uma_transicao_valida(): void
    {
        foreach (SituacaoQuarto::cases() as $situacao) {
            $this->assertNotEmpty(
                $situacao->transicoesValidas(),
                "A situação {$situacao->value} deveria ter ao menos uma transição.",
            );
        }
    }

    public function test_todo_estado_e_alcancavel_por_alguma_transicao(): void
    {
        $alcancaveis = [];
        foreach (SituacaoQuarto::cases() as $origem) {
            foreach ($origem->transicoesValidas() as $destino) {
                $alcancaveis[$destino->value] = true;
            }
        }

        foreach (SituacaoQuarto::cases() as $situacao) {
            $this->assertArrayHasKey(
                $situacao->value,
                $alcancaveis,
                "A situação {$situacao->value} não é alcançável por nenhuma transição.",
            );
        }
    }

    /**
     * @dataProvider transicoesValidas
     */
    public function test_transicoes_validas_sao_permitidas(SituacaoQuarto $origem, SituacaoQuarto $destino): void
    {
        $this->assertTrue($origem->podeTransicionarPara($destino));
    }

    /**
     * @dataProvider transicoesInvalidas
     */
    public function test_transicoes_invalidas_sao_bloqueadas(SituacaoQuarto $origem, SituacaoQuarto $destino): void
    {
        $this->assertFalse($origem->podeTransicionarPara($destino));
    }

    public function test_opcoes_contem_todas_as_situacoes(): void
    {
        $opcoes = SituacaoQuarto::opcoes();

        $this->assertCount(count(SituacaoQuarto::cases()), $opcoes);
        $this->assertSame('Disponível', $opcoes['disponivel']);
        $this->assertSame('Em Manutenção', $opcoes['manutencao_em_andamento']);
    }

    public function test_opcoes_de_transicao_lista_apenas_destinos_validos(): void
    {
        $opcoes = SituacaoQuarto::Disponivel->opcoesDeTransicao();

        $this->assertArrayHasKey('ocupado', $opcoes);
        $this->assertArrayNotHasKey('disponivel', $opcoes);
        $this->assertArrayNotHasKey('finalizada', $opcoes);
    }

    public function test_todo_caso_possui_rotulo_nao_vazio(): void
    {
        foreach (SituacaoQuarto::cases() as $situacao) {
            $this->assertNotSame('', $situacao->rotulo());
        }
    }

    /**
     * @return array<string, array{0: SituacaoQuarto, 1: SituacaoQuarto}>
     */
    public static function transicoesValidas(): array
    {
        return [
            'disponivel -> ocupado' => [SituacaoQuarto::Disponivel, SituacaoQuarto::Ocupado],
            'ocupado -> limpeza' => [SituacaoQuarto::Ocupado, SituacaoQuarto::LimpezaEmAndamento],
            'ocupado -> pedido' => [SituacaoQuarto::Ocupado, SituacaoQuarto::PedidoEncaminhado],
            'limpeza -> finalizada' => [SituacaoQuarto::LimpezaEmAndamento, SituacaoQuarto::Finalizada],
            'manutencao -> finalizada' => [SituacaoQuarto::ManutencaoEmAndamento, SituacaoQuarto::Finalizada],
            'finalizada -> disponivel' => [SituacaoQuarto::Finalizada, SituacaoQuarto::Disponivel],
            'pedido -> manutencao' => [SituacaoQuarto::PedidoEncaminhado, SituacaoQuarto::ManutencaoEmAndamento],
        ];
    }

    /**
     * @return array<string, array{0: SituacaoQuarto, 1: SituacaoQuarto}>
     */
    public static function transicoesInvalidas(): array
    {
        return [
            'disponivel -> finalizada' => [SituacaoQuarto::Disponivel, SituacaoQuarto::Finalizada],
            'finalizada -> ocupado' => [SituacaoQuarto::Finalizada, SituacaoQuarto::Ocupado],
            'ocupado -> disponivel' => [SituacaoQuarto::Ocupado, SituacaoQuarto::Disponivel],
            'limpeza -> ocupado' => [SituacaoQuarto::LimpezaEmAndamento, SituacaoQuarto::Ocupado],
        ];
    }
}
