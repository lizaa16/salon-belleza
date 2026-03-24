@extends('admin.layout')

@section('title', 'Personas')

@section('content_header')
    <h1>Personas</h1>
@stop

@section('admin_content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.personas.create') }}" class="btn btn-primary btn-sm">
                Nueva Persona
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre y Apellido</th>
                        <th>Documento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personas as $persona)
                        <tr>
                            <td>{{ $persona->id }}</td>
                            <td>{{ $persona->nombre }} {{ $persona->apellido }}</td>
                            <td>{{ $persona->documento }}</td>
                            <td>
                                <a href="{{ route('admin.personas.edit', $persona) }}"
                                   class="btn btn-warning btn-sm">Editar</a>

                                <form action="{{ route('admin.personas.destroy', $persona) }}"
                                      method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Eliminar esta persona?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No hay personas cargadas aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop