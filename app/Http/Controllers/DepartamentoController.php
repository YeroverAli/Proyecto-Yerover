<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Repositories\DepartamentoRepositoryInterface;
use App\Http\Requests\StoreDepartamentoRequest;
use App\Http\Requests\UpdateDepartamentoRequest;
use Illuminate\Support\Facades\Log;

class DepartamentoController extends Controller
{
    private DepartamentoRepositoryInterface $departamentos;

    public function __construct(DepartamentoRepositoryInterface $departamentos)
    {
        $this->departamentos = $departamentos;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Departamento::class);

        $departamentos = $request->filled('search')
            ? $this->departamentos->search($request->search)
            : $this->departamentos->all();

        return view('departamentos.index', compact('departamentos'));
    }

    public function create()
    {
        $this->authorize('create', Departamento::class);

        $departamentos = Departamento::all();

        return view('departamentos.create', compact('departamentos'));
        
    }

public function store(StoreDepartamentoRequest $request)
{
    $this->authorize('create', Departamento::class);

    try {
        $this->departamentos->create($request->validated());

        Log::info('Departamento creado correctamente', ['nombre' => $request->validated()['nombre']]);

        return redirect()->route('departamentos.index')->with('success', 'Departamento creado correctamente');
            
    } catch (\Illuminate\Database\QueryException $e) {
        Log::error('Error al crear departamento (BD)', ['error' => $e->getMessage(), 'data' => $request->validated()]);

        return redirect()->back()->withInput()->with('error', 'Error al crear el departamento. Por favor, inténtalo de nuevo.');
            
    } catch (\Exception $e) {
        Log::error('Error inesperado al crear departamento', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

        return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador.');
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Departamento $departamento)
    {
        $this->authorize('view', $departamento);

        $departamento->load('users');

        return view('departamentos.show', compact('departamento'));
    }

    public function edit(Departamento $departamento)
    {
        $this->authorize('update', $departamento);

        return view('departamentos.edit', compact('departamento'));
    }

    
    public function update(UpdateDepartamentoRequest $request, Departamento $departamento)
    {
        $this->authorize('update', $departamento);
        
        try {
            $this->departamentos->update($departamento->id, $request->validated());

            Log::info('Departamento actualizado correctamente', ['nombre' => $request->validated()['nombre']]);

            return redirect()->route('departamentos.index')->with('success', 'Departamento actualizado correctamente');
        }
        
        catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error al actualizar (BD)', ['error' => $e->getMessage(), 'data' => $request->validated()]);
            
            return redirect()->back()->withInput()->with('error', value: 'Error al actualizar departamento. Por favor, inténtalo de nuevo.');
        }

        catch (\Exception $e) {
            Log::error('Error inesperado al actualizar departamento', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador.');
        }
    }            


    public function destroy(Departamento $departamento)
    {
        $this->authorize('delete', $departamento);
    
        try {
            // Contar usuarios asociados
            $usuariosCount = $departamento->users()->count();
    
            $this->departamentos->delete($departamento->id);
    
            if ($usuariosCount > 0) {
                return redirect()->route('departamentos.index')->with('success', "Departamento eliminado. {$usuariosCount} usuario(s) quedaron sin departamento asignado.");
            }
    
            return redirect()->route('departamentos.index')->with('success', 'Departamento eliminado correctamente');
                
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error al eliminar departamento (BD)', ['error' => $e->getMessage(),'departamento_id' => $departamento->id,'nombre' => $departamento->nombre]);
    
            return redirect()->back()->with('error', 'Error al eliminar el departamento. Por favor, inténtalo de nuevo.');
                
        } catch (\Exception $e) {
            Log::error('Error inesperado al eliminar departamento', ['error' => $e->getMessage(),'trace' => $e->getTraceAsString()]);
    
            return redirect()->back()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador.');
        }
    }
    }
