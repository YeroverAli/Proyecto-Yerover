<?php
namespace App\Repositories;
interface UserRepositoryInterface
{
    //Obtener todos los usuarios
    public function all();

    //Buscar un usuario por ID
    public function find(int $id);

    //Crear un usuario
    public function create(array $data);

    //Actualizar un usuario
    public function update(int $id, array $data);

    //Eliminar un usuario
    public function delete(int $id);
    
}