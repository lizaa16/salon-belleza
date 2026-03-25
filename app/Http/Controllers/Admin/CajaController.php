<?php

// app/Http/Controllers/Admin/CajaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caja;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\CajaMovimiento;

class CajaController extends Controller
{
    public function index()
    {
        $cajaAbierta = Caja::with('movimientos')
                        ->where('user_id', auth()->id())
                            ->where('estado', 'abierta')
                            ->first();

        if (!$cajaAbierta) {
            return view('admin.cajas.create');
        }

        // Calculamos totales para los cuadritos
        $ingresos = $cajaAbierta->movimientos->where('tipo', 'ingreso')->sum('monto');
        $egresos = $cajaAbierta->movimientos->where('tipo', 'egreso')->sum('monto');
        $saldo_actual = $cajaAbierta->monto_apertura + $ingresos - $egresos;

        return view('admin.cajas.index', compact('cajaAbierta', 'ingresos', 'egresos', 'saldo_actual'));
    }

    public function abrir(Request $request)
    {
        $request->validate([
            'monto_apertura' => 'required|numeric|min:0'
        ]);

        Caja::create([
            'user_id' => auth()->id(),
            'fecha_apertura' => Carbon::now(),
            'monto_apertura' => $request->monto_apertura,
            'estado' => 'abierta'
        ]);

        return redirect()->route('admin.cajas.create')
                         ->with('success', '¡Caja abierta con éxito! Ya podés vender.');
    }

    public function registrarMovimiento(Request $request)
    {
        $request->validate([
            'monto' => 'required|numeric|min:1',
            'tipo' => 'required|in:ingreso,egreso',
            'concepto' => 'required|string|max:255'
        ]);

        $caja = Caja::where('user_id', auth()->id())
                    ->where('estado', 'abierta')
                    ->first();

        if (!$caja) {
            return back()->with('error', 'No hay una caja abierta.');
        }

        CajaMovimiento::create([
            'caja_id' => $caja->id,
            'tipo' => $request->tipo,
            'monto' => $request->monto,
            'concepto' => "[MANUAL] " . $request->concepto,
            'metodo_pago' => 'efectivo',
        ]);

        $mensaje = ($request->tipo == 'ingreso') ? 'Ingreso registrado.' : 'Salida registrada.';
        return back()->with('success', $mensaje);
    }
}