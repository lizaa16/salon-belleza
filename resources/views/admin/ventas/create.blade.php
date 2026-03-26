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
                                    <option value="" disabled selected>-- Seleccione un cliente--</option>
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
                                <div class="mt-3">
                                    <label>Seleccionar Cita (opcional)</label>
                                    <select id="cita_id" class="form-control">
                                        <option value="">-- Sin cita --</option>
                                    </select>
                                </div>
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
                                <h5>Pagos Registrados</h5>
                                <button type="button" class="btn btn-outline-success btn-block mb-3" onclick="agregarPago()">
                                    <i class="fas fa-plus"></i> Agregar Pago
                                </button>

                                <ul id="lista_pagos" class="list-group mb-3"></ul>

                                <div id="box_restante" class="alert alert-warning text-center">
                                    Resta pagar: <strong id="pago_restante">0 Gs.</strong>
                                </div>

                                <button type="button" id="btnGuardarVenta" class="btn btn-block btn-success btn-lg shadow" disabled onclick="confirmarVenta()">
                                    <i class="fas fa-save mr-2"></i> Finalizar Venta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <div class="mt-4">
                <a href="{{ route('admin.ventas.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-2"></i> Volver al Listado</a>
            </div>
        </div>
    
    </div>
</div>

<div class="modal fade" id="modalServicios">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title">Servicios</h5>
            </div>
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

<!-- Modal para registrar pagos -->
<div class="modal fade" id="modalPagos">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 2px solid var(--primary-color);">
                <h5 class="modal-title">REGISTRAR PAGO</h5>
            </div>
            <div class="modal-body">
                <form id="formPago">
                    <div class="form-group">
                        <label for="metodo_pago">Método de Pago</label>
                        <select class="form-control" id="metodo_pago" required>
                            <option value="">Seleccione un método</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                            <option value="transferencia">Transferencia Bancaria</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="monto_pago">Monto a Pagar</label>
                        <input type="number" class="form-control" id="monto_pago" min="0" step="100" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarPago()">Guardar Pago</button>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    let items = [];
    let pagos = [];

    $('#cliente_id').change(function() {
        let clienteId = $(this).val();

        if (!clienteId) return;

        $.get(`/admin/citas/pendientes/${clienteId}`, function(data) {
            let options = '<option value="">-- Sin cita --</option>';

            data.forEach(c => {
                options += `<option value="${c.id}">
                    Cita #${c.id} - ${c.fecha_hora}
                </option>`;
            });

            $('#cita_id').html(options);
        });
    });

    $('#cita_id').change(function() {
        let citaId = $(this).val();

        if (!citaId) return;

        $.get(`/admin/citas/${citaId}`, function(cita) {

            // 🔹 Limpiar carrito
            items = [];

            // 🔹 Cargar servicios de la cita
            cita.detalles.forEach(d => {
                items.push({
                    id: d.servicio_id,
                    tipo: 'serv',
                    nombre: d.servicio.nombre,
                    precio: d.precio_unitario,
                    cantidad: d.cantidad
                });
            });

            // 🔹 Aplicar seña como pago automático
            if (cita.seña_monto && cita.seña_monto > 0) {
                pagos = [{
                    metodo: cita.seña_metodo_pago,
                    monto: parseFloat(cita.seña_monto),
                    es_sena: true
                }];
            } else {
                pagos = [];
            }

            renderTodo();
        });
    });

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
    
    // Cálculo del Total de la Venta
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

    // Render Pagos y Cálculo de Total Pagado
    let totalPagado = pagos.reduce((sum, p) => sum + p.monto, 0);
    let htmlPagos = '';
    pagos.forEach((p, idx) => {
        htmlPagos += `<li class="list-group-item d-flex justify-content-between p-2">
            <span><b>${p.metodo.toUpperCase()}</b></span>
            <span>${p.monto.toLocaleString()} Gs. <button type="button" class="btn btn-xs text-danger ml-2" onclick="eliminarPago(${idx})">×</button></span>
        </li>`;
    });
    $('#lista_pagos').html(htmlPagos);

    // --- LÓGICA DE VUELTO ---
    let diferencia = totalPagado - total; // Si es positivo, es vuelto. Si es negativo, falta.

    if (total > 0 && diferencia >= 0) {
        // PAGO COMPLETO O CON VUELTO
        let vuelto = diferencia;
        $('#box_restante')
            .removeClass('alert-warning')
            .addClass('alert-success')
            .html(`<strong>¡Pago completo!</strong> ${vuelto > 0 ? '<br>Vuelto: <b>' + vuelto.toLocaleString() + ' Gs.</b>' : ''}`);
        
        $('#btnGuardarVenta').prop('disabled', false);
        $('#pago_restante').text('0 Gs.');
    } else {
        // FALTA PAGAR
        let faltante = Math.abs(diferencia);
        $('#box_restante')
                .addClass('alert-warning')
                .removeClass('alert-success')
                .html(`Falta: <strong>${faltante.toLocaleString()} Gs.</strong>`);
            
            $('#btnGuardarVenta').prop('disabled', true);
            $('#pago_restante').text(faltante.toLocaleString() + ' Gs.');
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

        if (faltante <= 0) {
            Swal.fire('Venta Cubierta', 'Ya se ha alcanzado el total de la venta.', 'info');
            return;
        }

        // Ponemos el monto que falta por defecto en el input del modal
        $('#monto_pago').val(faltante);
        
        // Abrimos el modal
        $('#modalPagos').modal('show');
    }

    function guardarPago() {
        let metodo = $('#metodo_pago').val(); // Captura del select del modal
        let monto = parseFloat($('#monto_pago').val()); // Captura del input del modal

        if (!metodo) {
            Swal.fire('Error', 'Debe seleccionar un método de pago', 'error');
            return;
        }

        if (isNaN(monto) || monto <= 0) {
            Swal.fire('Error', 'Ingrese un monto válido', 'error');
            return;
        }

        // Agregamos al array global de pagos
        pagos.push({ 
            metodo: metodo, 
            monto: monto 
        });

        // Cerramos el modal y limpiamos los campos para la próxima vez
        $('#modalPagos').modal('hide');
        $('#metodo_pago').val('');
        $('#monto_pago').val('');

        // LLAMAMOS A TU FUNCIÓN PARA REFRESCAR LA PANTALLA
        renderTodo();
    }

    function eliminarPago(idx) {
        pagos.splice(idx, 1);
        renderTodo();
    }

    function confirmarVenta() {
        if (items.length === 0) {
            Swal.fire('Error', 'El carrito está vacío', 'warning');
            return;
        }

        // 1. Asignamos valores
        $('#items_json').val(JSON.stringify(items));
        $('#pagos_json').val(JSON.stringify(pagos));

        // 2. Ejecutamos la alerta
        Swal.fire({
            title: '¿Confirmar Venta?',
            text: "Esta acción guardará la venta en el sistema",
            icon: 'question', // Ponemos ambos por compatibilidad
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Guardando venta...',
                    text: 'Por favor, espere un momento',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
            });

            document.getElementById('formVenta').submit();
}
        });
    }
</script>
@endpush