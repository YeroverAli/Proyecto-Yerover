<?php

namespace App\Http\Controllers;

use App\Models\OfertaCabecera;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ofertas = OfertaCabecera::with(['cliente', 'vehiculo'])->orderByDesc('fecha')->paginate(10);
        return view('ofertas.index', compact('ofertas'));
    }

    /**
     * Display the specified resource.
     */
    public function show(OfertaCabecera $oferta)
    {
        $oferta->load(['cliente', 'vehiculo', 'lineas']);
        return view('ofertas.show', compact('oferta'));
    }
}