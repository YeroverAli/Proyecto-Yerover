<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProcesarPdfRequest;
use App\Models\OfertaCabecera;
use App\Services\OfertaPdfService;
use DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\OfertaPdfNsmitService;
use App\Services\OfertaPdfSubidaRaService;
use Spatie\PdfToText\Pdf;

final class PDFController extends Controller
{
    public function index(Request $request)
    {
        return view('pdf');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // 10MB máximo
            'nombre' => 'nullable|string|max:255',
        ], [
            'pdf_file.required' => 'Debes seleccionar un archivo PDF.',
            'pdf_file.file' => 'El archivo no es válido.',
            'pdf_file.mimes' => 'El archivo debe ser un PDF.',
            'pdf_file.max' => 'El archivo no puede ser mayor a 10MB.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
        ]);

        try {
            $file = $request->file('pdf_file');

            // Obtener la ruta temporal del archivo
            $filePath = $file->getRealPath();

            $text = (new Pdf())
                ->setPdf($filePath)
                ->text();

            Log::info('PDF procesado correctamente', [
                'nombre' => $file->getClientOriginalName(),
                'tamaño' => $file->getSize(),
            ]);

            return back()->with([
                'text' => $text,
                'modelo_pdf' => $request->input('modelo_pdf'),
            ]);

        }
        catch (\Exception $e) {
            Log::error('Error al subir PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al subir el archivo. Por favor, inténtalo de nuevo.');
        }
    }

    public function procesarPdf(ProcesarPdfRequest $request)
    {
        try {
            DB::beginTransaction();

            $texto = $request->input('text');

            // 1. Elegir el servicio según el modelo de PDF
            switch ($request->modelo_pdf) {
                case 'nsmit':
                    $servicio = new OfertaPdfNsmitService($texto);
                    break;

                case 'subida_ra':
                    $servicio = new OfertaPdfSubidaRaService($texto);
                    break;

                default:
                    throw new \Exception('Modelo de PDF no soportado');
            }

            // 2. Extraer datos básicos
            $cliente = $servicio->extraerCliente();
            $vehiculo = $servicio->extraerVehiculo();
            $fecha = $servicio->extraerFechaPedido();

            // 3. Crear la oferta cabecera
            $oferta = OfertaCabecera::create([
                'cliente_id' => $cliente->id,
                'vehiculo_id' => $vehiculo->id,
                'fecha' => $fecha,
            ]);

            // 4. Extraer líneas económicas
            $servicio->extraerModeloInteres($oferta->id);
            $servicio->extraerNissanAssistance($oferta->id);
            $servicio->extraerPackDiseno($oferta->id);
            $servicio->extraerPinturaInterior($oferta->id);
            $servicio->extraerDescuentos($oferta->id);
            $servicio->extraerTransporte($oferta->id);
            $servicio->extraerBase($oferta->id);
            $servicio->extraerIgic($oferta->id);
            $servicio->extraerImpuesto($oferta->id);
            $servicio->extraerSubtotal($oferta->id);
            $servicio->extraerGastos($oferta->id);
            $servicio->extraerTotal($oferta->id);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Oferta guardada correctamente.');

        }
        catch (\Throwable $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Error al procesar el PDF: ' . $e->getMessage());
        }
    }

}