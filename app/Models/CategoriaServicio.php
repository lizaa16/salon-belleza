<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CategoriaServicio extends Model
{
    protected $table = 'categoria_servicio';
    protected $fillable = ['nombre', 'descripcion'];

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'categoria_id');
    }
}