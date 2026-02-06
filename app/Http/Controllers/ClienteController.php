<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Repositories\ClienteRepositoryInterface;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Illuminate\Support\Facades\Log;


class ClienteController extends Controller
{
    private ClienteRepositoryInterface $clientes;

    public function __construct(ClienteRepositoryInterface $clientes)
    {
        $this->clientes = $clientes;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Cliente::class);
        
        $clientes = $request->filled('search')
            ? $this->clientes->search($request->search)
            : $this->clientes->all();

        return view('clientes.index', compact('clientes'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Cliente::class);

        $empresas = Empresa::all();

        return view('clientes.create', compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request)
    {
        $this->authorize('create', Cliente::class);

        try {
            $this->clientes->create($request->validated());

            Log::info('Cliente creado correctamente', ['nombre' => $request->validated()['nombre']]);
            
            return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente');
        }

        catch(\Illuminate\Database\QueryException $e){
            Log::error('Error al crear departametno (DB)', ['error' => $e->getMessage(), 'data' => $request->validated()]);
            return redirect()->back()->withInput()->with('error', 'Error al crear Cliente. Por favor, inténtalo de nuevo');
        }

        catch(\Exception $e){
            Log::error('Error inesperado al crear Cliente', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $this->authorize('view', $cliente);

        $cliente->load('empresa');

        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        $this->authorize('update', $cliente);

        $empresas = Empresa::all();

        return view('clientes.edit', compact('cliente', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(UpdateClienteRequest $request, Cliente $cliente)
{
    $this->authorize('update', $cliente);
    
    try {
        $this->clientes->update($cliente->id, $request->validated());

        Log::info('Cliente actualizado correctamente', ['nombre' => $request->validated()['nombre']]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    catch(\Illuminate\Database\QueryException $e) {
        Log::error('Error al actualizar Cliente (BD)', ['error' => $e->getMessage(), 'data' => $request->validated()]);
        return redirect()->back()->withInput()->with('error', 'Error al actualizar Cliente. Por favor, inténtalo de nuevo.');
    }

    catch(\Exception $e){
        Log::error('Error inesperado al actualizar Cliente', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return redirect()->back()->withInput()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador');
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $this->authorize('delete', $cliente);
    
        try {
    
            $this->clientes->delete($cliente->id);
    
            return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente');
                
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error al eliminar Cliente (BD)', ['error' => $e->getMessage(),'Cliente_id' => $cliente->id,'nombre' => $cliente->nombre]);
    
            return redirect()->back()->with('error', 'Error al eliminar el Cliente. Por favor, inténtalo de nuevo.');
                
        } catch (\Exception $e) {
            Log::error('Error inesperado al eliminar Cliente', ['error' => $e->getMessage(),'trace' => $e->getTraceAsString()]);
    
            return redirect()->back()->with('error', 'Ha ocurrido un error inesperado. Por favor, contacta con el administrador.');
        }
    }
}


