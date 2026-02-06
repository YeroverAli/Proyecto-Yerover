<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CentroRepositoryInterface;
use App\Http\Requests\StoreCentroRequest;
use App\Http\Requests\UpdateCentroRequest;
use App\Models\Centro;
use App\Models\Empresa;
use Illuminate\Support\Facades\Log;

class CentroController extends Controller
{
    private CentroRepositoryInterface $centros;

    public function __construct(CentroRepositoryInterface $centros)
    {
        $this->centros = $centros;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Centro::class);
        
        $centros = $request->filled('search')
            ? $this->centros->search($request->search)
            : $this->centros->all();

        return view('centros.index', compact('centros'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Centro::class);

        $empresas = Empresa::all();

        return view('centros.create', compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCentroRequest $request)
    {
        $this->authorize('create', Centro::class);

        try {
            $this->centros->create($request->validated());

            Log::info('Centro creado correctamente', ['nombre' => $request->validated()['nombre']]);
            
            return redirect()->route('centros.index')->with('success', 'Centro creado correctamente');
        }

        catch(\Illuminate\Database\QueryException $e){
            Log::error('Error al crear departametno (DB)', ['error' => $e->getMessage(), 'data' => $request->validated()]);
            return redirect()->back()->withInput()->with('error', 'Error al crear centro. Por favor, inténtalo de nuevo');
        }

        catch(\Exception $e){
            Log::error('Error inesperado al crear centro', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Centro $centro)
    {
        $this->authorize('view', $centro);

        $centro->load(['empresa', 'users']);

        return view('centros.show', compact('centro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Centro $centro)
    {
        $this->authorize('update', $centro);
        $empresas = Empresa::all();

        return view('centros.edit', compact('centro', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(UpdateCentroRequest $request, Centro $centro)
{
    $this->authorize('update', $centro);
    
    try {
        $this->centros->update($centro->id, $request->validated());

        Log::info('Centro actualizado correctamente', ['nombre' => $request->validated()['nombre']]);

        return redirect()->route('centros.index')->with('success', 'Centro actualizado correctamente.');
    }

    catch(\Illuminate\Database\QueryException $e) {
        Log::error('Error al actualizar centro (BD)', ['error' => $e->getMessage(), 'data' => $request->validated()]);
        return redirect()->back()->withInput()->with('error', 'Error al actualizar centro. Por favor, inténtalo de nuevo.');
    }

    catch(\Exception $e){
        Log::error('Error inesperado al actualizar centro', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador');
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Centro $centro)
    {
        $this->authorize('delete', $centro);
    
        try {
            // Contar usuarios asociados
            $usuariosCount = $centro->users()->count();
    
            $this->centros->delete($centro->id);
    
            if ($usuariosCount > 0) {
                return redirect()->route('centros.index')->with('success', "Centro eliminado. {$usuariosCount} usuario(s) quedaron sin departamento asignado.");
            }
    
            return redirect()->route('centros.index')->with('success', 'Centro eliminado correctamente');
                
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error al eliminar centro (BD)', ['error' => $e->getMessage(),'centro_id' => $centro->id,'nombre' => $centro->nombre]);
    
            return redirect()->back()->with('error', 'Error al eliminar el centro. Por favor, inténtalo de nuevo.');
                
        } catch (\Exception $e) {
            Log::error('Error inesperado al eliminar centro', ['error' => $e->getMessage(),'trace' => $e->getTraceAsString()]);
    
            return redirect()->back()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador.');
        }
    }
}
