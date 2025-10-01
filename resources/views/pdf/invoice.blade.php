<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factura #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #d97706;
            margin-bottom: 10px;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .company-info, .client-info {
            margin-bottom: 20px;
        }
        .company-info h3, .client-info h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #111;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            width: 100%;
            max-width: 300px;
            margin-left: auto;
            margin-top: 20px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            padding: 5px 0;
        }
        .summary-total {
            font-weight: bold;
            font-size: 1.1em;
            border-top: 1px solid #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Cafetería Daylen</div>
        <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">FACTURA #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
        <div>Fecha: {{ $formatDate($order->created_at) }}</div>
    </div>

    <div class="invoice-info">
        <div style="display: flex; justify-content: space-between;">
            <div class="company-info" style="width: 48%;">
                <h3>Datos de la Empresa</h3>
                <div><strong>Cafetería Daylen</strong></div>
                <div>Calle Carlos Gómez, Barrio Remansito</div>
                <div>Teléfono: +595 986 195914</div>
                <div>RUC: 12345678-9</div>
            </div>
            
            <div class="client-info" style="width: 48%;">
                <h3>Datos del Cliente</h3>
                <div><strong>{{ $order->customer_name }}</strong></div>
                <div>Email: {{ $order->user->email ?? 'No especificado' }}</div>
                <div>Teléfono: {{ $order->phone ?? 'No especificado' }}</div>
                <div>Dirección: {{ $order->address ?? 'No especificada' }}</div>
            </div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th style="width: 60px; text-align: center;">Cant.</th>
                <th style="width: 100px; text-align: right;">Precio Unit.</th>
                <th style="width: 100px; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>
                    {{ $item->product->name }}
                    @if($item->options && count(json_decode($item->options, true)) > 0)
                        <div style="font-size: 10px; color: #666;">
                            @foreach(json_decode($item->options, true) as $option)
                                {{ $option['name'] }}
                                @if(!$loop->last), @endif
                            @endforeach
                        </div>
                    @endif
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">₲ {{ $formatNumber($item->price) }}</td>
                <td class="text-right">₲ {{ $formatNumber($item->price * $item->quantity) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span>Subtotal (sin IVA):</span>
            <span>₲ {{ $formatNumber($order->total / 1.1) }}</span>
        </div>
        <div class="summary-row">
            <span>IVA (10%):</span>
            <span>₲ {{ $formatNumber(($order->total / 1.1) * 0.1) }}</span>
        </div>
        <div class="summary-row">
            <span>Total (IVA incluido):</span>
            <span>₲ {{ $formatNumber($order->total) }}</span>
        </div>
        <div class="summary-row summary-total">
            <span>TOTAL A PAGAR:</span>
            <span>₲ {{ $formatNumber($order->total) }}</span>
        </div>
    </div>

    <div class="footer">
        <div>Gracias por su compra en Cafetería Daylen</div>
        <div>Este documento es una factura electrónica generada automáticamente</div>
        <div>Los precios incluyen IVA (10%)</div>
    </div>
</body>
</html>
