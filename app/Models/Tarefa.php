<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tarefa extends Model
{
    use LogsActivity;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quarto_id',
        'tipo_tarefa',
        'tipo_tarefa_id',
        'data',
        'hora_inicio',
        'hora_fim',
        'descricao',
    ];

    /**
     * Uma Tarefa possui um tipo de tarefa.
     */
    public function tipoTarefa(){
        return $this->belongsTo(TipoTarefa::class, 'tipo_tarefa_id');
    }

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
    public function tipoUrgencia(): BelongsTo
    {
        return $this->belongsTo(TipoUrgencia::class);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'user_id',
                'quarto_id',
                'tipo_tarefa',
                'tipo_tarefa_id',
                'tipo_urgencia_id',
                'data',
                'hora_inicio',
                'hora_fim',
                'descricao',
            ]);
    }
}
