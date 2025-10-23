<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;




class ItemChecklist extends Model{
    protected $guarded = ['id'];
    
    protected $fillable = [
        'descricao',
        'checklist_id',
    ];


    /**
     * Um item/tarefa pertence a um checklist.
     */
	public function checklist(){
        
        return $this->belongsTo(\App\Models\Checklist::class);
    // NAO PODE - return $this->belongsTo(\App\Models\Checklist::class, 'checklists_id');
    //  ANTIGO - return $this->belongsTo(Checklist::class);

    } 

}


