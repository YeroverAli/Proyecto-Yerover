@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-building"></i> Detalles del Departamento
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
                                        <td>{{ $departamento->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nombre:</td>
                                        <td>{{ $departamento->nombre }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Abreviatura:</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $departamento->abreviatura ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">CIF:</td>
                                        <td>{{ $departamento->cif ?? 'N/A' }}</td>
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
                                            <span class="badge bg-info">{{ $departamento->users->count() }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            @if($departamento->users->count() > 0)
                                <div class="mt-3">
                                    <small class="text-muted">Usuarios en este departamento:</small>
                                    <ul class="list-group mt-2">
                                        @foreach($departamento->users as $user)
                                            <li class="list-group-item">
                                                {{ $user->nombre }} {{ $user->apellidos }} 
                                                <small class="text-muted">({{ $user->email }})</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <p class="text-muted mt-2">No hay usuarios asignados a este departamento.</p>
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
                                            <i class="bi bi-calendar"></i> {{ $departamento->created_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $departamento->created_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Última actualización:</td>
                                        <td>
                                            <i class="bi bi-clock"></i> {{ $departamento->updated_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $departamento->updated_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('departamentos.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al listado
                        </a>
                        <div>
                            @can('update', $departamento)
                                <a href="{{ route('departamentos.edit', $departamento) }}" class="btn btn-warning">
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

