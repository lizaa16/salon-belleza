<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class VentaDetalle extends Model
{
    protected $table = 'venta_detalles';

    protected $fillable = [
        'venta_id', 'item_id', 'item_type', 'cantidad', 
        'precio_unitario', 'subtotal', 'tasa_iva', 'monto_iva'
    ];

    // Relación con la venta a la que pertenece
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    /**
     * Relación polimórfica dinámica
     */
    public function item(): MorphTo
    {
        // Laravel ahora ya sabe que 'serv' es Servicio gracias al Provider
        return $this->morphTo(__FUNCTION__, 'item_type', 'item_id');
    }
}
