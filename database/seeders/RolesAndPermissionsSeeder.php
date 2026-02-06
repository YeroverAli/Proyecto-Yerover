<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        //Limpar cache de permisos
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permisos de users
        Permission::create(['name' => 'ver usuarios']);
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);

        // Permisos de departamento
        Permission::create(['name'=> 'ver departamentos']);
        Permission::create(['name'=> 'crear departamentos']);
        Permission::create(['name'=> 'editar departamentos']);
        Permission::create(['name'=> 'eliminar departamentos']);

        // Permisos de centros
        Permission::create(['name'=> 'ver centros']);
        Permission::create(['name'=> 'crear centros']);
        Permission::create(['name'=> 'editar centros']);
        Permission::create(['name'=> 'eliminar centros']);

        // Permisos de roles
        Permission::create(['name'=> 'ver roles']);
        Permission::create(['name'=> 'crear roles']);
        Permission::create(['name'=> 'editar roles']);
        Permission::create(['name'=> 'eliminar roles']);

        // Permisos de clientes
        Permission::create(['name'=> 'ver clientes']);
        Permission::create(['name'=> 'crear clientes']);
        Permission::create(['name'=> 'editar clientes']);
        Permission::create(['name'=> 'eliminar clientes']);

        // Permisos de vehículos
        Permission::create(['name'=> 'ver vehiculos']);
        Permission::create(['name'=> 'crear vehiculos']);
        Permission::create(['name'=> 'editar vehiculos']);
        Permission::create(['name'=> 'eliminar vehiculos']);

        // Roles
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        // Asignar permisos a roles
        $admin->givePermissionTo(Permission::all());

        $user->givePermissionTo([
            'ver usuarios',
        ]);
    }
}
