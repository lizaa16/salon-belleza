@extends('admin.layout')

@section('title', 'Nueva Venta')

@section('content_header')
    <h1><i class="fas fa-shopping-cart"></i> Nueva Venta</h1>
@stop

@section('admin_content')
<form action="{{ route('admin.ventas.store') }}" method="POST" id="formVenta">
    @csrf
    {{-- Campo oculto para enviar los items y los pagos como JSON --}}
    <input type="hidden" name="items_json" id="items_json">
    <input type="hidden" name="pagos_json" id="pagos_json">

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-dark" style="border-top: 3px solid var(--primary-color);">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Cliente</label>
                            <select name="cliente_id" id="cliente_id" class="form-control select2">
                                <option value="">Cliente Ocasional</option>
                                @foreach($clientes as $c)
                                    {{-- Accedemos a través de la relación 'persona' --}}
                                    <option value="{{ $c->id }}">
                                        {{ $c->persona->nombre }} {{ $c->persona->apellido }} - {{ $c->persona->documento }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- <div class="col-md-3">
                            <label>Fecha</label>
                            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}">
                        </div> -->
                        <div class="col-md-5" id="seccion_citas" style="display:none;">
                            <label class="text-primary">Citas Pendientes de este Cliente</label>
                            <select id="select_citas_pendientes" class="form-control">
                                </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card bg-dark">
                <div class="card-header border-bottom border-secondary">
                    <h3 class="card-title">Detalle de la Venta</h3>
                </div>
                <div class="card-body p-0">
                    <div class="p-3">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" onclick="abrirModalServicios()">
                                ➕ Servicio
                            </button>

                            <button type="button" class="btn btn-success" onclick="abrirModalProductos()">
                                ➕ Producto
                            </button>

                            <button type="button" class="btn btn-info" onclick="abrirModalCitas()">
                                🔗 Cita
                            </button>
                        </div>
                    </div>
                    <table class="table table-dark m-0">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th width="100">Cant.</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tabla_detalle"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body text-center">
                    <h5>TOTAL A PAGAR</h5>
                    <h2 id="total_venta" style="color: var(--primary-color);">0 Gs.</h2>
                </div>
            </div>

            <div class="card bg-dark">
                <div class="card-header border-secondary">
                    <h3 class="card-title">Medios de Pago</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-7">
                            <select id="metodo_pago" class="form-control form-control-sm">
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta (POS)</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="seña">Usar Seña</option>
                            </select>
                        </div>
                        <div class="col-5">
                            <button type="button" class="btn btn-sm btn-primary btn-block" onclick="agregarPago()">+ Agregar</button>
                        </div>
                    </div>
                    
                    <ul class="list-group list-group-flush" id="lista_pagos">
                        </ul>

                    <div class="mt-3 text-center">
                        <p>Restante: <span id="pago_restante" class="text-danger font-weight-bold">0 Gs.</span></p>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block btn-lg" id="btn_finalizar" disabled>
                        <i class="fas fa-check-double"></i> FINALIZAR VENTA
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
    <div class="modal fade" id="modalServicios">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5>Seleccionar Servicio</h5>
            </div>
            <div class="modal-body">
                @foreach($servicios as $s)
                    <button class="btn btn-block btn-outline-primary mb-2"
                        onclick="agregarItem({{ $s->id }}, 'serv', '{{ $s->nombre }}', {{ $s->precio }})">
                        {{ $s->nombre }} - {{ number_format($s->precio,0,',','.') }} Gs
                    </button>
                @endforeach
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalProductos">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5>Seleccionar Producto</h5>
            </div>
            <div class="modal-body">
                @foreach($productos as $p)
                    <button class="btn btn-block btn-outline-primary mb-2"
                        onclick="agregarItem({{ $p->id }}, 'prod', '{{ $p->nombre }}', {{ $p->precio_venta }})">
                        {{ $p->nombre }} - {{ number_format($p->precio_venta,0,',','.') }} Gs
                    </button>
                @endforeach
            </div>
            </div>
        </div>
    </div>
@stop

@push('js')
<script>
    let items = [];
    let pagos = [];

    function renderTodo() {
        // Render Detalle
        let htmlItems = '';
        let totalItems = 0;
        items.forEach((it, idx) => {
            let sub = it.precio * it.cantidad;
            totalItems += sub;
            htmlItems += `<tr>
                <td>${it.nombre}</td>
                <td>${it.precio.toLocaleString()}</td>
                <td><input type="number" class="form-control form-control-sm" value="${it.cantidad}" onchange="items[${idx}].cantidad=this.value;renderTodo()"></td>
                <td>${sub.toLocaleString()}</td>
                <td><button type="button" class="btn btn-xs btn-danger" onclick="items.splice(${idx},1);renderTodo()">×</button></td>
            </tr>`;
        });
        $('#tabla_detalle').html(htmlItems);
        $('#total_venta').text(totalItems.toLocaleString() + ' Gs.');

        // Render Pagos
        let totalPagado = 0;
        let htmlPagos = '';
        pagos.forEach((p, idx) => {
            totalPagado += p.monto;
            htmlPagos += `<li class="list-group-item bg-dark border-secondary d-flex justify-content-between align-items-center">
                <span>${p.metodo.toUpperCase()}: <strong>${p.monto.toLocaleString()}</strong></span>
                <button type="button" class="btn btn-xs btn-danger" onclick="pagos.splice(${idx},1);renderTodo()">×</button>
            </li>`;
        });
        $('#lista_pagos').html(htmlPagos);

        // Validar Estado Final
        let restante = totalItems - totalPagado;
        $('#pago_restante').text(restante.toLocaleString() + ' Gs.');
        $('#btn_finalizar').prop('disabled', totalItems === 0);

        // Preparar JSONs para el Controller
        $('#items_json').val(JSON.stringify(items));
        $('#pagos_json').val(JSON.stringify(pagos));

        console.log('Items:', items);
        console.log('Pagos:', pagos);
    }

    function agregarPago() {
        let monto = prompt("Monto a pagar:");
        if (monto && !isNaN(monto) && monto > 0) {
            pagos.push({
                metodo: $('#metodo_pago').val(),
                monto: parseFloat(monto)
            });
            renderTodo();
        } else {
            alert("Monto inválido");
        }
    }

    function agregarItem(id, tipo, nombre, precio) {

        let existente = items.find(i => i.id == id && i.tipo == tipo);

        if (existente) {
            existente.cantidad++;
        } else {
            items.push({
                id: id,
                tipo: tipo,
                nombre: nombre,
                precio: Number(precio) || 0,
                cantidad: 1
            });
        }

        renderTodo();
    }

    $(document).ready(function() {
        // Usamos delegación de eventos para que no falle
        $(document).on('submit', '#formVenta', function(e) {
            console.log("¡Intento de envío detectado!"); // Si no ves esto en consola, el ID está mal

            // Llenar los campos ocultos
            $('#items_json').val(JSON.stringify(items));
            $('#pagos_json').val(JSON.stringify(pagos));

            if (items.length === 0) {
                e.preventDefault();
                alert('Debe agregar al menos un servicio o producto.');
                return false;
            }

            // Si llegamos aquí, el formulario debería enviarse
            console.log("Datos listos para enviar:", $('#items_json').val());
        });
    });

    function abrirModalServicios() {
        $('#modalServicios').modal('show');
    }

    function abrirModalProductos() {
        $('#modalProductos').modal('show');
    }
</script>
@endpush