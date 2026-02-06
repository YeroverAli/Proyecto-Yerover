@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Editar vehiculo') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('vehiculos.update', $vehiculo) }}">
                        @csrf
                        @method('PUT')

                        @include('vehiculos.partials.form', ['vehiculo' => $vehiculo])

                        {{-- Botones --}}
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Actualizar
                                </button>
                                <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary ms-2">
                                    Volver
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection