@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Editar Rol</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')

                @include('roles.partials.form')

                <div class="row mb-0">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Volver</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
