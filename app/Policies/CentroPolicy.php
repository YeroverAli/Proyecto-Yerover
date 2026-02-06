<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Centro;
use App\Models\User;

final class CentroPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver centros');
    }

    public function view(User $user, Centro $centro): bool
    {
        return $user->hasPermissionTo('ver centros');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('crear centros');
    }

    public function update(User $user, Centro $centro): bool
    {
        return $user->hasPermissionTo('editar centros');
    }

    public function delete(User $user, Centro $centro): bool
    {
        return $user->hasPermissionTo('eliminar centros');
    }

    public function restore(User $user, Centro $centro): bool
    {
        return false;
    }

    public function forceDelete(User $user, Centro $centro): bool
    {
        return false;
    }
}