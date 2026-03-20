<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = [
        'empleado_id', 'nombre', 'descripcion',
        'precio', 'cupos', 'fecha_inicio', 'fecha_fin',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }
}