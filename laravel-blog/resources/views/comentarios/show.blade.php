@extends('layouts.app')

@section('content')

    <h1 class="mt-3">{{ $comentario->email }}</h1>

    <table class="table table-striped">
        <tbody class="align-middle">
        <tr>
            <th>Texto</th>
            <td>{{ $comentario->texto }}</td>
        </tr>
        <tr>
            <th>Fecha</th>
            <td>{{ $comentario->fecha }}</td>
        </tr>
        <tr>
            <th>Visible</th>
            <td>{{ $comentario->visible ? 'Sí' : 'No' }}</td>
        </tr>
        </tbody>
    </table>

    <a class="btn btn-secondary mt-3" href="{{ route('comentarios.index') }}">Volver</a>

@endsection
