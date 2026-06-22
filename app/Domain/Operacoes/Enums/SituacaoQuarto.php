<?php

namespace App\Domain\Operacoes\Enums;

/**
 * Máquina de estados da situação de um Quarto (Módulo 2 — Operações do Hotel).
 *
 * Centraliza a regra de transição que antes vivia implícita nos Resources Filament
 * (smell CLS-03). Esta classe é pura: não importa Filament nem Eloquent, sendo
 * testável de forma isolada (métricas E6 e E9 do plano de evolução).
 *
 * As transições aqui descritas são uma proposta e requerem validação da equipe,
 * conforme observado no Plano de Evolução do Módulo 2.
 */
enum SituacaoQuarto: string
{
    case Disponivel = 'disponivel';
    case Ocupado = 'ocupado';
    case LimpezaEmAndamento = 'limpeza_em_andamento';
    case ManutencaoEmAndamento = 'manutencao_em_andamento';
    case PedidoEncaminhado = 'pedido_encaminhado';
    case Finalizada = 'finalizada';

    /**
     * Situações para as quais este estado pode transicionar.
     *
     * @return array<int, self>
     */
    public function transicoesValidas(): array
    {
        return match ($this) {
            self::Disponivel => [
                self::Ocupado,
                self::LimpezaEmAndamento,
                self::ManutencaoEmAndamento,
            ],
            self::Ocupado => [
                self::LimpezaEmAndamento,
                self::ManutencaoEmAndamento,
                self::PedidoEncaminhado,
            ],
            self::PedidoEncaminhado => [
                self::ManutencaoEmAndamento,
                self::LimpezaEmAndamento,
                self::Ocupado,
            ],
            self::ManutencaoEmAndamento => [
                self::Finalizada,
                self::LimpezaEmAndamento,
            ],
            self::LimpezaEmAndamento => [
                self::Finalizada,
                self::ManutencaoEmAndamento,
            ],
            self::Finalizada => [
                self::Disponivel,
            ],
        };
    }

    /**
     * Indica se a transição deste estado para $destino é permitida.
     */
    public function podeTransicionarPara(self $destino): bool
    {
        return in_array($destino, $this->transicoesValidas(), true);
    }

    /**
     * Rótulo legível para apresentação (reutilizável pela UI).
     */
    public function rotulo(): string
    {
        return match ($this) {
            self::Disponivel => 'Disponível',
            self::Ocupado => 'Ocupado',
            self::LimpezaEmAndamento => 'Em Limpeza',
            self::ManutencaoEmAndamento => 'Em Manutenção',
            self::PedidoEncaminhado => 'Pedido Encaminhado',
            self::Finalizada => 'Finalizada',
        };
    }

    /**
     * Mapa value => rótulo de todas as situações (para selects).
     *
     * @return array<string, string>
     */
    public static function opcoes(): array
    {
        $opcoes = [];
        foreach (self::cases() as $caso) {
            $opcoes[$caso->value] = $caso->rotulo();
        }

        return $opcoes;
    }

    /**
     * Mapa value => rótulo apenas das transições válidas a partir deste estado.
     *
     * @return array<string, string>
     */
    public function opcoesDeTransicao(): array
    {
        $opcoes = [];
        foreach ($this->transicoesValidas() as $caso) {
            $opcoes[$caso->value] = $caso->rotulo();
        }

        return $opcoes;
    }
}
