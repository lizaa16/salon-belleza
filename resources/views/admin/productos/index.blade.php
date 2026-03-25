@extends('admin.layout')

@section('title', 'Productos')

@section('content_header')
    <h1>Gestión de Productos</h1>
@stop

@section('admin_content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.productos.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Nuevo Producto
            </a>
        </div>
        
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Precio (Gs.)</th>
                        <th>Stock</th>
                        <th>IVA</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->codigo_barra ?? '-' }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ number_format($producto->precio_venta, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $producto->stock_actual <= $producto->stock_minimo ? 'badge-danger' : 'badge-success' }}">
                                    {{ $producto->stock_actual }}
                                </span>
                            </td>
                            <td>{{ $producto->iva }}%</td>
                            <td>
                                <span class="badge {{ $producto->estado ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $producto->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.productos.destroy', $producto) }}"
                                      method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar Producto"
                                        onclick="return confirm('¿Eliminar este producto?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay productos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop