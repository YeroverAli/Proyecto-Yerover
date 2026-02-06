<?php

namespace App\Repositories;

use App\Models\Departamento;

class DepartamentoRepository implements DepartamentoRepositoryInterface
{
    //Obtiene todos los departamentos y sus usuarios asociados mientras los devuelve paginados(10)
    public function all()
    {
        return Departamento::with('users')->simplePaginate(10);
    }
    
    //Se encarga de buscar por todas las columnas para devolver un departamento
    public function search(string $term)
    {
        return Departamento::with('users')
            ->where(function ($query) use ($term) {
                $query->where('nombre', 'LIKE', "%{$term}%")
                    ->orWhere('abreviatura', 'LIKE', "%{$term}%")
                    ->orWhere('cif', 'LIKE', "%{$term}%");
            })
            ->simplePaginate(10);
    }

    //Busca el departamento en función del id dado
    public function find(int $id)
    {
        return Departamento::find($id);
    }

    //Crea un departamento con los datos recibidos por el array
    public function create(array $data): Departamento
    {
        return Departamento::create($data);
    }


    //Actualiza el departamento que corresponde con el id escrito y lo actualiza con los datos que vienen en el array.
    public function update(int $id, array $data): Departamento
    {
        $departamento = $this->find($id);

        $departamento->update($data);

        return $departamento;
    }

    //Elimina un departamento pero antes actualiza el departamento de los usuarios asociados a null
    public function delete(int $id)
    {
        // Actualizar usuarios asociados a null
        \App\Models\User::where('departamento_id', $id)
            ->update(['departamento_id' => null]);
    
        // Eliminar el departamento
        return Departamento::destroy($id);
    }
}