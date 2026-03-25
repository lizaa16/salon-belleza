@extends('admin.layout')

@section('title', 'Apertura de Caja')

@section('content_header')
    <h1>Apertura de Caja</h1>
@stop

@section('admin_content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-dark"> {{-- Usamos card-dark para el look negro --}}
            <div class="card-header">
                <h3 class="card-title">Iniciar Turno</h3>
            </div>
            
            <form action="{{ route('admin.cajas.abrir') }}" method="POST">
                @csrf
                <div class="card-body">
                    <p class="text-muted">Antes de realizar ventas o cobrar señas, debes ingresar el monto inicial de efectivo en caja (sencillo/vuelto).</p>
                    
                    <div class="form-group">
                        <label for="monto_apertura">Monto de Apertura (Gs.)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                            </div>
                            <input type="number" name="monto_apertura" id="monto_apertura" 
                                   class="form-control form-control-lg" 
                                   placeholder="Ej: 100000" required autofocus>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-door-open"></i> Abrir Caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop