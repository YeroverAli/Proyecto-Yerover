@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Detalle de la Oferta') }} #{{ $oferta->id }}</span>
                    <a href="{{ route('ofertas.index') }}" class="btn btn-secondary btn-sm">Volver</a>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <h5>Información General</h5>
                        <p><strong>Cliente:</strong> {{ $oferta->cliente?->nombre ?? 'N/A' }} {{
                            $oferta->cliente?->apellidos ?? '' }}</p>
                        <p><strong>Vehículo:</strong> {{ $oferta->vehiculo?->modelo ?? 'N/A' }}</p>
                        <p><strong>Fecha:</strong> {{ $oferta->fecha }}</p>
                    </div>

                    <h5 class="mb-3">Líneas de la Oferta</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th class="text-end">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($oferta->lineas as $linea)
                            <tr>
                                <td>{{ $linea->tipo }}</td>
                                <td>{{ $linea->descripcion }}</td>
                                <td class="text-end">{{ number_format($linea->precio, 2) }} €</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay líneas registradas para esta oferta.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection