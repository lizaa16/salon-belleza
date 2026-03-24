@extends('admin.layout')

@section('title', 'Editar Persona')

@section('content_header')
    <h1>Editar Persona</h1>
@stop

@section('admin_content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.personas.update', $persona) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $persona->nombre) }}">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" name="apellido"
                           class="form-control @error('apellido') is-invalid @enderror"
                           value="{{ old('apellido', $persona->apellido) }}">
                    @error('apellido')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion"
                           class="form-control @error('direccion') is-invalid @enderror"
                           value="{{ old('direccion', $persona->direccion) }}">
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Documento</label>
                    <input type="text" name="documento"
                           class="form-control @error('documento') is-invalid @enderror"
                           value="{{ old('documento', $persona->documento) }}">
                    @error('documento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento"
                           class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                           value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento) }}">
                    @error('fecha_nacimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono"
                           class="form-control @error('telefono') is-invalid @enderror"
                           value="{{ old('telefono', $persona->telefono) }}">
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <a href="{{ route('admin.personas.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@stop