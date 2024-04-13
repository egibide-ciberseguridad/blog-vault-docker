@extends('layouts.app')

@section('content')

    <h1 class="mt-3">Entradas</h1>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Título</th>
            <th>Fecha</th>
            <th>Visible</th>
            <th colspan="2">Acciones</th>
        </tr>
        </thead>
        <tbody class="align-middle">
        @foreach($entradas as $entrada)
            <tr>
                <td><a href="{{ route('entradas.show', $entrada->id) }}">{{ $entrada->titulo }}</a></td>
                <td>{{ $entrada->fecha }}</td>
                <td>{{ $entrada->visible ? 'Sí' : 'No' }}</td>
                <td><a class="btn btn-secondary" href="{{ route('entradas.edit', $entrada->id) }}">Editar</a></td>
                <td>
                    <form action="{{ route('entradas.destroy', $entrada->id) }}" method="POST"
                          onsubmit="return confirm('¿Estás seguro?');">
                        @csrf
                        @method('DELETE')
                        <input class="btn btn-danger" type="submit" name="borrar" value="Borrar"/>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot></tfoot>
    </table>

    <a class="btn btn-primary mt-3" href="{{ route('entradas.create') }}">Nueva entrada</a>

@endsection
