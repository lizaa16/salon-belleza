@extends('admin.layout')

@section('title', 'Editar Empleado')

@section('content_header')
    <h1>Editar Empleado</h1>
@stop

@section('admin_content')
    <div class="card">
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.empleados.update', $empleado) }}" method="POST">
                @csrf
                @method('PUT')

                <h5 class="mb-3">Datos personales</h5>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $empleado->persona->nombre) }}">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Apellido</label>
                        <input type="text" name="apellido"
                               class="form-control @error('apellido') is-invalid @enderror"
                               value="{{ old('apellido', $empleado->persona->apellido) }}">
                        @error('apellido')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Documento</label>
                        <input type="text" name="documento"
                               class="form-control @error('documento') is-invalid @enderror"
                               value="{{ old('documento', $empleado->persona->documento) }}">
                        @error('documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono"
                               class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono', $empleado->persona->telefono) }}">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento"
                               class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                               value="{{ old('fecha_nacimiento', $empleado->persona->fecha_nacimiento) }}">
                        @error('fecha_nacimiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion"
                               class="form-control @error('direccion') is-invalid @enderror"
                               value="{{ old('direccion', $empleado->persona->direccion) }}">
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>
                <h5 class="mb-3">Datos laborales</h5>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Especialidad</label>
                        <input type="text" name="especialidad"
                               class="form-control @error('especialidad') is-invalid @enderror"
                               value="{{ old('especialidad', $empleado->especialidad) }}">
                        @error('especialidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Tasa de comisión (%)</label>
                        <input type="number" name="tasa_comision" step="0.01" min="0" max="100"
                               class="form-control @error('tasa_comision') is-invalid @enderror"
                               value="{{ old('tasa_comision', $empleado->tasa_comision) }}">
                        @error('tasa_comision')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="activo" value="1"
                               class="custom-control-input" id="activo"
                               {{ old('activo', $empleado->activo) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="activo">Activo</label>
                    </div>
                </div>

                <a href="{{ route('admin.empleados.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </form>
        </div>
    </div>
@stop