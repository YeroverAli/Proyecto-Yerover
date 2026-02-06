<?php

namespace App\Repositories;

use App\Models\Centro;

class CentroRepository implements CentroRepositoryInterface
{
    //Obtiene todos los Centros y sus usuarios asociados mientras los devuelve paginados(10)
    public function all()
    {
        return Centro::with(['empresa', 'users'])->simplePaginate(10);
    }
    
    //Se encarga de buscar por todas las columnas para devolver un Centro
    public function search(string $term)
    {
        return Centro::with(['empresa', 'users'])
            ->where(function ($query) use ($term) {
                $query->where('nombre', 'LIKE', "%{$term}%")
                    ->orWhere('direccion', 'LIKE', "%{$term}%")
                    ->orWhere('provincia', 'LIKE', "%{$term}%")
                    ->orWhere('municipio', 'LIKE', "%{$term}%");
            })
            ->simplePaginate(10);
    }

    //Busca el Centro en función del id dado
    public function find(int $id)
    {
        return Centro::find($id);
    }

    //Crea un Centro con los datos recibidos por el array
    public function create(array $data): Centro
    {
        return Centro::create($data);
    }


    //Actualiza el Centro que corresponde con el id escrito y lo actualiza con los datos que vienen en el array.
    public function update(int $id, array $data): Centro
    {
        $centro = $this->find($id);

        $centro->update($data);

        return $centro;
    }

    //Elimina un Centro pero antes actualiza el Centro de los usuarios asociados a null
    public function delete(int $id)
    {
        // Actualizar usuarios asociados a null
        \App\Models\User::where('centro_id', $id)
            ->update(['centro_id' => null]);
    
        // Eliminar el Centro
        return Centro::destroy($id);
    }
}