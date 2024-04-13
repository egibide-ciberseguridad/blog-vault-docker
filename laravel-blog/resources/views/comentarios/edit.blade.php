@extends('layouts.app')

@section('content')

    <h1 class="mt-3">Editar comentario</h1>

    <form action="{{ route('comentarios.update', $comentario->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <label class="col-2 form-label">Email: </label>
            <div class="col-10">
                <input class="form-control" type="text" name="email" value="{{ $comentario->email }}"/>
                <span class="text-danger">{{ $errors->first('email') }}</span>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-2 form-label">Texto: </label>
            <div class="col-10">
                <textarea class="form-control" name="texto">{{ $comentario->texto }}</textarea>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-2 form-label">Fecha: </label>
            <div class="col-10">
                <input class="form-control" type="text" name="fecha" value="{{ $comentario->fecha ?: now() }}"/>
                <span class="text-danger">{{ $errors->first('fecha') }}</span>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-2 form-label">Visible: </label>
            <div class="col-10">
                <input class="form-check-input" type="checkbox"
                       name="visible" {{ $comentario->visible ? 'checked' : '' }}/>
            </div>
        </div>
        <input class="btn btn-primary" type="submit" name="guardar" value="Guardar"/>
        <a class="link-secondary ms-2" href="{{ route('comentarios.index') }}">Cancelar</a>
    </form>

@endsection
