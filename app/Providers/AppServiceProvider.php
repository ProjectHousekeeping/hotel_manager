<?php

namespace App\Providers;

use App\Domain\Operacoes\Repositories\ItemRepositoryInterface;
use App\Domain\Operacoes\Repositories\QuartoRepositoryInterface;
use App\Infrastructure\Operacoes\Persistence\EloquentItemRepository;
use App\Infrastructure\Operacoes\Persistence\EloquentQuartoRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Módulo 2 — Operações do Hotel: portas de repositório (P1 — Repository).
        $this->app->bind(QuartoRepositoryInterface::class, EloquentQuartoRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, EloquentItemRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
