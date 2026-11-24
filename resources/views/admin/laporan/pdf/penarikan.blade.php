<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 18px; margin-bottom: 2px; }
        .subtitle { color: #555; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f0f4f2; }
        .text-right { text-align: right; }
        tfoot th { background: #e5efe9; }
    </style>
</head>
<body>
    <h1>Laporan Penarikan Saldo (Disetujui)</h1>
    <div class="subtitle">
        Periode: {{ $from ?? 'Semua' }} s/d {{ $to ?? 'Semua' }} · Dicetak {{ now()->format('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nasabah</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row->tanggal->format('d-m-Y') }}</td>
                    <td>{{ $row->nasabah->user->name }}</td>
                    <td class="text-right">Rp{{ number_format($row->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
        @if ($rows->isNotEmpty())
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th class="text-right">Rp{{ number_format($rows->sum('jumlah'), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>
