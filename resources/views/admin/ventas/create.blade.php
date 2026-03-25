@extends('admin.layout')

@section('title', 'Nueva Venta')

@section('content_header')
    <h1> Nueva Venta</h1>
@stop

@section('admin_content')
<div class="container-fluid">
    <div class="card card-primary card-outline card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="wizard-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-1" href="javascript:void(0)" onclick="irAPaso(1)">
                        <span class="badge badge-primary mr-1">1</span> Cliente
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" id="tab-2" href="javascript:void(0)" onclick="irAPaso(2)">
                        <span class="badge badge-secondary mr-1">2</span> Carrito
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" id="tab-3" href="javascript:void(0)" onclick="irAPaso(3)">
                        <span class="badge badge-secondary mr-1">3</span> Revisión y Cobro
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.ventas.store') }}" method="POST" id="formVenta">
                @csrf
                <input type="hidden" name="items_json" id="items_json">
                <input type="hidden" name="pagos_json" id="pagos_json">

                <div class="tab-content">
                    
                    <div id="paso-1" class="wizard-step">
                        <div class="row justify-content-center py-4">
                            <div class="col-md-6 text-center">
                                <h5>Seleccione el Cliente</h5>
                                <div>
                                    
                                </div>
                                <select name="cliente_id" id="cliente_id" class="form-control select2" style="width: 100%;">
                                    <option value="" disabled selected>-- Seleccione --</option>
                                    @foreach($clientes as $c)
                                        <option value="{{ $c->id }}">{{ $c->persona->nombre }} {{ $c->persona->apellido }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-link mt-2" onclick="window.location='{{ route('admin.clientes.create') }}'">
                                    <i class="fas fa-user-plus"></i> Agregar Nuevo Cliente
                                </button>
                                <button type="button" class="btn btn-primary btn-lg mt-4 px-5" onclick="irAPaso(2)">
                                    Siguiente <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="paso-2" class="wizard-step d-none">
                        <div class="d-flex justify-content-between mb-3">
                            <h4>Detalle de Venta</h4>
                            <div>
                                <button type="button" class="btn btn-info" onclick="$('#modalServicios').modal('show')">+ Servicio</button>
                                <button type="button" class="btn btn-success" onclick="$('#modalProductos').modal('show')">+ Producto</button>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th width="100">Cant.</th>
                                    <th>Subtotal</th>
                                    <th width="40"></th>
                                </tr>
                            </thead>
                            <tbody id="tabla_detalle"></tbody>
                        </table>
                        <div class="d-flex justify-content-between align-items-center mt-4 border-top pt-3">
                            <button type="button" class="btn btn-default" onclick="irAPaso(1)">Atrás</button>
                            <h2 id="total_venta_display" class="text-primary font-weight-bold mb-0">0 Gs.</h2>
                            <button type="button" class="btn btn-primary px-5" onclick="irAPaso(3)">
                                Ir al Resumen <i class="fas fa-chevron-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <div id="paso-3" class="wizard-step d-none">
                        <div class="row">
                            <div class="col-md-7 border-right">
                                <h5> Resumen de Venta</h5>
                                
                                <div class="p-3 bg-light border rounded mb-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block">CLIENTE:</small>
                                        <strong id="resumen_cliente" class="h6"></strong>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="irAPaso(1)">
                                        <i class="fas fa-edit"></i> Cambiar
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><strong>Artículos Seleccionados:</strong></h6>
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="irAPaso(2)">
                                            <i class="fas fa-shopping-cart"></i> Editar Carrito
                                        </button>
                                    </div>
                                    <ul id="resumen_items" class="list-group shadow-sm">
                                        </ul>
                                </div>

                                <div class="text-right mt-4">
                                    <h4 class="text-muted mb-0 font-weight-light">TOTAL A COBRAR</h4>
                                    <h1 id="resumen_total" class="font-weight-bold display-4">0 Gs.</h1>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <h5>Registrar Pago</h5>
                                <div class="input-group mb-3">
                                    <select id="metodo_pago" class="form-control">
                                        <option value="" disabled selected>Seleccionar Método de Pago</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="tarjeta">Tarjeta (POS)</option>
                                        <option value="transferencia">Transferencia</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success" onclick="agregarPago()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <ul id="lista_pagos" class="list-group mb-3"></ul>
                                <div id="box_restante" class="alert alert-warning text-center">
                                    Resta pagar: <strong id="pago_restante">0 Gs.</strong>
                                </div>
                                <button type="button" id="btnGuardarVenta" class="btn btn-block btn-success btn-lg shadow" disabled onclick="confirmarVenta()">
                                    <i class="fas fa-save mr-2"></i> FINALIZAR Y GUARDAR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalServicios">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info"><h5 class="modal-title">Servicios</h5></div>
            <div class="modal-body p-0">
                @foreach($servicios as $s)
                <button type="button" class="btn btn-light btn-block text-left m-0 border-bottom rounded-0" 
                    onclick="agregarItem({{ $s->id }}, 'serv', '{{ $s->nombre }}', {{ $s->precio }}); $('#modalServicios').modal('hide')">
                    {{ $s->nombre }} <span class="float-right font-weight-bold">{{ number_format($s->precio,0,',','.') }} Gs.</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProductos">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white"><h5 class="modal-title">Productos</h5></div>
            <div class="modal-body p-0">
                @foreach($productos as $p)
                <button type="button" class="btn btn-light btn-block text-left m-0 border-bottom rounded-0" 
                    onclick="agregarItem({{ $p->id }}, 'prod', '{{ $p->nombre }}', {{ $p->precio_venta }}); $('#modalProductos').modal('hide')">
                    {{ $p->nombre }} <span class="float-right font-weight-bold">{{ number_format($p->precio_venta,0,',','.') }} Gs.</span>
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

    // FUNCIÓN DE NAVEGACIÓN CORREGIDA
    function irAPaso(paso) {
        // Solo bloqueamos el Paso 3 si el carrito está vacío
        if (paso === 3 && items.length === 0) {
                Swal.fire('Carrito Vacío', 'Debe agregar artículos antes de revisar la venta.', 'warning');
                return;
            }

            // Si vamos al resumen, actualizamos los datos
            if (paso === 3) {
                generarResumen();
            }

            // 1. Cambiar visibilidad de los pasos
            $('.wizard-step').addClass('d-none');
            $(`#paso-${paso}`).removeClass('d-none');

            // 2. Manejo de las pestañas (Tabs)
            $('.nav-link').removeClass('active');
            $(`#tab-${paso}`).addClass('active').removeClass('disabled');

            // 3. Estética: Marcamos como completadas las pestañas anteriores
            for (let i = 1; i <= 3; i++) {
                if (i < paso) {
                    $(`#tab-${i} .badge`).removeClass('badge-primary').addClass('badge-success').html('<i class="fas fa-check"></i>');
                } else if (i === paso) {
                    $(`#tab-${i} .badge`).removeClass('badge-success badge-secondary').addClass('badge-primary').text(i);
                }
            }

            window.scrollTo(0, 0);
            renderTodo();
        }

    function renderTodo() {
        let htmlItems = '';
        let total = 0;
        
        items.forEach((it, idx) => {
            let sub = it.precio * it.cantidad;
            total += sub;
            htmlItems += `<tr>
                <td>${it.nombre}</td>
                <td>${it.precio.toLocaleString()} Gs.</td>
                <td><input type="number" class="form-control form-control-sm" value="${it.cantidad}" min="1" onchange="actualizarCantidad(${idx}, this.value)"></td>
                <td class="text-right">${sub.toLocaleString()} Gs.</td>
                <td><button type="button" class="btn btn-xs btn-danger" onclick="eliminarItem(${idx})">×</button></td>
            </tr>`;
        });
        
        $('#tabla_detalle').html(htmlItems);
        $('#total_venta_display, #resumen_total').text(total.toLocaleString() + ' Gs.');

        // Render Pagos
        let totalPagado = pagos.reduce((sum, p) => sum + p.monto, 0);
        let htmlPagos = '';
        pagos.forEach((p, idx) => {
            htmlPagos += `<li class="list-group-item d-flex justify-content-between p-2">
                <span><b>${p.metodo.toUpperCase()}</b></span>
                <span>${p.monto.toLocaleString()} Gs. <button type="button" class="btn btn-xs text-danger ml-2" onclick="eliminarPago(${idx})">×</button></span>
            </li>`;
        });
        $('#lista_pagos').html(htmlPagos);

        // Lógica de saldo y botón guardar
        let restante = total - totalPagado;
        if (restante < 0) restante = 0;
        $('#pago_restante').text(restante.toLocaleString() + ' Gs.');

        if (total > 0 && restante <= 0) {
            $('#box_restante').removeClass('alert-warning').addClass('alert-success').text('¡Pago completo!');
            $('#btnGuardarVenta').prop('disabled', false);
        } else {
            $('#box_restante').addClass('alert-warning').removeClass('alert-success').html(`Falta: <strong>${restante.toLocaleString()} Gs.</strong>`);
            $('#btnGuardarVenta').prop('disabled', true);
        }
    }

    function agregarItem(id, tipo, nombre, precio) {
        let existe = items.find(i => i.id == id && i.tipo == tipo);
        if (existe) existe.cantidad++;
        else items.push({id, tipo, nombre, precio, cantidad: 1});
        renderTodo();
    }

    function actualizarCantidad(idx, val) {
        items[idx].cantidad = parseInt(val > 0 ? val : 1);
        renderTodo();
    }

    function eliminarItem(idx) {
        items.splice(idx, 1);
        renderTodo();
    }

    function generarResumen() {
        let clienteNombre = $('#cliente_id option:selected').text();
        $('#resumen_cliente').text(clienteNombre || "Cliente Ocasional");

        let html = '';
        if (items.length === 0) {
            html = '<li class="list-group-item text-center text-muted">No hay artículos</li>';
        } else {
            items.forEach(it => {
                let subtotal = it.precio * it.cantidad;
                html += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-chevron-right text-xs text-primary mr-2"></i>
                            ${it.nombre} <small class="text-muted">(x${it.cantidad})</small>
                        </span>
                        <span class="font-weight-bold">${subtotal.toLocaleString()} Gs.</span>
                    </li>`;
            });
        }
        $('#resumen_items').html(html);
    }

    function agregarPago() {
        let totalVenta = items.reduce((sum, i) => sum + (i.precio * i.cantidad), 0);
        let totalPagado = pagos.reduce((sum, p) => sum + p.monto, 0);
        let faltante = totalVenta - totalPagado;
        if (faltante <= 0) return;

        let monto = prompt("Ingrese el monto a recibir:", faltante);
        if (monto !== null && monto > 0) {
            pagos.push({ metodo: $('#metodo_pago').val(), monto: parseFloat(monto) });
            renderTodo();
        }
    }

    function eliminarPago(idx) {
        pagos.splice(idx, 1);
        renderTodo();
    }

    function confirmarVenta() {
        if (confirm("¿Confirmar y guardar esta venta?")) {
            $('#items_json').val(JSON.stringify(items));
            $('#pagos_json').val(JSON.stringify(pagos));
            $('#formVenta').submit();
        }
    }

    $(document).ready(function() {
        $('.select2').select2();
        renderTodo();
    });
</script>
@endpush