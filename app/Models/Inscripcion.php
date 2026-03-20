<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $fillable = [
        'curso_id', 'cliente_id', 'pagado', 'inscripto_en',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function certificado()
    {
        return $this->hasOne(Certificado::class);
    }
}