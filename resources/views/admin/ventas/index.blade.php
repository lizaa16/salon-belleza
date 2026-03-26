@extends('admin.layout')

@section('title', 'Ventas')

@section('content_header')
    <h1>Ventas</h1>
@stop

@section('admin_content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <a href="{{ route('admin.ventas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Venta
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover m-0">
                <thead>
                    <tr>
                        <th>Fecha/Hora</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $v)
                    <tr>
                        <td>{{ $v->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $v->cliente->persona->nombre }} {{ $v->cliente->persona->apellido }}</td>
                        <td>{{ number_format($v->total, 0, ',', '.') }} Gs.</td>
                        <td>
                            @if($v->estado == 'PAGADO')
                                <span class="badge badge-success">Pagado</span>
                            @elseif($v->estado == 'PAGO_PARCIAL')
                                <span class="badge badge-warning">Parcial</span>
                            @else
                                <span class="badge badge-danger">Pendiente</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.ventas.show', $v->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center p-4">No hay ventas registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop