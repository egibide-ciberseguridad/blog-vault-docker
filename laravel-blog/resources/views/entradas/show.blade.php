@extends('layouts.app')

@section('content')

    <h1 class="mt-3">{{ $entrada->titulo }}</h1>

    <table class="table table-striped">
        <tbody class="align-middle">
        <tr>
            <th>Texto</th>
            <td>{{ $entrada->texto }}</td>
        </tr>
        <tr>
            <th>Fecha</th>
            <td>{{ $entrada->fecha }}</td>
        </tr>
        <tr>
            <th>Visible</th>
            <td>{{ $entrada->visible ? 'Sí' : 'No' }}</td>
        </tr>
        </tbody>
    </table>

    <a class="btn btn-secondary mt-3" href="{{ route('entradas.index') }}">Volver</a>

@endsection
