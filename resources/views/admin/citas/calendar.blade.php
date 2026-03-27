@extends('admin.layout')

@section('title', 'Calendario de Citas')

@section('content_header')
    <h1>Agenda de Citas</h1>
@stop

@section('admin_content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-body p-0">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEvento" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">Agendar Nueva Cita</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="formContainer">
                    <p class="text-center">Cargando formulario...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <style>
        #calendar { max-height: 700px; margin: 20px; }
        .fc-event { cursor: pointer; }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek', // Vista semanal con horas
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotMinTime: '08:00:00', // Hora de apertura
            slotMaxTime: '20:00:00', // Hora de cierre
            events: "{{ route('admin.citas.eventos') }}", // Carga los datos de la API
            
            // Evento al hacer click en una cita
            eventClick: function(info) {
                if (info.event.url) {
                    info.jsEvent.preventDefault();
                    window.location.href = info.event.url;
                }
            },
            
            selectable: true,
            select: function(info) {
                // Al seleccionar un rango de horas:
                $('#modalEvento').modal('show');
                
                // Opcional: Cargar tu vista 'create' dentro del modal vía AJAX
                // O simplemente redirigir pasando la fecha:
                // window.location.href = "{{ route('admin.citas.create') }}?fecha=" + info.startStr;
                
                // Tip: Si quieres hacerlo pro, usa un iframe para cargar 'admin/citas/create'
                $('#formContainer').html(`
                    <iframe src="{{ route('admin.citas.create') }}?fecha_hora=${info.startStr}" 
                            style="width:100%; height:500px; border:none;"></iframe>
                `);
            },
            // Estilo de los eventos
            eventDidMount: function(info) {
                // Puedes agregar un tooltip aquí si quieres ver las notas
                $(info.el).attr('title', info.event.title);
            }
        });
        calendar.render();
        
        window.addEventListener('message', function(event) {
            if (event.data === 'cita-guardada') {
                // 1. Cerramos el modal
                $('#modalEvento').modal('hide'); 
                
                // 2. Refrescamos los eventos del calendario sin recargar la página
                calendar.refetchEvents(); 
                
                // 3. Opcional: Mostrar una alerta de éxito en la página principal
                toastr.success('¡Cita agendada con éxito!');
            }
        }, false);
    });
    </script>
@endpush