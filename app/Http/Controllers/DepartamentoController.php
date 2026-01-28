<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Repositories\DepartamentoRepositoryInterface;
use App\Http\Requests\StoreDepartamentoRequest;
use App\Http\Requests\UpdateDepartamentoRequest;

class DepartamentoController extends Controller
{
    private DepartamentoRepositoryInterface $departamentos;

    public function __construct(DepartamentoRepositoryInterface $departamentos)
    {
        $this->departamentos = $departamentos;
    }

    public function index(Request $request)
    {
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

        $this->departamentos->create($request->validated());

        return redirect()->route('departamentos.index')
            ->with('success', 'Departamento creado correctamente');
    }

    public function edit(Departamento $departamento)
    {
        $this->authorize('update', $departamento);

        return view('departamentos.edit', compact('departamento'));
    }

    
    public function update(UpdateDepartamentoRequest $request, Departamento $departamento)
    {

        $this->departamentos->update($departamento->id, $request->validated());

        return redirect()->route('departamentos.index')
            ->with('success', 'Departamento actualizado correctamente');
    }
}
