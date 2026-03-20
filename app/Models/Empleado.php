<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'persona_id',
        'user_id',
        'especialidad',
        'tasa_comision',
        'activo',
    ];

    // Un empleado pertenece a una persona
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    // Un empleado pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un empleado tiene muchas citas
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    // Un empleado ofrece muchos servicios
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'empleado_servicios');
    }

    // Un empleado dicta muchos cursos
    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }
}