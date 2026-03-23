<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Services\PersonaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    protected $personaService;

    public function __construct(PersonaService $personaService)
    {
        $this->personaService = $personaService;
    }

    public function index()
    {
        $empleados = Empleado::with('persona', 'user')->get();
        return view('admin.empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('admin.empleados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|min:3|max:100',
            'apellido'        => 'required|min:3|max:100',
            'documento'       => 'nullable|max:20',
            'telefono'        => 'nullable|max:20',
            'fecha_nacimiento'=> 'nullable|date',
            'direccion'       => 'nullable|max:255',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:8|confirmed',
            'especialidad'    => 'nullable|max:100',
            'tasa_comision'   => 'nullable|numeric|min:0|max:100',
        ]);

        $this->personaService->crearEmpleado($request->all());

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado creado correctamente.');
    }

    public function edit(Empleado $empleado)
    {
        return view('admin.empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        // Quitamos 'email', 'password' y 'password_confirmation' de aquí
        $request->validate([
            'nombre'           => 'required|min:3|max:100',
            'apellido'         => 'required|min:3|max:100',
            'documento'        => 'nullable|max:20',
            'telefono'         => 'nullable|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'direccion'        => 'nullable|max:255',
            'especialidad'     => 'nullable|max:100',
            'tasa_comision'    => 'nullable|numeric|min:0|max:100',
        ]);

        $this->personaService->actualizarEmpleado($empleado, $request->all());

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Datos del empleado actualizados.');
    }

    // --- NUEVOS MÉTODOS PARA LA LLAVE ---

    public function editPassword(Empleado $empleado)
    {
        // Retorna la vista con el formulario pequeño de email/password
        return view('admin.empleados.password', compact('empleado'));
    }

    public function updatePassword(Request $request, Empleado $empleado)
    {
        $request->validate([
            'email'    => 'required|email|unique:users,email,' . $empleado->user_id,
            'password' => 'nullable|confirmed|min:8', // nullable por si solo quieren cambiar el email
        ]);

        $user = $empleado->user;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Credenciales de acceso actualizadas correctamente.');
    }

    public function destroy(Empleado $empleado)
    {
        $empleado->user->delete();
        $empleado->persona->delete();

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado eliminado correctamente.');
    }
}