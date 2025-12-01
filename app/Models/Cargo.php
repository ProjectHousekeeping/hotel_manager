<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'nome',
    ];

    /**
     * Um Cargo pode pertencer a muitos Funcionarios.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Um Cargo pode ter varios tipos de tarefas
     */
    public function tipoTarefas()
    {
        return $this->belongsToMany(\App\Models\TipoTarefa::class, 'cargo_tipo_tarefa');
    }


}
