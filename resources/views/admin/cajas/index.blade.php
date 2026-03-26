@extends('admin.layout')

@section('title', 'Estado de Caja')

@section('content_header')
    <h1>Control de Caja Chica</h1>
@stop

@section('admin_content')
<div class="row">
    {{-- Cuadro de Monto Inicial --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($cajaAbierta->monto_apertura, 0, ',', '.') }}</h3>
                <p>Monto Inicial (Gs.)</p>
            </div>
            <div class="icon"><i class="fas fa-cash-register"></i></div>
        </div>
    </div>

    {{-- Cuadro de Ingresos (Señas + Ventas) --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($ingresos, 0, ',', '.') }}</h3>
                <p>Total Ingresos</p>
            </div>
            <div class="icon"><i class="fas fa-arrow-up"></i></div>
        </div>
    </div>

    {{-- Cuadro de Egresos (Gastos varios) --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($egresos, 0, ',', '.') }}</h3>
                <p>Total Egresos</p>
            </div>
            <div class="icon"><i class="fas fa-arrow-down"></i></div>
        </div>
    </div>

    {{-- CUADRO PRINCIPAL: SALDO REAL --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-dark"> {{-- Aquí entra tu color Negro --}}
            <div class="inner">
                <h3>{{ number_format($saldo_actual, 0, ',', '.') }}</h3>
                <p>Efectivo en Caja</p>
            </div>
            <div class="icon text-white"><i class="fas fa-wallet"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        {{-- Tabla de últimos movimientos --}}
        <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title">Movimientos del Turno</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Concepto</th>
                                <th>Tipo</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cajaAbierta->movimientos as $mov)
                            <tr>
                                <td>{{ $mov->created_at->format('H:i') }}</td>
                                <td>{{ $mov->concepto }}</td>
                                <td>
                                    <span class="badge {{ $mov->tipo == 'ingreso' ? 'badge-success' : 'badge-danger' }}">
                                        {{ strtoupper($mov->tipo) }}
                                    </span>
                                </td>
                                <td>{{ number_format($mov->monto, 0, ',', '.') }} Gs.</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay movimientos aún.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Acciones Rápidas --}}
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Acciones</h3>
            </div>
            <div class="card-body">
                {{-- Botón para cerrar la caja --}}
                <button class="btn btn-danger btn-block mb-3">
                    <i class="fas fa-lock"></i> Cerrar Caja del Día
                </button>
                
                {{-- Botón para gasto rápido (ej: comprar agua, yerba) --}}
                <button class="btn btn-outline-light btn-block" data-toggle="modal" data-target="#modalMovimiento">
                    <i class="fas fa-plus-circle"></i> Nuevo Movimiento Rápido
                </button>
            </div>
        </div>
    </div>
</div>

    {{-- Modal de Movimiento Rápido --}}
    <div class="modal fade" id="modalMovimiento" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 1px solid var(--primary-color);">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt"></i> Nuevo Movimiento de Caja</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('admin.cajas.movimiento') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tipo de Movimiento</label>
                            <select name="tipo" class="form-control border-1"  required>
                                <option value="egreso" class="text-danger">SALIDA (Gasto, pago, etc.)</option>
                                <option value="ingreso" class="text-success">ENTRADA (Aporte de sencillo, etc.)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Monto (Gs.)</label>
                            <input type="number" name="monto" class="form-control form-control-lg border-1" 
                                 placeholder="Ej: 50000" required>
                        </div>

                        <div class="form-group">
                            <label>Concepto / Motivo</label>
                            <input type="text" name="concepto" class="form-control border-1" 
                                 
                                placeholder="Ej: Cambio por sencillo de 10.000" required>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #333;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Registrar Movimiento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop