@extends('admin.layout')

@section('admin_content')
<div class="card card-dark">
    <div class="card-header">
        <h3 class="card-title">Personalización del Salón</h3>
    </div>
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="form-group">
                <label>Nombre del Salón</label>
                <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? '' }}">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Color Principal (Botones y Acentos)</label>
                        <input type="color" name="primary_color" class="form-control" value="{{ $settings['primary_color'] ?? '#FF85A1' }}">
                        <small class="text-muted">Recomendado: Rosa suave o Dorado.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Color del Sidebar (Barra Lateral)</label>
                        <input type="color" name="sidebar_color" class="form-control" value="{{ $settings['sidebar_color'] ?? '#1a1a1a' }}">
                        <small class="text-muted">Recomendado: Negro o Gris muy oscuro.</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>
@stop