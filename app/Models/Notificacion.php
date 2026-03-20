<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $fillable = [
        'user_id', 'tipo', 'mensaje', 'enviado_en', 'leido_en',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}