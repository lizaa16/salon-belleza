<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
    {
        // Esto traduce los nombres cortos de tu BD a las Clases reales de PHP
        Relation::enforceMorphMap([
            'serv' => \App\Models\Servicio::class,
            'prod' => \App\Models\Producto::class,
        ]);
    }
}
