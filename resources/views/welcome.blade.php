@extends('layouts.app')

@section('content')
    <div class="container">

        <h1 class="mb-4">Panel principal</h1>

        <div class="row">

            {{-- CRUD Usuarios --}}
            @can('viewAny', App\Models\User::class)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Usuarios</h5>
                            <p class="card-text">
                                Gestión y visualización de usuarios del sistema.
                            </p>
                            <a href="{{ route('users.index') }}" class="btn btn-primary">
                                Ir al listado
                            </a>
                        </div>
                    </div>
                </div>
            @endcan

            @can('viewAny', App\Models\Departamento::class)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Departamentos</h5>
                            <p class="card-text">
                                Gestión y visualización de los distintos departamentos de la empresa.
                            </p>
                            <a href="{{ route('departamentos.index') }}" class="btn btn-primary">
                                Ir al listado
                            </a>
                        </div>
                    </div>
                </div>
            @endcan

            @can('viewAny', App\Models\Centro::class)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Centros</h5>
                            <p class="card-text">
                                Gestión y visualización de los distintos centros de la empresa.
                            </p>
                            <a href="{{ route('centros.index') }}" class="btn btn-primary">
                                Ir al listado
                            </a>
                        </div>
                    </div>
                </div>
            @endcan
            @if(auth()->user()->hasPermissionTo('ver roles'))
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Roles</h5>
                            <p class="card-text">
                                Gestión y visualización de los distintos roles de usuario.
                            </p>
                            <a href="{{ route('roles.index') }}" class="btn btn-primary">
                                Ir al listado
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @can('viewAny', App\Models\Cliente::class)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Clientes</h5>
                            <p class="card-text">
                                Gestión y visualización de los clientes.
                            </p>
                            <a href="{{ route('clientes.index') }}" class="btn btn-primary">
                                Ir al listado
                            </a>
                        </div>
                    </div>
                </div>
            @endcan
            @can('viewAny', App\Models\Vehiculo::class)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Vehiculos</h5>
                            <p class="card-text">
                                Visualización de los vehiculos.
                            </p>
                            <a href="{{ route('vehiculos.index') }}" class="btn btn-primary">
                                Ir al listado
                            </a>
                        </div>
                    </div>
                </div>
            @endcan

            @can('viewAny', App\Models\OfertaCabecera::class)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Ofertas</h5>
                            <p class="card-text">
                                Gestión y visualización de las ofertas.
                            </p>
                            <a href="{{ route('ofertas.index') }}" class="btn btn-primary">
                                Ir al listado
                            </a>
                        </div>
                    </div>
                </div>
            @endcan

            @can('viewAny', App\Models\OfertaCabecera::class)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Importar PDF</h5>
                            <p class="card-text">
                                Subir y procesar PDFs de ofertas (Dacia, Renault, Nsmit).
                            </p>
                            <a href="{{ route('pdf.index') }}" class="btn btn-primary">
                                Ir al importador
                            </a>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>


    </div>
@endsection