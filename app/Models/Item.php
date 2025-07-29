<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'quarto_id',
        'nome',
        'classificacao',
        'marca',
        'preco',
        'quantidade',
    ];

    /**
     * Um Item pertence a um Quarto.
     */
    public function quarto(): BelongsTo
    {
        return $this->belongsTo(Quarto::class);
    }
}