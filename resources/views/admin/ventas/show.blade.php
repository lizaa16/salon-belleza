@extends('admin.layout')

@section('title', 'Detalle Venta')

@section('content_header')
<h1>Detalle de Venta #{{ $venta->id }}</h1>
@stop

@section('admin_content')

<div class="card">
    <div class="card-body">

        <p><b>Cliente:</b> {{ $venta->cliente->persona->nombre ?? 'Cliente' }} {{ $venta->cliente->persona->apellido ?? 'Ocasional' }}</p>
        <p><b>Estado:</b> {{ $venta->estado }}</p>
        <p><b>Total:</b> {{ number_format($venta->total,0,',','.') }} Gs.</p>

        <hr>

        <h5>Items</h5>
        
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venta->detalles as $d)
                    <tr>
                        <td>{{ $d->item->nombre ?? 'No identificado' }}</td>
                        <td>
                            @if($d->item_type == 'serv')
                                <span>Servicio</span>
                            @else
                                <span>Producto</span>
                            @endif
                        </td>
                        <td>{{ $d->cantidad }}</td>
                        <td>{{ number_format($d->precio_unitario, 0, ',', '.') }}</td>
                        <td>{{ number_format($d->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr>

        <h5>Pagos</h5>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Método de pago</th>
                        <th>Monto Pagado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venta->cobros as $c)
                    <tr>
                        <td>{{ $c->metodo_pago }}</td>
                        <td>{{ number_format($c->monto,0,',','.') }} Gs.</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        
            <div class="mt-4">
                <a href="{{ route('admin.ventas.index') }}" class="btn btn-secondary">Volver</a>
            </div>
    </div>
</div>

@stop