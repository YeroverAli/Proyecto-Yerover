<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\OfertaLinea;

class OfertaPdfService
{
    protected string $texto;

    public function __construct(string $texto)
    {
        $this->texto = $this->normalizarTexto($texto);
    }

    /**
     * Normaliza el texto del PDF
     */
    protected function normalizarTexto(string $texto): string
    {
        // Unificar saltos de línea
        $texto = str_replace("\r\n", "\n", $texto);

        // Quitar espacios duplicados
        $texto = preg_replace('/[ \t]+/', ' ', $texto);

        return trim($texto);
    }

    public function extraerFechaPedido(): ?Carbon
    {
        // Busca: "Fecha Pedido 20/06/2025"
        $patron = '/Fecha Pedido\s+(\d{2}\/\d{2}\/\d{4})/';

        if (preg_match($patron, $this->texto, $coincidencias)) {
            return Carbon::createFromFormat('d/m/Y', $coincidencias[1]);
        }

        return null;
    }


    /**
     * Extrae la fecha del pedido del PDF
     */
    public function extraerCliente(): Cliente
    {
        /*
         * 1. Bloque del cliente
         * Empieza en "Sr." y termina en "Fecha estimada de entrega"
         */
        preg_match(
            '/Sr\.\s+(.*?)\n\s*Fecha estimada de entrega/si',
            $this->texto,
            $bloqueMatch
        );

        if (empty($bloqueMatch[1])) {
            throw new \Exception('No se pudo localizar el bloque del cliente');
        }

        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $bloqueMatch[1]))
        ));

        /*
         * 2. Nombre y apellidos
         */
        $partesNombre = explode(' ', $lineas[0] ?? '');
        $nombre = $partesNombre[0] ?? 'Desconocido';
        $apellidos = implode(' ', array_slice($partesNombre, 1)) ?: 'Desconocido';

        /*
         * 3. Domicilio
         */
        $domicilio = $lineas[1] ?? 'Desconocido';

        /*
         * 4. Código postal (solo de la línea de ciudad)
         */
        $codigo_postal = null;
        $lineaCiudad = $lineas[2] ?? '';

        if (preg_match('/\((\d{5})\)\s*$/', $lineaCiudad, $cpMatch)) {
            $codigo_postal = $cpMatch[1];
        }

        /*
         * 5. Teléfono y email (SOLO del bloque del cliente)
         */
        $telefono = null;
        $email = null;

        foreach ($lineas as $linea) {
            if (!$telefono && preg_match('/\b\d{9}\b/', $linea, $telefonoMatch)) {
                $telefono = $telefonoMatch[0];
            }

            if (
            !$email &&
            preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $linea, $emailMatch)
            ) {
                $email = $emailMatch[0];
            }
        }

        /*
         * 6. DNI (opcional)
         */
        preg_match('/NIF[:\s]+([0-9]{8}[A-Z])/i', $this->texto, $dniMatch);
        $dni = isset($dniMatch[1]) ? strtoupper($dniMatch[1]) : null;

        /*
         * 7. Buscar cliente existente SOLO si hay DNI
         */
        if ($dni) {
            $clienteExistente = Cliente::where('dni', $dni)->first();
            if ($clienteExistente) {
                return $clienteExistente;
            }
        }

        /*
         * 8. Crear cliente nuevo
         */
        return Cliente::create([
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'dni' => $dni,
            'domicilio' => $domicilio,
            'codigo_postal' => $codigo_postal,
            'telefono' => $telefono,
            'email' => $email,
            'empresa_id' => 1, // empresa emisora
        ]);
    }

    public function extraerVehiculo(): Vehiculo
    {
        /*
         * 1. Bastidor (VIN)
         */
        preg_match(
            '/Bastidor\s+([A-Z0-9]{17})/i',
            $this->texto,
            $bastidorMatch
        );

        if (empty($bastidorMatch[1])) {
            throw new \Exception('No se pudo extraer el bastidor del PDF');
        }

        $bastidor = strtoupper($bastidorMatch[1]);

        /*
         * 2. Bloque "Modelo de interés"
         */
        preg_match(
            '/Modelo de interés\s*(.*?)\n\s*Opciones/si',
            $this->texto,
            $modeloMatch
        );

        if (empty($modeloMatch[1])) {
            throw new \Exception('No se pudo extraer el bloque Modelo de interés');
        }

        $bloqueModelo = trim($modeloMatch[1]);

        $lineasModelo = array_values(array_filter(
            array_map('trim', explode("\n", $bloqueModelo))
        ));

        /*
         * 3. Localizar la línea REAL del modelo
         * (la única que contiene [REFERENCIA])
         */
        $lineaModelo = null;

        foreach ($lineasModelo as $linea) {
            if (str_contains($linea, '[') && str_contains($linea, ']')) {
                $lineaModelo = $linea;
                break;
            }
        }

        if (!$lineaModelo) {
            throw new \Exception('No se encontró la línea del modelo del vehículo');
        }

        /*
         * Ejemplo real de $lineaModelo:
         * Townstar Combi 5 L1 1.3G EU6E 96 kW (130 CV) 6M/T N-Connecta [TWC53GM1N-D3KQA5]
         */

        /*
         * 4. Referencia
         */
        preg_match('/\[(.*?)\]/', $lineaModelo, $refMatch);
        $referencia = $refMatch[1] ?? null;

        /*
         * 5. Modelo y versión
         */
        $descripcion = trim(preg_replace('/\[.*?\]/', '', $lineaModelo));

        // Modelo = "Townstar Combi 5"
        $partes = explode(' ', $descripcion, 4);
        $modelo = implode(' ', array_slice($partes, 0, 3));

        // Versión = resto
        $version = trim(substr($descripcion, strlen($modelo)));

        /*
         * 6. Buscar vehículo existente
         */
        $vehiculoExistente = Vehiculo::where('bastidor', $bastidor)->first();
        if ($vehiculoExistente) {
            return $vehiculoExistente;
        }

        /*
         * 7. Crear vehículo
         */
        return Vehiculo::create([
            'bastidor' => $bastidor,
            'referencia' => $referencia,
            'modelo' => $modelo,
            'version' => $version,
            'empresa_id' => 1,
        ]);
    }


    public function extraerModeloInteres(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $capturando = false;
        $descripcion = null;
        $precio = null;

        foreach ($lineas as $linea) {

            // 1. Detectar inicio del bloque
            if ($linea === 'Modelo de interés') {
                $capturando = true;
                continue;
            }

            if (!$capturando) {
                continue;
            }

            /*
             * 2. La línea REAL del modelo
             * es la única que contiene [REFERENCIA]
             */
            if ($descripcion === null) {
                if (str_contains($linea, '[') && str_contains($linea, ']')) {
                    // Quitar referencia [XXXX]
                    $descripcion = trim(preg_replace('/\[.*?\]/', '', $linea));
                }
                continue;
            }

            // 3. Capturar precio
            if (preg_match('/\d{1,3}(\.\d{3})*,\d{2}\s*€/', $linea)) {

                $raw = str_replace(['€', ' '], '', $linea);
                $raw = str_replace('.', '', $raw);
                $raw = str_replace(',', '.', $raw);

                $precio = (float)$raw;
                break;
            }
        }

        if ($descripcion !== null && $precio !== null) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'Modelo de interés',
                'descripcion' => $descripcion,
                'precio' => $precio,
            ]);
        }
    }

    public function extraerNissanAssistance(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $capturando = false;
        $precio = null;

        foreach ($lineas as $linea) {

            // 1. Detectar Nissan Assistance
            if (stripos($linea, 'Nissan Assistance') !== false) {
                $capturando = true;
                continue;
            }

            if (!$capturando) {
                continue;
            }

            // 2. Capturar precio positivo
            if (preg_match('/\d{1,3}(\.\d{3})*,\d{2}\s*€/', $linea)) {

                // Conversión formato europeo → numérico
                $raw = str_replace(['€', ' '], '', $linea);
                $raw = str_replace('.', '', $raw);
                $raw = str_replace(',', '.', $raw);

                $precio = (float)$raw;
                break;
            }
        }

        if ($precio !== null) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'Nissan Assistance',
                'precio' => $precio,
            ]);
        }
    }


    public function extraerPackDiseno(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $capturando = false;
        $precio = null;

        foreach ($lineas as $linea) {

            // 1. Detectar Pack Diseño
            if (stripos($linea, 'Pack Diseño') !== false) {
                $capturando = true;
                continue;
            }

            if (!$capturando) {
                continue;
            }

            // 2. Detectar precio positivo
            if (preg_match('/\d{1,3}(\.\d{3})*,\d{2}\s*€/', $linea)) {

                // ignorar descuentos
                if (str_contains($linea, '-')) {
                    continue;
                }

                $precio = (float)str_replace(
                ['.', ',', '€', ' '],
                ['', '.', '', ''],
                    $linea
                );

                break;
            }
        }

        if ($precio !== null) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'opcion',
                'descripcion' => 'Pack Diseño',
                'precio' => $precio,
            ]);
        }
    }


    public function extraerPinturaInterior(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $capturando = false;
        $descripcion = null;
        $precio = null;

        foreach ($lineas as $linea) {

            // 1. Detectar inicio del bloque
            if ($linea === 'Pintura / Interior') {
                $capturando = true;
                continue;
            }

            if (!$capturando) {
                continue;
            }

            // 2. Primera línea NO precio → descripción real
            if ($descripcion === null) {

                // Ignorar ruido
                if (
                stripos($linea, 'Oferta Promocional') !== false ||
                preg_match('/^\[\d+\]/', $linea)
                ) {
                    continue;
                }

                // Si no es precio, es la descripción
                if (!preg_match('/\d{1,3}(\.\d{3})*,\d{2}\s*€/', $linea)) {
                    $descripcion = $linea;
                    continue;
                }
            }

            // 3. Capturar precio positivo
            if (preg_match('/\d{1,3}(\.\d{3})*,\d{2}\s*€/', $linea)) {

                // Ignorar negativos
                if (str_contains($linea, '-')) {
                    continue;
                }

                $raw = str_replace(['€', ' '], '', $linea);
                $raw = str_replace('.', '', $raw);
                $raw = str_replace(',', '.', $raw);

                $precio = (float)$raw;
                break;
            }
        }

        if ($descripcion !== null && $precio !== null) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'Pintura / Interior',
                'descripcion' => $descripcion,
                'precio' => $precio,
            ]);
        }
    }



    public function extraerDescuentos(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $descripciones = [];
        $precios = [];

        foreach ($lineas as $linea) {

            // Stop condition: Stop at Transporte, Base, or IGIC to avoid reading footer prices
            if (stripos($linea, 'Transporte') !== false || $linea === 'Base' || stripos($linea, 'IGIC') !== false) {
                break;
            }

            // 1. Capture Description starting with [
            // User regex: starts with [ followed by anything not ]
            if (preg_match('/^\[[^\]]+\].+/', $linea)) {
                $descripciones[] = $linea;
                continue;
            }

            // 2. Capture Price (Negative OR Zero)
            // Positive prices are skipped (belong to options like Paint or Pack)
            if (preg_match('/(-)?\d{1,3}(?:[.\s]\d{3})*,\d{2}\s*€/', $linea)) {

                // Parse value
                $raw = str_replace(['€', ' ', '.'], '', $linea);
                $raw = str_replace(',', '.', $raw);
                $val = (float)$raw;

                // Only accept Negative or Zero prices for discounts
                if ($val <= 0) {
                    $precios[] = $val;
                }
            }
        }

        // 3. Zip lists. If price missing, default to 0.
        foreach ($descripciones as $i => $desc) {
            $precio = $precios[$i] ?? 0.0;

            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'descuento',
                'descripcion' => $desc,
                'precio' => $precio,
            ]);
        }
    }


    public function extraerTransporte(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $capturando = false;
        $precio = null;

        foreach ($lineas as $linea) {

            // 1. Detectar Transporte
            if (stripos($linea, 'Transporte') !== false) {
                $capturando = true;
                continue;
            }

            if (!$capturando) {
                continue;
            }

            // 2. Detectar precio POSITIVO
            if (preg_match('/\d{1,3}(\.\d{3})*,\d{2}\s*€/', $linea)) {

                // Ignorar negativos por seguridad
                if (str_contains($linea, '-')) {
                    continue;
                }

                // Conversión correcta
                $raw = str_replace(['€', ' '], '', $linea);
                $raw = str_replace('.', '', $raw);
                $raw = str_replace(',', '.', $raw);

                $precio = (float)$raw;
                break;
            }
        }

        if ($precio !== null) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'transporte',
                'precio' => $precio,
            ]);
        }
    }

    public function extraerBase(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $capturando = false;
        $precio = null;

        foreach ($lineas as $linea) {

            // 1. Detectar Base (línea exacta)
            if ($linea === 'Base') {
                $capturando = true;
                continue;
            }

            // Stop condition: si llegamos a IGIC, Impuesto o Total, paramos
            if ($capturando && (stripos($linea, 'IGIC') !== false || stripos($linea, 'Impuesto') !== false || stripos($linea, 'Total') !== false)) {
                break;
            }

            if (!$capturando) {
                continue;
            }

            // 2. Detectar precio POSITIVO
            // Regex más flexible para puntos/espacios en miles
            if (preg_match('/\d{1,3}(?:[.\s]\d{3})*,\d{2}\s*€/', $linea)) {

                // Por seguridad, ignorar negativos
                if (str_contains($linea, '-')) {
                    continue;
                }

                // Conversión formato europeo → numérico
                $raw = str_replace(['€', ' ', '.'], '', $linea);
                $raw = str_replace(',', '.', $raw);

                $precio = (float)$raw;
                break;
            }
        }

        if ($precio !== null) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'base',
                'precio' => $precio,
            ]);
        }
    }

    public function extraerIgic(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $capturando = false;
        $descripcion = null;
        $precio = null;

        foreach ($lineas as $linea) {

            // 1. Detectar inicio IGIC
            if ($linea === 'IGIC') {
                $capturando = true;
                continue;
            }

            if (!$capturando) {
                continue;
            }

            // 2. Capturar descripción (primera línea no vacía tras IGIC)
            if ($descripcion === null && $linea !== 'Impuesto') {
                $descripcion = $linea;
                continue;
            }

            // 3. Detectar precio POSITIVO (el IGIC real)
            if (preg_match('/\d{1,3}(\.\d{3})*,\d{2}\s*€/', $linea)) {

                // Ignorar 0,00 € (Impuesto Exento 100%)
                if (str_contains($linea, '0,00')) {
                    continue;
                }

                // Conversión formato europeo → numérico
                $raw = str_replace(['€', ' '], '', $linea);
                $raw = str_replace('.', '', $raw);
                $raw = str_replace(',', '.', $raw);

                $precio = (float)$raw;
                break;
            }
        }

        if ($descripcion !== null && $precio !== null) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'igic',
                'descripcion' => $descripcion,
                'precio' => $precio,
            ]);
        }
    }

    public function extraerImpuesto(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $capturando = false;
        $descripcion = null;
        $precio = null;

        foreach ($lineas as $linea) {

            // 1. Detectar inicio Impuesto
            if ($linea === 'Impuesto') {
                $capturando = true;
                continue;
            }

            if (!$capturando) {
                continue;
            }

            // 2. Capturar descripción
            if ($descripcion === null) {
                $descripcion = $linea;
                continue;
            }

            // 3. Capturar SOLO el 0,00 €
            if (preg_match('/0,00\s*€/', $linea)) {

                // Conversión formato europeo → numérico
                $precio = 0.0;
                break;
            }
        }

        if ($descripcion !== null && $precio !== null) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'impuesto',
                'descripcion' => $descripcion,
                'precio' => $precio,
            ]);
        }
    }

    public function extraerSubtotal(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $capturando = false;
        $precio = null;

        foreach ($lineas as $linea) {

            // 1. Detectar Subtotal
            if ($linea === 'Subtotal') {
                $capturando = true;
                continue;
            }

            if (!$capturando) {
                continue;
            }

            // 2. Ignorar texto intermedio
            if (stripos($linea, 'Gastos') !== false) {
                continue;
            }

            // 3. Detectar precio POSITIVO
            if (preg_match('/\d{1,3}(\.\d{3})*,\d{2}\s*€/', $linea)) {

                // Conversión formato europeo → numérico
                $raw = str_replace(['€', ' '], '', $linea);
                $raw = str_replace('.', '', $raw);
                $raw = str_replace(',', '.', $raw);

                $precio = (float)$raw;
                break;
            }
        }

        if ($precio !== null) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'subtotal',
                'precio' => $precio,
            ]);
        }
    }

    public function extraerGastos(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $capturando = false;
        $descripcion = null;
        $precio = null;

        foreach ($lineas as $linea) {

            // 1. Detectar inicio Gastos
            if ($linea === 'Gastos') {
                $capturando = true;
                continue;
            }

            if (!$capturando) {
                continue;
            }

            // 2. Si aún no hay descripción, buscar la primera línea NO precio
            if ($descripcion === null) {

                // Si la línea parece un precio, la ignoramos
                if (preg_match('/\d{1,3}(\.\d{3})*,\d{2}\s*€/', $linea)) {
                    continue;
                }

                $descripcion = $linea;
                continue;
            }

            // 3. Capturar el precio
            if (preg_match('/\d{1,3}(\.\d{3})*,\d{2}\s*€/', $linea)) {

                $raw = str_replace(['€', ' '], '', $linea);
                $raw = str_replace('.', '', $raw);
                $raw = str_replace(',', '.', $raw);

                $precio = (float)$raw;
                break;
            }
        }

        if ($descripcion !== null && $precio !== null) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'gasto',
                'descripcion' => $descripcion,
                'precio' => $precio,
            ]);
        }
    }

    public function extraerTotal(int $ofertaCabeceraId): void
    {
        $lineas = array_values(array_filter(
            array_map('trim', explode("\n", $this->texto))
        ));

        $capturando = false;
        $precio = null;

        foreach ($lineas as $linea) {

            // 1. Detectar TOTAL
            if ($linea === 'TOTAL') {
                $capturando = true;
                continue;
            }

            if (!$capturando) {
                continue;
            }

            // 2. Capturar el primer precio que aparezca
            if (preg_match('/\d{1,3}(\.\d{3})*,\d{2}\s*€/', $linea)) {

                $raw = str_replace(['€', ' '], '', $linea);
                $raw = str_replace('.', '', $raw);
                $raw = str_replace(',', '.', $raw);

                $precio = (float)$raw;
                break;
            }
        }

        if ($precio !== null) {
            OfertaLinea::create([
                'oferta_cabecera_id' => $ofertaCabeceraId,
                'tipo' => 'total',
                'precio' => $precio,
            ]);
        }
    }





}