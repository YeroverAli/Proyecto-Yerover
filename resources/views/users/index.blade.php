@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Usuarios</h1>
        @can('create', App\Models\User::class)
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Crear usuario
            </a>
        @endcan
    </div>

    {{-- Buscador --}}
    <form method="GET" class="mb-3">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Buscar usuarios"
               value="{{ request('search') }}">
    </form>

    {{-- Tabla de usuarios --}}
    <table class="table table-bordered w-auto mx-auto">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Empresa</th>
                <th>Departamento</th>
                <th>Centro</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Extensión</th>
                @can('create', App\Models\User::class)
                <th>Acciones</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->nombre }}</td>
                    <td>{{ $user->apellidos }}</td>
                    <td>{{ $user->empresa_id }}</td> {{-- Se debería mostrar el nombre de la empresa --}}
                    <td>{{ $user->departamento_id }}</td> {{-- Se debería mostrar el nombre del departamento --}}
                    <td>{{ $user->centro_id }}</td> {{-- Se debería mostrar el nombre del centro --}}
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->telefono }}</td>
                    <td>{{ $user->extension }}</td>
                    @can('update', $user)
                    <td>
                            <a href="{{ route('users.edit', $user) }}"
                               class="btn btn-sm btn-warning">
                                Editar
                            </a>
                        @endcan
            
                        @can('delete', $user)
                            <form action="{{ route('users.destroy', $user) }}"
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
                    <td colspan="4">No hay usuarios</td>
                </tr>
            @endforelse
            </tbody>
    </table>

    {{-- Paginación --}}
    {{ $users->withQueryString()->links() }}

</div>
@endsection

