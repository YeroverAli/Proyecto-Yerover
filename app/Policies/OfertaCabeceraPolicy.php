<?php

namespace App\Policies;

use App\Models\User;
use App\Models\OfertaCabecera;

class OfertaCabeceraPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver pdf');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OfertaCabecera $ofertaCabecera): bool
    {
        return $user->hasPermissionTo('ver pdf');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('crear pdf');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OfertaCabecera $ofertaCabecera): bool
    {
        return $user->hasPermissionTo('editar pdf');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OfertaCabecera $ofertaCabecera): bool
    {
        return $user->hasPermissionTo('eliminar pdf');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OfertaCabecera $ofertaCabecera): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OfertaCabecera $ofertaCabecera): bool
    {
        return false;
    }
}
