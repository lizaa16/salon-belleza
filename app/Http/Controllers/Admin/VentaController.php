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

    public function create(Request $request)
    {
        $caja = Caja::where('user_id', auth()->id())
            ->where('estado', 'abierta')
            ->first();

        if (!$caja) {
            return redirect()->route('admin.cajas.index')
                ->with('error', 'Debes abrir la caja antes de vender.');
        }

        $clientes  = Cliente::with('persona')->get(); 
        $productos = Producto::where('stock_actual', '>', 0)->get();
        $servicios = Servicio::all();

        $cita = null;

        if ($request->cita_id) {
            $cita = Cita::with('detalles.servicio')->find($request->cita_id);
        }

        return view('admin.ventas.create', compact(
            'clientes', 'productos', 'servicios', 'cita'
        ));
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

            // 🔵 CAJA
            $caja = Caja::where('user_id', auth()->id())
                        ->where('estado', 'abierta')
                        ->first();

            if (!$caja) {
                throw new \Exception("No hay caja abierta.");
            }

            // 🔴 VALIDAR CITA NO COBRADA
            if ($request->cita_id) {
                $existe = Venta::where('cita_id', $request->cita_id)->exists();

                if ($existe) {
                    throw new \Exception('Esta cita ya fue cobrada.');
                }
            }

            // 🟢 CREAR VENTA
            $venta = Venta::create([
                'cliente_id'  => $request->cliente_id,
                'empleado_id' => auth()->id(),
                'estado'      => 'PENDIENTE_PAGO',
                'total'       => 0,
                'total_pagar' => 0,
                'monto_final_cobrado' => 0,
                'total_bruto' => 0,
                'cita_id'     => $request->cita_id 
            ]);

            $totalVenta = 0;

            // 🟡 DETALLES
            foreach ($items as $item) {
                $subtotal = $item['precio'] * $item['cantidad'];

                VentaDetalle::create([
                    'venta_id'        => $venta->id,
                    'item_id'         => $item['id'],
                    'item_type'       => $item['tipo'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal'        => $subtotal,
                    'tasa_iva'        => 0,
                    'monto_iva'       => 0
                ]);

                if ($item['tipo'] === 'prod') {
                    $producto = Producto::find($item['id']);

                    if (!$producto) {
                        throw new \Exception("Producto no encontrado.");
                    }

                    if ($producto->stock_actual < $item['cantidad']) {
                        throw new \Exception("Stock insuficiente para {$producto->nombre}");
                    }

                    $producto->stock_actual -= $item['cantidad'];
                    $producto->save();
                }

                $totalVenta += $subtotal;
            }

            // 🟣 PAGOS
            $totalPagado = 0;
            $montoRestante = $totalVenta;

            foreach ($pagos as $pago) {

                $montoReal = min($pago['monto'], $montoRestante);

                $esSena = isset($pago['es_sena']) && $pago['es_sena'];

                $cobro = VentaCobro::create([
                    'venta_id'    => $venta->id,
                    'metodo_pago' => $pago['metodo'],
                    'monto'       => $montoReal,
                    'referencia'  => 'Venta #' . $venta->id
                ]);

                // 🚨 SOLO registrar en caja si:
                // - NO es seña
                // - Y es efectivo
                if (!$esSena && $pago['metodo'] === 'efectivo') {
                    CajaMovimiento::create([
                        'caja_id'         => $caja->id,
                        'tipo'            => 'ingreso',
                        'monto'           => $montoReal,
                        'metodo_pago'     => 'efectivo',
                        'concepto'        => 'Venta #' . $venta->id,
                        'referencia_id'   => $cobro->id,
                        'referencia_type' => 'App\Models\VentaCobro'
                    ]);
                }

                $totalPagado += $montoReal;
                $montoRestante -= $montoReal;

                if ($montoRestante <= 0) break;
            }

            // 🔴 ESTADO
            if ($totalPagado == 0) {
                $estado = 'PENDIENTE_PAGO';
            } elseif ($totalPagado < $totalVenta) {
                $estado = 'PAGO_PARCIAL';
            } else {
                $estado = 'PAGADO';
            }

            // 🟠 UPDATE FINAL
            $venta->update([
                'total'               => $totalVenta,
                'total_pagar'         => $totalVenta,
                'monto_final_cobrado' => $totalPagado,
                'estado'              => $estado
            ]);

            // 🟢 FINALIZAR CITA SOLO SI PAGÓ TODO
            if ($venta->cita_id && $estado === 'PAGADO') {
                Cita::where('id', $venta->cita_id)
                    ->update(['estado' => 'finalizada']);
            }

            DB::commit();

            return redirect()
                ->route('admin.ventas.index')
                ->with('success', 'Venta registrada con éxito.');

        } catch (\Exception $e) {
            DB::rollBack();

            dd([
                'Error' => $e->getMessage(),
                'Linea' => $e->getLine()
            ]);
        }
    }

    public function show($id)
    {
        // Cargamos 'detalles.item' para que traiga el Producto o Servicio automáticamente
        $venta = Venta::with(['cliente.persona', 'detalles.item', 'cobros'])->findOrFail($id);

        return view('admin.ventas.show', compact('venta'));
    }
}
