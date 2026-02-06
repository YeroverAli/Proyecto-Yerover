<?php

namespace App\Repositories;

interface ClienteRepositoryInterface
{
    //Obtener todos los clientes
    public function all();

    //Buscar un centro por ID
    public function find(int $id);

    //Crear un centro
    public function create(array $data);

    //Actualizar un centro
    public function update(int $id, array $data);

    //Eliminar un centro
    public function delete(int $id);

    //Buscar clientes
    public function search(string $term);
}
