<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Ver listado de usuarios
     */
    public function viewAny(User $user): bool
    {
        return true;
    }
    
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }
    
    public function update(User $user, User $model): bool
    {
        return $user->hasRole('admin');
    }
    
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('admin');
    }
    

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
