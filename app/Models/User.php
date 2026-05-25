<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes, LogsActivity;

    public const CARGO_GERENTE = 'Gerente';
    public const CARGO_RECEPCIONISTA = 'Recepcionista';
    public const CARGO_CAMAREIRA = 'Camareira';
    public const CARGO_TECNICO = 'Técnico de Manutenção';

    public const CARGOS_PERMITIDOS = [
        self::CARGO_GERENTE,
        self::CARGO_RECEPCIONISTA,
        self::CARGO_CAMAREIRA,
        self::CARGO_TECNICO,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'telefone',
        'situacao',
        'cargo_id',
        'gerente_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->situacao !== 'inativo'
            && in_array($this->cargo?->nome, self::CARGOS_PERMITIDOS, true);
    }

    public function isGerente(): bool
    {
        return $this->cargo?->nome === self::CARGO_GERENTE;
    }

    public function isRecepcionista(): bool
    {
        return $this->cargo?->nome === self::CARGO_RECEPCIONISTA;
    }

    public function isCamareira(): bool
    {
        return $this->cargo?->nome === self::CARGO_CAMAREIRA;
    }

    public function isTecnico(): bool
    {
        return $this->cargo?->nome === self::CARGO_TECNICO;
    }

    public function isOperacional(): bool
    {
        return $this->isCamareira() || $this->isTecnico();
    }

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
        return $this->hasMany(Tarefa::class, 'user_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'email',
                'password',
                'cpf',
                'telefone',
                'situacao',
                'cargo_id',
                'gerente_id',
            ]);
    }
}
