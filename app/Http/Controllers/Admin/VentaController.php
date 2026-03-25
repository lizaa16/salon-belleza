<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Venta;
use App\Models\Cita;
use App\Models\VentaDetalle;
use App\Models\VentaCobro;
use App\Models\CajaMovimiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('cliente')->latest()->get();
        return view('admin.ventas.index', compact('ventas'));
    }

    public function create()
    {
        // Verificamos caja abierta (Regla de negocio)
        $caja = Caja::where('user_id', auth()->id())->where('estado', 'abierta')->first();

        if (!$caja) {
            return redirect()->route('admin.cajas.index')
                            ->with('error', 'Debes abrir la caja antes de vender.');
        }

        $clientes = Cliente::with('persona')->get(); 
        $productos = Producto::where('stock_actual', '>', 0)->get();
        $servicios = Servicio::all();

        return view('admin.ventas.create', compact('clientes', 'productos', 'servicios'));    }

    public function getCitasPendientes($cliente_id)
    {
        $citas = Cita::where('cliente_id', $cliente_id)
                                ->where('estado', 'confirmada')
                                ->get();
        return response()->json($citas);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $items = json_decode($request->items_json, true);
            $pagos = json_decode($request->pagos_json, true);

            if (!$items || count($items) === 0) {
                return back()->with('error', 'Debe agregar al menos un item.');
            }

            // 🟢 1. Crear venta (Sin el campo fecha, usa created_at por defecto)
            $venta = Venta::create([
                'cliente_id'  => $request->cliente_id,
                'empleado_id' => auth()->id(),
                'estado'      => 'PENDIENTE_PAGO',
                'total'       => 0, // Se actualiza al final
                'total_pagar' => 0, // Se actualiza al final
                'monto_final_cobrado' => 0, // Se actualiza al final
                'total_bruto'       => 0, // Se actualizará al final
            ]);

            $totalVenta = 0;

            // 🟡 2. Guardar detalle (Usando venta_detalles)
            foreach ($items as $item) {
                $subtotal = $item['precio'] * $item['cantidad'];

                VentaDetalle::create([
                    'venta_id'        => $venta->id,
                    'item_id'         => $item['id'],
                    'item_type'       => $item['tipo'], // 'serv' o 'prod'
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal'        => $subtotal,
                    'tasa_iva'        => 0, // O el valor que manejes
                    'monto_iva'       => 0
                ]);

                $totalVenta += $subtotal;
            }

            // 🔵 3. Obtener caja abierta
            $caja = Caja::where('user_id', auth()->id())
                        ->where('estado', 'abierta')
                        ->first();

            // 🟣 4. Guardar pagos y movimientos
            $totalPagado = 0;
            if ($pagos) {
                foreach ($pagos as $pago) {
                    $cobro = VentaCobro::create([
                        'venta_id'    => $venta->id,
                        'metodo_pago' => $pago['metodo'],
                        'monto'       => $pago['monto'],
                        'referencia'  => 'Cobro Venta #' . $venta->id
                    ]);

                    $totalPagado += $pago['monto'];

                    if ($caja) {
                        CajaMovimiento::create([
                            'caja_id'         => $caja->id,
                            'tipo'            => 'ingreso',
                            'monto'           => $pago['monto'],
                            'metodo_pago'     => $pago['metodo'],
                            'concepto'        => 'Venta #' . $venta->id,
                            'referencia_id'   => $cobro->id,
                            'referencia_type' => 'App\Models\VentaCobro'
                        ]);
                    }
                }
            }

            // 🔴 5. Actualizar totales y estado en la venta
            $estado = ($totalPagado >= $totalVenta) ? 'PAGADO' : ($totalPagado > 0 ? 'PAGO_PARCIAL' : 'PENDIENTE_PAGO');

            $venta->update([
                'total'               => $totalVenta,
                'total_pagar'         => $totalVenta,
                'monto_final_cobrado' => $totalPagado,
                'estado'              => $estado
            ]);

            DB::commit();
            return redirect()->route('admin.ventas.index')->with('success', 'Venta registrada con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Esto matará el proceso y te mostrará una pantalla naranja/negra con el error exacto
            dd([
                'Error_Mensaje' => $e->getMessage(),
                'Archivo' => $e->getFile(),
                'Linea' => $e->getLine(),
                'Datos_Recibidos' => $request->all()
            ]);
        }
    }
}
