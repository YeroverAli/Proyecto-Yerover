<?php

namespace App\Policies;

use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver roles');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('ver roles');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('crear roles');
    }

    public function update(User $user, Role $Role): bool
    {
        return $user->hasPermissionTo('editar roles');
    }

    public function delete(User $user, Role $Role): bool
    {
        return $user->hasPermissionTo('eliminar roles');
    }

    public function restore(User $user, Role $Role): bool
    {
        return false;
    }

    public function forceDelete(User $user, Role $Role): bool
    {
        return false;
    }
}