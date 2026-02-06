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
        <h1 class="mb-0">Centros</h1>
        @can('create', App\Models\Centro::class)
            <a href="{{ route('centros.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Crear centro
            </a>
        @endcan
    </div>

    {{-- Buscador --}}
    <form method="GET" class="mb-3">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Buscar centros"
               value="{{ request('search') }}">
    </form>

    {{-- Tabla de centros --}}
    <table class="table table-bordered w-100">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Empresa</th>
                <th class="text-center">Direccion</th>
                <th class="text-center">Provincia</th>
                <th class="text-center">Municipio</th>
                @if(auth()->user()->hasPermissionTo('ver centros') || auth()->user()->hasPermissionTo('editar centros') || auth()->user()->hasPermissionTo('eliminar centros'))
                <th class="text-center">Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($centros as $centro)
                <tr>
                    <td class="text-center">{{ $centro->id }}</td>
                    <td class="text-center">{{ $centro->nombre }}</td>
                    <td class="text-center">{{ $centro->empresa->nombre ?? 'N/A'  }}</td>
                    <td class="text-center">{{ $centro->direccion }}</td>
                    <td class="text-center">{{ $centro->provincia }}</td>
                    <td class="text-center">{{ $centro->municipio }}</td>
                    @if(auth()->user()->can('view', $centro) || auth()->user()->can('update', $centro) || auth()->user()->can('delete', $centro))
                    <td class="text-center">
                        @can('view', $centro)
                            <a href="{{ route('centros.show', $centro) }}" class="btn btn-sm btn-info">
                                Ver
                            </a>
                        @endcan
                        @can('update', $centro)
                            <a href="{{ route('centro.edit', $centro) }}" class="btn btn-sm btn-warning">
                                Editar
                            </a>
                        @endcan
                        @can('delete', $centro)
                            <form action="{{ route('centros.destroy', $centro) }}" method="POST" class="d-inline" id="delete-form-{{ $centro->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $centro->id }}, {{ json_encode($centro->nombre) }}, {{ $centro->users->count() }})">
                                    Eliminar
                                </button>
                            </form>
                        @endcan
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay centros</td>
                </tr>
            @endforelse
            </tbody>
        </table>
   {{-- Paginación --}}
   <div class="d-flex justify-content-center mt-4">
        {{ $centros->withQueryString()->links() }}
    </div>
</div>


<script>
function confirmDelete(id, nombre, usuariosCount) {
    let mensaje = '¿Estás seguro de eliminar el centro "' + nombre + '"?';
    
    if (usuariosCount > 0) {
        mensaje += '\n\n⚠️ Este centro tiene ' + usuariosCount + ' usuario(s) asociado(s).';
        mensaje += '\nSi continúas, el centro será eliminado y los usuarios quedarán sin centro asignado.';
    }
    
    if (confirm(mensaje)) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
