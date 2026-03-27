@extends('admin.layout')

@section('title', 'Calendario')

@section('content_header')
    <h1>Calendario de Citas</h1>
@stop

@section('admin_content')
<div class="card">
    <div class="card-body">
        <div id="calendar" style="width: 100%;"></div>
    </div>
</div>

<div class="modal fade" id="citaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de la Cita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Cliente:</strong> <span id="modalCliente"></span></p>
                        <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                    </div>
                    <div class="col-md-6 text-right">
                        <p><strong>Estado:</strong> <span id="modalEstado" class="badge"></span></p>
                    </div>
                </div>
                <hr>
                <h6><strong>Servicios Detallados:</strong></h6>
                <table class="table table-sm table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Servicio</th>
                            <th>Cant.</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="modalDetallesCita">
                        </tbody>
                </table>
                <div class="text-right">
                    <strong>Total: <span id="modalTotal"></span></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <a href="#" id="btnEditarCita" class="btn btn-primary">Editar Cita</a>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        let calendarEl = document.getElementById('calendar');

        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 650,

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },

            events: "{{ route('admin.citas.events') }}",

            eventClick: function(info) {
                let citaId = info.event.id;
                
                // Limpiar tabla antes de cargar
                document.getElementById('modalDetallesCita').innerHTML = '<tr><td colspan="3" class="text-center">Cargando...</td></tr>';

                // 1. Petición al método show del controlador
                fetch(`/admin/citas/${citaId}`)
                    .then(response => response.json())
                    .then(cita => {
                        // 2. Llenar datos de cabecera
                        document.getElementById('modalCliente').innerText = info.event.extendedProps.cliente;
                        document.getElementById('modalFecha').innerText = info.event.extendedProps.fecha;
                        document.getElementById('modalEstado').innerText = info.event.extendedProps.estado;
                        
                        // 3. Configurar botón editar
                        document.getElementById('btnEditarCita').href = `/admin/citas/${citaId}/edit`;

                        // 4. Llenar la tabla de servicios
                        let htmlDetalles = '';
                        let totalCita = 0;

                        cita.detalles.forEach(det => {
                            htmlDetalles += `
                                <tr>
                                    <td>${det.servicio.nombre}</td>
                                    <td>${det.cantidad}</td>
                                    <td>$${parseFloat(det.subtotal).toLocaleString()}</td>
                                </tr>`;
                            totalCita += parseFloat(det.subtotal);
                        });

                        document.getElementById('modalDetallesCita').innerHTML = htmlDetalles;
                        document.getElementById('modalTotal').innerText = `$${totalCita.toLocaleString()}`;

                        // 5. Mostrar el Modal
                        $('#citaModal').modal('show');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('No se pudo cargar el detalle de la cita.');
                    });
            }
        });

        calendar.render();
    });
</script>
@endpush