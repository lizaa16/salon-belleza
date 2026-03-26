@extends('admin.layout')

@section('title', 'Nuevo Servicio')

@section('content_header')
    <h1>Nuevo Servicio</h1>
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

            <form action="{{ route('admin.servicios.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria_id"
                            class="form-control select2 @error('categoria_id') is-invalid @enderror">
                        <option value="" selected disabled>-- Seleccione una categoría --</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Precio (Gs.)</label>
                    <input type="number" name="precio" step="0.01"
                           class="form-control @error('precio') is-invalid @enderror"
                           value="{{ old('precio') }}">
                    @error('precio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Duración</label>
                    <div class="row">
                        <div class="col-md-3">
                            <input type="number" name="horas" min="0" max="12"
                                class="form-control @error('horas') is-invalid @enderror"
                                placeholder="Horas"
                                value="{{ old('horas', 0) }}">

                                <small class="form-text text-muted">Horas</small>

                            @error('horas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="minutos" min="0" max="59"
                                class="form-control @error('minutos') is-invalid @enderror"
                                placeholder="Minutos"
                                value="{{ old('minutos', 30) }}">

                                <small class="form-text text-muted">Minutos</small>

                            @error('minutos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="activo" value="1"
                               class="custom-control-input" id="activo"
                               {{ old('activo', '1') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="activo">Activo</label>
                    </div>
                </div>

                <a href="{{ route('admin.servicios.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@stop