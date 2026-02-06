@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-person-circle"></i> Detalles del Usuario
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
                                        <td>{{ $user->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nombre:</td>
                                        <td>{{ $user->nombre }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Apellidos:</td>
                                        <td>{{ $user->apellidos }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Email:</td>
                                        <td>
                                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                                {{ $user->email }}
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Información de Contacto --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-telephone"></i> Información de Contacto</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">Teléfono:</td>
                                        <td>{{ $user->telefono ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Extensión:</td>
                                        <td>{{ $user->extension ?? 'N/A' }}</td>
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
                                            <span class="badge bg-info">{{ $user->empresa->nombre ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Departamento:</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $user->departamento->nombre ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Centro:</td>
                                        <td>
                                            <span class="badge bg-success">{{ $user->centro->nombre ?? 'N/A' }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Roles y Permisos --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-shield-check"></i> Roles y Permisos</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">Roles:</td>
                                        <td>
                                            @if($user->roles->count() > 0)
                                                @foreach($user->roles as $role)
                                                    <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Sin roles asignados</span>
                                            @endif
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
                                            <i class="bi bi-calendar"></i> {{ $user->created_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $user->created_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Última actualización:</td>
                                        <td>
                                            <i class="bi bi-clock"></i> {{ $user->updated_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $user->updated_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al listado
                        </a>
                        <div>
                            @can('update', $user)
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
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