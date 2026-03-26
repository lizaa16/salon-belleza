@extends('admin.layout')

@section('title', 'Nueva Cita')

@section('content_header')
    <h1>Nueva Cita</h1>
@stop

@section('admin_content')
<div class="card">
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

        <form action="{{ route('admin.citas.store') }}" method="POST" id="formCita">
            @csrf

            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Cliente</label>
                    <select name="cliente_id" class="form-control select2 @error('cliente_id') is-invalid @enderror">
                        <option value="" selected disabled>-- Seleccione un cliente --</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->persona->nombre }} {{ $cliente->persona->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Empleado</label>
                    <select name="empleado_id" class="form-control select2 @error('empleado_id') is-invalid @enderror">
                        <option value="" selected disabled>-- Seleccione un empleado --</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}"
                                {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->persona->nombre }} {{ $empleado->persona->apellido }}
                            </option>
                        @endforeach
                    </select>
                    @error('empleado_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 form-group px-0">
                <label>Fecha y hora</label>
                <input type="datetime-local" name="fecha_hora"
                       class="form-control @error('fecha_hora') is-invalid @enderror"
                       value="{{ old('fecha_hora') }}">
                @error('fecha_hora')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr>
            <h5>Servicios</h5>

            {{-- Selector de servicios --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <select id="selectServicio" class="form-control select2">
                        <option value="" selected disabled>-- Agregar servicio --</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}"
                                    data-precio="{{ $servicio->precio }}"
                                    data-nombre="{{ $servicio->nombre }}">
                                {{ $servicio->nombre }} — Gs. {{ number_format($servicio->precio, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('fecha_hora')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-success" onclick="agregarServicio()">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                </div>
            </div>

            {{-- Tabla de detalles --}}
            <table class="table table-bordered" id="tablaDetalles">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="detallesBody">
                    <tr id="filaVacia">
                        <td colspan="5" class="text-center text-muted">
                            No hay servicios agregados aún.
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Total:</th>
                        <th id="totalGeneral">Gs. 0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            {{-- Campo oculto con los detalles en JSON --}}
            <input type="hidden" name="detalles" id="inputDetalles">

            {{-- Justo debajo de la tabla de servicios o arriba del botón guardar --}}
            @error('detalles')
                <div class="alert alert-danger mt-2">
                    <i class="fas fa-exclamation-triangle"></i> Debe agregar al menos un servicio a la cita.
                </div>
            @enderror
            <hr>
            <h5>Seña / Adelanto <small class="text-muted">(opcional)</small></h5>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Monto de seña</label>
                    <input type="number" name="seña_monto" step="0.01" min="0"
                           class="form-control @error('seña_monto') is-invalid @enderror"
                           value="{{ old('seña_monto') }}">
                    @error('seña_monto')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Método de pago de seña</label>
                    <select name="seña_metodo_pago"
                            class="form-control @error('seña_metodo_pago') is-invalid @enderror">
                        <option value="">-- Seleccione --</option>
                        <option value="efectivo"     {{ old('seña_metodo_pago') == 'efectivo'     ? 'selected' : '' }}>Efectivo</option>
                        <option value="tarjeta"      {{ old('seña_metodo_pago') == 'tarjeta'      ? 'selected' : '' }}>Tarjeta</option>
                        <option value="transferencia"{{ old('seña_metodo_pago') == 'transferencia'? 'selected' : '' }}>Transferencia</option>
                    </select>
                    @error('seña_metodo_pago')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Notas</label>
                <textarea name="notas" class="form-control" rows="3">{{ old('notas') }}</textarea>
            </div>

            <a href="{{ route('admin.citas.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar Cita</button>
        </form>
    </div>
</div>
@stop

@push('js')
<script>
let detalles = [];

function agregarServicio() {
    const select = document.getElementById('selectServicio');
    const option = select.options[select.selectedIndex];

    if (!option.value) return;

    const servicioId = option.value;
    const nombre     = option.dataset.nombre;
    const precio     = parseFloat(option.dataset.precio);

    const existe = detalles.find(d => d.servicio_id == servicioId);
    if (existe) {
        alert('Este servicio ya fue agregado.');
        return;
    }

    detalles.push({ servicio_id: servicioId, nombre, precio_unitario: precio, cantidad: 1 });
    renderizarTabla();
    select.value = '';
    $('#selectServicio').val('').trigger('change');
}

function renderizarTabla() {
    const tbody = document.getElementById('detallesBody');
    const filaVacia = document.getElementById('filaVacia');

    if (detalles.length === 0) {
        tbody.innerHTML = `<tr id="filaVacia"><td colspan="5" class="text-center text-muted">No hay servicios agregados aún.</td></tr>`;
        document.getElementById('totalGeneral').textContent = 'Gs. 0';
        document.getElementById('inputDetalles').value = '';
        return;
    }

    let html  = '';
    let total = 0;

    detalles.forEach((d, i) => {
        const subtotal = d.precio_unitario * d.cantidad;
        total += subtotal;
        html += `
        <tr>
            <td>${d.nombre}</td>
            <td>
                <input type="number" class="form-control form-control-sm"
                       value="${d.precio_unitario}" min="0" step="0.01"
                       onchange="actualizarPrecio(${i}, this.value)" style="width:130px">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm"
                       value="${d.cantidad}" min="1"
                       onchange="actualizarCantidad(${i}, this.value)" style="width:80px">
            </td>
            <td>Gs. ${subtotal.toLocaleString()}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="eliminarDetalle(${i})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>`;
    });

    tbody.innerHTML = html;
    document.getElementById('totalGeneral').textContent = 'Gs. ' + total.toLocaleString();
    document.getElementById('inputDetalles').value = JSON.stringify(detalles);
}

function actualizarPrecio(index, valor) {
    detalles[index].precio_unitario = parseFloat(valor) || 0;
    renderizarTabla();
}

function actualizarCantidad(index, valor) {
    detalles[index].cantidad = parseInt(valor) || 1;
    renderizarTabla();
}

function eliminarDetalle(index) {
    detalles.splice(index, 1);
    renderizarTabla();
}
</script>
@endpush