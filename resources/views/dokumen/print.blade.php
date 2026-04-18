<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Daftar Dokumen - E-Arsip</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #334155;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            color: #0f172a;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 13px;
            color: #64748b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        th {
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            text-align: left;
            border: 1px solid #cbd5e1;
            padding: 10px 8px;
        }
        td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background: #f8fafc;
        }
        .text-bold {
            font-weight: 700;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #64748b;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>LAPORAN DAFTAR DOKUMEN ARSIP DIGITAL</h1>
        <p>Unit Pelaksana Teknis PT dan HMT Tuban</p>
        <p style="font-size: 11px; font-weight: 600;">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 120px;">Kode</th>
                <th>Nama Dokumen</th>
                <th>Kategori</th>
                <th>Folder</th>
                <th>Status</th>
                <th>Lokasi</th>
                <th>Retensi</th>
                <th style="width: 80px;">Tgl Dokumen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $index => $doc)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td class="text-bold">{{ $doc->kode ?? '-' }}</td>
                <td class="text-bold">{{ $doc->nama }}</td>
                <td>{{ $doc->kategori }}</td>
                <td>{{ $doc->folder ?? '-' }}</td>
                <td style="font-weight: 600; color: {{ $doc->status == 'Aktif' ? '#16a34a' : '#ef4444' }}">{{ $doc->status }}</td>
                <td>{{ $doc->lokasi ?? '-' }}</td>
                <td>{{ $doc->masa_retensi ?? '-' }}</td>
                <td>{{ $doc->tanggal ? $doc->tanggal->format('d/m/Y') : $doc->tanggal_upload->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak otomatis melalui Sistem E-Arsip UPT PT dan HMT Tuban</p>
    </div>
</body>
</html>
