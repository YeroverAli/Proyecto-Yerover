@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h2>Roles</h2>
            <a href="{{ route('roles.create') }}" class="btn btn-primary">Crear Rol</a>
        </div>
    </div>

    {{-- Buscador --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <form action="{{ route('roles.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" 
                    placeholder="Buscar por nombre..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-primary me-2">Buscar</button>
                @if(request('search'))
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </form>
        </div>
    </div>

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

    <table class="table table-striped w-100">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>Nombre</th>
                <th>Guard</th>
                <th>Permisos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr class="text-center">
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->guard_name }}</td>
                    <td>
                        @if($role->permissions->count() > 0)
                            @foreach($role->permissions as $permission)
                                <span class="badge bg-secondary">{{ $permission->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Sin permisos</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" 
                            onsubmit="return confirmDelete({{ $role->users_count ?? 0 }})">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No hay roles registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $roles->withQueryString()->links() }}
    </div>

    <div class="text-center mt-3">
        <a href="{{ route('welcome') }}" class="btn btn-secondary">Volver al Inicio</a>
    </div>
</div>

<script>
    function confirmDelete(usersCount) {
        if (usersCount > 0) {
            return confirm(`Este rol tiene ${usersCount} usuario(s) asignado(s). ¿Estás seguro de eliminarlo?`);
        }
        return confirm('¿Estás seguro de eliminar este rol?');
    }
</script>
@endsection