<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .receipt {
            background-color: #fff;
            width: 300px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }
        .info {
            font-size: 12px;
            margin-bottom: 15px;
        }
        .info div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .items {
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 5px;
        }
        .item-details {
            font-size: 11px;
            color: #555;
            margin-bottom: 8px;
        }
        .totals {
            font-size: 14px;
            font-weight: bold;
        }
        .totals div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 11px;
            border-top: 1px dashed #000;
            padding-top: 15px;
        }
        .btn-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #ea580c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        @media print {
            .btn-print {
                display: none;
            }
            body {
                background-color: #fff;
                padding: 0;
            }
            .receipt {
                box-shadow: none;
                width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <button class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button>

    <div class="receipt">
        <div class="header">
            <h1>YoruCafe</h1>
            <p>Jl. Contoh No. 123, Kota Anda</p>
            <p>Telp: 0812-3456-7890</p>
        </div>

        <div class="info">
            <div><span>No. Pesanan:</span> <span>#{{ $order->order_number }}</span></div>
            <div><span>Tanggal:</span> <span>{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
            <div><span>Pelanggan:</span> <span>{{ $order->customer_name }}</span></div>
            <div><span>Kasir:</span> <span>{{ $order->user->name ?? 'Admin' }}</span></div>
            <div><span>Tipe:</span> <span>{{ ucfirst($order->notes) }}</span></div>
        </div>

        <div class="items">
            @foreach($order->items as $item)
            <div class="item">
                <span>{{ $item->menu->name }}</span>
                <span>{{ number_format($item->price * $item->quantity / 1000, 0) }}k</span>
            </div>
            <div class="item-details">
                {{ $item->quantity }} x {{ number_format($item->price / 1000, 0) }}k
            </div>
            @endforeach
        </div>

        <div class="totals">
            <div><span>TOTAL:</span> <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
            <div style="font-size: 11px; font-weight: normal; margin-top: 5px;">
                <span>Metode Pembayaran:</span> <span>{{ strtoupper($order->payment_method) }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
            <p>yorucafe.com</p>
        </div>
    </div>

    <script>
        // Auto print on load
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
