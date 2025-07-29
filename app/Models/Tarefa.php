<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarefa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quarto_id',
        'tipo_tarefa',
        'data',
        'hora_inicio',
        'hora_fim',
        'descricao',
    ];

    /**
     * Uma Tarefa é realizada por um Funcionario.
     */

    public function user()
    {
        // Altere de 'App\Models\Employee' para 'App\Models\User'
        return $this->belongsTo(User::class);
    }

    /**
     * Uma Tarefa pertence a um Quarto.
     */
    public function quarto(): BelongsTo
    {
        return $this->belongsTo(Quarto::class);
    }
}
