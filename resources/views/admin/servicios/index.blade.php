@extends('admin.layout')

@section('title', 'Servicios')

@section('content_header')
    <h1>Servicios</h1>
@stop

@section('admin_content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.servicios.create') }}" class="btn btn-primary btn-sm">
                Nuevo Servicio
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Duración</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                        <tr>
                            <td>{{ $servicio->id }}</td>
                            <td>{{ $servicio->nombre }}</td>
                            <td>{{ $servicio->categoria->nombre ?? '-' }}</td>
                            <td>Gs. {{ number_format($servicio->precio, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $horas = floor($servicio->duracion_min / 60);
                                    $mins = $servicio->duracion_min % 60;
                                @endphp
                                {{ $horas > 0 ? $horas.'h ' : '' }}{{ $mins > 0 ? $mins.'min' : '' }}
                            </td>
                            <td>
                                @if($servicio->activo)
                                    <span class="badge badge-success">Sí</span>
                                @else
                                    <span class="badge badge-danger">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.servicios.edit', $servicio) }}"
                                   class="btn btn-warning btn-sm" title="Editar Servicio">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.servicios.destroy', $servicio) }}"
                                      method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar Servicio"
                                        onclick="return confirm('¿Eliminar este servicio?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay servicios cargados aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop