<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $fillable = [
        'venta_id', 'servicio_id',
        'cantidad', 'precio_unitario', 'subtotal',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}