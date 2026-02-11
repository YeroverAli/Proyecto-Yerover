<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehiculoRequest;
use App\Http\Requests\UpdateVehiculoRequest;
use App\Models\Empresa;
use App\Models\Vehiculo;
use App\Repositories\VehiculoRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VehiculosExport;
use Barryvdh\DomPDF\Facade\Pdf;

class VehiculoController extends Controller
{
    private VehiculoRepositoryInterface $vehiculos;

    public function __construct(VehiculoRepositoryInterface $vehiculos)
    {
        $this->vehiculos = $vehiculos;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Vehiculo::class);

        $vehiculos = $request->filled('search')
            ? $this->vehiculos->search($request->search)
            : $this->vehiculos->all();

        return view('vehiculos.index', compact('vehiculos'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Vehiculo::class);

        $empresas = Empresa::all();

        return view('vehiculos.create', compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehiculoRequest $request)
    {
        $this->authorize('create', Vehiculo::class);

        try {
            $this->vehiculos->create($request->validated());

            Log::info('Vehiculo creado correctamente', ['bastidor' => $request->validated()['bastidor']]);

            return redirect()->route('vehiculos.index')->with('success', 'Vehiculo creado correctamente');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error al crear departametno (DB)', ['error' => $e->getMessage(), 'data' => $request->validated()]);
            return redirect()->back()->withInput()->with('error', 'Error al crear Vehiculo. Por favor, inténtalo de nuevo');
        } catch (\Exception $e) {
            Log::error('Error inesperado al crear Vehiculo', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehiculo $vehiculo)
    {
        $this->authorize('view', $vehiculo);

        $vehiculo->load('empresa');

        return view('vehiculos.show', compact('vehiculo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehiculo $vehiculo)
    {
        $this->authorize('update', $vehiculo);

        $empresas = Empresa::all();

        return view('vehiculos.edit', compact('vehiculo', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehiculoRequest $request, Vehiculo $vehiculo)
    {
        $this->authorize('update', $vehiculo);

        try {
            $this->vehiculos->update($vehiculo->id, $request->validated());

            Log::info('Vehiculo actualizado correctamente', ['bastidor' => $request->validated()['bastidor']]);

            return redirect()->route('vehiculos.index')->with('success', 'Vehiculo actualizado correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error al actualizar Vehiculo (BD)', ['error' => $e->getMessage(), 'data' => $request->validated()]);
            return redirect()->back()->withInput()->with('error', 'Error al actualizar Vehiculo. Por favor, inténtalo de nuevo.');
        } catch (\Exception $e) {
            Log::error('Error inesperado al actualizar Vehiculo', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehiculo $vehiculo)
    {
        $this->authorize('delete', $vehiculo);

        try {

            $this->vehiculos->delete($vehiculo->id);

            return redirect()->route('vehiculos.index')->with('success', 'Vehiculo eliminado correctamente');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error al eliminar Vehiculo (BD)', ['error' => $e->getMessage(), 'Vehiculo_id' => $vehiculo->id, 'bastidor' => $vehiculo->bastidor]);

            return redirect()->back()->with('error', 'Error al eliminar el Vehiculo. Por favor, inténtalo de nuevo.');

        } catch (\Exception $e) {
            Log::error('Error inesperado al eliminar Vehiculo', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->back()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador.');
        }
    }

    public function export()
    {
        return Excel::download(new VehiculosExport, 'vehiculos.xlsx');
    }

    public function exportPdf()
    {
        $vehiculos = Vehiculo::with('empresa')->get();
        $pdf = Pdf::loadView('vehiculos.pdf', compact('vehiculos'));
        return $pdf->download('vehiculos.pdf');
    }
}
