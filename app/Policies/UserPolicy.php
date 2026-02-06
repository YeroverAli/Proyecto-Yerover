<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

final class UserPolicy
{
    /**
     * Ver listado de usuarios - requiere permiso 'ver usuarios'
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver usuarios');
    }

    /**
     * Ver usuarios - requiere permiso 'ver usuarios'
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('ver usuarios');
    }

    /**
     * Crear usuarios - requiere permiso 'crear usuarios'
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('crear usuarios');
    }
    
    /**
     * Editar usuarios - requiere permiso 'editar usuarios'
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('editar usuarios');
    }
    
    /**
     * Eliminar usuarios - requiere permiso 'eliminar usuarios'
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('eliminar usuarios');
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
