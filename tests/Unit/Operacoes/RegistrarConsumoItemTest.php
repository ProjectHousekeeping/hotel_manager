<?php

namespace Tests\Unit\Operacoes;

use App\Domain\Operacoes\Actions\RegistrarConsumoItem;
use App\Domain\Operacoes\Exceptions\ConsumoInvalidoException;
use App\Models\Item;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Support\Fakes\FakeItemRepository;

/**
 * Cobre a Action de registro de consumo sem Filament e sem banco (E5/E6/F7).
 */
class RegistrarConsumoItemTest extends TestCase
{
    private function item(int $quantidade, float $preco = 10.0, int $id = 1): Item
    {
        $item = new Item([
            'quarto_id' => 1,
            'nome' => 'Água',
            'preco' => $preco,
            'quantidade' => $quantidade,
        ]);
        $item->id = $id;

        return $item;
    }

    public function test_consumo_valido_abate_estoque_e_calcula_valor(): void
    {
        $item = $this->item(quantidade: 5, preco: 7.50);
        $repo = new FakeItemRepository();
        $action = new RegistrarConsumoItem($repo);

        $resultado = $action->executar($item, 2);

        $this->assertSame(2, $resultado->quantidadeConsumida);
        $this->assertSame(3, $resultado->quantidadeRestante);
        $this->assertSame(15.0, $resultado->valorTotal);
        $this->assertSame(3, (int) $item->quantidade);
        $this->assertCount(1, $repo->salvos);
    }

    public function test_consumo_igual_ao_estoque_zera_restante(): void
    {
        $item = $this->item(quantidade: 4, preco: 3.0);
        $action = new RegistrarConsumoItem(new FakeItemRepository());

        $resultado = $action->executar($item, 4);

        $this->assertSame(0, $resultado->quantidadeRestante);
        $this->assertSame(12.0, $resultado->valorTotal);
    }

    public function test_consumo_acima_do_estoque_lanca_excecao_e_nao_persiste(): void
    {
        $item = $this->item(quantidade: 1);
        $repo = new FakeItemRepository();
        $action = new RegistrarConsumoItem($repo);

        try {
            $action->executar($item, 2);
            $this->fail('Esperava ConsumoInvalidoException.');
        } catch (ConsumoInvalidoException $e) {
            $this->assertCount(0, $repo->salvos);
            $this->assertSame(1, (int) $item->quantidade);
        }
    }

    public function test_quantidade_nao_positiva_lanca_excecao(): void
    {
        $item = $this->item(quantidade: 5);
        $action = new RegistrarConsumoItem(new FakeItemRepository());

        $this->expectException(ConsumoInvalidoException::class);

        $action->executar($item, 0);
    }

    public function test_valor_total_e_arredondado_para_dois_decimais(): void
    {
        $item = $this->item(quantidade: 10, preco: 3.333);
        $action = new RegistrarConsumoItem(new FakeItemRepository());

        $resultado = $action->executar($item, 3);

        $this->assertSame(10.0, $resultado->valorTotal);
    }

    public function test_resolve_item_por_id(): void
    {
        $item = $this->item(quantidade: 5, preco: 2.0, id: 42);
        $repo = new FakeItemRepository([$item]);
        $action = new RegistrarConsumoItem($repo);

        $resultado = $action->executar(42, 1);

        $this->assertSame(42, $resultado->itemId);
        $this->assertSame(4, $resultado->quantidadeRestante);
    }

    public function test_id_inexistente_lanca_invalid_argument(): void
    {
        $action = new RegistrarConsumoItem(new FakeItemRepository());

        $this->expectException(InvalidArgumentException::class);

        $action->executar(999, 1);
    }
}
