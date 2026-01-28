@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Crear departamento') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('departamentos.store') }}">
                        @csrf

                        @include('departamentos.partials.form', ['departamento' => null])

                        {{-- Botones --}}
                        <div class="row mb-0">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    Guardar
                                </button>
                                <a href="{{ route('departamentos.index') }}" class="btn btn-secondary ms-2">
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

