<?php

declare(strict_types=1);

namespace App\Repositories;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class RoleRepository implements RoleRepositoryInterface
{
    //Obtiene todos los Roles con sus permisos y conteo de usuarios
    public function all(): Paginator
    {
        return Role::with('permissions')
            ->simplePaginate(10);
    }
    
    //Busca un Rol por su ID
    public function find(int $id): ?Role
    {
        return Role::find($id);
    }
    
    //Crea un nuevo Rol con los datos validados y sincroniza permisos
    public function create(array $data): Role
    {
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);
        
        /** @var Role $role */
        $role = Role::create($data);
        
        // Convertir IDs a enteros y obtener los modelos Permission
        if (!empty($permissions)) {
            $permissionIds = array_map('intval', $permissions);
            $permissionModels = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissionModels);
        }
        
        // Limpiar cache de permisos
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        
        return $role;
    }
    
    //Actualiza un Rol existente y sincroniza permisos
    public function update(int $id, array $data): Role
    {
        /** @var Role|null $role */
        $role = $this->find($id);
        
        if ($role === null) {
            throw new ModelNotFoundException("Rol con ID {$id} no encontrado");
        }
        
        // Extraer permisos y convertir IDs a enteros
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);
        
        // Convertir IDs de string a int y obtener los modelos Permission
        $permissionModels = collect([]);
        if (!empty($permissions)) {
            $permissionIds = array_map('intval', $permissions);
            $permissionModels = Permission::whereIn('id', $permissionIds)->get();
        }
        
        // Actualizar el rol
        $role->update($data);
        
        // Sincronizar permisos usando los modelos Permission
        $role->syncPermissions($permissionModels);
        
        // IMPORTANTE: Limpiar la caché de TODOS los usuarios que tienen este rol
        // Esto asegura que los cambios de permisos se apliquen inmediatamente
        $usersWithRole = User::whereHas('roles', function($query) use ($id) {
            $query->where('roles.id', $id);
        })->get();
        
        foreach ($usersWithRole as $user) {
            $user->forgetCachedPermissions();
        }
        
        // Limpiar cache de permisos global
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        
        return $role;
    }
    
    //Elimina un Rol por su ID
    public function delete(int $id): int
    {
        // Limpiar cache de permisos
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        
        // Usar eliminación directa via Query Builder para evitar problemas con relaciones
        return \DB::table('roles')->where('id', $id)->delete();
    }
    
    //Busca roles por nombre
    public function search(string $term): Paginator
    {
        return Role::with('permissions')
            ->where('name', 'LIKE', "%{$term}%")
            ->simplePaginate(10);
    }
}
