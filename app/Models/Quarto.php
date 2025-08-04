<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Quarto extends Model
{
    use LogsActivity;
    use HasFactory;

    protected $fillable = [
        'numero',
        'tipo',
        'valor_diaria',
        'situacao',
    ];

    /**
     * Um Quarto pode ter muitos Itens.
     */
    public function itens(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Um Quarto pode ter muitas Tarefas associadas a ele.
     */
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
