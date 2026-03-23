<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    public function index()
    {
        $personas = Persona::all();
        return view('admin.personas.index', compact('personas'));
    }

    public function create()
    {
        return view('admin.personas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|min:3|max:50',
            'apellido'         => 'required|min:3|max:50',
            'telefono'         => 'nullable|regex:/^[0-9\s\-\+\(\)]+$/|min:7|max:20',
            'documento'        => 'required|unique:personas,documento|min:5|max:20',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'direccion'        => 'nullable|max:255',
        ]);

        Persona::create($request->only('nombre', 'apellido', 'telefono', 'documento', 'fecha_nacimiento', 'direccion'));

        return redirect()->route('admin.personas.index')
            ->with('success', 'Persona creada correctamente.');
    }

    public function edit(Persona $persona)
    {
        return view('admin.personas.edit', compact('persona'));
    }

    public function update(Request $request, Persona $persona)
    {
        $request->validate([
            'nombre'           => 'required|min:3|max:50',
            'apellido'         => 'required|min:3|max:50',
            'telefono'         => 'nullable|regex:/^[0-9\s\-\+\(\)]+$/|min:7|max:20',
            'documento'        => 'required|unique:personas,documento,' . $persona->id . '|min:5|max:20',
            'fecha_nacimiento' => 'required|date|before:today',
            'direccion'        => 'nullable|max:255',
        ]);

        $persona->update($request->only('nombre', 'apellido', 'telefono', 'documento', 'fecha_nacimiento', 'direccion'));

        return redirect()->route('admin.personas.index')
            ->with('success', 'Persona actualizada correctamente.');
    }

    public function destroy(Persona $persona)
    {
        $persona->delete();

        return redirect()->route('admin.personas.index')
            ->with('success', 'Persona eliminada correctamente.');
    }
}
