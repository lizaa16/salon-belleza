<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\CitaDetalle;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $query = Cita::with('cliente.persona', 'empleado.persona', 'detalles.servicio');

        if ($request->filled('empleado_id')) {
            $query->where('empleado_id', $request->empleado_id);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_hora', $request->fecha);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $citas     = $query->orderBy('fecha_hora', 'desc')->get();
        $empleados = Empleado::with('persona')->where('activo', true)->get();

        return view('admin.citas.index', compact('citas', 'empleados'));
    }

    public function create()
    {
        $clientes  = Cliente::with('persona')->get();
        $empleados = Empleado::with('persona')->where('activo', true)->get();
        $servicios = Servicio::where('activo', true)->get();

        return view('admin.citas.create', compact('clientes', 'empleados', 'servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'empleado_id'      => 'required|exists:empleados,id',
            'fecha_hora'       => 'required|date|after:now',
            'notas'            => 'nullable|max:500',
            'seña_monto'       => 'nullable|numeric|min:0',
            'seña_metodo_pago' => 'nullable|required_with:seña_monto|in:efectivo,tarjeta,transferencia',
            'detalles'         => 'required|json',
        ]);

        $detalles = json_decode($request->detalles, true);

        if (empty($detalles)) {
            return back()->withErrors(['detalles' => 'Debe agregar al menos un servicio.'])
                         ->withInput();
        }

        DB::transaction(function () use ($request, $detalles) {
            // 1. Crear cabecera
            $cita = Cita::create([
                'cliente_id'       => $request->cliente_id,
                'empleado_id'      => $request->empleado_id,
                'fecha_hora'       => $request->fecha_hora,
                'estado'           => 'pendiente',
                'notas'            => $request->notas,
                'seña_monto'       => $request->seña_monto,
                'seña_metodo_pago' => $request->seña_metodo_pago,
            ]);

            // 2. Insertar detalles
            foreach ($detalles as $detalle) {
                CitaDetalle::create([
                    'cita_id'         => $cita->id,
                    'servicio_id'     => $detalle['servicio_id'],
                    'cantidad'        => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal'        => $detalle['cantidad'] * $detalle['precio_unitario'],
                ]);
            }
        });

        return redirect()->route('admin.citas.index')
            ->with('success', 'Cita registrada correctamente.');
    }

    public function edit(Cita $cita)
    {
        $clientes  = Cliente::with('persona')->get();
        $empleados = Empleado::with('persona')->where('activo', true)->get();
        $servicios = Servicio::where('activo', true)->get();

        return view('admin.citas.edit', compact('cita', 'clientes', 'empleados', 'servicios'));
    }

    public function update(Request $request, Cita $cita)
    {
        $request->validate([
            'cliente_id'       => 'required|exists:clientes,id',
            'empleado_id'      => 'required|exists:empleados,id',
            'fecha_hora'       => 'required|date|after:now',
            'notas'            => 'nullable|max:500',
            'seña_monto'       => 'nullable|numeric|min:0',
            'seña_metodo_pago' => 'nullable|required_with:seña_monto|in:efectivo,tarjeta,transferencia',
            'detalles'         => 'required|json',
        ]);

        $detalles = json_decode($request->detalles, true);

        DB::transaction(function () use ($request, $detalles, $cita) {
            // 1. Actualizar cabecera
            $cita->update([
                'cliente_id'       => $request->cliente_id,
                'empleado_id'      => $request->empleado_id,
                'fecha_hora'       => $request->fecha_hora,
                'notas'            => $request->notas,
                'seña_monto'       => $request->seña_monto,
                'seña_metodo_pago' => $request->seña_metodo_pago,
            ]);

            // 2. Borrar detalles anteriores y reinsertar
            $cita->detalles()->delete();

            foreach ($detalles as $detalle) {
                CitaDetalle::create([
                    'cita_id'         => $cita->id,
                    'servicio_id'     => $detalle['servicio_id'],
                    'cantidad'        => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal'        => $detalle['cantidad'] * $detalle['precio_unitario'],
                ]);
            }
        });

        return redirect()->route('admin.citas.index')
            ->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita)
    {
        DB::transaction(function () use ($cita) {
            $cita->detalles()->delete();
            $cita->delete();
        });

        return redirect()->route('admin.citas.index')
            ->with('success', 'Cita eliminada correctamente.');
    }

    public function cancelar(Cita $cita)
    {
        $cita->update(['estado' => 'cancelada']);

        return redirect()->route('admin.citas.index')
            ->with('success', 'Cita cancelada correctamente.');
    }

}