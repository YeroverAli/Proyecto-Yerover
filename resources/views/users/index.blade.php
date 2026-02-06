@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
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
    <table class="table table-bordered w-100">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Apellidos</th>
                <th class="text-center">Empresa</th>
                <th class="text-center">Departamento</th>
                <th class="text-center">Centro</th>
                <th class="text-center">Email</th>
                <th class="text-center">Teléfono</th>
                <th class="text-center">Extensión</th>
                @if(auth()->user()->hasPermissionTo('editar usuarios') || auth()->user()->hasPermissionTo('eliminar usuarios' || auth()->user()->hasPermissionTo('ver usuarios')))
                <th class="text-center">Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td class="text-center">{{ $user->id }}</td>
                    <td class="text-center">{{ $user->nombre }}</td>
                    <td class="text-center">{{ $user->apellidos }}</td>
                    <td class="text-center">{{ $user->empresa->nombre ?? 'N/A'  }}</td>
                    <td class="text-center">{{ $user->departamento->nombre ?? 'N/A' }}</td>
                    <td class="text-center">{{ $user->centro->nombre ?? 'N/A' }}</td>
                    <td class="text-center">{{ $user->email }}</td>
                    <td class="text-center">{{ $user->telefono }}</td>
                    <td class="text-center">{{ $user->extension }}</td>
                    @if(auth()->user()->can('update', $user) || auth()->user()->can('delete', $user))
                    <td class="text-center">
                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">
                            Ver
                        </a>
                        @can('update', $user)
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                Editar
                            </a>
                        @endcan
                        @can('delete', $user)
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        @endcan
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No hay usuarios</td>
                </tr>
            @endforelse
            </tbody>
    </table>

     {{-- Paginación --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $users->withQueryString()->links() }}
    </div>

</div>
@endsection

