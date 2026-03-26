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

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $items = json_decode($request->items_json, true);
            $pagos = json_decode($request->pagos_json, true);

            if (!$items || count($items) === 0) {
                return back()->with('error', 'Debe agregar al menos un item.');
            }

            // 🔵 CAJA OBLIGATORIA
            $caja = Caja::where('user_id', auth()->id())
                        ->where('estado', 'abierta')
                        ->first();

            if (!$caja) {
                throw new \Exception("No hay caja abierta.");
            }

            // 🟢 1. CREAR VENTA
            $venta = Venta::create([
                'cliente_id'  => $request->cliente_id,
                'empleado_id' => auth()->id(),
                'estado'      => 'PENDIENTE_PAGO',
                'total'       => 0,
                'total_pagar' => 0,
                'monto_final_cobrado' => 0,
                'total_bruto' => 0,
            ]);

            $totalVenta = 0;

            // 🟡 2. DETALLE + STOCK
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

                // 🔥 DESCONTAR STOCK SOLO SI ES PRODUCTO
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

            // 🟣 3. PAGOS + CAJA
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

                    // 🚨 NO REGISTRAR SEÑA EN CAJA
                    if ($pago['metodo'] !== 'seña') {
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

            // 🔴 4. ESTADO
            if ($totalPagado == 0) {
                $estado = 'PENDIENTE_PAGO';
            } elseif ($totalPagado < $totalVenta) {
                $estado = 'PAGO_PARCIAL';
            } else {
                $estado = 'PAGADO';
            }

            // 🟠 5. UPDATE FINAL
            $venta->update([
                'total'               => $totalVenta,
                'total_pagar'         => $totalVenta,
                'monto_final_cobrado' => $totalPagado,
                'estado'              => $estado
            ]);

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
