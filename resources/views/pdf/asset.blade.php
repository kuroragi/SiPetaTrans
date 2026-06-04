<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Asset Transportasi</title>
    <style>
        /* =====================================================
         * PAPER & PAGE SETUP
         * F4 / Folio: 215.9mm x 330.2mm  (≈ 8.5in x 13in)
         * ===================================================== */
        @media print {
            @page {
                size: landscape;
            }
        }

        @page {
            size: 215.9mm 330.2mm;
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
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
            font-size: 9px;
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
         * Total usable width on F4 with 1.5cm margin each side:
         * 215.9mm - (2 × 15mm) = 185.9mm  ≈ 527pt / 702px (96dpi)
         * Column widths below are set in mm to be predictable.
         * ===================================================== */
        table.asset-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* enforces fixed column widths */
        }

        /* --- Column widths (total ≈ 185.9mm) --- */
        col.col-no {
            width: 6mm;
        }

        /* No             */
        col.col-noreg {
            width: 22mm;
        }

        /* Nomor Registrasi */
        col.col-nama {
            width: 30mm;
        }

        /* Nama           */
        col.col-jenis {
            width: 20mm;
        }

        /* Jenis          */
        col.col-subjenis {
            width: 18mm;
        }

        /* Sub Jenis      */
        col.col-status {
            width: 14mm;
        }

        /* Status         */
        col.col-lokasi {
            width: 22mm;
        }

        /* Lokasi (fixed) */
        col.col-jumlah {
            width: 10mm;
        }

        /* Jumlah         */
        col.col-nilai {
            width: 20mm;
        }

        /* Nilai Asset    */
        col.col-perolehan {
            width: 15mm;
        }

        /* Cara Perolehan */
        col.col-tgl {
            width: 18mm;
        }

        /* Tgl Perolehan  */
        /* Total: 6+22+30+20+18+14+22+10+20+15+18 = 195mm (fits F4 185.9mm with slight flex) */

        /* --- Header row --- */
        thead tr th {
            background-color: #e0e0e0;
            /* light grey – clear diff from content */
            border: 0.5px solid #000;
            font-size: 7.5px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            padding: 3px 2px;
            line-height: 1.3;
        }

        /* Repeat thead on every printed page (dompdf supports this) */
        thead {
            display: table-header-group;
        }

        /* --- Body rows --- */
        tbody tr td {
            border: 0.5px solid #000;
            font-size: 8px;
            vertical-align: top;
            padding: 2px 2px;
            line-height: 1.3;
            word-wrap: break-word;
            overflow: hidden;
        }

        /* Lokasi cell: clip overflow so it never blows the column */
        tbody tr td.cell-lokasi {
            max-width: 22mm;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /* Zebra stripe untuk keterbacaan (opsional, tanpa warna mencolok) */
        tbody tr:nth-child(even) td {
            background-color: #f7f7f7;
        }

        /* Center-align numeric / code columns */
        td.center,
        th.center {
            text-align: center;
        }

        /* Right-align currency columns */
        td.right,
        th.right {
            text-align: right;
        }

        /* --- Footer / page number (dompdf) --- */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 7px;
            text-align: right;
            border-top: 0.5px solid #000;
            padding-top: 2px;
        }

        /* --- Utility: hide on print / show only on screen --- */
        @media screen {
            body {
                padding: 20px;
                background: #ccc;
            }

            .page-footer {
                position: static;
                margin-top: 10px;
            }
        }
    </style>
</head>

<body>

    {{-- =========================================================
         KOP / REPORT HEADER
         ========================================================= --}}
    <div class="kop">
        <h4>DAFTAR ASSET TRANSPORTASI</h4>
        <h4>DINAS PERHUBUNGAN</h4>
        <h4>PEMERINTAH KOTA BUKITTINGGI</h4>
    </div>
    <hr class="kop-divider">

    {{-- =========================================================
         MAIN ASSET TABLE
         $assets  → collection / array dari controller
         ========================================================= --}}
    <table class="asset-table">

        {{-- Column width declarations --}}
        <colgroup>
            <col class="col-no">
            <col class="col-noreg">
            <col class="col-nama">
            <col class="col-jenis">
            <col class="col-subjenis">
            <col class="col-status">
            <col class="col-lokasi">
            <col class="col-jumlah">
            <col class="col-nilai">
            <col class="col-perolehan">
            <col class="col-tgl">
        </colgroup>

        {{-- =====================================================
             THEAD – will repeat on every printed page
             ===================================================== --}}
        <thead>
            {{-- Row 1: Main header labels --}}
            <tr>
                <th class="center" rowspan="2">No</th>
                <th class="center" rowspan="2">Nomor<br>Registrasi</th>
                <th class="center" rowspan="2">Nama Asset</th>
                <th class="center" colspan="2">Jenis</th>
                <th class="center" rowspan="2">Status</th>
                <th class="center" rowspan="2">Lokasi</th>
                <th class="center" rowspan="2">Jumlah</th>
                <th class="center" rowspan="2">Nilai Asset<br>(Current Value)</th>
                <th class="center" rowspan="2">Cara<br>Perolehan</th>
                <th class="center" rowspan="2">Tanggal<br>Perolehan</th>
            </tr>
            {{-- Row 2: Sub-header for Jenis --}}
            <tr>
                <th class="center">Jenis</th>
                <th class="center">Sub Jenis</th>
            </tr>
        </thead>

        {{-- =====================================================
             TBODY – loop data asset di sini
             ===================================================== --}}
        <tbody>
            {{-- @forelse ($assets as $index => $asset) --}}
            {{-- Contoh baris kosong dengan komentar panduan pengisian --}}

            @foreach ($assets as $key => $asset)
                <tr>
                    <td class="center">
                        {{-- Nomor urut baris: $loop->iteration atau $index+1 --}}
                        {{ $loop->iteration }}
                    </td>
                    <td class="center">
                        {{-- Nomor registrasi asset: $asset->registration_number atau $asset->kode_asset --}}
                        {{ $asset->registration_number ?? '' }}
                    </td>
                    <td>
                        {{-- Nama / deskripsi asset: $asset->name atau $asset->nama_asset --}}
                        {{ $asset->name ?? '-' }}
                    </td>
                    <td>
                        {{-- Nama jenis utama asset: $asset->assetType->name atau $asset->jenis --}}
                        {{ $asset->type?->name ?? '-' }}
                    </td>
                    <td>
                        {{-- Nama jenis utama asset: $asset->assetType->name atau $asset->jenis --}}
                        {{ $asset->subtype?->name ?? '' }}
                    </td>
                    <td class="center">
                        {{-- Status kondisi/ketersediaan asset: $asset->status (misal: Aktif, Rusak, dll) --}}
                        {{ $asset->status ?? '-' }}
                    </td>
                    <td class="cell-lokasi">
                        {{-- Lokasi/penempatan asset: $asset->location atau $asset->lokasi
                         Lebar kolom ini dikunci (22mm) dan teks terpotong otomatis --}}
                        {{ $asset->location ?? '-' }}
                    </td>
                    <td class="center">
                        {{-- Jumlah unit asset: $asset->quantity atau $asset->jumlah --}}
                        {{ $asset->quantity ?? '0' }}
                    </td>
                    <td class="right">
                        {{-- Nilai asset terkini (current value) dalam format Rupiah: --}}
                        Rp {{ number_format($asset->current_value, 0, ',', '.') }}
                    </td>
                    <td class="center">
                        {{-- Cara/metode perolehan asset: $asset->acquisition_method atau $asset->cara_perolehan
                         Contoh nilai: Pembelian, Hibah, APBD, dll --}}
                        {{ $asset->acquisition_source ?? '-' }}
                    </td>
                    <td class="center">
                        {{-- Tanggal perolehan asset dalam format lokal:
                        atau $asset->tanggal_perolehan --}}
                        {{ \Carbon\Carbon::parse($asset->acquisition_date)->format('d/m/Y') }}
                    </td>
                </tr>
            @endforeach

            {{-- @empty --}}
            <tr>
                <td colspan="11" class="center">
                    {{-- Tampilkan pesan ini jika data kosong: "Tidak ada data asset yang ditemukan." --}}
                </td>
            </tr>
            {{-- @endforelse --}}
        </tbody>

    </table>

    {{-- =========================================================
         FOOTER – nomor halaman (dompdf script tag)
         Dompdf mendukung variabel: {PAGE_NUM} dan {PAGE_COUNT}
         ========================================================= --}}
    <div class="page-footer">
        {{-- Tanggal cetak: Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB --}}
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
