@extends('adminlte::page')

@section('title', 'Gestionar Acceso')

@section('content_header')
    <h1>Gestionar Acceso: {{ $empleado->persona->nombre }} {{ $empleado->persona->apellido }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Credenciales del Sistema</h3>
                </div>
                
                <form action="{{ route('admin.empleados.password.update', $empleado) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
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

                        <div class="form-group">
                            <label for="email">Correo Electrónico (Usuario)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" name="email" id="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $empleado->user->email) }}" required>
                            </div>
                        </div>

                        <hr>
                        <p class="text-muted">Si no desea cambiar la contraseña, deje los siguientes campos en blanco.</p>

                        <div class="form-group">
                            <label for="password">Nueva Contraseña</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="password" id="password" 
                                       class="form-control @error('password') is-invalid @enderror">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-save mr-1"></i> Actualizar Credenciales
                        </button>
                        <a href="{{ route('admin.empleados.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-box shadow-none border">
                <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Aviso de Seguridad</span>
                    <span class="info-box-number text-muted" style="font-weight: normal;">
                        Al cambiar el correo electrónico o la contraseña, el empleado deberá utilizar las nuevas credenciales en su próximo inicio de sesión.
                    </span>
                </div>
            </div>
        </div>
    </div>
@stop