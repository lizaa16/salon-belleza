@extends('admin.layout')

@section('admin_content')
<div class="card">
    <div class="card-header bg-dark">
        <h3 class="card-title">Historial de Cierres de Caja</h3>
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Fecha Cierre</th>
                    <th>Cajero</th>
                    <th>Efectivo Real</th>
                    <th>Diferencia Total</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cajas as $c)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($c->fecha_cierre)->format('d/m/Y H:i') }}</td>
                    <td>{{ $c->usuario->name }}</td>
                    <td>{{ number_format($c->monto_real_en_caja, 0, ',', '.') }} Gs.</td>
                    <td>
                        <span class="badge {{ $c->diferencia < 0 ? 'badge-danger' : 'badge-success' }}">
                            {{ number_format($c->diferencia, 0, ',', '.') }} Gs.
                        </span>
                    </td>
                    <td><span class="badge badge-secondary">CERRADA</span></td>
                    <td>
                        <a href="{{ route('admin.cajas.resumen', $c->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Ver Detalle
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $cajas->links() }} {{-- Paginación por si hay muchas cajas --}}
    </div>
</div>
@stop