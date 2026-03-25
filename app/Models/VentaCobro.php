<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaCobro extends Model
{
    protected $table = 'venta_cobros';

    protected $fillable = [
        'venta_id', 
        'monto', 
        'metodo_pago', 
        'referencia'
    ];

    // Relación con la venta a la que pertenece
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}
