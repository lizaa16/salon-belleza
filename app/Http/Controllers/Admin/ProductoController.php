<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index() 
    {
        $productos = Producto::orderBy('id', 'desc')->get();
        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        return view('admin.productos.create');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'nombre'       => 'required|max:255',
            'codigo_barra' => 'nullable|unique:productos,codigo_barra',
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'iva'          => 'required|in:exenta,5,10',
            'estado'       => 'required|boolean',
        ]);

        Producto::create($request->all());

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto creado con éxito.');
    }

    public function edit(Producto $producto)
    {
        return view('admin.productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre'       => 'required|max:255',
            // Validamos que el código sea único, exceptuando el ID de este producto
            'codigo_barra' => 'nullable|unique:productos,codigo_barra,' . $producto->id,
            'precio_venta' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'iva'          => 'required|in:exenta,5,10',
            'estado'       => 'required|boolean',
        ]);

        $producto->update($request->all());

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        // Podrías verificar si el producto tiene ventas antes de eliminarlo
        $producto->delete();

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto eliminado.');
    }

    // Opcional: Para ver detalles (si lo necesitas más adelante)
    public function show(Producto $producto)
    {
        return view('admin.productos.show', compact('producto'));
    }
}