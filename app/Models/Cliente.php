<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['persona_id', 'notas', 'activo'];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }
}