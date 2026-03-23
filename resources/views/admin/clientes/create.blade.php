@extends('adminlte::page')

@section('title', 'Nuevo Cliente')

@section('content_header')
    <h1>Nuevo Cliente</h1>
@stop

@section('content')
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

            <form action="{{ route('admin.clientes.store') }}" method="POST">
                @csrf

                <h5 class="mb-3">Datos personales</h5>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Apellido</label>
                        <input type="text" name="apellido"
                               class="form-control @error('apellido') is-invalid @enderror"
                               value="{{ old('apellido') }}">
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
                               value="{{ old('documento') }}">
                        @error('documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono"
                               class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono') }}">
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
                               value="{{ old('fecha_nacimiento') }}">
                        @error('fecha_nacimiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Dirección</label>
                        <input type="text" name="direccion"
                               class="form-control @error('direccion') is-invalid @enderror"
                               value="{{ old('direccion') }}">
                        @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Email</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        {{-- Campo oculto para asegurar que siempre se envíe un valor --}}
                        <input type="hidden" name="activo" value="0">
                        
                        <input type="checkbox" name="activo" value="1"
                            class="custom-control-input" id="activo"
                            {{ old('activo', $cliente->activo ?? '1') == '1' ? 'checked' : '' }}>
                        <label class="custom-control-label" for="activo">Activo</label>
                    </div>
                </div>

                <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@stop