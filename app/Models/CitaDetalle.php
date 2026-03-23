<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitaDetalle extends Model
{
    protected $fillable = [
        'cita_id',
        'servicio_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}