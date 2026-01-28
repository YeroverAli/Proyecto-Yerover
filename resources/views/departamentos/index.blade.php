@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Departamentos</h1>
        @can('create', App\Models\Departamento::class)
            <a href="{{ route('departamentos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Crear departamento
            </a>
        @endcan
    </div>

    {{-- Buscador --}}
    <form method="GET" class="mb-3">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Buscar departamentos"
               value="{{ request('search') }}">
    </form>

    {{-- Tabla de departamentos --}}
    <table class="table table-bordered w-auto mx-auto">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Abreviatura</th>
                <th>CIF</th>
                @can('create', App\Models\Departamento::class)
                <th>Acciones</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @forelse ($departamentos as $departamento)
                <tr>
                    <td>{{ $departamento->id }}</td>
                    <td>{{ $departamento->nombre }}</td>
                    <td>{{ $departamento->abreviatura }}</td>
                    <td>{{ $departamento->cif }}</td>
                    @can('update', $departamento)
                    <td>
                            <a href="{{ route('departamentos.edit', $departamento) }}"
                               class="btn btn-sm btn-warning">
                                Editar
                            </a>
                        @endcan
            
                        @can('delete', $departamento)
                            <form action="{{ route('departamentos.destroy', $departamento) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    Eliminar
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay departamentos</td>
                </tr>
            @endforelse
            </tbody>
    </table>

    {{-- Paginación --}}
    {{ $departamentos->withQueryString()->links() }}

</div>
@endsection
