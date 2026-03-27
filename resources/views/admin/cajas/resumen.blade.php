@extends('admin.layout')

@section('admin_content')
<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Resumen de Cierre de Caja #{{ $caja->id }}</h3>
            <div class="card-tools">
                <button onclick="window.print()" class="btn btn-default btn-sm"><i class="fas fa-print"></i> Imprimir</button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Información General</h5>
                    <p><b>Cajero:</b> {{ $caja->usuario->name }}<br>
                       <b>Apertura:</b> {{ $caja->fecha_apertura }}<br>
                       <b>Cierre:</b> {{ $caja->fecha_cierre }}</p>
                </div>
                <div class="col-md-6 text-right">
                    <h2 class="{{ $caja->diferencia == 0 ? 'text-success' : 'text-danger' }}">
                        Diferencia: {{ number_format($caja->diferencia, 0, ',', '.') }} Gs.
                    </h2>
                </div>
            </div>

            <table class="table table-bordered table-striped mt-4">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Método de Pago</th>
                        <th>Esperado (Sistema)</th>
                        <th>Declarado (Cajero)</th>
                        <th>Diferencia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Efectivo</b></td>
                        <td>{{ number_format($caja->monto_cierre, 0, ',', '.') }} Gs.</td>
                        <td>{{ number_format($caja->monto_real_en_caja, 0, ',', '.') }} Gs.</td>
                        <td class="{{ $caja->diferencia < 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($caja->diferencia, 0, ',', '.') }} Gs.
                        </td>
                    </tr>
                    <tr>
                        <td><b>Tarjeta</b></td>
                        <td>{{ number_format($caja->total_tarjeta_sistema, 0, ',', '.') }} Gs.</td>
                        <td>{{ number_format($caja->total_tarjeta_real, 0, ',', '.') }} Gs.</td>
                        <td class="{{ ($caja->total_tarjeta_real - $caja->total_tarjeta_sistema) < 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($caja->total_tarjeta_real - $caja->total_tarjeta_sistema, 0, ',', '.') }} Gs.
                        </td>
                    </tr>
                    <tr>
                        <td><b>Transferencia</b></td>
                        <td>{{ number_format($caja->total_transferencia_sistema, 0, ',', '.') }} Gs.</td>
                        <td>{{ number_format($caja->total_transferencia_real, 0, ',', '.') }} Gs.</td>
                        <td class="{{ ($caja->total_transferencia_real - $caja->total_transferencia_sistema) < 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($caja->total_transferencia_real - $caja->total_transferencia_sistema, 0, ',', '.') }} Gs.
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-4 p-3 border rounded bg-light">
                <strong>Observaciones:</strong><br>
                {{ $caja->observaciones ?? 'Sin observaciones.' }}
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.reportes.cajas') }}" class="btn btn-primary">Volver al Panel</a>
        </div>
    </div>
</div>
@stop