<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #{{ $transaksi->id }}</title>
    <style>
        @page {
            margin: 0; /* Menghilangkan header/footer browser bawaan */
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            margin: 0 auto;
            padding: 10px;
            width: 58mm; /* Standar ukuran printer struk thermal kecil (58mm) */
            max-width: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 14px; /* Dikecilkan sedikit agar muat di 58mm */
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .info {
            margin-bottom: 10px;
            font-size: 11px;
        }
        .info table {
            width: 100%;
        }
        .info table td {
            vertical-align: top;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .items td, .items th {
            text-align: left;
            vertical-align: top;
            padding: 2px 0;
        }
        .items .right {
            text-align: right;
        }
        .totals {
            margin-top: 10px;
            font-size: 11px;
        }
        .totals table {
            width: 100%;
        }
        .totals .right {
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }
        .footer p {
            margin: 2px 0;
        }
        @media print {
            body {
                width: 100%; /* Lebar menyesuaikan ukuran kertas printer */
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>{{ $globalSettings['store_name'] ?? 'Toko POS Laravel' }}</h2>
        @if(!empty($globalSettings['store_alamat']))
            <p>{{ $globalSettings['store_alamat'] }}</p>
        @endif
        @if(!empty($globalSettings['store_telepon']))
            <p>Telp: {{ $globalSettings['store_telepon'] }}</p>
        @endif
    </div>

    <div class="divider"></div>

    <div class="info">
        <table>
            <tr>
                <td>No</td>
                <td>: TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td>: {{ $transaksi->kasir ?? 'Kasir' }}</td>
            </tr>
            <tr>
                <td>Bayar via</td>
                <td>: {{ ucfirst($transaksi->metode_bayar ?? 'tunai') }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <div class="items">
        <table>
            @foreach($detail as $item)
            <tr>
                <td colspan="3">{{ $item->nama_barang }}</td>
            </tr>
            <tr>
                <td>{{ $item->qty }} x</td>
                <td>{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                <td class="right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="divider"></div>

    <div class="totals">
        <table>
            <tr>
                <td>Total</td>
                <td class="right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bayar</td>
                <td class="right">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="right">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <div class="footer">
        <p>{{ $globalSettings['store_thank_you'] ?? 'Terima Kasih' }}</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
    </div>
    
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 5px 10px; cursor: pointer;">Cetak Ulang</button>
        <button onclick="window.close()" style="padding: 5px 10px; cursor: pointer;">Tutup</button>
    </div>

</body>
</html>
