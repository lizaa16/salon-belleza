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
</style>
@endpush

@section('content')
    @yield('admin_content') {{-- Aquí se inyectará el contenido de tus otras vistas --}}
@stop