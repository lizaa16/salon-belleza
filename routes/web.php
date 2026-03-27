<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoriaServicioController;
use App\Http\Controllers\Admin\ServicioController;
use App\Http\Controllers\Admin\PersonaController;
use App\Http\Controllers\Admin\EmpleadoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\CitaController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\CajaController;
use App\Http\Controllers\Admin\VentaController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::prefix('admin')->name('admin.')->group(function() {
        
        // Rutas para categorias
        Route::resource('categorias', CategoriaServicioController::class)
        ->names('categorias');

        // Rutas para servicios
        Route::resource('servicios', ServicioController::class)
        ->names('servicios');

        // Rutas para personas
        Route::resource('personas', PersonaController::class)
        ->names('personas');

        // Rutas para clientes
        Route::resource('clientes', ClienteController::class)
        ->names('clientes');

        // Rutas para la Empleados
        Route::resource('empleados', EmpleadoController::class)
        ->names('empleados');
            Route::get('empleados/{empleado}/password', [EmpleadoController::class, 'editPassword'])
            ->name('empleados.password.edit');
            Route::put('empleados/{empleado}/password', [EmpleadoController::class, 'updatePassword'])
            ->name('empleados.password.update');

        // Rutas para la Agenda de Citas
        Route::get('citas/pendientes/{cliente_id}', [CitaController::class, 'pendientes'])
            ->name('citas.pendientes'); // <--- AGREGA ESTA LÍNEA

        Route::get('citas/calendar', [CitaController::class, 'calendar'])
            ->name('citas.calendar');

        Route::get('citas/events', [CitaController::class, 'events'])
            ->name('citas.events');

        Route::resource('citas', CitaController::class)
            ->names('citas');

        Route::patch('citas/{cita}/cancelar', [CitaController::class, 'cancelar'])
            ->name('citas.cancelar');
            
        // Rutas para ventas-citas
        Route::get('/citas/{id}', [CitaController::class, 'show']);
        // Eventos citas
       

        // Rutas para la configuyracion visual
        Route::get('configuracion', [SettingController::class, 'index'])
        ->name('settings.index');
        Route::post('configuracion', [SettingController::class, 'update'])
        ->name('settings.update');

        //Routas Productos
        Route::resource('productos', ProductoController::class)
        ->names('productos');

        // Rutas para la caja
        Route::get('caja', [CajaController::class, 'index'])
            ->name('cajas.index');
            Route::post('caja/abrir', [CajaController::class, 'abrir'])
                ->name('cajas.abrir');
            Route::post('cajas/movimiento', [CajaController::class, 'registrarMovimiento'])
                ->name('cajas.movimiento');
            // Rutas para cierre-caja
            Route::post('cajas/cerrar', [CajaController::class, 'cerrar'])
                ->name('cajas.cerrar');
            Route::get('cajas/resumen/{id}', [CajaController::class, 'verResumen'])
                ->name('cajas.resumen');
            Route::get('reportes/cajas', [CajaController::class, 'reporteHistorial'])
                ->name('reportes.cajas');

        // Rutas para ventas
        Route::resource('ventas', VentaController::class)
            ->names('ventas');
        Route::get('/ventas/{id}', [VentaController::class, 'show'])
            ->name('ventas.show');
        
    });

});

require __DIR__.'/auth.php';
