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
    <table class="table table-bordered w-100">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Abreviatura</th>
                <th class="text-center">CIF</th>
            @if(auth()->user()->hasPermissionTo('ver departamentos') || auth()->user()->hasPermissionTo('editar departamentos') || auth()->user()->hasPermissionTo('eliminar departamentos'))
            <th class="text-center">Acciones</th>
            @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($departamentos as $departamento)
                <tr>
                    <td class="text-center">{{ $departamento->id }}</td>
                    <td class="text-center">{{ $departamento->nombre }}</td>
                    <td class="text-center">{{ $departamento->abreviatura }}</td>
                    <td class="text-center">{{ $departamento->cif }}</td>
                    @if(auth()->user()->can('view', $departamento) || auth()->user()->can('update', $departamento) || auth()->user()->can('delete', $departamento))
                    <td class="text-center">
                        @can('view', $departamento)
                            <a href="{{ route('departamentos.show', $departamento) }}" class="btn btn-sm btn-info">
                                Ver
                            </a>
                        @endcan
                        @can('update', $departamento)
                            <a href="{{ route('departamentos.edit', $departamento) }}" class="btn btn-sm btn-warning">
                                Editar
                            </a>
                        @endcan
                        @can('delete', $departamento)
                            <form action="{{ route('departamentos.destroy', $departamento) }}" method="POST" class="d-inline" id="delete-form-{{ $departamento->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $departamento->id }}, {{ json_encode($departamento->nombre) }}, {{ $departamento->users->count() }})">
                                    Eliminar
                                </button>
                            </form>
                        @endcan
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay departamentos</td>
                </tr>
            @endforelse
            </tbody>
        </table>
   {{-- Paginación --}}
   <div class="d-flex justify-content-center mt-4">
        {{ $departamentos->withQueryString()->links() }}
    </div>
</div>


<script>
function confirmDelete(id, nombre, usuariosCount) {
    let mensaje = '¿Estás seguro de eliminar el departamento "' + nombre + '"?';
    
    if (usuariosCount > 0) {
        mensaje += '\n\n⚠️ Este departamento tiene ' + usuariosCount + ' usuario(s) asociado(s).';
        mensaje += '\nSi continúas, el departamento será eliminado y los usuarios quedarán sin departamento asignado.';
    }
    
    if (confirm(mensaje)) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
