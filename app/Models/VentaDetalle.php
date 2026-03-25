<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    // Relación con el producto (si es un producto)
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Relación con el servicio (si es un servicio)
    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
