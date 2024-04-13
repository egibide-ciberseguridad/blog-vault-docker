@extends('layouts.app')

@section('content')

    <h1 class="mt-3">Nuevo comentario</h1>

    <form action="{{ route('comentarios.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <label class="col-2 form-label">Email: </label>
            <div class="col-10">
                <input class="form-control" type="text" name="email"/>
                <span class="text-danger">{{ $errors->first('email') }}</span>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-2 form-label">Texto: </label>
            <div class="col-10">
                <textarea class="form-control" name="texto"></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-2 form-label">Fecha: </label>
            <div class="col-10">
                <input class="form-control" type="text" name="fecha" value="{{ now() }}"/>
                <span class="text-danger">{{ $errors->first('fecha') }}</span>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-2 form-label">Visible: </label>
            <div class="col-10">
                <input class="form-check-input" type="checkbox" name="visible" checked/>
            </div>
        </div>
        <input class="btn btn-primary" type="submit" name="guardar" value="Guardar"/>
        <a class="link-secondary ms-2" href="{{ route('comentarios.index') }}">Cancelar</a>
    </form>

@endsection
