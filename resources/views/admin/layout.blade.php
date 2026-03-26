@extends('adminlte::page')

{{-- Aquí usamos el nombre del salón dinámico --}}
@section('title', $globalSettings['site_name'] ?? 'Mi Salón de Belleza')

@push('css')
<style>
    :root {
        /* Si no hay color en la DB, usa Rosa por defecto */
        --primary-color: {{ $globalSettings['primary_color'] ?? '#FF85A1' }};
        --sidebar-color: {{ $globalSettings['sidebar_color'] ?? '#1a1a1a' }};
        --header-color: {{ $globalSettings['header_color'] ?? '#dfdfdf' }};
    }

    /* Cambiar el color del Sidebar (Barra lateral) */
    .main-sidebar { 
        background-color: var(--sidebar-color) !important; 
    }

    /* Cambiar el color de los botones principales */
    .btn-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }

    .card-primary {
        border-top: 3px solid var(--primary-color) !important;
    }

    .badge-primary {
        background-color: var(--primary-color) !important;
    }

    /* Cambiar el color de los elementos activos del menú */
    .nav-pills .nav-link.active, 
    .page-item.active .page-link {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }

    /* El logo (Brand Link) */
    .brand-link {
        background-color: var(--sidebar-color) !important;
        color: #fff !important;
    }

    /* Corregir altura y bordes para que parezcan de AdminLTE/Bootstrap */
    .select2-container .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
        border: 1px solid #ced4da !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 2.25rem !important;
        padding-left: 0.75rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 2.25rem !important;
    }

    /* Borde rojo cuando hay error de validación en Laravel */
    .is-invalid + .select2-container .select2-selection--single {
        border: 1px solid #dc3545 !important;
    }
</style>
@endpush

@section('content')
    @yield('admin_content') {{-- Aquí se inyectará el contenido de tus otras vistas --}}
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Inicialización global: se ejecutará en TODAS las páginas
        $('.select2').select2({
            theme: 'default',
            width: '100%',
            language: { noResults: () => "No se encontraron resultados" }
        });
    });
</script>
@stack('js') 
@stop