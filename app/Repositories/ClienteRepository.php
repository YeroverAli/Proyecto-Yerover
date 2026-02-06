<?php

namespace App\Repositories;

use App\Models\Cliente;

class ClienteRepository implements ClienteRepositoryInterface
{
    //Obtiene todos los Clientes y sus usuarios asociados mientras los devuelve paginados(10)
    public function all()
    {
        return Cliente::with(['empresa'])->simplePaginate(10);
    }
    
    //Se encarga de buscar por todas las columnas para devolver un Cliente
    public function search(string $term)
    {
        return Cliente::with(['empresa'])
            ->where(function ($query) use ($term) {
                $query->where('nombre', 'LIKE', "%{$term}%")
                    ->orWhere('apellidos', 'LIKE', "%{$term}%")
                    ->orWhere('dni', 'LIKE', "%{$term}%")
                    ->orWhere('domicilio', 'LIKE', "%{$term}%")
                    ->orWhere('codigo_postal', 'LIKE', "%{$term}%")
                    ->orWhere('telefono', 'LIKE', "%{$term}%")
                    ->orWhere('email', 'LIKE', "%{$term}%");
            })
            ->simplePaginate(10);
    }

    //Busca el Cliente en función del id dado
    public function find(int $id)
    {
        return Cliente::find($id);
    }

    //Crea un Cliente con los datos recibidos por el array
    public function create(array $data): Cliente
    {
        return Cliente::create($data);
    }


    //Actualiza el Cliente que corresponde con el id escrito y lo actualiza con los datos que vienen en el array.
    public function update(int $id, array $data): Cliente
    {
        $cliente = $this->find($id);

        $cliente->update($data);

        return $cliente;
    }

    //Elimina un Cliente pero antes actualiza el Cliente de los usuarios asociados a null
    public function delete(int $id)
    {
        // Eliminar el Cliente
        return Cliente::destroy($id);
    }
}