@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Crear cliente') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('clientes.store') }}">
                        @csrf

                        @include('clientes.partials.form', ['cliente' => null])

                        {{-- Botones --}}
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Guardar
                                </button>
                                <a href="{{ route('clientes.index') }}" class="btn btn-secondary ms-2">
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