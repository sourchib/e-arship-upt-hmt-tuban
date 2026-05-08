<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Daftar Surat Keluar - E-Arsip</title>
    <style>
        @page {
            size: landscape;
            margin: 1cm;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1e293b;
            background: #fff;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 0.05em;
        }

        .header p {
            margin: 2px 0 0;
            font-size: 12px;
            color: #64748b;
        }

        .info-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 11px;
            font-weight: 600;
        }

        .info-item {
            color: #475569;
        }

        .info-item span {
            color: #0f172a;
            background: #e2e8f0;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            table-layout: fixed;
        }

        th {
            background: #1e293b;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            text-align: left;
            border: 1px solid #334155;
            padding: 10px 6px;
            letter-spacing: 0.02em;
        }

        td {
            border: 1px solid #e2e8f0;
            padding: 8px 6px;
            vertical-align: top;
            word-wrap: break-word;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: 700;
        }

        /* Column Widths */
        .col-no {
            width: 40px;
        }

        .col-nomor {
            width: 150px;
        }

        .col-perihal {
            width: 250px;
        }

        .col-tujuan {
            width: 150px;
        }

        .col-tgl {
            width: 100px;
        }

        .col-status {
            width: 100px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 9px;
            text-transform: uppercase;
            text-align: center;
            width: 100%;
        }

        .bg-draft {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .bg-terkirim {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .bg-selesai {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #94a3b8;
            font-style: italic;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }

            th {
                -webkit-print-color-adjust: exact;
                background-color: #1e293b !important;
                color: white !important;
            }

            .status-badge {
                -webkit-print-color-adjust: exact;
            }

            .bg-draft {
                background-color: #f1f5f9 !important;
            }

            .bg-terkirim {
                background-color: #dbeafe !important;
            }

            .bg-selesai {
                background-color: #dcfce7 !important;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <h1>LAPORAN DAFTAR SURAT KELUAR</h1>
        <p>Unit Pelaksana Teknis PT dan HMT Tuban</p>
        <p style="font-size: 10px; font-weight: 600; margin-top: 5px;">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="info-bar">
        <div class="info-item">Status: <span>{{ request('status', 'Semua') }}</span></div>
        <div class="info-item">Total: <span>{{ count($suratKeluar) }} surat</span></div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no text-center">NO</th>
                <th class="col-nomor">NOMOR SURAT</th>
                <th class="col-perihal">PERIHAL</th>
                <th class="col-tujuan">TUJUAN</th>
                <th class="col-tgl text-center">TANGGAL SURAT</th>
                <th class="col-tgl text-center">TANGGAL KIRIM</th>
                <th class="col-status text-center">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratKeluar as $index => $surat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-bold">{{ $surat->nomor_surat }}</td>
                    <td>{{ $surat->perihal }}</td>
                    <td>{{ $surat->tujuan }}</td>
                    <td class="text-center">{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $surat->tanggal_kirim ? $surat->tanggal_kirim->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">
                        @php
                            $sc = 'bg-draft';
                            if ($surat->status == 'Terkirim')
                                $sc = 'bg-terkirim';
                            if ($surat->status == 'Selesai')
                                $sc = 'bg-selesai';
                        @endphp
                        <span class="status-badge {{ $sc }}">{{ $surat->status }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak otomatis melalui Sistem E-Arsip UPT PT dan HMT Tuban pada {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>