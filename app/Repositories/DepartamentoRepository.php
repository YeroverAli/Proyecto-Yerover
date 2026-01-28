<?php

namespace App\Repositories;

use App\Models\Departamento;

class DepartamentoRepository implements DepartamentoRepositoryInterface
{
    public function all()
    {
        return Departamento::paginate(10);
    }

    public function find(int $id)
    {
        return Departamento::find($id);
    }

    public function create(array $data): Departamento
    {
        return Departamento::create($data);
    }

    public function update(int $id, array $data): Departamento
    {
        $departamento = $this->find($id);

        $departamento->update($data);

        return $departamento;
    }

    public function delete(int $id)
    {
        return Departamento::destroy($id);
    }

    public function search(string $term)
    {
        return Departamento::where(function ($query) use ($term) {
                $query->where('nombre', 'LIKE', "%{$term}%")
                    ->orWhere('abreviatura', 'LIKE', "%{$term}%")
                    ->orWhere('cif', 'LIKE', "%{$term}%");
            })
            ->paginate(10);
    }
}