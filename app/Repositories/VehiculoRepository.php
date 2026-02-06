<?php

namespace App\Repositories;

use App\Models\Vehiculo;

class VehiculoRepository implements VehiculoRepositoryInterface
{
    //Obtiene todos los Vehiculos y sus usuarios asociados mientras los devuelve paginados(10)
    public function all()
    {
        return Vehiculo::with(['empresa'])->simplePaginate(10);
    }
    
    //Se encarga de buscar por todas las columnas para devolver un Vehiculo
    public function search(string $term)
    {
        return Vehiculo::with(['empresa'])
            ->where(function ($query) use ($term) {
                $query->where('chasis', 'LIKE', "%{$term}%")
                    ->orWhere('bastidor', 'LIKE', "%{$term}%")
                    ->orWhere('referencia', 'LIKE', "%{$term}%")
                    ->orWhere('modelo', 'LIKE', "%{$term}%")
                    ->orWhere('version', 'LIKE', "%{$term}%")
                    ->orWhere('color_externo', 'LIKE', "%{$term}%")
                    ->orWhere('color_interno', 'LIKE', "%{$term}%");
            })
            ->simplePaginate(10);
    }

    //Busca el Vehiculo en función del id dado
    public function find(int $id)
    {
        return Vehiculo::find($id);
    }

    //Crea un Vehiculo con los datos recibidos por el array
    public function create(array $data): Vehiculo
    {
        return Vehiculo::create($data);
    }


    //Actualiza el Vehiculo que corresponde con el id escrito y lo actualiza con los datos que vienen en el array.
    public function update(int $id, array $data): Vehiculo
    {
        $vehiculo = $this->find($id);

        $vehiculo->update($data);

        return $vehiculo;
    }

    //Elimina un Vehiculo pero antes actualiza el Vehiculo de los usuarios asociados a null
    public function delete(int $id)
    {
        // Eliminar el Vehiculo
        return Vehiculo::destroy($id);
    }
}