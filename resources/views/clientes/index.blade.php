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
        <h1 class="mb-0">Clientes</h1>
        @can('create', App\Models\Cliente::class)
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Crear Cliente
            </a>
        @endcan
    </div>

    {{-- Buscador --}}
    <form method="GET" class="mb-3">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Buscar Clientes"
               value="{{ request('search') }}">
    </form>

    {{-- Tabla de Clientes --}}
    <table class="table table-bordered w-100">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Apellidos</th>
                <th class="text-center">Empresa</th>
                <th class="text-center">DNI</th>
                <th class="text-center">Domicilio</th>
                <th class="text-center">Código Postal</th>
                @if(auth()->user()->hasPermissionTo('ver clientes') || auth()->user()->hasPermissionTo('editar clientes') || auth()->user()->hasPermissionTo('eliminar clientes'))
                <th class="text-center">Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($clientes as $cliente)
                <tr>
                    <td class="text-center">{{ $cliente->id }}</td>
                    <td class="text-center">{{ $cliente->nombre }}</td>
                    <td class="text-center">{{ $cliente->apellidos }}</td>
                    <td class="text-center">{{ $cliente->empresa->nombre ?? 'N/A'  }}</td>
                    <td class="text-center">{{ $cliente->dni }}</td>
                    <td class="text-center">{{ $cliente->domicilio }}</td>
                    <td class="text-center">{{ $cliente->codigo_postal }}</td>
                    @if(auth()->user()->can('view', $cliente) || auth()->user()->can('update', $cliente) || auth()->user()->can('delete', $cliente))
                    <td class="text-center">
                        @can('view', $cliente)
                            <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-info">
                                Ver
                            </a>
                        @endcan
                        @can('update', $cliente)
                            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-warning">
                                Editar
                            </a>
                        @endcan
                        @can('delete', $cliente)
                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline" id="delete-form-{{ $cliente->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $cliente->id }}, {{ json_encode($cliente->nombre) }})">
                                    Eliminar
                                </button>
                            </form>
                        @endcan
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hay Clientes</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            {{ $clientes->withQueryString()->links() }}
        </div>
    </div> {{-- cierre de .container --}}
    
    <script>
        function confirmDelete(id, nombre) {
            const mensaje = '¿Estás seguro de eliminar el cliente "' + nombre + '"?';
    
            if (confirm(mensaje)) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
    @endsection
