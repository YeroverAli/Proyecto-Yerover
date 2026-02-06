<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Departamento;
use App\Models\User;

final class DepartamentoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver departamentos');
    }

    public function view(User $user, Departamento $departamento): bool
    {
        return $user->hasPermissionTo('ver departamentos');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('crear departamentos');
    }

    public function update(User $user, Departamento $departamento): bool
    {
        return $user->hasPermissionTo('editar departamentos');
    }

    public function delete(User $user, Departamento $departamento): bool
    {
        return $user->hasPermissionTo('eliminar departamentos');
    }

    public function restore(User $user, Departamento $departamento): bool
    {
        return false;
    }

    public function forceDelete(User $user, Departamento $departamento): bool
    {
        return false;
    }
}