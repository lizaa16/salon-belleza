<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    protected $fillable = [
        'inscripcion_id', 'ruta_pdf', 'emitido_en', 'enviado_en',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class);
    }
}