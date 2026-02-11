<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\OfertaLinea;
use Illuminate\Support\Str;
use App\Services\Contracts\OfertaPdfServiceInterface;

class OfertaPdfSubidaDaciaService implements OfertaPdfServiceInterface
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

        // Buscar línea del cliente (Sra./Sr. + Doña/Don opcional)
        if (preg_match('/^(Sra\.|Sr\.)\s+(Doña|Don)?\s*(.+)$/mi', $this->texto, $match)) {

            // $match[3] contiene SOLO "Asuncion Sosa"
            $nombreCompleto = trim($match[3]);

            // Separar nombre y apellidos
            $partes = preg_split('/\s+/', $nombreCompleto);

            $nombre = array_shift($partes); // Asuncion
            $apellidos = implode(' ', $partes); // Sosa
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

        // Check for existing client by Email
        if ($email) {
            $existingClient = Cliente::where('email', $email)->first();
            if ($existingClient) {
                return $existingClient;
            }
        }

        // Check for existing client by Phone
        if ($telefono) {
            $existingClient = Cliente::where('telefono', $telefono)->first();
            if ($existingClient) {
                return $existingClient;
            }
        }

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
        $modelo = 'Desconocido';
        $version = '';
        $referencia = null;
        $colorExterno = null;

        /*
         * 1. MODELO y VERSIÓN
         */
        if (preg_match('/Modelo:\s*\n([^\n]+)/i', $this->texto, $match)) {

            $lineaCompleta = trim($match[1]);

            // Heurística DACIA
            if (preg_match('/^(DACIA\s+SANDERO\s+Stepway)/i', $lineaCompleta, $m)) {
                $modelo = trim($m[1]);
                $version = trim(str_ireplace($modelo, '', $lineaCompleta));
            }
            else {
                $modelo = $lineaCompleta;
            }
        }

        /*
         * 2. REFERENCIA = símbolo
         * • símbolo : DJFBEVMT6WA45M520B
         */
        if (preg_match('/símbolo\s*:\s*([A-Z0-9]+)/i', $this->texto, $m)) {
            $referencia = strtoupper($m[1]);
        }

        /*
         * 3. COLOR EXTERNO
         * Negro Nacarado 676
         */
        if (preg_match('/Color:\s*\n([^\n\r]+)/i', $this->texto, $m)) {
            $colorExterno = trim($m[1]);
        }

        /*
         * 4. BASTIDOR
         */
        $bastidor = null;

        if (preg_match('/\b[A-Z0-9]{17}\b/', $this->texto, $m)) {
            $bastidor = $m[0];
        }
        elseif (preg_match('/DJF\d{9}/', $this->texto, $m)) {
            $bastidor = $m[0];
        }
        else {
            // Fallback: Generate a unique ID but fit in 17 chars
            // uniqid is 13 chars. "REN_" + 13 = 17.
            // Or just use substr of uniqid + random
            $bastidor = 'F_' . substr(uniqid(), -15);
        }

        /*
         * 5. Buscar vehículo existente
         */
        $existente = Vehiculo::where('bastidor', $bastidor)->first();
        if ($existente) {
            return $existente;
        }

        /*
         * 6. Crear vehículo
         */
        return Vehiculo::create([
            'bastidor' => $bastidor,
            'referencia' => $referencia,
            'modelo' => $modelo,
            'version' => $version,
            'color_externo' => $colorExterno,
            'empresa_id' => 1,
        ]);
    }




    // --- Extracción de Líneas ---

    private function guardarLinea(int $cabeceraId, string $tipo, string $desc, float $precio)
    {
        OfertaLinea::create([
            'oferta_cabecera_id' => $cabeceraId,
            'tipo' => $tipo,
            'descripcion' => $desc,
            'precio' => $precio
        ]);
    }

    private function parsePrecio(string $linea): ?float
    {
        // Busca precio con formato 1.000,00 € o -1.000,00 €
        if (preg_match('/(-?\d{1,3}(?:[.]\d{3})*,\d{2})\s*€/', $linea, $m)) {
            $raw = str_replace(['.', '€', ' '], '', $m[1]);
            $raw = str_replace(',', '.', $raw);
            return (float)$raw;
        }
        return null; // Return null if not found
    }

    public function extraerModeloInteres(int $id): void
    {
        $descripcion = null;

        // Modelo:
        // DACIA SANDERO Stepway Expression Go 74kW (100CV) ECO-G SMVG MT 6WGS
        if (preg_match('/Modelo:\s*\n([^\n]+)/i', $this->texto, $match)) {
            $descripcion = trim($match[1]);
        }

        if ($descripcion !== null) {
            // De momento precio 0.0
            $this->guardarLinea(
                $id,
                'Modelo de interés',
                $descripcion,
                0.0
            );
        }
    }

    public function extraerPrecioModelo(int $id): void
    {
        // OLD Logic was wrong (looking at TOTAL IMPUESTOS)
        // NEW Logic: Look at "Modelo:" section
        if (preg_match('/Modelo:\s*(.*?)(?=\n[A-Z0-9]{17}|\nColor:|\n[A-Z]+)/s', $this->texto, $m)) {
            $modelBlock = $m[1];
            // Look for price in this block
            if (preg_match('/(\d{1,3}(?:[.]\d{3})*,\d{2})\s*€/', $modelBlock, $pm)) {
                $precio = $this->parsePrecio($pm[0]);
                if ($precio !== null) {
                    OfertaLinea::where('oferta_cabecera_id', $id)
                        ->where('tipo', 'Modelo de interés')
                        ->update([
                        'precio' => $precio,
                    ]);
                    return; // Found it, exit
                }
            }
        }

        // Fallback: If not found, look for displaced price near "Gastos:"
        // Pattern: TOTAL IMPUESTOS INCLUIDOS ... Gastos: ... [PRICE1] ... [PRICE2]
        if (preg_match('/TOTAL IMPUESTOS INCLUIDOS.*?Gastos:\s*\n+((?:.*?\n)*?)(?=Matriculaci|TOTAL VEHICULO)/s', $this->texto, $m)) {
            $block = $m[1];
            preg_match_all('/\d{1,3}(?:[.]\d{3})*,\d{2}\s*€/', $block, $matches);
            if (count($matches[0]) >= 2) {
                // Assuming first price is Model Price (displaced) and second is Total
                $precio = $this->parsePrecio($matches[0][0]);
                if ($precio !== null) {
                    OfertaLinea::where('oferta_cabecera_id', $id)
                        ->where('tipo', 'Modelo de interés')
                        ->update([
                        'precio' => $precio,
                    ]);
                }
            }
        }
    }

    private function extraerPreciosFlotantes(): array
    {
        $colorPrice = 0.0;
        $tapiceriaPrice = 0.0;

        // Buscar bloque justo antes de "Transporte:"
        if (preg_match('/((?:.*?\n)*?)Transporte:/s', $this->texto, $m)) {
            $before = $m[1];
            $lines = explode("\n", trim($before));
            $prices = [];

            // Recorrer hacia atrás buscando líneas que sean SOLO precio
            for ($i = count($lines) - 1; $i >= 0; $i--) {
                $line = trim($lines[$i]);
                if ($line === '')
                    continue;

                $p = $this->parsePrecio($line);
                if ($p !== null) {
                    array_unshift($prices, $p);
                }
                else {
                    // Si encontramos una línea con texto que no es precio, paramos
                    break;
                }
            }

            // Asumimos que los dos últimos son Color y Tapicería
            if (count($prices) >= 1) {
                $tapiceriaPrice = $prices[count($prices) - 1];
            }
            if (count($prices) >= 2) {
                $colorPrice = $prices[count($prices) - 2];
            }
        }

        return ['color' => $colorPrice, 'tapiceria' => $tapiceriaPrice];
    }

    public function extraerPintura(int $id, float $precio = 0.0): void
    {
        // Color:
        // Negro Nacarado 676
        if (preg_match('/Color:\s*\n+([^\n]+)/i', $this->texto, $match)) {
            $descripcion = trim($match[1]);

            $this->guardarLinea(
                $id,
                'Color',
                $descripcion,
                $precio
            );
        }
    }

    public function extraerTapiceria(int $id, float $precio = 0.0): void
    {
        // Tapicería:
        // Tapicería Stepway DRAP08
        if (preg_match('/Tapicería:\s*\n+([^\n]+)/i', $this->texto, $match)) {
            $descripcion = trim($match[1]);

            $this->guardarLinea(
                $id,
                'Tapicería',
                $descripcion,
                $precio
            );
        }
    }

    public function extraerOpciones(int $id): void
    {
        // Capture block between "Opciones:" and "Transporte:"
        if (preg_match('/Opciones:\s*\n(.*?)Transporte:/s', $this->texto, $m)) {
            $block = trim($m[1]);
            $lines = explode("\n", $block);

            $currentDesc = null;

            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '')
                    continue;

                $price = $this->parsePrecio($line);

                if ($price !== null) {
                    // It's a price
                    if ($currentDesc !== null) {
                        // We have a description, so this price belongs to it. Match!
                        $this->guardarLinea(
                            $id,
                            'Opcion',
                            $currentDesc,
                            $price
                        );
                        $currentDesc = null; // Reset
                    }
                    else {
                    // Price without description. Floating price (Color/Tapiceria). Ignore for Options.
                    }
                }
                else {
                    // It's text (Description)
                    $currentDesc = $line;
                }
            }
        }
    }

    public function extraerTransporte(int $id): void
    {
        // Search for "Transporte:" and find the first price after it.
        if (preg_match('/Transporte:/i', $this->texto, $m, PREG_OFFSET_CAPTURE)) {
            $offset = $m[0][1];
            $rest = substr($this->texto, $offset);

            // Find first price
            if (preg_match('/(?:\d{1,3}(?:[.]\d{3})*,\d{2})\s*€/', $rest, $priceMatch)) {
                $precio = $this->parsePrecio($priceMatch[0]);
                if ($precio !== null) {
                    $this->guardarLinea(
                        $id,
                        'Transporte',
                        '',
                        $precio
                    );
                }
            }
        }
    }

    public function extraerPromociones(int $id): void
    {
        // 1. Capture Main Promociones block
        // End at BASE IMPONIBLE
        if (preg_match('/Promociones:\s*\n(.*?)(?=BASE IMPONIBLE)/s', $this->texto, $m, PREG_OFFSET_CAPTURE)) {
            $blockContent = $m[1][0];
            $blockEndOffset = $m[1][1] + strlen($blockContent);

            $lines = explode("\n", $blockContent);
            $pendingDescs = [];

            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '')
                    continue;
                if (str_contains($line, 'Promociones:'))
                    continue;

                $price = $this->parsePrecio($line);
                if ($price !== null) {
                    if (!empty($pendingDescs)) {
                        $desc = array_pop($pendingDescs);
                        $this->guardarLinea($id, 'Promocion', $desc, $price);
                    }
                }
                else {
                    $pendingDescs[] = $line;
                }
            }

            // 2. Look for displaced prices after the block
            if (!empty($pendingDescs)) {
                $restOfText = substr($this->texto, $blockEndOffset);
                // Find all negative prices in the rest
                preg_match_all('/-\d{1,3}(?:[.]\d{3})*,\d{2}\s*€/', $restOfText, $matches);

                foreach ($matches[0] as $match) {
                    if (empty($pendingDescs))
                        break;

                    $val = $this->parsePrecio($match);
                    $desc = array_shift($pendingDescs);

                    $this->guardarLinea($id, 'Promocion', $desc, $val);
                }
            }
        }
    }

    public function extraerTramitacion(int $id): void
    {
        // 1. Base Imponible
        if (preg_match('/BASE IMPONIBLE\s*\n\s*([0-9.,]+.*€)/', $this->texto, $m)) {
            $baseImponible = $this->parsePrecio($m[1]);
            if ($baseImponible !== null) {
                $this->guardarLinea($id, 'Base Imponible', '', $baseImponible);
            }
        }

        // 2. Taxes (Imp. Matriculación, IGIC)
        // 2. Taxes (Imp. Matriculación, IGIC)
        if (preg_match('/BASE IMPONIBLE.*?\n.*?€\s*\n(.*?)(?=TOTAL IMPUESTOS INCLUIDOS)/s', $this->texto, $m)) {
            $block = trim($m[1]);

            // Extract Prices First
            preg_match_all('/\d{1,3}(?:[.]\d{3})*,\d{2}\s*€/', $block, $pricesMatch);
            $prices = [];
            foreach ($pricesMatch[0] as $pStr)
                $prices[] = $this->parsePrecio($pStr);

            // Extract Descriptions (Text + (N%))
            // Pattern: Words separated by single spaces, ending in (N,N%)
            // We use a regex that captures "Word Word (N%)" but stops at double spaces or newlines.
            $descRegex = '/(?:\b[A-Za-z0-9.]+(?:\s[A-Za-z0-9.]+)*)\s*\([0-9,]+%\)/u';
            preg_match_all($descRegex, $block, $descMatches);
            $descriptions = $descMatches[0];

            // Remove Descriptions and Prices from block to find Headers
            // This prevents "IGIC" inside "IGIC Normal" from being counted as a header.
            $cleanBlock = $block;
            foreach ($descriptions as $d) {
                $cleanBlock = str_replace($d, '', $cleanBlock);
            }
            foreach ($pricesMatch[0] as $pStr) {
                $cleanBlock = str_replace($pStr, '', $cleanBlock);
            }

            // Extract Headers from cleaned block
            // Look for specific known headers or general capital words if needed.
            $headerRegex = '/(Imp\.\s*Matriculaci[óo]n|Imp\.\s*Mat\.|IGIC|IVA|IPSI)/ui';
            preg_match_all($headerRegex, $cleanBlock, $headerMatches);
            $headers = $headerMatches[0];

            // Pair them
            for ($i = 0; $i < count($prices); $i++) {
                $header = $headers[$i] ?? 'Impuesto';
                $desc = $descriptions[$i] ?? '';
                $this->guardarLinea($id, $header, $desc, $prices[$i]);
            }
        }

        // 3. Total Impuestos Incluidos
        if (preg_match('/TOTAL IMPUESTOS INCLUIDOS(.*?)(?=Matriculación|TOTAL VEHICULO)/s', $this->texto, $m)) {
            $block = $m[1];
            $totalImpuestos = 0.0;
            preg_match_all('/\d{1,3}(?:[.]\d{3})*,\d{2}\s*€/', $block, $pm);
            foreach ($pm[0] as $pStr) {
                $val = $this->parsePrecio($pStr);
                // Heuristic: pick largest positive that is likely the total
                if ($val > $totalImpuestos) {
                    $totalImpuestos = $val;
                }
            }
            if ($totalImpuestos > 0) {
                $this->guardarLinea($id, 'Total Impuestos Incluidos', '', $totalImpuestos);
            }
        }
    }

    public function extraerGastos(int $id): void
    {
        // Block from "Gastos:" to "TOTAL VEHICULO"
        if (preg_match('/Gastos:(.*?)TOTAL VEHICULO/s', $this->texto, $m)) {
            $block = $m[1];
            $lines = explode("\n", $block);
            $currentDesc = null;

            // We need to avoid capturing the Total Impuestos price again if it appears here?
            // Or grouping headers.
            // And avoid Negative prices (Promos).

            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '')
                    continue;

                $price = $this->parsePrecio($line);

                if ($price !== null) {
                    if ($price < 0)
                        continue; // Skip promos handled elsewhere
                    // Skip if it looks like a large total? (e.g. > 10000)
                    // But Gastos can be expensive? Usually not > 10000.
                    // Total Impuestos Incluidos is ~13000.
                    if ($price > 10000)
                        continue;

                    if ($currentDesc !== null) {
                        $this->guardarLinea($id, 'Gasto', $currentDesc, $price);
                        $currentDesc = null;
                    }
                }
                else {
                    $currentDesc = $line;
                }
            }
        }
    }

    public function extraerTotal(int $id): void
    {
        // TOTAL A PAGAR (Usually same)
        if (preg_match('/TOTAL A PAGAR\s*\n\s*([0-9.,]+.*€)/', $this->texto, $m)) {
            $totalPagar = $this->parsePrecio($m[1]);
            if ($totalPagar !== null) {
                $this->guardarLinea($id, 'Total a Pagar', '', $totalPagar);
            }
        }
    }

    public function procesarOferta(int $id): void
    {
        // Este PDF es distinto → otro orden, otros métodos
        $this->extraerModeloInteres($id);
        $this->extraerPrecioModelo($id);

        $precios = $this->extraerPreciosFlotantes();

        $this->extraerPintura($id, $precios['color']);
        $this->extraerTapiceria($id, $precios['tapiceria']);
        $this->extraerOpciones($id);
        $this->extraerTransporte($id);
        $this->extraerPromociones($id);
        $this->extraerTramitacion($id);
        $this->extraerGastos($id);
        $this->extraerTotal($id);

    }


}