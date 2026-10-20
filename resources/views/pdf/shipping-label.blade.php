<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Label Pengiriman #{{ $order->number }}</title>
    <style>
        @page {
            size: 100mm 150mm portrait;
            margin: 5mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.4;
        }
        .label-container {
            border: 2px solid #1a1a1a;
            padding: 8px;
            box-sizing: border-box;
            height: 100%;
        }
        .header {
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .brand {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .courier-badge {
            background-color: #1a1a1a;
            color: #ffffff;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
        }
        .resi-box {
            border: 1px dashed #1a1a1a;
            background-color: #f7f7f7;
            text-align: center;
            padding: 8px 4px;
            margin: 6px 0;
        }
        .resi-number {
            font-family: 'Courier New', Courier, monospace;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .section-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #555;
            border-bottom: 1px solid #ddd;
            margin-bottom: 4px;
            padding-bottom: 2px;
        }
        .recipient-box {
            border: 1px solid #1a1a1a;
            padding: 6px;
            margin-bottom: 8px;
            background-color: #fafafa;
        }
        .recipient-name {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .sender-box {
            font-size: 9px;
            color: #444;
            margin-bottom: 8px;
            border-bottom: 1px solid #eee;
            padding-bottom: 4px;
        }
        .items-summary {
            font-size: 9px;
            color: #333;
            max-height: 80px;
            overflow: hidden;
            margin-bottom: 6px;
        }
        .footer-table {
            width: 100%;
            border-top: 1px solid #1a1a1a;
            padding-top: 4px;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <div class="label-container">
        <!-- Header -->
        <table style="width: 100%; margin-bottom: 6px;">
            <tr>
                <td style="vertical-align: middle;">
                    <div class="brand">BloomingKembang</div>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <div class="courier-badge">
                        {{ strtoupper($order->courier ?? 'KURIR') }} {{ strtoupper($order->courier_service ?? '') }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Resi & Order Number -->
        <div class="resi-box">
            <div style="font-size: 9px; color: #666; text-transform: uppercase; letter-spacing: 1px;">No. Resi Pengiriman:</div>
            <div class="resi-number">{{ $order->tracking_number ?? 'NON-RESI / MENUNGGU' }}</div>
            <div style="font-size: 9px; color: #777; margin-top: 2px;">Order ID: #{{ $order->number }}</div>
        </div>

        <!-- Penerima -->
        <div class="recipient-box">
            <div class="section-title">PENERIMA:</div>
            <div class="recipient-name">{{ $order->shipping_address['recipient_name'] ?? $order->customer_name }}</div>
            <div style="font-weight: bold; font-size: 11px; margin-bottom: 4px;">{{ $order->shipping_address['phone'] ?? $order->customer_phone }}</div>
            <div style="font-size: 10px; line-height: 1.3;">
                {{ $order->shipping_address['detail'] ?? '' }}<br>
                {{ $order->shipping_address['district'] ?? '' }}{{ !empty($order->shipping_address['district']) ? ', ' : '' }}{{ $order->shipping_address['city'] ?? '' }}<br>
                {{ $order->shipping_address['province'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}
            </div>
        </div>

        <!-- Pengirim -->
        <div class="sender-box">
            <strong>PENGIRIM:</strong> BloomingKembang Official (0812-3456-7890)<br>
            Bandung, Jawa Barat &bull; cs@bloomingkembang.com
        </div>

        <!-- Isi Paket -->
        <div class="section-title">ISI PAKET ({{ count($order->items) }} ITEM):</div>
        <div class="items-summary">
            @foreach($order->items as $item)
                <div>&bull; {{ $item->product_name }} ({{ $item->variant_label }}) &times; {{ $item->quantity }}</div>
            @endforeach
        </div>

        @if($order->customer_note)
            <div style="font-size: 9px; background: #fffde7; padding: 3px; border: 1px solid #fff59d; margin-bottom: 6px;">
                <strong>Instruksi Pengiriman:</strong> {{ $order->customer_note }}
            </div>
        @endif

        <!-- Footer -->
        <table class="footer-table">
            <tr>
                <td>Berat: <strong>{{ number_format($order->total_weight_gram, 0) }} gr</strong></td>
                <td style="text-align: right;">Ongkir: <strong>NON-COD</strong></td>
            </tr>
        </table>
    </div>
</body>
</html>
