@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-person-badge"></i> Detalles del Cliente
                    </h4>
                </div>

                <div class="card-body">
                    {{-- Información Personal --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-person"></i> Información Personal</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">ID:</td>
                                        <td>{{ $cliente->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nombre:</td>
                                        <td>{{ $cliente->nombre }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Apellidos:</td>
                                        <td>{{ $cliente->apellidos }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">DNI:</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $cliente->dni }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Información de Contacto --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Información de Contacto</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">Domicilio:</td>
                                        <td>{{ $cliente->domicilio ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Código Postal:</td>
                                        <td>{{ $cliente->codigo_postal ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Información Organizacional --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-building"></i> Información Organizacional</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">Empresa:</td>
                                        <td>
                                            <span class="badge bg-info">{{ $cliente->empresa->nombre ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Información del Sistema --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Información del Sistema</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">Fecha de creación:</td>
                                        <td>
                                            <i class="bi bi-calendar"></i> {{ $cliente->created_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $cliente->created_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Última actualización:</td>
                                        <td>
                                            <i class="bi bi-clock"></i> {{ $cliente->updated_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $cliente->updated_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al listado
                        </a>
                        <div>
                            @can('update', $cliente)
                                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

