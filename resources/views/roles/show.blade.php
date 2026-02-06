@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-shield-check"></i> Detalles del Rol
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
                                        <td>{{ $role->id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Nombre:</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $role->name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Guard:</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $role->guard_name }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Permisos Asignados --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-key"></i> Permisos Asignados</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" style="width: 30%;">Total de permisos:</td>
                                        <td>
                                            <span class="badge bg-success">{{ $role->permissions->count() }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            @if($role->permissions->count() > 0)
                                <div class="mt-3">
                                    <small class="text-muted">Permisos del rol:</small>
                                    <div class="mt-2">
                                        @foreach($role->permissions as $permission)
                                            <span class="badge bg-info me-1 mb-1">{{ $permission->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p class="text-muted mt-2">Este rol no tiene permisos asignados.</p>
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
                                            <i class="bi bi-calendar"></i> {{ $role->created_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $role->created_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Última actualización:</td>
                                        <td>
                                            <i class="bi bi-clock"></i> {{ $role->updated_at->format('d/m/Y') }}
                                            <small class="text-muted">a las {{ $role->updated_at->format('H:i') }}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al listado
                        </a>
                        <div>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

