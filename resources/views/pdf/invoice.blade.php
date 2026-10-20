<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1a1a1a;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0;
        }
        .brand-sub {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .invoice-title {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .meta-table, .items-table, .calc-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            vertical-align: top;
            padding: 4px 0;
        }
        .box {
            background-color: #f9f9f9;
            border: 1px solid #e5e5e5;
            padding: 10px;
            margin-bottom: 20px;
        }
        .box-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #555;
            margin-bottom: 5px;
        }
        .items-table {
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #1a1a1a;
            color: #ffffff;
            text-align: left;
            padding: 8px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #e5e5e5;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .calc-table td {
            padding: 4px 8px;
        }
        .grand-total-row td {
            border-top: 2px solid #1a1a1a;
            font-size: 14px;
            font-weight: bold;
            padding-top: 8px;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #e5e5e5;
            padding-top: 15px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <h1 class="brand-title">BloomingKembang</h1>
                <div class="brand-sub">Koleksi Hijab Elegan & Berkarakter</div>
                <div style="margin-top: 8px; font-size: 11px; color: #555;">
                    Bandung, Jawa Barat &bull; cs@bloomingkembang.com<br>
                    WhatsApp: 0812-3456-7890
                </div>
            </td>
            <td style="width: 50%; vertical-align: top; text-align: right;">
                <div class="invoice-title">INVOICE</div>
                <div style="font-size: 12px; font-weight: bold; margin-top: 4px;">#{{ $order->number }}</div>
                <div style="font-size: 10px; color: #666; margin-top: 2px;">
                    Tanggal: {{ $order->created_at->format('d/m/Y H:i') }} WIB
                </div>
                <div style="margin-top: 6px;">
                    <span class="badge">{{ $order->payment_status->label() }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 48%; vertical-align: top;">
                <div class="box">
                    <div class="box-title">DITAGIHKAN KEPADA:</div>
                    <strong>{{ $order->customer_name }}</strong><br>
                    Email: {{ $order->customer_email }}<br>
                    Telepon: {{ $order->customer_phone }}
                </div>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%; vertical-align: top;">
                <div class="box">
                    <div class="box-title">TUJUAN PENGIRIMAN:</div>
                    <strong>{{ $order->shipping_address['recipient_name'] ?? $order->customer_name }}</strong> ({{ $order->shipping_address['phone'] ?? $order->customer_phone }})<br>
                    {{ $order->shipping_address['detail'] ?? '' }}<br>
                    {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['province'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}<br>
                    <span style="font-size: 10px; color: #666;">Kurir: {{ strtoupper($order->courier ?? '') }} ({{ $order->courier_service ?? '' }})</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 45%;">Deskripsi Produk</th>
                <th style="width: 15%;" class="text-right">Harga</th>
                <th style="width: 10%;" class="text-center">Qty</th>
                <th style="width: 25%;" class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        <div style="font-size: 10px; color: #666;">Varian: {{ $item->variant_label }} (SKU: {{ $item->sku }})</div>
                    </td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                @if($order->customer_note)
                    <div class="box">
                        <div class="box-title">Catatan Pesanan:</div>
                        <p style="margin: 0; font-size: 11px;">{{ $order->customer_note }}</p>
                    </div>
                @endif
                @if($order->tracking_number)
                    <div class="box">
                        <div class="box-title">Nomor Resi:</div>
                        <strong>{{ $order->tracking_number }}</strong> ({{ strtoupper($order->courier ?? '') }})
                    </div>
                @endif
            </td>
            <td style="width: 50%; vertical-align: top;">
                <table class="calc-table">
                    <tr>
                        <td style="color: #666;">Subtotal Produk:</td>
                        <td class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if($order->discount_total > 0)
                        <tr>
                            <td style="color: #15803d;">Diskon Voucher ({{ $order->voucher_code }}):</td>
                            <td class="text-right" style="color: #15803d;">- Rp {{ number_format($order->discount_total, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td style="color: #666;">Ongkos Kirim ({{ $order->courier }}):</td>
                        <td class="text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="grand-total-row">
                        <td>TOTAL AKHIR:</td>
                        <td class="text-right">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>Terima kasih telah mempercayakan pilihan hijab Anda kepada <strong>BloomingKembang</strong>.</p>
        <p>Dokumen ini diterbitkan secara otomatis oleh sistem dan sah tanpa tanda tangan basah.</p>
    </div>
</body>
</html>
