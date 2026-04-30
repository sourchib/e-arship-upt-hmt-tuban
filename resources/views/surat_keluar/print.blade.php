<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Daftar Surat Keluar - E-Arsip</title>
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
        <h1>LAPORAN DAFTAR SURAT KELUAR</h1>
        <p>Unit Pelaksana Teknis PT dan HMT Tuban</p>
        <p style="font-size: 11px; font-weight: 600;">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 120px;">No. Surat</th>
                <th>Tujuan</th>
                <th>Perihal</th>
                <th style="width: 80px;">Tgl Surat</th>
                <th style="width: 80px;">Tgl Kirim</th>
                <th>Prioritas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratKeluar as $index => $surat)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td class="text-bold">{{ $surat->nomor_surat }}</td>
                <td>{{ $surat->tujuan }}</td>
                <td class="text-bold">{{ $surat->perihal }}</td>
                <td>{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-' }}</td>
                <td>{{ $surat->tanggal_kirim ? $surat->tanggal_kirim->format('d/m/Y') : '-' }}</td>
                <td>{{ $surat->prioritas }}</td>
                <td>{{ $surat->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak otomatis melalui Sistem E-Arsip UPT PT dan HMT Tuban</p>
    </div>
</body>
</html>
