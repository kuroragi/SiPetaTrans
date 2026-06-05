<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Konfirmasi Laporan Kerusakan – Dishub Kota Bukittinggi</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Source+Serif+4:ital,wght@0,300;0,400;0,600;1,300&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #e8e4dc;
            font-family: 'Source Serif 4', Georgia, serif;
            color: #1a1a1a;
            padding: 40px 16px;
        }

        .wrapper {
            max-width: 640px;
            margin: 0 auto;
            background: #f5f2ec;
            border: 1px solid #c8bfaa;
            border-top: 5px solid #1a3a5c;
        }

        /* Header */
        .header {
            background-color: #1a3a5c;
            padding: 28px 36px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-logo {
            width: 58px;
            height: 58px;
            flex-shrink: 0;
        }

        .header-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: #ffffff;
            letter-spacing: 0.5px;
            line-height: 1.3;
        }

        .header-text p {
            font-family: 'Source Serif 4', serif;
            font-size: 11.5px;
            color: #a8bfd4;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 3px;
        }

        /* Divider strip */
        .strip {
            background-color: #c9a84c;
            height: 3px;
        }

        /* Body */
        .body {
            padding: 40px 36px;
        }

        .label-tag {
            display: inline-block;
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #1a3a5c;
            border: 1px solid #1a3a5c;
            padding: 3px 10px;
            margin-bottom: 24px;
        }

        .greeting {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: #1a3a5c;
            margin-bottom: 20px;
            line-height: 1.35;
        }

        .body p {
            font-size: 14.5px;
            line-height: 1.85;
            color: #2d2d2d;
            margin-bottom: 16px;
        }

        /* Info box */
        .info-box {
            border-left: 3px solid #c9a84c;
            background-color: #efe9d8;
            padding: 18px 22px;
            margin: 28px 0;
        }

        .info-box p {
            margin: 0;
            font-size: 13.5px;
            color: #3a3028;
            line-height: 1.75;
        }

        .info-box strong {
            font-weight: 600;
            color: #1a3a5c;
        }

        /* Status badge */
        .status-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 28px 0;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #c9a84c;
            flex-shrink: 0;
        }

        .status-text {
            font-size: 13px;
            letter-spacing: 0.5px;
            color: #1a3a5c;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Closing */
        .closing {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #d0c8b5;
        }

        .closing p {
            font-size: 14px;
            color: #2d2d2d;
            line-height: 1.8;
            margin-bottom: 6px;
        }

        .signature {
            margin-top: 20px;
        }

        .signature .name {
            font-family: 'Playfair Display', serif;
            font-size: 15px;
            color: #1a3a5c;
        }

        .signature .position {
            font-size: 12px;
            color: #7a6e5f;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Footer */
        .footer {
            background-color: #1a3a5c;
            padding: 18px 36px;
            text-align: center;
        }

        .footer p {
            font-size: 11px;
            color: #7fa3c0;
            letter-spacing: 0.5px;
            line-height: 1.7;
        }

        .footer a {
            color: #c9a84c;
            text-decoration: none;
        }

        /* SVG logo placeholder */
        svg text {
            font-family: serif;
        }
    </style>
</head>

<body>

    <div class="wrapper">

        <!-- HEADER -->
        <div class="header">
            <!-- Logo Dishub (SVG sederhana) -->
            <svg class="header-logo" viewBox="0 0 58 58" xmlns="http://www.w3.org/2000/svg">
                <circle cx="29" cy="29" r="27" fill="none" stroke="#c9a84c" stroke-width="1.5" />
                <circle cx="29" cy="29" r="22" fill="#132d48" />
                <!-- Road symbol -->
                <rect x="26" y="12" width="6" height="34" rx="2" fill="#c9a84c" opacity="0.9" />
                <rect x="14" y="26" width="30" height="6" rx="2" fill="#ffffff" opacity="0.85" />
                <rect x="26" y="26" width="6" height="6" fill="#132d48" />
                <!-- Dots on road -->
                <rect x="26.5" y="14" width="5" height="5" rx="1" fill="#c9a84c" />
                <rect x="26.5" y="39" width="5" height="5" rx="1" fill="#c9a84c" />
            </svg>

            <div class="header-text">
                <h1>Dinas Perhubungan<br>Kota Bukittinggi</h1>
                <p>Pemerintah Kota Bukittinggi</p>
            </div>
        </div>

        <div class="strip"></div>

        <!-- BODY -->
        <div class="body">

            <span class="label-tag">Notifikasi Laporan</span>

            <h2 class="greeting">Laporan Anda Telah<br>Kami Terima</h2>

            <p>Yth. Bapak/Ibu,<br>Warga Kota Bukittinggi</p>

            <p>
                Dengan hormat, kami dari <strong>Dinas Perhubungan Kota Bukittinggi</strong> menyampaikan bahwa
                laporan kerusakan yang Bapak/Ibu sampaikan telah kami terima dan telah dibaca oleh petugas
                kami yang berwenang.
            </p>

            <div class="info-box">
                <p>
                    <strong>Status Laporan&nbsp;:</strong> Diterima &amp; Sedang Ditinjau<br>
                    <strong>Instansi Penerima&nbsp;:</strong> Dinas Perhubungan Kota Bukittinggi<br>
                    <strong>Tindak Lanjut&nbsp;:</strong> Akan segera diproses sesuai prosedur yang berlaku
                </p>
            </div>

            <div class="status-row">
                <div class="status-dot"></div>
                <span class="status-text">Laporan sedang dalam proses penanganan</span>
            </div>

            <p>
                Laporan Bapak/Ibu akan segera kami tindaklanjuti dengan melakukan peninjauan lapangan
                dan koordinasi bersama pihak-pihak terkait. Proses penanganan akan dilaksanakan sesuai
                dengan skala prioritas dan prosedur yang berlaku di lingkungan Pemerintah Kota Bukittinggi.
            </p>

            <p>
                Apabila Bapak/Ibu memerlukan informasi lebih lanjut mengenai perkembangan laporan,
                silakan menghubungi kami melalui saluran resmi yang tersedia.
            </p>

            <div class="closing">
                <p>Atas perhatian dan partisipasi aktif Bapak/Ibu dalam menjaga kualitas
                    infrastruktur dan pelayanan perhubungan di Kota Bukittinggi, kami menyampaikan
                    terima kasih yang sebesar-besarnya.</p>

                <p style="margin-top:16px;">Hormat kami,</p>

                <div class="signature">
                    <div class="name">Dinas Perhubungan Kota Bukittinggi</div>
                    <div class="position">Pemerintah Kota Bukittinggi</div>
                    <div class="position">Sumatera Barat</div>
                </div>
            </div>

        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p>
                Dinas Perhubungan Kota Bukittinggi &nbsp;|&nbsp; Jl. A. Yani No.1, Bukittinggi, Sumatera Barat<br>
                Email resmi: <a href="mailto:dishub@bukittinggikota.go.id">dishub@bukittinggikota.go.id</a>
                &nbsp;|&nbsp; Telp: (0752) 000-0000<br>
                <span style="color:#556e82; font-size:10px;">Pesan ini dikirimkan secara otomatis. Mohon tidak membalas
                    email ini.</span>
            </p>
        </div>

    </div>

</body>

</html>
