<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'cliente_id',
        'empleado_id',
        'servicio_id',
        'fecha_hora',
        'estado',
        'notas',
        'seña_monto',
        'seña_metodo_pago',
        'reagendado_de',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function detalles()
    {
        return $this->hasMany(CitaDetalle::class);
    }

    public function citaOriginal()
    {
        return $this->belongsTo(Cita::class, 'reagendado_de');
    }

    public function venta()
    {
        return $this->hasOne(Venta::class);
    }
}