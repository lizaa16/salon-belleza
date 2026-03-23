<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaServicio;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with('categoria')->get();
        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        $categorias = CategoriaServicio::all();
        return view('admin.servicios.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categoria_servicio,id',
            'nombre'       => 'required|min:3|max:100',
            'precio'       => 'required|numeric|min:0',
            'horas'        => 'required|integer|min:0|max:12',
            'minutos'      => 'required|integer|min:0|max:59',
        ]);

        $duracion = ($request->horas * 60) + $request->minutos;

        Servicio::create([
            'categoria_id' => $request->categoria_id,
            'nombre'       => $request->nombre,
            'precio'       => $request->precio,
            'duracion_min' => $duracion,
            'activo'       => $request->activo ?? 0,
        ]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio creado correctamente.');
    }

    public function edit(Servicio $servicio)
    {
        $categorias = CategoriaServicio::all();
        return view('admin.servicios.edit', compact('servicio', 'categorias'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categoria_servicio,id',
            'nombre'       => 'required|min:3|max:100',
            'precio'       => 'required|numeric|min:0',
            'horas'        => 'required|integer|min:0|max:12',
            'minutos'      => 'required|integer|min:0|max:59',
        ]);

        $duracion = ($request->horas * 60) + $request->minutos;

        $servicio->update([
            'categoria_id' => $request->categoria_id,
            'nombre'       => $request->nombre,
            'precio'       => $request->precio,
            'duracion_min' => $duracion,
            'activo'       => $request->activo ?? 0,
        ]);

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio eliminado correctamente.');
    }
}