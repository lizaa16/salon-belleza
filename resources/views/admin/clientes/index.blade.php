@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    <h1>Clientes</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary btn-sm">
                Nuevo Cliente
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
                        <th>Email</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->id }}</td>
                            <td>{{ $cliente->persona->nombre }} {{ $cliente->persona->apellido }}</td>
                            <td>{{ $cliente->persona->documento ?? '-' }}</td>
                            <td>{{ $cliente->persona->telefono ?? '-' }}</td>
                            <td>{{ $cliente->persona->email ?? '-' }}</td>
                            <td>
                                @if($cliente->activo)
                                    <span class="badge badge-success">Sí</span>
                                @else
                                    <span class="badge badge-danger">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.clientes.edit', $cliente) }}"
                                class="btn btn-warning btn-sm" title="Editar Datos">
                                <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.clientes.destroy', $cliente) }}" 
                                    method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('¿Eliminar este cliente?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay clientes cargados aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop