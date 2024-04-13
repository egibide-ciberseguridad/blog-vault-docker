@extends('layouts.app')

@section('content')

    <h1 class="mt-3">Comentarios</h1>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Email</th>
            <th>Fecha</th>
            <th>Visible</th>
            <th colspan="2">Acciones</th>
        </tr>
        </thead>
        <tbody class="align-middle">
        @foreach($comentarios as $comentario)
            <tr>
                <td><a href="{{ route('comentarios.show', $comentario->id) }}">{{ $comentario->email }}</a></td>
                <td>{{ $comentario->fecha }}</td>
                <td>{{ $comentario->visible ? 'Sí' : 'No' }}</td>
                <td><a class="btn btn-secondary" href="{{ route('comentarios.edit', $comentario->id) }}">Editar</a></td>
                <td>
                    <form action="{{ route('comentarios.destroy', $comentario->id) }}" method="POST"
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

    <a class="btn btn-primary mt-3" href="{{ route('comentarios.create') }}">Nueva comentario</a>

@endsection
