<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Quarto extends Model
{
    use LogsActivity;
    use HasFactory;

    public const SITUACAO_DISPONIVEL = 'disponivel';
    public const SITUACAO_OCUPADO = 'ocupado';
    public const SITUACAO_LIMPEZA_PENDENTE = 'limpeza_pendente';
    public const SITUACAO_LIMPEZA_EM_ANDAMENTO = 'limpeza_em_andamento';
    public const SITUACAO_MANUTENCAO_PENDENTE = 'manutencao_pendente';
    public const SITUACAO_MANUTENCAO_EM_ANDAMENTO = 'manutencao_em_andamento';
    public const SITUACAO_FECHADO = 'fechado';

    public const SITUACOES = [
        self::SITUACAO_DISPONIVEL => 'Disponível',
        self::SITUACAO_OCUPADO => 'Ocupado',
        self::SITUACAO_LIMPEZA_PENDENTE => 'Limpeza Pendente',
        self::SITUACAO_LIMPEZA_EM_ANDAMENTO => 'Limpeza em Andamento',
        self::SITUACAO_MANUTENCAO_PENDENTE => 'Manutenção Pendente',
        self::SITUACAO_MANUTENCAO_EM_ANDAMENTO => 'Manutenção em Andamento',
        self::SITUACAO_FECHADO => 'Fechado',
    ];

    public const TRANSICOES = [
        self::SITUACAO_DISPONIVEL => [
            self::SITUACAO_OCUPADO,
            self::SITUACAO_LIMPEZA_PENDENTE,
            self::SITUACAO_MANUTENCAO_PENDENTE,
            self::SITUACAO_FECHADO,
        ],
        self::SITUACAO_OCUPADO => [
            self::SITUACAO_LIMPEZA_PENDENTE,
            self::SITUACAO_MANUTENCAO_PENDENTE,
        ],
        self::SITUACAO_LIMPEZA_PENDENTE => [
            self::SITUACAO_LIMPEZA_EM_ANDAMENTO,
            self::SITUACAO_DISPONIVEL,
        ],
        self::SITUACAO_LIMPEZA_EM_ANDAMENTO => [
            self::SITUACAO_DISPONIVEL,
            self::SITUACAO_MANUTENCAO_PENDENTE,
        ],
        self::SITUACAO_MANUTENCAO_PENDENTE => [
            self::SITUACAO_MANUTENCAO_EM_ANDAMENTO,
            self::SITUACAO_DISPONIVEL,
        ],
        self::SITUACAO_MANUTENCAO_EM_ANDAMENTO => [
            self::SITUACAO_DISPONIVEL,
            self::SITUACAO_LIMPEZA_PENDENTE,
        ],
        self::SITUACAO_FECHADO => [
            self::SITUACAO_DISPONIVEL,
        ],
    ];

    protected $fillable = [
        'numero',
        'tipo',
        'valor_diaria',
        'situacao',
    ];

    public function podeTransitarPara(string $novoEstado): bool
    {
        return in_array($novoEstado, self::TRANSICOES[$this->situacao] ?? [], true);
    }

    public function transitarPara(string $novoEstado): void
    {
        if (! $this->podeTransitarPara($novoEstado)) {
            throw new DomainException(
                "Transição inválida de '{$this->situacao}' para '{$novoEstado}'."
            );
        }

        $this->update(['situacao' => $novoEstado]);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function tarefas(): HasMany
    {
        return $this->hasMany(Tarefa::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'numero',
                'tipo',
                'valor_diaria',
                'situacao',
            ]);
    }
}
