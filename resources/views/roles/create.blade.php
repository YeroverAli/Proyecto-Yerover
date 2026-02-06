@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Crear Rol</h3>
        </div>
        <div class="card-body"></div>
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                @include('roles.partials.form')

                <div class="row mb-0">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Volver</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection