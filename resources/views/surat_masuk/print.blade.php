<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Daftar Surat Masuk - E-Arsip</title>
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
            width: 30px;
        }

        .col-nomor {
            width: 100px;
        }

        .col-perihal {
            width: 150px;
        }

        .col-pengirim {
            width: 100px;
        }

        .col-penerima {
            width: 80px;
        }

        .col-tgl {
            width: 65px;
        }

        .col-disposisi-user {
            width: 90px;
        }

        .col-disposisi-isi {
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

        .bg-pending {
            background: #fef9c3;
            color: #713f12;
            border: 1px solid #fde047;
        }

        .bg-diproses {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .bg-terarsip {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
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

            .bg-pending {
                background-color: #fef9c3 !important;
            }

            .bg-diproses {
                background-color: #dbeafe !important;
            }

            .bg-terarsip {
                background-color: #dcfce7 !important;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <h1>LAPORAN DAFTAR SURAT MASUK</h1>
        <p>Unit Pelaksana Teknis PT dan HMT Tuban</p>
        <p style="font-size: 10px; font-weight: 600; margin-top: 5px;">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="info-bar">
        <div class="info-item">Status: <span>{{ request('status', 'Semua') }}</span></div>
        <div class="info-item">Prioritas: <span>{{ request('prioritas', 'Semua') }}</span></div>
        <div class="info-item">Kategori: <span>{{ request('kategori', 'Semua') }}</span></div>
        <div class="info-item">Total: <span>{{ count($suratMasuk) }} surat</span></div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no text-center">NO</th>
                <th class="col-nomor">NOMOR SURAT</th>
                <th class="col-perihal">PERIHAL</th>
                <th class="col-pengirim">PENGIRIM</th>
                <th class="col-penerima">PENERIMA</th>
                <th class="col-tgl">TGL SURAT</th>
                <th class="col-tgl">TGL DITERIMA</th>
                <th class="col-penerima">KATEGORI</th>
                <th class="col-disposisi-user">PENERIMA DISPOSISI</th>
                <th class="col-disposisi-isi">ISI DISPOSISI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratMasuk as $index => $surat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-bold">{{ $surat->nomor_surat }}</td>
                    <td>{{ $surat->perihal }}</td>
                    <td>{{ $surat->pengirim }}</td>
                    <td>{{ $surat->penerima ?? '-' }}</td>
                    <td class="text-center">{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $surat->tanggal_terima ? $surat->tanggal_terima->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $surat->kategori ?? '-' }}</td>
                    <td>{{ $surat->penerima_disposisi ?? '-' }}</td>
                    <td>{{ $surat->disposisi ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak otomatis melalui Sistem E-Arsip UPT PT dan HMT Tuban pada {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>