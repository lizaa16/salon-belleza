<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Services\PersonaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{

    protected $personaService;

    public function __construct(PersonaService $personaService)
    {
        $this->personaService = $personaService;
    }

    public function index()
    {
        $clientes = Cliente::with('persona')->get();
        return view('admin.clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|min:3|max:100',
            'apellido'        => 'required|min:3|max:100',
            'documento'       => 'required|unique:personas,documento|max:20',
            'telefono'        => 'nullable|max:20',
            'fecha_nacimiento'=> 'nullable|date',
            'direccion'       => 'nullable|max:255',
            'email'           => 'nullable|email|unique:personas,email'
        ]);

        $this->personaService->crearCliente($request->all());

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));     
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre'          => 'required|min:3|max:100',
            'apellido'        => 'required|min:3|max:100',
            'documento'       => 'required|max:20|unique:personas,documento,' . $cliente->persona->id,
            'telefono'        => 'nullable|max:20',
            'fecha_nacimiento'=> 'nullable|date',
            'direccion'       => 'nullable|max:255',
            'email'           => 'nullable|email|unique:personas,email,' . $cliente->persona->id,
        ]);

        $this->personaService->actualizarCliente($cliente, $request->all());

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->persona->delete();

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
