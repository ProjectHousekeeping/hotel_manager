<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoUrgencia extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'tarefa_id',
    ];
    public function tarefa(): HasMany
    {
        return $this->hasMany(Tarefa::class);
    }
}
