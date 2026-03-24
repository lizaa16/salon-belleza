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
        Route::resource('categorias', CategoriaServicioController::class)
        ->names('categorias');

        Route::resource('servicios', ServicioController::class)
        ->names('servicios');

        Route::resource('personas', PersonaController::class)
        ->names('personas');

        Route::resource('clientes', ClienteController::class)
        ->names('clientes');

        Route::resource('empleados', EmpleadoController::class)
        ->names('empleados');
            Route::get('empleados/{empleado}/password', [EmpleadoController::class, 'editPassword'])
            ->name('empleados.password.edit');
            Route::put('empleados/{empleado}/password', [EmpleadoController::class, 'updatePassword'])
            ->name('empleados.password.update');

        Route::resource('citas', CitaController::class)
            ->names('citas');
        Route::patch('citas/{cita}/cancelar', [CitaController::class, 'cancelar'])
            ->name('citas.cancelar');

        Route::get('configuracion', [SettingController::class, 'index'])
        ->name('settings.index');
        Route::post('configuracion', [SettingController::class, 'update'])
        ->name('settings.update');
    });

});

require __DIR__.'/auth.php';
