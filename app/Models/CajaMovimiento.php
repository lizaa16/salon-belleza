<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaMovimiento extends Model
{
    protected $table = 'caja_movimientos';

    protected $fillable = [
        'caja_id', 
        'tipo', 
        'monto', 
        'concepto', 
        'metodo_pago', 
        'referencia_id', 
        'referencia_type'
    ];

    // Relación con la caja a la que pertenece
    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    // Esta es la parte "pro": permite que el movimiento apunte a una Cita o a una Venta
    public function referencia()
    {
        return $this->morphTo();
    }
}