<?php

declare(strict_types=1);

namespace App\Repositories;

use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Pagination\Paginator;

interface RoleRepositoryInterface
{
    //Obtener todos los Roles paginados
    public function all(): Paginator;

    //Buscar un Rol por ID
    public function find(int $id): ?Role;

    //Crear un Rol
    public function create(array $data): Role;

    //Actualizar un Rol
    public function update(int $id, array $data): Role;

    //Eliminar un Rol
    public function delete(int $id): int;

    //Buscar Roles por término
    public function search(string $term): Paginator;
}
