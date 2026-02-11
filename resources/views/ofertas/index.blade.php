@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Ofertas Comerciales') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Vehículo</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ofertas as $oferta)
                            <tr>
                                <td>{{ $oferta->id }}</td>
                                <td>{{ $oferta->cliente->nombre ?? 'N/A' }} {{ $oferta->cliente->apellidos ?? '' }}</td>
                                <td>{{ $oferta->vehiculo->modelo ?? 'N/A' }} </td>
                                <td>{{ $oferta->fecha }}</td>
                                <td>
                                    <a href="{{ route('ofertas.show', $oferta) }}" class="btn btn-sm btn-info">Ver</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $ofertas->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection