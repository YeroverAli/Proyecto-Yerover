<?php

namespace App\Exports;

use App\Models\Vehiculo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VehiculosExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Vehiculo::with('empresa')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Modelo',
            'Versión',
            'Bastidor',
            'Referencia',
            'Color Externo',
            'Color Interno',
            'Empresa',
        ];
    }

    public function map($vehiculo): array
    {
        return [
            $vehiculo->id,
            $vehiculo->modelo,
            $vehiculo->version,
            $vehiculo->bastidor,
            $vehiculo->referencia,
            $vehiculo->color_externo,
            $vehiculo->color_interno,
            $vehiculo->empresa->nombre ?? 'N/A',
        ];
    }
}
