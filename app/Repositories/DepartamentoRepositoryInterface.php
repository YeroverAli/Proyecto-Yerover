<?php

namespace App\Repositories;

interface DepartamentoRepositoryInterface
{
    //Obtener todos los departamentos
    public function all();

    //Buscar un departamento por ID
    public function find(int $id);

    //Crear un departamento
    public function create(array $data);

    //Actualizar un departamento
    public function update(int $id, array $data);

    //Eliminar un departamento
    public function delete(int $id);

    //Buscar departamentos
    public function search(string $term);
}
