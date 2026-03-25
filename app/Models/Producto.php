<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo_barra',
        'descripcion',
        'precio_venta',
        'stock_actual',
        'stock_minimo',
        'iva',
        'estado'
    ];

    // Relación polimórfica inversa para los detalles de venta
    public function ventaDetalles()
    {
        return $this->morphMany(VentaDetalle::class, 'item');
    }
}