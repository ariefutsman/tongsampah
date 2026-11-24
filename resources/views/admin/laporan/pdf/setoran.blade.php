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
    <h1>Laporan Setoran Sampah</h1>
    <div class="subtitle">
        Periode: {{ $from ?? 'Semua' }} s/d {{ $to ?? 'Semua' }} · Dicetak {{ now()->format('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nasabah</th>
                <th>Item Sampah</th>
                <th class="text-right">Berat (kg)</th>
                <th class="text-right">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row->tanggal->format('d-m-Y') }}</td>
                    <td>{{ $row->nasabah->user->name }}</td>
                    <td>{{ $row->item->nama_item }}</td>
                    <td class="text-right">{{ number_format($row->berat_kg, 2) }}</td>
                    <td class="text-right">Rp{{ number_format($row->total_nilai, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
        @if ($rows->isNotEmpty())
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th class="text-right">{{ number_format($rows->sum('berat_kg'), 2) }}</th>
                    <th class="text-right">Rp{{ number_format($rows->sum('total_nilai'), 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>
