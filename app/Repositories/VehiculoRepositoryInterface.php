<?php

namespace App\Repositories;

interface VehiculoRepositoryInterface
{
    //Obtener todos los Vehiculos
    public function all();

    //Buscar un vehiculo por ID
    public function find(int $id);

    //Crear un vehiculo
    public function create(array $data);

    //Actualizar un vehiculo
    public function update(int $id, array $data);

    //Eliminar un vehiculo
    public function delete(int $id);

    //Buscar Vehiculos
    public function search(string $term);
}