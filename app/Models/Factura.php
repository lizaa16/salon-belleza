<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        'venta_id', 'nro_factura', 'estado',
        'xml_contenido', 'respuesta_sifen', 'emitido_en',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }
}