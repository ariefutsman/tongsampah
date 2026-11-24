<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #222; margin: 0; padding: 24px; }
        .receipt { max-width: 380px; margin: 0 auto; border: 1px solid #ccc; padding: 20px; }
        .header { text-align: center; margin-bottom: 12px; }
        .header h1 { font-size: 16px; margin: 0 0 2px 0; color: #072F1F; }
        .header p { margin: 0; color: #666; font-size: 10px; }
        .divider { border-top: 1px dashed #999; margin: 12px 0; }
        table.info { width: 100%; border-collapse: collapse; }
        table.info td { padding: 3px 0; font-size: 11px; vertical-align: top; }
        table.info td.label { color: #666; width: 40%; }
        table.info td.value { text-align: right; font-weight: bold; }
        .total-row td { font-size: 14px; padding-top: 8px; }
        .footer { text-align: center; margin-top: 16px; font-size: 10px; color: #888; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>BUKTI SETORAN SAMPAH</h1>
            <p>Bank Sampah Digital</p>
        </div>

        <div class="divider"></div>

        <table class="info">
            <tr>
                <td class="label">No. Transaksi</td>
                <td class="value">#{{ str_pad($setoran->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td class="value">{{ $setoran->tanggal->translatedFormat('d M Y') }}</td>
            </tr>
            <tr>
                <td class="label">Nasabah</td>
                <td class="value">{{ $setoran->nasabah->user->name }}</td>
            </tr>
            <tr>
                <td class="label">No. HP</td>
                <td class="value">{{ $setoran->nasabah->user->no_hp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Petugas</td>
                <td class="value">{{ $setoran->petugas->name }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <table class="info">
            <tr>
                <td class="label">Kategori</td>
                <td class="value">{{ $setoran->item->kategori->nama_kategori }}</td>
            </tr>
            <tr>
                <td class="label">Item</td>
                <td class="value">{{ $setoran->item->nama_item }}</td>
            </tr>
            <tr>
                <td class="label">Berat</td>
                <td class="value">{{ number_format($setoran->berat_kg, 2) }} {{ $setoran->item->satuan }}</td>
            </tr>
            <tr>
                <td class="label">Harga per {{ $setoran->item->satuan }}</td>
                <td class="value">Rp{{ number_format($setoran->item->harga_per_kg, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <table class="info">
            <tr class="total-row">
                <td class="label">Total Nilai</td>
                <td class="value">Rp{{ number_format($setoran->total_nilai, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="footer">
            Dicetak pada {{ now()->translatedFormat('d M Y H:i') }}<br>
            Simpan bukti ini sebagai referensi setoran Anda.
        </div>
    </div>
</body>
</html>
