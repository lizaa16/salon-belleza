@extends('admin.layout')

@section('title', 'Nuevo Producto')

@section('content_header')
    <h1>Nuevo Producto</h1>
@stop

@section('admin_content')
<div class="card">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.productos.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Nombre del Producto</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre') }}" placeholder="Ej. Shampoo Post-Keratina">
                </div>
                <div class="col-md-6 form-group">
                    <label>Código de Barras (opcional)</label>
                    <input type="text" name="codigo_barra" class="form-control" value="{{ old('codigo_barra') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Precio de Venta (Gs.)</label>
                    <input type="number" name="precio_venta" class="form-control" value="{{ old('precio_venta', 0) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Stock Inicial</label>
                    <input type="number" name="stock_actual" class="form-control" value="{{ old('stock_actual', 0) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Stock Mínimo (Alerta)</label>
                    <input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', 5) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Tasa de IVA (Paraguay)</label>
                    <select name="iva" class="form-control">
                        <option value="10" {{ old('iva') == '10' ? 'selected' : '' }}>IVA 10%</option>
                        <option value="5" {{ old('iva') == '5' ? 'selected' : '' }}>IVA 5%</option>
                        <option value="exenta" {{ old('iva') == 'exenta' ? 'selected' : '' }}>Exenta</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Producto</button>
            </div>
        </form>
    </div>
</div>
@stop