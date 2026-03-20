<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'categoria_id', 'nombre',
        'precio', 'duracion_min', 'activo',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaServicio::class, 'categoria_id');
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'empleado_servicios');
    }
}