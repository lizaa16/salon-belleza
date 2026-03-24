@extends('adminlte::page')

@section('title', 'Citas')

@section('content_header')
    <h1>Citas</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.citas.create') }}" class="btn btn-primary btn-sm">
                Nueva Cita
            </a>
        </div>

        {{-- Filtros --}}
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.citas.index') }}" class="row">
                <div class="col-md-3 form-group mb-0">
                    <label>Empleado</label>
                    <select name="empleado_id" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}"
                                {{ request('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->persona->nombre }} {{ $empleado->persona->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group mb-0">
                    <label>Fecha</label>
                    <input type="date" name="fecha" class="form-control form-control-sm"
                           value="{{ request('fecha') }}">
                </div>
                <div class="col-md-3 form-group mb-0">
                    <label>Estado</label>
                    <select name="estado" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        <option value="pendiente"   {{ request('estado') == 'pendiente'   ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmada"  {{ request('estado') == 'confirmada'  ? 'selected' : '' }}>Confirmada</option>
                        <option value="completada"  {{ request('estado') == 'completada'  ? 'selected' : '' }}>Completada</option>
                        <option value="cancelada"   {{ request('estado') == 'cancelada'   ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <div class="col-md-3 form-group mb-0 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary btn-sm mr-2">Filtrar</button>
                    <a href="{{ route('admin.citas.index') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
                </div>
            </form>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Empleado</th>
                        <th>Servicios</th>
                        <th>Fecha y hora</th>
                        <th>Seña</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citas as $cita)
                        <tr>
                            <td>{{ $cita->id }}</td>
                            <td>{{ $cita->cliente->persona->nombre }} {{ $cita->cliente->persona->apellido }}</td>
                            <td>{{ $cita->empleado->persona->nombre }} {{ $cita->empleado->persona->apellido }}</td>
                            <td>
                                @foreach($cita->detalles as $detalle)
                                    <span class="badge badge-info">{{ $detalle->servicio->nombre }}</span>
                                @endforeach
                            </td>
                            <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($cita->seña_monto)
                                    Gs. {{ number_format($cita->seña_monto, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @php
                                    $badges = [
                                        'pendiente'  => 'warning',
                                        'confirmada' => 'info',
                                        'completada' => 'success',
                                        'cancelada'  => 'danger',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $badges[$cita->estado] ?? 'secondary' }}">
                                    {{ ucfirst($cita->estado) }}
                                </span>
                            </td>
                            <td>
                                @if(!in_array($cita->estado, ['cancelada', 'completada']))
                                    <a href="{{ route('admin.citas.edit', $cita) }}"
                                       class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Cancelar --}}
                                    <form action="{{ route('admin.citas.cancelar', $cita) }}"
                                          method="POST" style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                title="Cancelar"
                                                onclick="return confirm('¿Cancelar esta cita?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay citas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@stop