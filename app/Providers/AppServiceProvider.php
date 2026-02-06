<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\DepartamentoRepository;
use App\Repositories\DepartamentoRepositoryInterface;
use App\Repositories\CentroRepository;
use App\Repositories\CentroRepositoryInterface;
use App\Repositories\RoleRepository;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\ClienteRepository;
use App\Repositories\ClienteRepositoryInterface;
use App\Repositories\VehiculoRepository;
use App\Repositories\VehiculoRepositoryInterface;
use Spatie\Cliente\Models\Role;
use App\Policies\RolePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            DepartamentoRepositoryInterface::class,
            DepartamentoRepository::class
        );
        $this->app->bind(
            CentroRepositoryInterface::class,
            CentroRepository::class
        );

        $this->app->bind(
            RoleRepositoryInterface::class,
            RoleRepository::class
        );

        $this->app->bind(
            ClienteRepositoryInterface::class,
            ClienteRepository::class
        );
        
        $this->app->bind(
            VehiculoRepositoryInterface::class,
            VehiculoRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar Policies para modelos de Spatie (no están en App\Models)
        Gate::policy(Role::class, RolePolicy::class);
    }
}