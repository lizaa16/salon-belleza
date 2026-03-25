@extends('admin.layout')

@section('title', 'Editar Producto')

@section('content_header')
    <h1>Editar Producto</h1>
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

        <form action="{{ route('admin.productos.update', $producto) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Nombre del Producto</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>Código de Barras</label>
                    <input type="text" name="codigo_barra" class="form-control" value="{{ old('codigo_barra', $producto->codigo_barra) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Precio de Venta (Gs.)</label>
                    <input type="number" name="precio_venta" class="form-control" value="{{ old('precio_venta', $producto->precio_venta) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Stock Actual</label>
                    <input type="number" name="stock_actual" class="form-control" value="{{ old('stock_actual', $producto->stock_actual) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label>Stock Mínimo</label>
                    <input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', $producto->stock_minimo) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Tasa de IVA</label>
                    <select name="iva" class="form-control">
                        <option value="10" {{ $producto->iva == '10' ? 'selected' : '' }}>IVA 10%</option>
                        <option value="5" {{ $producto->iva == '5' ? 'selected' : '' }}>IVA 5%</option>
                        <option value="exenta" {{ $producto->iva == 'exenta' ? 'selected' : '' }}>Exenta</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Estado</label>
                    <select name="estado" class="form-control">
                        <option value="1" {{ $producto->estado ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !$producto->estado ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.productos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
            </div>
        </form>
    </div>
</div>
@stop