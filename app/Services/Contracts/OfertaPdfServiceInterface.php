<?php

namespace App\Services\Contracts;

use App\Models\Cliente;
use App\Models\Vehiculo;
use Carbon\Carbon;

interface OfertaPdfServiceInterface
{
    public function extraerCliente(): Cliente;

    public function extraerVehiculo(): Vehiculo;

    public function extraerFechaPedido(): ?Carbon;

    /**
     * Aquí va TODA la extracción de líneas económicas
     */
    public function procesarOferta(int $ofertaCabeceraId): void;
}
