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
            <h1 class="mb-0">Vehículos</h1>
            @can('create', App\Models\Vehiculo::class)
                <div>
                    <a href="{{ route('vehiculos.export') }}" class="btn btn-success me-2">
                        <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                    </a>
                    <a href="{{ route('vehiculos.pdf') }}" class="btn btn-danger me-2">
                        <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                    </a>
                    <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Crear Vehículo
                    </a>
                </div>
            @endcan
        </div>

        {{-- Buscador --}}
        <form method="GET" class="mb-3">
            <input type="text" name="search" class="form-control" placeholder="Buscar Vehiculos"
                value="{{ request('search') }}">
        </form>

        {{-- Tabla de Vehiculos --}}
        <table class="table table-bordered w-100">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                <th class="text-center">Modelo</th>
                <th class="text-center">Version</th>
                <th class="text-center">Bastidor</th>
                <th class="text-center">Referencia</th>
                <th class="text-center">Color Externo</th>
                <th class="text-center">Color Interno</th>
                <th class="text-center">Empresa</th>
                    @if(auth()->user()->hasPermissionTo('ver vehiculos') || auth()->user()->hasPermissionTo('editar vehiculos') || auth()->user()->hasPermissionTo('eliminar vehiculos'))
                        <th class="text-center">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($vehiculos as $vehiculo)
                    <tr>
                        <td class="text-center">{{ $vehiculo->id }}</td>
                    <td class="text-center">{{ $vehiculo->modelo }}</td>
                    <td class="text-center">{{ $vehiculo->version }}</td>
                    <td class="text-center">{{ $vehiculo->bastidor ?? 'N/A' }}</td>
                    <td class="text-center">{{ $vehiculo->referencia ?? 'N/A' }}</td>
                    <td class="text-center">{{ $vehiculo->color_externo }}</td>
                    <td class="text-center">{{ $vehiculo->color_interno }}</td>
                    <td class="text-center">{{ $vehiculo->empresa->nombre ?? 'N/A'  }}</td>
                        @if(auth()->user()->can('view', $vehiculo) || auth()->user()->can('update', $vehiculo) || auth()->user()->can('delete', $vehiculo))
                            <td class="text-center">
                                @can('view', $vehiculo)
                                    <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-sm btn-info">
                                        Ver
                                    </a>
                                @endcan
                                @can('update', $vehiculo)
                                    <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-sm btn-warning">
                                        Editar
                                    </a>
                                @endcan
                                @can('delete', $vehiculo)
                                    <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline"
                                        id="delete-form-{{ $vehiculo->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $vehiculo->id }}, {{ json_encode($vehiculo->bastidor) }})">
                                            Eliminar
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                    <td colspan="9" class="text-center">No hay vehículos</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            {{ $vehiculos->withQueryString()->links() }}
        </div>
    </div> {{-- cierre de .container --}}

    <script>
        function confirmDelete(id, bastidor) {
            const mensaje = '¿Estás seguro de eliminar el vehiculo "' + bastidor + '"?';

            if (confirm(mensaje)) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endsection