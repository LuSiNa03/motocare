<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.5;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            background: #fff;
        }
        .header {
            border-bottom: 2px solid #810B38;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #810B38;
            text-transform: uppercase;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #541A1A;
            text-align: right;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table td {
            vertical-align: top;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table th {
            background-color: #810B38;
            color: #fff;
            padding: 10px;
            font-weight: bold;
            text-align: left;
        }
        .details-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right !important;
        }
        .total-row td {
            font-weight: bold;
            font-size: 16px;
            color: #810B38;
            border-top: 2px solid #810B38;
            padding-top: 15px;
        }
        .status-paid {
            display: inline-block;
            padding: 5px 12px;
            background-color: #d1fae5;
            color: #065f46;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status-unpaid {
            display: inline-block;
            padding: 5px 12px;
            background-color: #fef3c7;
            color: #92400e;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
            font-size: 12px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 50px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="info-table">
            <tr>
                <td>
                    <div class="company-name">MotoCare</div>
                    <p style="margin: 5px 0 0 0; color: #555;">
                        <strong>MotoCare Pusat Bandung</strong><br>
                        Jl. Raya Bengkel No.1, Bandung<br>
                        Telp: 08111222333
                    </p>
                </td>
                <td class="text-right">
                    <div class="invoice-title">INVOICE</div>
                    <p style="margin: 5px 0 0 0; font-family: monospace; font-size: 15px;">
                        #{{ $invoice->invoice_number }}
                    </p>
                    <p style="margin: 10px 0 0 0;">
                        Tanggal: {{ $invoice->created_at->format('d/m/Y') }}<br>
                        Status: 
                        @if($invoice->status === 'lunas')
                            <span class="status-paid">LUNAS</span>
                        @else
                            <span class="status-unpaid">BELUM BAYAR</span>
                        @endif
                    </p>
                </td>
            </tr>
        </table>

        <div style="margin-bottom: 25px; border-top: 1px solid #eee; padding-top: 15px;">
            <strong>Ditagihkan Kepada:</strong><br>
            {{ $invoice->service->booking->user->name }} ({{ $invoice->service->booking->user->email }})<br>
            <strong>Kendaraan:</strong> {{ $invoice->service->vehicle->brand }} {{ $invoice->service->vehicle->model }} ({{ $invoice->service->vehicle->plate_number }})
        </div>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Deskripsi Item</th>
                    <th class="text-right" style="width: 150px;">Biaya</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Jasa Servis & Suku Cadang ({{ $invoice->service->booking->servicePackage?->name ?? 'Servis Reguler' }})
                    </td>
                    <td class="text-right" style="font-family: monospace;">
                        Rp {{ number_format($invoice->total_amount - $invoice->tax, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right" style="font-weight: bold; color: #555;">Pajak (11%)</td>
                    <td class="text-right" style="font-family: monospace; color: #555;">
                        Rp {{ number_format($invoice->tax, 0, ',', '.') }}
                    </td>
                </tr>
                <tr class="total-row">
                    <td class="text-right">TOTAL TAGIHAN</td>
                    <td class="text-right" style="font-family: monospace;">
                        Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        @if($invoice->status === 'lunas')
            <div style="margin-top: 20px; border: 1px solid #a7f3d0; background-color: #ecfdf5; padding: 15px; border-radius: 6px; color: #065f46;">
                <strong>Metode Pembayaran:</strong> {{ $invoice->payment_method ?? 'Cash/Transfer' }}<br>
                Terima kasih telah mempercayakan perawatan motor Anda kepada MotoCare!
            </div>
        @endif

        <div class="footer">
            <p><strong>MotoCare - Motor Terawat, Perjalanan Lebih Aman</strong></p>
            <p>Invoice ini sah dan diproduksi secara komputerisasi oleh sistem MotoCare.</p>
        </div>
    </div>
</body>
</html>
