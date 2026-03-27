<?php

// app/Http/Controllers/Admin/CajaController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caja;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\CajaMovimiento;
use Illuminate\Support\Facades\DB;

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

        $efectivo = $cajaAbierta->movimientos
            ->where('tipo', 'ingreso')
            ->where('metodo_pago', 'efectivo')
            ->sum('monto');

        $transferencia = $cajaAbierta->movimientos
            ->where('tipo', 'ingreso')
            ->where('metodo_pago', 'transferencia')
            ->sum('monto');

        $tarjeta = $cajaAbierta->movimientos
            ->where('tipo', 'ingreso')
            ->where('metodo_pago', 'tarjeta')
            ->sum('monto');

        // Calculamos totales para los cuadritos
        $ingresos = $cajaAbierta->movimientos->where('tipo', 'ingreso')->where('metodo_pago', 'efectivo')->sum('monto');
        $egresos = $cajaAbierta->movimientos->where('tipo', 'egreso')->sum('monto');
        $saldo_actual = $cajaAbierta->monto_apertura + $ingresos - $egresos;

        return view('admin.cajas.index', compact(
            'cajaAbierta',
            'ingresos',
            'egresos',
            'saldo_actual',
            'efectivo',
            'transferencia',
            'tarjeta'
        ));
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

        return redirect()->route('admin.cajas.index')
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

    public function cerrar(Request $request)
    {
        $request->validate([
            'monto_real_efectivo' => 'required|numeric|min:0',
            'monto_real_tarjeta' => 'required|numeric|min:0',
            'monto_real_transferencia' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string'
        ]);

        $caja = Caja::where('user_id', auth()->id())->where('estado', 'abierta')->firstOrFail();

        return DB::transaction(function () use ($request, $caja) {
            // 1. Obtener lo que el sistema dice que debería haber por cada método
            $totalesSistema = CajaMovimiento::where('caja_id', $caja->id)
                ->where('tipo', 'ingreso')
                ->selectRaw("
                    SUM(CASE WHEN metodo_pago = 'efectivo' THEN monto ELSE 0 END) as efectivo,
                    SUM(CASE WHEN metodo_pago = 'tarjeta' THEN monto ELSE 0 END) as tarjeta,
                    SUM(CASE WHEN metodo_pago = 'transferencia' THEN monto ELSE 0 END) as transferencia
                ")
                ->first();

            $egresos = CajaMovimiento::where('caja_id', $caja->id)->where('tipo', 'egreso')->sum('monto');

            // 2. Calcular saldos esperados
            $esperadoEfectivo = ($caja->monto_apertura + $totalesSistema->efectivo) - $egresos;
            $esperadoTarjeta = $totalesSistema->tarjeta ?? 0;
            $esperadoTransf = $totalesSistema->transferencia ?? 0;

            // 3. Calcular Diferencias Individuales
            $difEfectivo = $request->monto_real_efectivo - $esperadoEfectivo;
            $difTarjeta = $request->monto_real_tarjeta - $esperadoTarjeta;
            $difTransf = $request->monto_real_transferencia - $esperadoTransf;

            // 4. Actualizar Caja con el total consolidado
            $caja->update([
                'fecha_cierre' => now(),
                'monto_cierre' => $esperadoEfectivo, // Efectivo Sistema
                'monto_real_en_caja' => $request->monto_real_efectivo, // Efectivo Real
                'diferencia' => $difEfectivo,
                
                // Guardamos los otros métodos
                'total_tarjeta_sistema' => $esperadoTarjeta,
                'total_tarjeta_real' => $request->monto_real_tarjeta,
                'total_transferencia_sistema' => $esperadoTransf,
                'total_transferencia_real' => $request->monto_real_transferencia,
                
                'estado' => 'cerrada',
                'observaciones' => $request->observaciones
            ]);

            // 5. Redirigir a una vista de resumen (la crearemos ahora)
            return redirect()->route('admin.cajas.resumen', $caja->id);
        });
    }

    public function reporteHistorial()
    {
        // Traemos todas las cajas cerradas, la más reciente primero
        $cajas = Caja::with('usuario')
                    ->where('estado', 'cerrada')
                    ->orderBy('fecha_cierre', 'desc')
                    ->paginate(15);

        return view('admin.reportes.cajas', compact('cajas'));
    }

    public function verResumen($id)
    {
        $caja = Caja::with('usuario')->findOrFail($id);

        // Calculamos lo que el sistema dice que entró para esa caja específica
        $totalesSistema = CajaMovimiento::where('caja_id', $caja->id)
            ->where('tipo', 'ingreso')
            ->selectRaw("
                SUM(CASE WHEN metodo_pago = 'efectivo' THEN monto ELSE 0 END) as efectivo,
                SUM(CASE WHEN metodo_pago = 'tarjeta' THEN monto ELSE 0 END) as tarjeta,
                SUM(CASE WHEN metodo_pago = 'transferencia' THEN monto ELSE 0 END) as transferencia
            ")
            ->first();

        return view('admin.cajas.resumen', compact('caja', 'totalesSistema'));
    }
}