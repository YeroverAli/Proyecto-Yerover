<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::paginate(10);
    }

    public function find(int $id)
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        $role = $data['role'] ?? null;
        unset($data['role']);

        // El modelo User tiene el cast 'hashed' para password, no necesita bcrypt aquí
        $user = User::create($data);

        if ($role) {
            $user->syncRoles($role);
        }

        return $user;
    }

    public function update(int $id, array $data): User
    {
        $user = $this->find($id);

        $role = $data['role'] ?? null;
        unset($data['role']);

        // Si no se proporciona password, lo eliminamos para no actualizar
        if (!isset($data['password']) || !$data['password']) {
            unset($data['password']);
        }
        // El modelo User tiene el cast 'hashed' para password, no necesita bcrypt aquí

        $user->update($data);

        if ($role) {
            $user->syncRoles($role);
        }

        return $user;
    }

    public function delete(int $id){
        return User::destroy($id);
    }

    //Buscar usuarios por todas las columnas relevantes
    public function search(string $term)
    {
        return User::where(function($query) use ($term) {
            $query->where('nombre', 'LIKE', "%{$term}%")
            ->orWhere('apellidos', 'LIKE', "%{$term}%")
            ->orWhere('email', 'LIKE', "%{$term}%")
            ->orWhere('telefono', 'LIKE', "%{$term}%")
            ->orWhere('extension', 'LIKE', "%{$term}%");
        })-> paginate(10);
    }
}
    
