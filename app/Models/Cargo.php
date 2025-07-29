<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
    ];

    /**
     * Um Cargo pode pertencer a muitos Funcionarios.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
