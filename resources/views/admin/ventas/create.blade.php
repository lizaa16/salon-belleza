@extends('adminlte::page')

@section('title', 'Nueva Venta')

@section('admin_content')
<div class="row">
    <div class="col-md-7">
        <div class="card" style="background-color: var(--sidebar-color); color: white; border-top: 3px solid var(--primary-color);">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-magic"></i> Servicios y Productos</h3>
            </div>
            <div class="card-body">
                <label>Accesos Rápidos</label>
                <div class="d-flex flex-wrap mb-4" style="gap: 10px;">
                    @foreach($servicios->take(4) as $sv)
                        <button type="button" class="btn btn-outline-light btn-lg flex-fill" 
                                onclick="agregarItem('serv', {{ $sv->id }}, '{{ $sv->nombre }}', {{ $sv->precio }}, {{ $sv->iva ?? 10 }})">
                            <i class="fas fa-star text-warning"></i><br>
                            <small>{{ $sv->nombre }}</small><br>
                            <strong>{{ number_format($sv->precio, 0, ',', '.') }}</strong>
                        </button>
                    @endforeach
                </div>

                <hr style="border-top: 1px solid #444;">

                <div class="form-group">
                    <label>Buscador General (F2)</label>
                    <select id="buscador_general" class="form-control select2">
                        <option value="">Buscar servicio o producto...</option>
                        <optgroup label="Servicios">
                            @foreach($servicios as $sv)
                                <option value="serv-{{ $sv->id }}" data-tipo="serv" data-id="{{ $sv->id }}" data-nombre="{{ $sv->nombre }}" data-precio="{{ $sv->precio }}" data-iva="{{ $sv->iva ?? 10 }}">
                                    [S] {{ $sv->nombre }} - {{ number_format($sv->precio, 0, ',', '.') }} Gs.
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Productos">
                            @foreach($productos as $prod)
                                <option value="prod-{{ $prod->id }}" data-tipo="prod" data-id="{{ $prod->id }}" data-nombre="{{ $prod->nombre }}" data-precio="{{ $prod->precio_venta }}" data-iva="{{ $prod->iva ?? 10 }}">
                                    [P] {{ $prod->nombre }} - {{ number_format($prod->precio_venta, 0, ',', '.') }} Gs. (Stock: {{ $prod->stock_actual }})
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card card-dark">
            <div class="card-header border-0">
                <h3 class="card-title"><i class="fas fa-shopping-cart text-primary"></i> Detalle de Venta</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-dark m-0" id="tabla_venta">
                    <thead>
                        <tr style="color: var(--primary-color);">
                            <th>Ítem</th>
                            <th width="80">Cant.</th>
                            <th width="100">Subtotal</th>
                            <th width="30"></th>
                        </tr>
                    </thead>
                    <tbody id="detalle_venta">
                        </tbody>
                </table>
            </div>
            <div class="card-footer" style="background-color: #222;">
                <div class="d-flex justify-content-between h4">
                    <span>TOTAL:</span>
                    <span id="txt_total" style="color: var(--primary-color);">0 Gs.</span>
                </div>
                <button class="btn btn-primary btn-block btn-lg mt-3" data-toggle="modal" data-target="#modalCobro">
                    <i class="fas fa-money-check-alt"></i> PROCEDER AL COBRO
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    let items = [];

    $(document).ready(function() {
        $('.select2').select2({ theme: 'bootstrap4' });

        // Al seleccionar del buscador
        $('#buscador_general').on('select2:select', function (e) {
            let data = e.params.data.element.dataset;
            agregarItem(data.tipo, data.id, data.nombre, data.precio, data.iva);
            $(this).val(null).trigger('change'); // Limpiar buscador
        });
    });

    function agregarItem(tipo, id, nombre, precio, iva) {
        let existe = items.find(i => i.id === id && i.tipo === tipo);
        
        if (existe) {
            existe.cantidad++;
        } else {
            items.push({ tipo, id, nombre, precio, iva, cantidad: 1 });
        }
        renderizarTabla();
    }

    function renderizarTabla() {
        let html = '';
        let total = 0;

        items.forEach((item, index) => {
            let subtotal = item.precio * item.cantidad;
            total += subtotal;
            html += `
                <tr>
                    <td><small>${item.nombre}</small></td>
                    <td>
                        <input type="number" class="form-control form-control-sm bg-dark text-white border-0" 
                               value="${item.cantidad}" onchange="actualizarCant(${index}, this.value)">
                    </td>
                    <td>${subtotal.toLocaleString('es-PY')}</td>
                    <td><button class="btn btn-xs btn-danger" onclick="eliminarItem(${index})">×</button></td>
                </tr>
            `;
        });

        $('#detalle_venta').html(html);
        $('#txt_total').text(total.toLocaleString('es-PY') + ' Gs.');
    }

    function eliminarItem(index) {
        items.splice(index, 1);
        renderizarTabla();
    }

    function actualizarCant(index, valor) {
        if(valor < 1) return;
        items[index].cantidad = valor;
        renderizarTabla();
    }
</script>
@endpush