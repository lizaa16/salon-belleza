<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Servicio;

class VentaController extends Controller
{
    public function create()
    {
        // Verificamos que la caja esté abierta antes de dejar entrar al POS
        $caja = Caja::where('user_id', auth()->id())
                            ->where('estado', 'abierta')
                            ->first();

        if (!$caja) {
            return redirect()->route('admin.cajas.index')
                            ->with('error', 'Debes abrir la caja antes de realizar una venta.');
        }

        $clientes = Cliente::all();
        $productos = Producto::where('stock_actual', '>', 0)->get();
        $servicios = Servicio::all(); // Tus servicios del M3

        return view('admin.ventas.create', compact('clientes', 'productos', 'servicios'));
    }
}
