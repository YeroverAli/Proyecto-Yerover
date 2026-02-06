<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\OfertaLinea;
use Illuminate\Support\Str;

class OfertaPdfSubidaRaService
{
    protected string $texto;

    public function __construct(string $texto)
    {
        $this->texto = $this->normalizarTexto($texto);
    }

    protected function normalizarTexto(string $texto): string
    {
        $texto = str_replace("\r\n", "\n", $texto);
        return trim($texto);
    }

    public function extraerFechaPedido(): ?Carbon
    {
        // "Martes, 10 Junio 2025 / 11:00 hs"
        // Regex: dia + mes + anio
        if (preg_match('/(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/', $this->texto, $matches)) {
            $dia = $matches[1];
            $mesTexto = strtolower($matches[2]);
            $anio = $matches[3];

            $meses = [
                'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4, 'mayo' => 5, 'junio' => 6,
                'julio' => 7, 'agosto' => 8, 'septiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12
            ];

            $mes = $meses[$mesTexto] ?? 1;

            return Carbon::create($anio, $mes, $dia);
        }
        return now();
    }

    public function extraerCliente(): Cliente
    {
        // El texto "CLIENTE" seguido de "ESTABLECIMIENTO..." y luego el nombre
        // Sra. Doña Asuncion Sosa
        
        $nombre = 'Desconocido';
        $apellidos = 'Desconocido';
        
        // Buscar línea que empiece por Sra/Sr/D/Doña
        if (preg_match('/(?:Sra\.|Sr\.|D\.|Doña)\s+((?:Doña|Don)?\s+.*?)\n/', $this->texto, $match)) {
            $nombreCompleto = trim($match[1]); // Asuncion Sosa o Doña Asuncion Sosa
            $partes = explode(' ', $nombreCompleto);
            // Si empieza por Doña/Don, quitarlo para el nombre? O dejarlo
            if (in_array($partes[0], ['Doña', 'Don'])) { 
                 // Dejarlo como parte del nombre o quitarlo
            }
            $nombre = array_shift($partes);
            $apellidos = implode(' ', $partes);
        }

        // Email: buscar @
        $email = null;
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $this->texto, $match)) {
            $email = $match[0];
        }

        // Telefono: 9 digits starting with 6/7/8/9
        $telefono = null;
        if (preg_match('/\b[6789]\d{8}\b/', $this->texto, $match)) {
            $telefono = $match[0];
        }

        // Dirección: Linea antes del telefono? O cerca del CP
        $domicilio = 'Desconocido';
        $cp = null;
        
        // Buscamos CP (35xxx)
        if (preg_match('/\((\d{5})\)/', $this->texto, $match)) {
            $cp = $match[1];
            
            // La direccion suele estar en la misma linea o antes
            // "Santa Maria De Guia De Gran C., Las Palmas (35450)"
            // Buscar la linea que contiene el CP string
             $lines = explode("\n", $this->texto);
             foreach ($lines as $line) {
                 if (str_contains($line, "($cp)")) {
                     $domicilio = trim(str_replace(["($cp)", $cp], '', $line));
                     break;
                 }
             }
        }
        
        $dni = null; // No claro en el texto

        return Cliente::create([
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'dni' => $dni,
            'domicilio' => $domicilio,
            'codigo_postal' => $cp,
            'telefono' => $telefono,
            'email' => $email,
            'empresa_id' => 1,
        ]);
    }

    public function extraerVehiculo(): Vehiculo
    {
        // Modelo: ...
        $modelo = 'Desconocido';
        $version = '';
        
        // "Modelo:\nDACIA SANDERO Stepway Expression Go 74kW (100CV) ECO-G SMVG MT 6WGS"
        if (preg_match('/Modelo:\s*\n(.*?)\n/s', $this->texto, $match)) {
            $linea = trim($match[1]);
            // Separar Modelo de Version? DACIA SANDERO Stepway es modelo?
            $modelo = 'DACIA SANDERO Stepway'; // Hardcode o heurística
            if (stripos($linea, $modelo) !== false) {
                 $version = trim(str_ireplace($modelo, '', $linea));
            } else {
                 $modelo = $linea; 
            }
        }
        
        // Bastidor: Buscar ID superior si no hay VIN
        // "DJF174867443" - 12 chars
        $bastidor = 'PENDIENTE_' . uniqid();
        if (preg_match('/\b[A-Z0-9]{17}\b/', $this->texto, $m)) {
            $bastidor = $m[0];
        } elseif (preg_match('/DJF\d{9}/', $this->texto, $m)) {
            // Usar el ID de pedido como fallback si no hay VIN
            $bastidor = $m[0]; 
        }

        // Si existe vehículo lo devuelves? El create fallara si dup key
        $existente = Vehiculo::where('bastidor', $bastidor)->first();
        if ($existente) return $existente;

        return Vehiculo::create([
            'bastidor' => $bastidor,
            'referencia' => 'S/R',
            'modelo' => $modelo,
            'version' => $version,
            'empresa_id' => 1,
        ]);
    }


    // --- Extracción de Líneas ---

    private function guardarLinea(int $cabeceraId, string $tipo, string $desc, float $precio) {
        OfertaLinea::create([
            'oferta_cabecera_id' => $cabeceraId,
            'tipo' => $tipo,
            'descripcion' => $desc,
            'precio' => $precio
        ]);
    }

    private function parsePrecio(string $linea): ?float {
        // Busca precio con formato 1.000,00 € o -1.000,00 €
        if (preg_match('/(-?\d{1,3}(?:[.]\d{3})*,\d{2})\s*€/', $linea, $m)) {
            $raw = str_replace(['.', '€', ' '], '', $m[1]);
            $raw = str_replace(',', '.', $raw);
            return (float)$raw;
        }
        return null; // Return null if not found
    }

    public function extraerModeloInteres(int $id): void {
        // Modelo:\n TEXTO \n ... \n 0,00 €
        // Asignamos 0.0 si no encontramos precio base aqui
        if (preg_match('/Modelo:\s*\n(.*?)\n/s', $this->texto, $match)) {
            $this->guardarLinea($id, 'Modelo de interés', trim($match[1]), 0.0);
        }
    }

    public function extraerNissanAssistance(int $id): void {
        // No aplica
    }

    public function extraerPackDiseno(int $id): void {
        // No aplica
    }

    public function extraerPinturaInterior(int $id): void {
        // "Color:\nNegro Nacarado 676"
        if (preg_match('/Color:\s*\n(.*?)(?:\n|$)/s', $this->texto, $m)) {
             $desc = trim($m[1]);
             // A veces el precio esta debajo "0,00 €"
             $this->guardarLinea($id, 'opcion', "Color: $desc", 0.0);
        }
        // "Tapicería:\nTapicería Stepway DRAP08"
        if (preg_match('/Tapicería:\s*\n(.*?)(?:\n|$)/s', $this->texto, $m)) {
             $desc = trim($m[1]);
             $this->guardarLinea($id, 'opcion', $desc, 0.0);
        }
    }

    public function extraerDescuentos(int $id): void {
        // Promociones:\n ...lineas...
        // Bloque hasta BASE IMPONIBLE
        // Regex: (DTO ... -XXX €)
        
        $lines = explode("\n", $this->texto);
        foreach ($lines as $line) {
             // Buscar lineas con precio negativo
             if (str_contains($line, '-') && str_contains($line, '€')) {
                 $precio = $this->parsePrecio($line);
                 if ($precio !== null && $precio < 0) {
                     // Descripcion es el resto de la linea
                     $desc = trim(preg_replace('/-?\d{1,3}(?:[.]\d{3})*,\d{2}\s*€/', '', $line));
                     $desc = str_replace(['Promociones:', '€'], '', $desc); // Limpieza extra
                     $this->guardarLinea($id, 'descuento', trim($desc), $precio);
                 }
             }
        }
    }

    public function extraerTransporte(int $id): void {
        // Transporte: 270,00 €
        if (preg_match('/Transporte:\s*.*(\d{1,3}(?:[.]\d{3})*,\d{2}\s*€)/', $this->texto, $m)) {
            $val = $this->parsePrecio($m[1]) ?? 0.0;
            $this->guardarLinea($id, 'transporte', 'Transporte', $val);
        }
    }

    public function extraerBase(int $id): void {
        // BASE IMPONIBLE 12.650,04 €
        if (preg_match('/BASE IMPONIBLE\s*.*(\d{1,3}(?:[.]\d{3})*,\d{2}\s*€)/', $this->texto, $m)) {
             $val = $this->parsePrecio($m[1]) ?? 0.0;
             $this->guardarLinea($id, 'base', 'Base Imponible', $val);
        }
    }

    public function extraerIgic(int $id): void {
        // IGIC ... 1.201,75 €
        // Buscar linea IGIC y tomar ultimo precio
        $lines = explode("\n", $this->texto);
        foreach ($lines as $line) {
            if (stripos($line, 'IGIC') !== false && str_contains($line, '€')) {
                 // Puede haber (9.5%) 0,00 € ... 1201 €
                 // Extraer todos los precios
                 preg_match_all('/-?\d{1,3}(?:[.]\d{3})*,\d{2}\s*€/', $line, $matches);
                 if (!empty($matches[0])) {
                     $ultimoPrecio = end($matches[0]);
                     $val = $this->parsePrecio($ultimoPrecio);
                     if ($val > 0) {
                         $this->guardarLinea($id, 'igic', 'IGIC', $val);
                         break; // Asumo solo una linea de IGIC total
                     }
                 }
            }
        }
    }

    public function extraerImpuesto(int $id): void {
        // Imp. Matriculación ... 0,00 €
        $lines = explode("\n", $this->texto);
        foreach ($lines as $line) {
            if (stripos($line, 'Matriculación') !== false && stripos($line, 'Imp') !== false && str_contains($line, '€')) {
                 $val = $this->parsePrecio($line) ?? 0.0;
                 $this->guardarLinea($id, 'impuesto', 'Imp. Matriculación', $val);
                 break;
            }
        }
    }

    public function extraerSubtotal(int $id): void {
        // TOTAL IMPUESTOS INCLUIDOS ... 13.325,04 €
        if (preg_match('/TOTAL IMPUESTOS INCLUIDOS.*?(\d{1,3}(?:[.]\d{3})*,\d{2}\s*€)/', $this->texto, $m)) {
             $val = $this->parsePrecio($m[1]) ?? 0.0;
             $this->guardarLinea($id, 'subtotal', 'Total Impuestos Incluidos', $val);
        }
    }

    public function extraerGastos(int $id): void {
        // Matriculación y Pre-entrega ... 850,00 €
        if (preg_match('/Matriculación y Pre-entrega.*?(\d{1,3}(?:[.]\d{3})*,\d{2}\s*€)/', $this->texto, $m)) {
             $val = $this->parsePrecio($m[1]) ?? 0.0;
             $this->guardarLinea($id, 'gasto', 'Matriculación y Pre-entrega', $val);
        }
        // DEFLECTORES + ANTENA TIB ... 237,17 €
        // Buscar lineas en bloque GASTOS? O sueltas
        // El texto "DEFLECTORES..."
        if (preg_match('/DEFLECTORES.*?(\d{1,3}(?:[.]\d{3})*,\d{2}\s*€)/', $this->texto, $m)) {
             $val = $this->parsePrecio($m[1]) ?? 0.0;
             $this->guardarLinea($id, 'gasto', 'Deflectores + Antena', $val);
        }
    }

    public function extraerTotal(int $id): void {
        // TOTAL A PAGAR 14.938,96 €
         if (preg_match('/TOTAL A PAGAR.*?(\d{1,3}(?:[.]\d{3})*,\d{2}\s*€)/', $this->texto, $m)) {
             $val = $this->parsePrecio($m[1]) ?? 0.0;
             $this->guardarLinea($id, 'total', 'Total a Pagar', $val);
        }
    }
}