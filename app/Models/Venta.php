<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'cita_id', 'cliente_id', 'empleado_id', 'total', 'estado', 
        'total_pagar', 'monto_final_cobrado', 'total_bruto'
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

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
        return $this->hasMany(VentaDetalle::class);
    }

    public function factura()
    {
        return $this->hasOne(Factura::class);
    }
}