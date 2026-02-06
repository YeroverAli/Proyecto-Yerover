@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-geo-alt"></i> Detalles del Centro
                    </h4>
                </div>

                <div class="card-body">
                    {{-- Información Principal --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información Principal</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">ID:</td>
                                        <td>{{ $centro->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nombre:</td>
                                        <td>{{ $centro->nombre }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Empresa:</td>
                                        <td>
                                            <span class="badge bg-info">{{ $centro->empresa->nombre ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Información de Ubicación --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-geo-alt-fill"></i> Información de Ubicación</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">Dirección:</td>
                                        <td>{{ $centro->direccion ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Provincia:</td>
                                        <td>{{ $centro->provincia ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Municipio:</td>
                                        <td>{{ $centro->municipio ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Usuarios Asociados --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-people"></i> Usuarios Asociados</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">Total de usuarios:</td>
                                        <td>
                                            <span class="badge bg-info">{{ $centro->users->count() }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            @if($centro->users->count() > 0)
                                <div class="mt-3">
                                    <small class="text-muted">Usuarios en este centro:</small>
                                    <ul class="list-group mt-2">
                                        @foreach($centro->users as $user)
                                            <li class="list-group-item">
                                                {{ $user->nombre }} {{ $user->apellidos }} 
                                                <small class="text-muted">({{ $user->email }})</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <p class="text-muted mt-2">No hay usuarios asignados a este centro.</p>
                            @endif
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
                                            <i class="bi bi-calendar"></i> {{ $centro->created_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $centro->created_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Última actualización:</td>
                                        <td>
                                            <i class="bi bi-clock"></i> {{ $centro->updated_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $centro->updated_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('centros.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al listado
                        </a>
                        <div>
                            @can('update', $centro)
                                <a href="{{ route('centro.edit', $centro) }}" class="btn btn-warning">
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

