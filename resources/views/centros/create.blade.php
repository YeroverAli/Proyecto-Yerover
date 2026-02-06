@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Crear centro') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('centros.store') }}">
                        @csrf

                        @include('centros.partials.form', ['centro' => null])

                        {{-- Botones --}}
                        <div class="row mb-0">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    Guardar
                                </button>
                                <a href="{{ route('centros.index') }}" class="btn btn-secondary ms-2">
                                    Volver
                                </a>
                            </div>
                        </div>

                    </form>      
                </div>
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
            </div>
        </div>
    </div>
</div>
@endsection

