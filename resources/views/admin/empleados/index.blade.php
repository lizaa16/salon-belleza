@extends('adminlte::page')

@section('title', 'Empleados')

@section('content_header')
    <h1>Empleados</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.empleados.create') }}" class="btn btn-primary btn-sm">
                Nuevo Empleado
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Teléfono</th>
                        <th>Especialidad</th>
                        <th>Comisión</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empleados as $empleado)
                        <tr>
                            <td>{{ $empleado->id }}</td>
                            <td>{{ $empleado->persona->nombre }} {{ $empleado->persona->apellido }}</td>
                            <td>{{ $empleado->persona->documento ?? '-' }}</td>
                            <td>{{ $empleado->persona->telefono ?? '-' }}</td>
                            <td>{{ $empleado->especialidad ?? '-' }}</td>
                            <td>{{ $empleado->tasa_comision }}%</td>
                            <td>
                                @if($empleado->activo)
                                    <span class="badge badge-success">Sí</span>
                                @else
                                    <span class="badge badge-danger">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.empleados.edit', $empleado) }}"
                                class="btn btn-warning btn-sm" title="Editar Datos">
                                <i class="fas fa-edit"></i>
                                </a>

                                <a href="{{ route('admin.empleados.password.edit', $empleado) }}" 
                                class="btn btn-info btn-sm" title="Cambiar Contraseña">
                                    <i class="fas fa-key"></i>
                                </a>

                                <form action="{{ route('admin.empleados.destroy', $empleado) }}" 
                                    method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('¿Eliminar este empleado?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay empleados cargados aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop