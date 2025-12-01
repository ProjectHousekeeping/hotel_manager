<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoTarefa extends Model{

    protected $guarded = ['id'];

    protected $fillable = [
        'desc_tipo_tarefa',
        'checklist_id',
        'status',
    ];

    /**
     * Um tipo de tarefa possui um checklist.
     */
	public function checklist(){
        
        return $this->belongsTo(\App\Models\Checklist::class);
    }

    public function tarefas(){
        return $this->hasMany(Tarefa::class, 'tipo_tarefa_id');
    }

    /**
     * Um tipo de tarefa pode estar vinculado a varios tipos de cargos.
     */
   public function cargos()
    {
        return $this->belongsToMany(\App\Models\Cargo::class, 'cargo_tipo_tarefa');
    } 

}
