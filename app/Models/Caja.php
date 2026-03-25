<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'cajas';

    protected $fillable = [
        'user_id', 
        'fecha_apertura', 
        'fecha_cierre', 
        'monto_apertura', 
        'monto_cierre', 
        'monto_real_en_caja', 
        'diferencia', 
        'estado'
    ];

    // Relación con el usuario que abrió la caja
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con todos los movimientos que ocurrieron en esta caja
    public function movimientos()
    {
        return $this->hasMany(CajaMovimiento::class);
    }
}