<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipoTarefa extends Model{

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

}
