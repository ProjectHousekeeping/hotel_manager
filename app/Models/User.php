<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- Adicionado
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable implements FilamentUser
{

    use HasFactory, Notifiable, SoftDeletes; // <-- Adicionado

    use LogsActivity;
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Novos campos
        'cpf',
        'telefone',
        'situacao',
        'cargo_id',
        'gerente_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Aqui você pode adicionar lógica de permissão se desejar
        return true;
    }

    // NOVOS RELACIONAMENTOS
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function gerente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gerente_id');
    }

    public function subordinados(): HasMany
    {
        return $this->hasMany(User::class, 'gerente_id');
    }

    public function tarefas(): HasMany
    {
        // O nome da chave estrangeira na tabela de tarefas ainda é 'funcionario_id'
        return $this->hasMany(Tarefa::class, 'funcionario_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'email',
                'password',
                // Novos campos
                'cpf',
                'telefone',
                'situacao',
                'cargo_id',
                'gerente_id',
            ]);
    }
}
