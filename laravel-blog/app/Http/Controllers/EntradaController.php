<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntradaRequest;
use App\Http\Requests\UpdateEntradaRequest;
use App\Models\Entrada;

class EntradaController extends Controller
{
    public function index()
    {
        $entradas = Entrada::all();

        return view('entradas.index', compact('entradas'));
    }

    public function create()
    {
        return view('entradas.create');
    }

    public function store(StoreEntradaRequest $request)
    {
        Entrada::create([
            'titulo' => request('titulo'),
            'texto' => request('texto'),
            'fecha' => request('fecha'),
            'visible' => $request->has('visible'),
        ]);

        return redirect(route('entradas.index'));
    }

    public function show(Entrada $entrada)
    {
        return view('entradas.show', compact('entrada'));
    }

    public function edit(Entrada $entrada)
    {
        return view('entradas.edit', compact('entrada'));
    }

    public function update(UpdateEntradaRequest $request, Entrada $entrada)
    {
        $entrada->update([
            'titulo' => request('titulo'),
            'texto' => request('texto'),
            'fecha' => request('fecha'),
            'visible' => $request->has('visible'),
        ]);

        return redirect(route('entradas.index'));
    }

    public function destroy(Entrada $entrada)
    {
        $entrada->delete();

        return back();
    }
}
