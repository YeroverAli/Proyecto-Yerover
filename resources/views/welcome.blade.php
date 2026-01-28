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

        {{-- EJEMPLOS FUTUROS --}}
        {{-- 
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Productos</h5>
                    <p class="card-text">CRUD de productos</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        Ir al listado
                    </a>
                </div>
            </div>
        </div>
        --}}

    </div>

</div>
@endsection