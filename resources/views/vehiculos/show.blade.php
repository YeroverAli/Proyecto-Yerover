@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-car-front"></i> Detalles del Vehículo
                    </h4>
                </div>

                <div class="card-body">
                    {{-- Información del Vehículo --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Vehículo</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">ID:</td>
                                        <td>{{ $vehiculo->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Chasis:</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $vehiculo->chasis }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Modelo:</td>
                                        <td>{{ $vehiculo->modelo }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Versión:</td>
                                        <td>{{ $vehiculo->version }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Bastidor:</td>
                                        <td>{{ $vehiculo->bastidor ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Referencia:</td>
                                        <td>{{ $vehiculo->referencia ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Información de Colores --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-palette"></i> Información de Colores</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">Color Externo:</td>
                                        <td>
                                            @if($vehiculo->color_externo)
                                                <span class="badge" style="background-color: {{ strtolower($vehiculo->color_externo) }}; color: white;">
                                                    {{ $vehiculo->color_externo }}
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Color Interno:</td>
                                        <td>
                                            @if($vehiculo->color_interno)
                                                <span class="badge" style="background-color: {{ strtolower($vehiculo->color_interno) }}; color: white;">
                                                    {{ $vehiculo->color_interno }}
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
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
                                            <span class="badge bg-info">{{ $vehiculo->empresa->nombre ?? 'N/A' }}</span>
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
                                            <i class="bi bi-calendar"></i> {{ $vehiculo->created_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $vehiculo->created_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Última actualización:</td>
                                        <td>
                                            <i class="bi bi-clock"></i> {{ $vehiculo->updated_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $vehiculo->updated_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al listado
                        </a>
                        <div>
                            @can('update', $vehiculo)
                                <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning">
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

