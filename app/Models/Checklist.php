<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

//use HashFactory;


class Checklist extends Model{

    protected $guarded = ['id'];

    protected $fillable = [
        'nome',
        'status',
    ];

    public function itensDoChecklist(){
		  return $this->hasMany(ItemChecklist::class);
	}

    public function tipoTarefas(){
        return $this->hasMany(TipoTarefa::class, 'checklist_id');
    }

}
