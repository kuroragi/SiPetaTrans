<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Usulan Masyarakat</title>
    <style>
        /* =====================================================
         * PAPER & PAGE SETUP
         * F4 / Folio Landscape: 330.2mm x 215.9mm
         * ===================================================== */
        @media print {
            @page {
                size: landscape;
            }
        }

        @page {
            size: 330.2mm 215.9mm;
            margin: 1.5cm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #000;
            background: #fff;
        }

        /* =====================================================
         * REPORT HEADER / KOP
         * ===================================================== */
        .kop {
            text-align: center;
            margin-bottom: 8px;
        }

        .kop h4 {
            font-size: 12px;
            font-weight: bold;
            line-height: 1.6;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .kop-divider {
            border: none;
            border-top: 2px solid #000;
            margin: 4px 0 6px 0;
        }

        /* =====================================================
         * TABLE
         * ===================================================== */
        table.proposal-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        /* --- Column widths --- */
        col.col-no { width: 4%; }
        col.col-pengusul { width: 14%; }
        col.col-tanggal { width: 9%; }
        col.col-jenis { width: 14%; }
        col.col-jumlah { width: 6%; }
        col.col-lokasi { width: 17%; }
        col.col-anggaran { width: 12%; }
        col.col-kelayakan { width: 10%; }
        col.col-status { width: 14%; }

        /* --- Header row --- */
        thead tr th {
            background-color: #e0e0e0;
            border: 0.5px solid #000;
            font-size: 8.5px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            padding: 4px 3px;
            line-height: 1.3;
        }

        thead {
            display: table-header-group;
        }

        /* --- Body rows --- */
        tbody tr td {
            border: 0.5px solid #000;
            font-size: 9px;
            vertical-align: top;
            padding: 3px 3px;
            line-height: 1.3;
            word-wrap: break-word;
            overflow: hidden;
        }

        tbody tr td.cell-lokasi {
            max-width: 25mm;
            overflow: hidden;
            white-space: normal;
        }

        tbody tr:nth-child(even) td {
            background-color: #f7f7f7;
        }

        td.center,
        th.center {
            text-align: center;
        }

        td.right,
        th.right {
            text-align: right;
        }

        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 8px;
            text-align: right;
            border-top: 0.5px solid #000;
            padding-top: 2px;
        }

        @media screen {
            body {
                padding: 20px;
                background: #fff;
            }

            .page-footer {
                position: static;
                margin-top: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="kop">
        <h4>DAFTAR USULAN MASYARAKAT</h4>
        <h4>DINAS PERHUBUNGAN</h4>
        <h4>PEMERINTAH KOTA BUKITTINGGI</h4>
    </div>
    <hr class="kop-divider">

    <table class="proposal-table">

        <colgroup>
            <col class="col-no">
            <col class="col-pengusul">
            <col class="col-tanggal">
            <col class="col-jenis">
            <col class="col-jumlah">
            <col class="col-lokasi">
            <col class="col-anggaran">
            <col class="col-kelayakan">
            <col class="col-status">
        </colgroup>

        <thead>
            <tr>
                <th class="center">No</th>
                <th class="center">Pengusul</th>
                <th class="center">Tanggal</th>
                <th class="center">Jenis Permintaan</th>
                <th class="center">Jumlah</th>
                <th class="center">Lokasi</th>
                <th class="center">Anggaran</th>
                <th class="center">Kelayakan</th>
                <th class="center">Tindak Lanjut (Status)</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($proposals as $proposal)
                <tr>
                    <td class="center">
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $proposal->pengusul }}
                    </td>
                    <td class="center">
                        {{ \Carbon\Carbon::parse($proposal->tanggal)->format('d/m/Y') }}
                    </td>
                    <td>
                        {{ $proposal->jenis_permintaan }}
                    </td>
                    <td class="center">
                        {{ $proposal->jumlah }}
                    </td>
                    <td class="cell-lokasi">
                        {{ $proposal->lokasi }}
                    </td>
                    <td class="right">
                        @if($proposal->perkiraan_anggaran)
                            Rp {{ number_format($proposal->perkiraan_anggaran, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="center">
                        {{ $proposal->kelayakan ? ucfirst($proposal->kelayakan) : '-' }}
                    </td>
                    <td class="center">
                        {{ ucfirst($proposal->status) }}
                        @if($proposal->tindak_lanjut)
                            <br><small class="text-gray-600">({{ $proposal->tindak_lanjut }})</small>
                        @endif
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="9" class="center">
                    Tidak ada data usulan yang ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>

    <div class="page-footer">
        &nbsp;&nbsp;
        Halaman
        <script type="text/php">
            if (isset($pdf)) {
                echo $pdf->get_page_number() . ' / ' . $pdf->get_page_count();
            }
        </script>
    </div>

    <script>
        window.addEventListener('load', () => {
            window.print();
        });
    </script>

</body>

</html>
