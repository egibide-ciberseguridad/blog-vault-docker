<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComentarioRequest;
use App\Http\Requests\UpdateComentarioRequest;
use App\Models\Comentario;

class ComentarioController extends Controller
{
    public function index()
    {
        $comentarios = Comentario::all();

        return view('comentarios.index', compact('comentarios'));
    }

    public function create()
    {
        return view('comentarios.create');
    }

    public function store(StoreComentarioRequest $request)
    {
        Comentario::create([
            'email' => request('email'),
            'texto' => request('texto'),
            'fecha' => request('fecha'),
            'visible' => $request->has('visible'),
        ]);

        return redirect(route('comentarios.index'));
    }

    public function show(Comentario $comentario)
    {
        return view('comentarios.show', compact('comentario'));
    }

    public function edit(Comentario $comentario)
    {
        return view('comentarios.edit', compact('comentario'));
    }

    public function update(UpdateComentarioRequest $request, Comentario $comentario)
    {
        $comentario->update([
            'email' => request('email'),
            'texto' => request('texto'),
            'fecha' => request('fecha'),
            'visible' => $request->has('visible'),
        ]);

        return redirect(route('comentarios.index'));
    }

    public function destroy(Comentario $comentario)
    {
        $comentario->delete();

        return back();
    }
}
