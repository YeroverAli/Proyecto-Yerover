<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    //Recibe todos los datos de las variables: empresa, departamento, centro y los pagina al llegar a 10
    public function all()
    {
        return User::with(['empresa', 'departamento', 'centro'])->simplePaginate(10);
    }

    //Busca el usuario en funcion de su id
    public function find(int $id)
    {
        return User::find($id);
    }

    //Crea un nuevo usuario con los datos validados y, si se ha enviado un rol, se lo asigna usando Spatie.
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

    //Actualiza un usuario con los datos validados y, si se ha enviado un rol, se lo asigna usando Spatie. Si la variable password viene vacia no se actualiza este campo
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

    //Elimina el usuario en función de la id seleccionada
    public function delete(int $id){
        return User::destroy($id);
    }

    //Buscar usuarios por todas las columnas relevantes
    public function search(string $term)
    {
        //Buscador por columnas de la tabla users y relaciones con las tablas empresas, departamentos y centros
        return User::with(['empresa', 'departamento', 'centro'])
        ->where(function($query) use ($term) {
            $query->where('nombre', 'LIKE', "%{$term}%")
            ->orWhere('apellidos', 'LIKE', "%{$term}%")
            ->orWhere('email', 'LIKE', "%{$term}%")
            ->orWhere('telefono', 'LIKE', "%{$term}%")
            ->orWhere('extension', 'LIKE', "%{$term}%");
        })-> simplePaginate(10);
    }
}
    
