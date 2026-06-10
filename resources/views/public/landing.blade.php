<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPETA-TRANS — Sistem Informasi Pemetaan Aset Transportasi Kota Bukittinggi</title>
    <meta name="description"
        content="Platform GIS modern untuk monitoring, pemetaan, dan pelaporan aset transportasi Kota Bukittinggi oleh Dinas Perhubungan.">
    <meta name="theme-color" id="meta-theme" content="#f0f4ff">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.0/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.0/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster@1.5.0/dist/leaflet.markercluster.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ══════════════════════════════════
           THEME TOKENS — light is default
        ══════════════════════════════════ */
        :root {
            --bg: #f0f4ff;
            --bg2: #e8f0fe;
            --bg3: #ffffff;
            --bg4: rgba(255, 255, 255, .78);
            --bg5: rgba(240, 244, 255, .97);
            --t1: #0f172a;
            --t2: #334155;
            --t3: #64748b;
            --t4: #94a3b8;
            --bd: rgba(26, 86, 219, .13);
            --bd2: rgba(26, 86, 219, .07);
            --orb-op: .05;
        }

        html.dark {
            --bg: #060d1a;
            --bg2: #07101f;
            --bg3: rgba(15, 30, 61, .55);
            --bg4: rgba(255, 255, 255, .05);
            --bg5: rgba(8, 18, 36, .96);
            --t1: #e2e8f0;
            --t2: #94a3b8;
            --t3: #64748b;
            --t4: #475569;
            --bd: rgba(255, 255, 255, .08);
            --bd2: rgba(255, 255, 255, .05);
            --orb-op: .16;
        }

        /* ══════════════════════════════════
           BASE
        ══════════════════════════════════ */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--t1);
            overflow-x: hidden;
            transition: background .35s, color .35s;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg2);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(26, 86, 219, .28);
            border-radius: 3px;
        }

        /* ══════════════════════════════════
           ANIMATIONS
        ══════════════════════════════════ */
        .grid-bg {
            background-image: linear-gradient(rgba(26, 86, 219, .07) 1px, transparent 1px), linear-gradient(90deg, rgba(26, 86, 219, .07) 1px, transparent 1px);
            background-size: 56px 56px;
            animation: gridMove 22s linear infinite;
        }

        html:not(.dark) .grid-bg {
            background-image: linear-gradient(rgba(26, 86, 219, .04) 1px, transparent 1px), linear-gradient(90deg, rgba(26, 86, 219, .04) 1px, transparent 1px);
        }

        @keyframes gridMove {
            0% {
                background-position: 0 0
            }

            100% {
                background-position: 56px 56px
            }
        }

        @keyframes orbFloat {

            0%,
            100% {
                transform: translateY(0) scale(1)
            }

            50% {
                transform: translateY(-28px) scale(1.04)
            }
        }

        @keyframes dashAnim {
            to {
                stroke-dashoffset: -100
            }
        }

        @keyframes pulse-out {
            0% {
                transform: scale(1);
                opacity: .5
            }

            100% {
                transform: scale(2.4);
                opacity: 0
            }
        }

        @keyframes floatBtn {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-7px)
            }
        }

        /* ══════════════════════════════════
           COMPONENTS
        ══════════════════════════════════ */
        .glass {
            background: var(--bg4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--bd);
            transition: background .35s, border-color .35s;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(80px);
            opacity: var(--orb-op);
            animation: orbFloat 9s ease-in-out infinite;
        }

        .route-line {
            stroke-dasharray: 14 7;
            animation: dashAnim 3.5s linear infinite;
        }

        .ldot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .text-gradient {
            background: linear-gradient(90deg, #38bdf8, #1a56db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .text-gradient-green {
            background: linear-gradient(90deg, #38bdf8, #22c55e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .reveal {
            opacity: 0;
            transform: translateY(26px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: none;
        }

        .reveal-delay-1 {
            transition-delay: .1s;
        }

        .reveal-delay-2 {
            transition-delay: .2s;
        }

        .reveal-delay-3 {
            transition-delay: .3s;
        }

        .reveal-delay-4 {
            transition-delay: .4s;
        }

        .sec-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(26, 86, 219, .1);
            border: 1px solid rgba(26, 86, 219, .22);
            color: #1d4ed8;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 16px;
        }

        html.dark .sec-badge {
            background: rgba(26, 86, 219, .14);
            border-color: rgba(26, 86, 219, .28);
            color: #93c5fd;
        }

        .feat-card {
            background: var(--bg3);
            border: 1px solid var(--bd);
            border-radius: 18px;
            padding: 26px 22px;
            transition: background .35s, border-color .25s, transform .3s, box-shadow .3s;
            position: relative;
            overflow: hidden;
        }

        html.dark .feat-card {
            background: rgba(15, 30, 61, .55);
        }

        .feat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 0%, rgba(26, 86, 219, .07) 0%, transparent 70%);
            opacity: 0;
            transition: opacity .3s;
        }

        .feat-card:hover {
            border-color: rgba(26, 86, 219, .4);
            transform: translateY(-4px);
            box-shadow: 0 14px 44px rgba(26, 86, 219, .1);
        }

        html.dark .feat-card:hover {
            box-shadow: 0 14px 44px rgba(0, 0, 0, .32);
        }

        .feat-card:hover::before {
            opacity: 1;
        }

        /* maps */
        #hero-map,
        #main-map {
            width: 100%;
        }

        #hero-map {
            height: 100%;
        }

        #main-map {
            height: 580px;
        }

        .hero-map-wrap {
            height: 460px;
            border-radius: 0;
        }

        /* form fields */
        .field-label {
            display: block;
            font-size: 10.5px;
            font-weight: 800;
            color: var(--t3);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 6px;
        }

        .field-input {
            display: block;
            width: 100%;
            background: var(--bg3);
            border: 1.5px solid var(--bd);
            border-radius: 10px;
            padding: 10px 14px;
            color: var(--t1);
            font-size: 13.5px;
            transition: border-color .2s, background .2s, box-shadow .2s;
            outline: none;
        }

        html.dark .field-input {
            background: rgba(255, 255, 255, .05);
            border-color: rgba(255, 255, 255, .1);
        }

        .field-input::placeholder {
            color: var(--t4);
        }

        .field-input:focus {
            border-color: #1a56db;
            background: rgba(26, 86, 219, .04);
            box-shadow: 0 0 0 3px rgba(26, 86, 219, .12);
        }

        html.dark .field-input:focus {
            background: rgba(26, 86, 219, .08);
        }

        .field-input option {
            background: var(--bg3);
            color: var(--t1);
        }

        html.dark .field-input option {
            background: #0f1e3d;
            color: #e2e8f0;
        }

        textarea.field-input {
            resize: vertical;
        }

        /* upload */
        .upload-zone {
            border: 2px dashed var(--bd);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            background: rgba(26, 86, 219, .02);
        }

        html.dark .upload-zone {
            background: rgba(255, 255, 255, .03);
            border-color: rgba(255, 255, 255, .14);
        }

        .upload-zone:hover,
        .upload-zone.drag-over {
            border-color: #1a56db;
            background: rgba(26, 86, 219, .06);
        }

        .upload-zone.has-file {
            border-color: #22c55e;
            background: rgba(34, 197, 94, .05);
        }

        /* buttons */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #1a56db, #1e40af);
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            padding: 11px 22px;
            border-radius: 10px;
            transition: all .25s;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(26, 86, 219, .28);
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(26, 86, 219, .45);
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(26, 86, 219, .07);
            border: 1px solid var(--bd);
            color: var(--t2);
            font-weight: 600;
            font-size: 13px;
            padding: 11px 22px;
            border-radius: 10px;
            transition: all .25s;
            cursor: pointer;
            text-decoration: none;
        }

        html.dark .btn-ghost {
            background: rgba(255, 255, 255, .07);
            color: #e2e8f0;
        }

        .btn-ghost:hover {
            background: rgba(26, 86, 219, .12);
            border-color: rgba(26, 86, 219, .28);
            color: #1a56db;
            transform: translateY(-1px);
        }

        html.dark .btn-ghost:hover {
            background: rgba(255, 255, 255, .11);
            border-color: rgba(255, 255, 255, .24);
            color: #e2e8f0;
        }

        .btn-orange {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #000;
            font-weight: 800;
            font-size: 13px;
            padding: 11px 22px;
            border-radius: 10px;
            transition: all .25s;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(245, 158, 11, .3);
            text-decoration: none;
        }

        .btn-orange:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(245, 158, 11, .45);
        }

        /* progress */
        .progress-bar {
            height: 5px;
            border-radius: 3px;
            background: rgba(26, 86, 219, .1);
            overflow: hidden;
        }

        html.dark .progress-bar {
            background: rgba(255, 255, 255, .07);
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 1.6s cubic-bezier(.25, .46, .45, .94);
        }

        /* divider */
        .sec-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(26, 86, 219, .15), transparent);
            max-width: 600px;
            margin: 0 auto;
        }

        html.dark .sec-divider {
            background: linear-gradient(90deg, transparent, rgba(26, 86, 219, .25), transparent);
        }

        /* navbar */
        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            transition: all .35s cubic-bezier(.4, 0, .2, 1);
        }

        #navbar.scrolled {
            background: var(--bg5);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--bd);
            box-shadow: 0 4px 28px rgba(26, 86, 219, .07);
        }

        html.dark #navbar.scrolled {
            background: rgba(8, 18, 36, .9);
            border-color: rgba(255, 255, 255, .07);
            box-shadow: 0 4px 28px rgba(0, 0, 0, .45);
        }

        /* toast */
        #toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(80px);
            z-index: 99999;
            transition: transform .4s cubic-bezier(.34, 1.56, .64, 1);
            background: var(--bg3);
            border: 1px solid rgba(34, 197, 94, .35);
            border-radius: 12px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--t1);
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .1);
            white-space: nowrap;
        }

        html.dark #toast {
            background: #0f1e3d;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .5);
        }

        #toast.show {
            transform: translateX(-50%) translateY(0);
        }

        /* sticky btn */
        #sticky-report {
            position: fixed;
            bottom: 28px;
            right: 24px;
            z-index: 8000;
            animation: floatBtn 3s ease-in-out infinite;
        }

        /* leaflet */
        .custom-popup .leaflet-popup-content-wrapper {
            background: var(--bg3) !important;
            backdrop-filter: blur(16px);
            border: 1px solid var(--bd) !important;
            border-radius: 14px !important;
            color: var(--t1);
            box-shadow: 0 14px 44px rgba(26, 86, 219, .07) !important;
        }

        html.dark .custom-popup .leaflet-popup-content-wrapper {
            background: rgba(8, 18, 36, .96) !important;
            border-color: rgba(255, 255, 255, .11) !important;
            box-shadow: 0 14px 44px rgba(0, 0, 0, .55) !important;
        }

        .custom-popup .leaflet-popup-tip {
            background: var(--bg3) !important;
        }

        html.dark .custom-popup .leaflet-popup-tip {
            background: rgba(8, 18, 36, .96) !important;
        }

        .custom-popup .leaflet-popup-close-button {
            color: var(--t3) !important;
            font-size: 16px !important;
            top: 10px !important;
            right: 10px !important;
        }

        .leaflet-control-zoom a {
            background: var(--bg3) !important;
            border-color: var(--bd) !important;
            color: var(--t2) !important;
        }

        /* theme toggle button */
        #theme-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: 1px solid var(--bd);
            background: rgba(26, 86, 219, .07);
            color: var(--t2);
            cursor: pointer;
            transition: all .25s;
            flex-shrink: 0;
        }

        html.dark #theme-toggle {
            background: rgba(255, 255, 255, .07);
        }

        #theme-toggle:hover {
            background: rgba(26, 86, 219, .14);
            color: #1a56db;
            border-color: rgba(26, 86, 219, .28);
        }

        html.dark #theme-toggle:hover {
            background: rgba(255, 255, 255, .12);
            color: #e2e8f0;
            border-color: rgba(255, 255, 255, .24);
        }

        /* ══════════════════════════════════
           TEXT HELPERS (.tw = white in dark, primary in light)
        ══════════════════════════════════ */
        html:not(.dark) .tw {
            color: var(--t1) !important;
        }

        html.dark .tw {
            color: #fff !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            color: var(--t1);
        }

        html.dark h1,
        html.dark h2,
        html.dark h3,
        html.dark h4,
        html.dark h5 {
            color: #fff;
        }

        /* ══════════════════════════════════
           SECTION BACKGROUNDS
        ══════════════════════════════════ */
        html:not(.dark) #hero-sec {
            background: linear-gradient(155deg, #dbeafe 0%, #eff6ff 45%, #e0effe 100%) !important;
        }

        html:not(.dark) #statistik {
            background: var(--bg) !important;
        }

        html:not(.dark) #peta-aset {
            background: var(--bg2) !important;
        }

        html:not(.dark) #fitur {
            background: var(--bg) !important;
        }

        html:not(.dark) #form-pengaduan {
            background: var(--bg2) !important;
        }

        html:not(.dark) #alur-sec {
            background: var(--bg) !important;
        }

        html:not(.dark) #tentang {
            background: var(--bg2) !important;
        }

        html:not(.dark) #cta-sec {
            background: linear-gradient(140deg, #dbeafe 0%, #eff6ff 35%, #dde8ff 65%, #e0effe 100%) !important;
        }

        html:not(.dark) #main-footer {
            background: #e8f0fe !important;
            border-top-color: rgba(26, 86, 219, .1) !important;
        }

        html.dark #hero-sec {
            background: linear-gradient(155deg, #030812 0%, #081527 35%, #0c1d42 65%, #040c1a 100%) !important;
        }

        html.dark #statistik {
            background: #07101f !important;
        }

        html.dark #peta-aset {
            background: #060d1a !important;
        }

        html.dark #fitur {
            background: #07101f !important;
        }

        html.dark #form-pengaduan {
            background: #060d1a !important;
        }

        html.dark #alur-sec {
            background: #07101f !important;
        }

        html.dark #tentang {
            background: #060d1a !important;
        }

        html.dark #cta-sec {
            background: linear-gradient(140deg, #030812 0%, #081527 35%, #0c1d42 65%, #040c1a 100%) !important;
        }

        html.dark #main-footer {
            background: #030810 !important;
            border-top-color: rgba(255, 255, 255, .05) !important;
        }

        /* ══════════════════════════════════
           SPECIFIC ELEMENT OVERRIDES
        ══════════════════════════════════ */
        html:not(.dark) #map-toolbar {
            background: var(--bg5) !important;
            border-color: var(--bd) !important;
        }

        html.dark #map-toolbar {
            background: rgba(8, 18, 36, .96) !important;
            border-color: rgba(255, 255, 255, .07) !important;
        }

        html:not(.dark) #map-search,
        html:not(.dark) #filter-status,
        html:not(.dark) #filter-type,
        html:not(.dark) #filter-trayek,
        html:not(.dark) #filter-mode {
            background: rgba(255, 255, 255, .88) !important;
            border-color: var(--bd) !important;
            color: var(--t2) !important;
        }

        html:not(.dark) #form-card {
            background: rgba(255, 255, 255, .98) !important;
            border-color: var(--bd) !important;
            box-shadow: 0 24px 60px rgba(26, 86, 219, .07) !important;
        }

        html.dark #form-card {
            background: rgba(12, 24, 52, .7) !important;
        }

        html:not(.dark) #form-card-header {
            background: linear-gradient(135deg, rgba(26, 86, 219, .07), rgba(240, 244, 255, .9)) !important;
            border-color: var(--bd) !important;
        }

        html.dark #form-card-header {
            background: linear-gradient(135deg, rgba(26, 86, 219, .4), rgba(10, 20, 50, .6)) !important;
            border-color: rgba(255, 255, 255, .07) !important;
        }

        html:not(.dark) #about-card {
            background: linear-gradient(135deg, rgba(219, 234, 254, .7), rgba(239, 246, 255, .9)) !important;
            border-color: rgba(26, 86, 219, .15) !important;
        }

        html.dark #about-card {
            background: linear-gradient(135deg, rgba(26, 86, 219, .12), rgba(8, 18, 50, .35)) !important;
            border-color: rgba(26, 86, 219, .18) !important;
        }

        html:not(.dark) #stats-strip {
            background: rgba(255, 255, 255, .88) !important;
            border-color: rgba(26, 86, 219, .1) !important;
        }

        html.dark #stats-strip {
            background: rgba(255, 255, 255, .05) !important;
            border-color: rgba(255, 255, 255, .08) !important;
        }

        html:not(.dark) #hero-browser-chrome {
            background: #e8f0fe !important;
            border-color: rgba(26, 86, 219, .1) !important;
        }

        html.dark #hero-browser-chrome {
            background: #0a1628 !important;
            border-color: rgba(255, 255, 255, .07) !important;
        }

        html:not(.dark) #hero-map-card {
            border-color: rgba(26, 86, 219, .15) !important;
            box-shadow: 0 0 60px rgba(26, 86, 219, .06), 0 32px 80px rgba(26, 86, 219, .05) !important;
        }

        html.dark #hero-map-card {
            border-color: rgba(255, 255, 255, .1) !important;
            box-shadow: 0 0 60px rgba(26, 86, 219, .22), 0 32px 80px rgba(0, 0, 0, .5) !important;
        }

        html:not(.dark) .map-card-wrap {
            border-color: var(--bd) !important;
            box-shadow: 0 4px 40px rgba(26, 86, 219, .07) !important;
        }

        html.dark .map-card-wrap {
            border-color: rgba(255, 255, 255, .08) !important;
            box-shadow: 0 0 60px rgba(0, 0, 0, .5) !important;
        }

        html:not(.dark) .logo-dot {
            border-color: var(--bg) !important;
        }

        html.dark .logo-dot {
            border-color: #060d1a !important;
        }

        html:not(.dark) .about-pillar-row {
            background: rgba(26, 86, 219, .04) !important;
            border-color: rgba(26, 86, 219, .1) !important;
        }

        html.dark .about-pillar-row {
            background: rgba(255, 255, 255, .03) !important;
            border-color: rgba(255, 255, 255, .06) !important;
        }

        html:not(.dark) .hero-stat-sep {
            background: rgba(26, 86, 219, .1) !important;
        }

        html.dark .hero-stat-sep {
            background: rgba(255, 255, 255, .08) !important;
        }

        html:not(.dark) .scroll-hint {
            color: rgba(26, 86, 219, .35) !important;
        }

        html.dark .scroll-hint {
            color: #334155 !important;
        }

        html:not(.dark) .alur-arrow {
            color: rgba(26, 86, 219, .25) !important;
        }

        html.dark .alur-arrow {
            color: #1e3a5f !important;
        }

        html:not(.dark) #upload-icon {
            color: rgba(26, 86, 219, .3) !important;
        }

        html:not(.dark) .footer-copyright-bar {
            border-top-color: rgba(26, 86, 219, .08) !important;
        }

        html.dark .footer-copyright-bar {
            border-top-color: rgba(255, 255, 255, .04) !important;
        }

        html:not(.dark) .footer-nav-link {
            color: var(--t3) !important;
        }

        html:not(.dark) .footer-nav-link:hover {
            color: var(--t2) !important;
        }

        html.dark .footer-nav-link {
            color: #334155 !important;
        }

        html:not(.dark) .footer-contact-text {
            color: var(--t3) !important;
        }

        html.dark .footer-contact-text {
            color: #334155 !important;
        }

        html:not(.dark) .footer-brand-sub {
            color: var(--t4) !important;
        }

        html.dark .footer-brand-sub {
            color: #334155 !important;
        }

        html:not(.dark) .footer-brand-desc {
            color: var(--t4) !important;
        }

        html.dark .footer-brand-desc {
            color: #334155 !important;
        }

        html:not(.dark) .copyright-text {
            color: var(--t4) !important;
        }

        html.dark .copyright-text {
            color: #1e293b !important;
        }

        /* ══════════════════════════════════
           LIGHT MODE — COMPREHENSIVE TEXT LEGIBILITY
           Inline style="color:..." overrides CSS vars, so we
           need attribute selectors + !important to win.
        ══════════════════════════════════ */

        /* 1. Headings — override inline color:#fff */
        html:not(.dark) h1,
        html:not(.dark) h2,
        html:not(.dark) h3,
        html:not(.dark) h4,
        html:not(.dark) h5 {
            color: var(--t1) !important;
        }

        /* 2. Navbar links base color (JS hover handlers also updated) */
        #navbar .nav-link {
            color: #94a3b8;
        }

        html:not(.dark) #navbar .nav-link {
            color: var(--t2) !important;
        }

        /* 3. All <p> text — override inline light grays */
        html:not(.dark) p {
            color: var(--t2) !important;
        }

        /* 4. White inline text on div/span (non-.tw) → primary dark */
        html:not(.dark) div[style*="color:#fff"]:not(.tw),
        html:not(.dark) span[style*="color:#fff"]:not(.tw) {
            color: var(--t1) !important;
        }

        /* 5. Medium-gray inline text — darken for readability */
        html:not(.dark) div[style*="color:#94a3b8"],
        html:not(.dark) span[style*="color:#94a3b8"] {
            color: var(--t3) !important;
        }

        html:not(.dark) div[style*="color:#64748b"],
        html:not(.dark) span[style*="color:#64748b"] {
            color: var(--t3) !important;
        }

        html:not(.dark) div[style*="color:#475569"],
        html:not(.dark) span[style*="color:#475569"] {
            color: var(--t2) !important;
        }

        /* 6. Very light text (near-invisible on white) → dark override */
        html:not(.dark) div[style*="color:#e2e8f0"],
        html:not(.dark) span[style*="color:#e2e8f0"] {
            color: var(--t2) !important;
        }

        html:not(.dark) div[style*="color:#334155"],
        html:not(.dark) span[style*="color:#334155"] {
            color: var(--t2) !important;
        }

        /* Note: accent status colors (#22c55e, #f59e0b, #ef4444, etc.) are NOT
           targeted by rules 4-6 so they retain their original values naturally. */

        @media(max-width:768px) {
            #main-map {
                height: 380px;
            }

            .hero-map-wrap {
                height: 260px;
            }
        }
    </style>
</head>

<body>

    {{-- STICKY REPORT BTN --}}
    <div id="sticky-report">
        <a href="#form-pengaduan" class="btn-orange shadow-2xl" style="font-size:12px;padding:10px 18px;">
            <i class="fas fa-triangle-exclamation"></i>
            <span class="hidden sm:inline">Laporkan Kerusakan</span>
            <span class="sm:hidden">Lapor</span>
        </a>
    </div>

    {{-- TOAST --}}
    <div id="toast">
        <i class="fas fa-circle-check" style="color:#4ade80;font-size:18px;"></i>
        <span id="toast-msg">Pengaduan berhasil dikirim!</span>
    </div>

    {{-- ======================================================
     NAVBAR
====================================================== --}}
    <nav id="navbar" style="padding:12px 20px;">
        <div style="max-width:1280px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;">
            <a href="#" style="display:flex;align-items:center;gap:12px;text-decoration:none;">
                <div style="position:relative;">
                    <div
                        style="width:38px;height:38px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 16px rgba(59,130,246,.35);">
                        <i class="fas fa-map-location-dot" style="color:#fff;font-size:16px;"></i>
                    </div>
                    <div class="logo-dot"
                        style="position:absolute;top:-3px;right:-3px;width:10px;height:10px;background:#4ade80;border-radius:50%;border:2px solid #060d1a;animation:pulse-out 2s ease-out infinite;">
                    </div>
                </div>
                <div>
                    <div class="tw"
                        style="color:#fff;font-weight:900;font-size:13px;letter-spacing:.12em;line-height:1;">
                        SIPETA-TRANS</div>
                    <div
                        style="color:#60a5fa;font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;margin-top:2px;">
                        Dishub Kota Bukittinggi</div>
                </div>
            </a>

            <div style="display:flex;align-items:center;gap:4px;" class="hidden lg:flex">
                <a href="#peta-aset" class="nav-link"
                    style="padding:7px 14px;border-radius:8px;font-size:13px;text-decoration:none;transition:all .2s;"
                    onmouseover="var d=document.documentElement.classList.contains('dark');this.style.color=d?'#fff':'#1a56db';this.style.background=d?'rgba(255,255,255,.06)':'rgba(26,86,219,.08)'"
                    onmouseout="this.style.color='';this.style.background=''">Peta Aset</a>
                <a href="#statistik" class="nav-link"
                    style="padding:7px 14px;border-radius:8px;font-size:13px;text-decoration:none;transition:all .2s;"
                    onmouseover="var d=document.documentElement.classList.contains('dark');this.style.color=d?'#fff':'#1a56db';this.style.background=d?'rgba(255,255,255,.06)':'rgba(26,86,219,.08)'"
                    onmouseout="this.style.color='';this.style.background=''">Statistik</a>
                <a href="#fitur" class="nav-link"
                    style="padding:7px 14px;border-radius:8px;font-size:13px;text-decoration:none;transition:all .2s;"
                    onmouseover="var d=document.documentElement.classList.contains('dark');this.style.color=d?'#fff':'#1a56db';this.style.background=d?'rgba(255,255,255,.06)':'rgba(26,86,219,.08)'"
                    onmouseout="this.style.color='';this.style.background=''">Fitur</a>
                <a href="#tentang" class="nav-link"
                    style="padding:7px 14px;border-radius:8px;font-size:13px;text-decoration:none;transition:all .2s;"
                    onmouseover="var d=document.documentElement.classList.contains('dark');this.style.color=d?'#fff':'#1a56db';this.style.background=d?'rgba(255,255,255,.06)':'rgba(26,86,219,.08)'"
                    onmouseout="this.style.color='';this.style.background=''">Tentang</a>
            </div>

            <div style="display:flex;align-items:center;gap:8px;">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-ghost" style="font-size:12px;padding:8px 16px;"
                        class="hidden sm:inline-flex">
                        <i class="fas fa-th-large" style="color:#60a5fa;"></i><span class="hidden sm:inline">
                            Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost" style="font-size:12px;padding:8px 16px;"
                        class="hidden sm:inline-flex">
                        <i class="fas fa-sign-in-alt" style="color:#60a5fa;"></i><span class="hidden sm:inline">
                            Login</span>
                    </a>
                @endauth
                <a href="#form-pengaduan" class="btn-orange" style="font-size:12px;padding:8px 16px;">
                    <i class="fas fa-paper-plane"></i>
                    <span class="hidden sm:inline">Pengaduan</span>
                </a>
                <button id="theme-toggle" onclick="toggleTheme()" title="Ganti Tema">
                    <i id="theme-icon" class="fas fa-moon"></i>
                </button>
            </div>
        </div>
    </nav>

    {{-- ======================================================
     HERO — FULLSCREEN IMMERSIVE
====================================================== --}}
    <section id="hero-sec"
        style="min-height:100vh;position:relative;display:flex;align-items:center;overflow:hidden;background:linear-gradient(155deg,#030812 0%,#081527 35%,#0c1d42 65%,#040c1a 100%);">

        <div class="absolute inset-0 grid-bg" style="opacity:.35;"></div>

        {{-- orbs --}}
        <div class="orb" style="width:500px;height:500px;background:#1a56db;top:-15%;left:-8%;animation-delay:0s;">
        </div>
        <div class="orb"
            style="width:350px;height:350px;background:#0ea5e9;bottom:5%;right:2%;animation-delay:3.5s;"></div>
        <div class="orb" style="width:250px;height:250px;background:#4f46e5;top:35%;left:25%;animation-delay:6s;">
        </div>

        {{-- SVG route lines --}}
        <svg style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none;opacity:.18;"
            preserveAspectRatio="none">
            <path d="M 0 280 Q 280 120 600 250 T 1300 180" fill="none" stroke="#38bdf8" stroke-width="1.5"
                class="route-line" />
            <path d="M 100 480 Q 420 320 750 440 T 1400 360" fill="none" stroke="#1a56db" stroke-width="1"
                class="route-line" style="animation-delay:.9s" />
            <circle cx="320" cy="250" r="5" fill="#22c55e" opacity=".9" />
            <circle cx="680" cy="190" r="4" fill="#38bdf8" opacity=".9" />
            <circle cx="980" cy="310" r="6" fill="#f59e0b" opacity=".9" />
            <circle cx="220" cy="430" r="4" fill="#ef4444" opacity=".8" />
            <circle cx="1150" cy="165" r="4" fill="#a855f7" opacity=".7" />
        </svg>

        <div style="max-width:1280px;margin:0 auto;padding:120px 24px 80px;width:100%;position:relative;z-index:10;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;"
                class="grid grid-cols-1 lg:grid-cols-2 gap-14">

                {{-- LEFT --}}
                <div>
                    <div class="sec-badge">
                        <span
                            style="width:6px;height:6px;border-radius:50%;background:#4ade80;animation:pulse-out 2s ease-out infinite;"></span>
                        Sistem Aktif · GIS Real-Time · Dishub Bukittinggi
                    </div>

                    <h1
                        style="font-size:clamp(2.2rem,4.5vw,3.6rem);font-weight:900;line-height:1.07;color:#fff;margin-top:8px;">
                        Pemetaan Aset &<br>
                        <span class="text-gradient">Rute Trayek Transportasi</span><br>
                        Kota Secara<br>
                        <span style="color:#f59e0b;">Digital & Real-Time</span>
                    </h1>

                    <p style="margin-top:20px;color:#94a3b8;font-size:15px;line-height:1.75;max-width:520px;">
                        Sistem terintegrasi untuk monitoring aset transportasi, pelaporan kerusakan masyarakat,
                        dan pengelolaan fasilitas publik berbasis GIS — Dinas Perhubungan Kota Bukittinggi.
                    </p>

                    {{-- mini stats --}}
                    <div style="display:flex;flex-wrap:wrap;gap:24px;margin:28px 0;">
                        <div>
                            <div style="font-size:28px;font-weight:900;color:#fff;font-variant-numeric:tabular-nums;">
                                {{ $assets->count() }}</div>
                            <div style="font-size:11px;color:#64748b;margin-top:2px;">Aset Terpetakan</div>
                        </div>
                        <div class="hero-stat-sep" style="width:1px;background:rgba(255,255,255,.08);"></div>
                        <div>
                            <div
                                style="font-size:28px;font-weight:900;color:#4ade80;font-variant-numeric:tabular-nums;">
                                {{ $assets->where('status', 'baik')->count() }}</div>
                            <div style="font-size:11px;color:#64748b;margin-top:2px;">Kondisi Baik</div>
                        </div>
                        <div class="hero-stat-sep" style="width:1px;background:rgba(255,255,255,.08);"></div>
                        <div>
                            <div
                                style="font-size:28px;font-weight:900;color:#f87171;font-variant-numeric:tabular-nums;">
                                {{ $assets->whereIn('status', ['rusak', 'perlu_perbaikan'])->count() }}</div>
                            <div style="font-size:11px;color:#64748b;margin-top:2px;">Perlu Perhatian</div>
                        </div>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:12px;">
                        <a href="#peta-aset" class="btn-primary">
                            <i class="fas fa-map"></i> Eksplorasi Peta Aset
                        </a>
                        <a href="#form-pengaduan" class="btn-ghost">
                            <i class="fas fa-triangle-exclamation" style="color:#fbbf24;"></i> Buat Pengaduan
                        </a>
                    </div>

                    <div class="scroll-hint"
                        style="margin-top:48px;display:flex;align-items:center;gap:8px;color:#334155;font-size:12px;animation:orbFloat 2s ease-in-out infinite;">
                        <i class="fas fa-chevron-down"></i> Scroll untuk menjelajah
                    </div>
                </div>

                {{-- RIGHT — hero map card --}}
                <div class="hidden lg:block" style="position:relative;">
                    <div
                        style="position:absolute;inset:-16px;border-radius:28px;background:radial-gradient(circle,rgba(26,86,219,.18) 0%,transparent 70%);">
                    </div>

                    <div id="hero-map-card"
                        style="position:relative;border-radius:20px;overflow:hidden;border:1px solid rgba(255,255,255,.1);box-shadow:0 0 60px rgba(26,86,219,.22),0 32px 80px rgba(0,0,0,.5);">
                        {{-- browser chrome --}}
                        <div id="hero-browser-chrome"
                            style="display:flex;align-items:center;gap:8px;padding:10px 16px;background:#0a1628;border-bottom:1px solid rgba(255,255,255,.07);">
                            <div style="display:flex;gap:5px;">
                                <div style="width:10px;height:10px;border-radius:50%;background:#ef4444;opacity:.7;">
                                </div>
                                <div style="width:10px;height:10px;border-radius:50%;background:#f59e0b;opacity:.7;">
                                </div>
                                <div style="width:10px;height:10px;border-radius:50%;background:#22c55e;opacity:.7;">
                                </div>
                            </div>
                            <div style="flex:1;text-align:center;font-size:10px;color:#475569;font-family:monospace;">
                                sipeta-trans.dishub-bukittinggi.go.id</div>
                            <div
                                style="width:8px;height:8px;border-radius:50%;background:#4ade80;animation:orbFloat 2s ease-in-out infinite;">
                            </div>
                        </div>

                        {{-- hero map --}}
                        <div class="hero-map-wrap">
                            <div id="hero-map"></div>
                        </div>

                        {{-- overlay button to fullscreen map --}}
                        <div style="position:absolute;bottom:12px;right:12px;z-index:1000;">
                            <a href="#peta-aset" class="btn-primary" style="padding:8px 16px;font-size:12px;box-shadow:0 4px 15px rgba(0,0,0,0.3);border-radius:8px;">
                                <i class="fas fa-expand"></i> Peta Penuh & Filter
                            </a>
                        </div>

                        {{-- overlay stats --}}
                        <div id="hero-stats-overlay" class="glass"
                            style="position:absolute;top:12px;right:12px;border-radius:12px;padding:12px 14px;font-size:11px;min-width:160px;">
                            <div
                                style="color:#64748b;font-size:9px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px;">
                                Status Aset</div>
                            <div style="display:flex;flex-direction:column;gap:6px;">
                                @foreach ([['baik', '#22c55e', 'Baik', $assets->where('status', 'baik')->count()], ['perlu_perbaikan', '#f59e0b', 'Perlu Perbaikan', $assets->where('status', 'perlu_perbaikan')->count()], ['rusak', '#ef4444', 'Rusak', $assets->where('status', 'rusak')->count()], ['dalam_pemeliharaan', '#a855f7', 'Pemeliharaan', $assets->where('status', 'dalam_pemeliharaan')->count()]] as $st)
                                    <div style="display:flex;align-items:center;gap:7px;">
                                        <span class="ldot"
                                            style="background:{{ $st[1] }};box-shadow:0 0 5px {{ $st[1] }};"></span>
                                        <span style="color:#94a3b8;flex:1;">{{ $st[2] }}</span>
                                        <span class="tw"
                                            style="color:#fff;font-weight:700;">{{ $st[3] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- live badge --}}
                        <div id="hero-live-badge" class="glass"
                            style="position:absolute;bottom:12px;left:12px;border-radius:8px;padding:6px 12px;display:inline-flex;align-items:center;gap:7px;font-size:11px;">
                            <span
                                style="width:6px;height:6px;border-radius:50%;background:#4ade80;animation:pulse-out 2s ease-out infinite;"></span>
                            <span style="color:#4ade80;font-weight:700;">LIVE</span>
                            <span style="color:#475569;">· GIS Real-Time</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ======================================================
     STATS
====================================================== --}}
    <section id="statistik" style="padding:80px 0;background:#07101f;position:relative;">
        <div style="position:absolute;inset:0;" class="grid-bg" style="opacity:.18;"></div>

        @php
            $total = $assets->count();
            $baik = $assets->where('status', 'baik')->count();
            $perlu = $assets->where('status', 'perlu_perbaikan')->count();
            $rusak = $assets->where('status', 'rusak')->count();
            $pelihara = $assets->where('status', 'dalam_pemeliharaan')->count();
            $pctBaik = $total > 0 ? round(($baik / $total) * 100) : 0;
            $pctPerlu = $total > 0 ? round(($perlu / $total) * 100) : 0;
            $pctRusak = $total > 0 ? round(($rusak / $total) * 100) : 0;
        @endphp

        <div style="max-width:1280px;margin:0 auto;padding:0 24px;position:relative;z-index:10;">
            <div class="text-center reveal" style="margin-bottom:48px;">
                <div class="sec-badge" style="justify-content:center;"><i class="fas fa-chart-pie"
                        style="color:#60a5fa;"></i> Statistik Kondisi</div>
                <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);font-weight:900;color:#fff;">Data Kondisi Aset <span
                        class="text-gradient">Real-Time</span></h2>
                <p
                    style="color:#64748b;margin-top:10px;font-size:13.5px;max-width:480px;margin-left:auto;margin-right:auto;">
                    Seluruh aset transportasi Kota Bukittinggi terpantau dalam satu platform.</p>
            </div>

            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;"
                class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ([['icon' => 'fa-layer-group', 'color' => '#3b82f6', 'bg' => 'rgba(59,130,246,.12)', 'val' => $total, 'label' => 'Total Aset', 'sub' => 'Terdaftar', 'pct' => 100], ['icon' => 'fa-check-circle', 'color' => '#22c55e', 'bg' => 'rgba(34,197,94,.1)', 'val' => $baik, 'label' => 'Kondisi Baik', 'sub' => $pctBaik . '% dari total', 'pct' => $pctBaik], ['icon' => 'fa-exclamation-triangle', 'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,.1)', 'val' => $perlu, 'label' => 'Perlu Perbaikan', 'sub' => $pctPerlu . '% dari total', 'pct' => $pctPerlu], ['icon' => 'fa-times-circle', 'color' => '#ef4444', 'bg' => 'rgba(239,68,68,.1)', 'val' => $rusak, 'label' => 'Rusak', 'sub' => $pctRusak . '% dari total', 'pct' => $pctRusak]] as $i => $s)
                    <div class="feat-card reveal" style="transition-delay:{{ $i * 0.08 }}s;text-align:center;">
                        <div
                            style="width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:20px;background:{{ $s['bg'] }};box-shadow:0 0 20px {{ $s['color'] }}22;">
                            <i class="fas {{ $s['icon'] }}" style="color:{{ $s['color'] }};"></i>
                        </div>
                        <div class="count-up" data-target="{{ $s['val'] }}"
                            style="font-size:38px;font-weight:900;color:{{ $s['color'] }};font-variant-numeric:tabular-nums;line-height:1;">
                            {{ $s['val'] }}</div>
                        <div class="tw" style="color:#fff;font-weight:700;font-size:13px;margin-top:6px;">
                            {{ $s['label'] }}</div>
                        <div style="color:#475569;font-size:11px;margin-top:2px;">{{ $s['sub'] }}</div>
                        <div class="progress-bar" style="margin-top:14px;">
                            <div class="progress-fill"
                                style="width:{{ $s['pct'] }}%;background:{{ $s['color'] }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- smart metrics strip --}}
            <div id="stats-strip" class="glass reveal"
                style="border-radius:18px;padding:20px 28px;margin-top:20px;display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
                <div style="text-align:center;">
                    <div class="tw" style="font-size:26px;font-weight:900;color:#fff;">{{ $pctBaik }}%</div>
                    <div style="font-size:11px;color:#64748b;margin-top:4px;">Tingkat Aset Baik</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:26px;font-weight:900;color:#c084fc;">{{ $pelihara }}</div>
                    <div style="font-size:11px;color:#64748b;margin-top:4px;">Dalam Pemeliharaan</div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:26px;font-weight:900;color:#38bdf8;">
                        {{ $assets->groupBy('asset_type_id')->count() }}</div>
                    <div style="font-size:11px;color:#64748b;margin-top:4px;">Jenis Aset</div>
                </div>
                <div style="text-align:center;">
                    <div
                        style="font-size:26px;font-weight:900;color:#4ade80;display:flex;align-items:center;justify-content:center;gap:6px;">
                        <span
                            style="width:8px;height:8px;border-radius:50%;background:#4ade80;animation:pulse-out 2s ease-out infinite;"></span>LIVE
                    </div>
                    <div style="font-size:11px;color:#64748b;margin-top:4px;">Status Monitoring</div>
                </div>
            </div>
        </div>
    </section>

    <div class="sec-divider"></div>

    {{-- ======================================================
     FULLSCREEN MAP
====================================================== --}}
    <section id="peta-aset" style="padding:64px 0;background:#060d1a;">
        <div style="max-width:1280px;margin:0 auto;padding:0 24px;">

            <div style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:24px;"
                class="reveal">
                <div>
                    <div class="sec-badge"><i class="fas fa-satellite-dish" style="color:#4ade80;"></i> GIS Live Map
                    </div>
                    <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);font-weight:900;color:#fff;">Peta Sebaran Aset
                        dan Rute Trayek Transportasi</h2>
                    <p style="color:#64748b;font-size:13px;margin-top:4px;">Interaktif · Multi-Layer · Real-Time ·
                        Filterable</p>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                    @foreach ([['#22c55e', 'Baik'], ['#f59e0b', 'Perlu Perbaikan'], ['#ef4444', 'Rusak'], ['#a855f7', 'Pemeliharaan']] as $l)
                        <span
                            style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:999px;background:{{ $l[0] }}14;border:1px solid {{ $l[0] }}30;color:{{ $l[0] }};font-size:11px;font-weight:600;">
                            <span class="ldot" style="background:{{ $l[0] }};"></span>{{ $l[1] }}
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="reveal map-card-wrap"
                style="border-radius:20px;overflow:hidden;border:1px solid rgba(255,255,255,.08);box-shadow:0 0 60px rgba(0,0,0,.5);">
                {{-- Filter toolbar --}}
                <div id="map-toolbar"
                    style="display:flex;flex-wrap:wrap;align-items:center;gap:10px;padding:14px 18px;background:rgba(8,18,36,.96);border-bottom:1px solid rgba(255,255,255,.07);">
                    <select id="filter-mode"
                        style="background:rgba(26,86,219,.1);border:1.5px solid rgba(26,86,219,.3);border-radius:8px;padding:8px 12px;color:#38bdf8;font-size:12px;font-weight:700;outline:none;min-width:140px;"
                        onchange="toggleMapMode()">
                        <option value="aset">Peta Aset</option>
                        <option value="trayek">Peta Rute Trayek</option>
                    </select>
                    <div id="aset-filters" style="display:flex;gap:10px;flex-wrap:wrap;">
                        <select id="filter-type"
                            style="background:rgba(255,255,255,.04);border:1.5px solid rgba(255,255,255,.09);border-radius:8px;padding:8px 12px;color:#e2e8f0;font-size:12px;min-width:140px;outline:none;"
                            onchange="filterMarkers()">
                            <option value="">Semua Jenis</option>
                            @foreach ($assets->groupBy(fn($a) => $a->type?->name ?? 'Lainnya') as $typeName => $group)
                                <option value="{{ $typeName }}">{{ $typeName }}</option>
                            @endforeach
                        </select>
                        <select id="filter-status"
                            style="background:rgba(255,255,255,.04);border:1.5px solid rgba(255,255,255,.09);border-radius:8px;padding:8px 12px;color:#e2e8f0;font-size:12px;min-width:140px;outline:none;"
                            onchange="filterMarkers()">
                            <option value="">Semua Kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="perlu_perbaikan">Perlu Perbaikan</option>
                            <option value="rusak">Rusak</option>
                            <option value="dalam_pemeliharaan">Dalam Pemeliharaan</option>
                        </select>
                    </div>
                    <div id="trayek-filters" style="display:none;">
                        <select id="filter-trayek"
                            style="background:rgba(255,255,255,.04);border:1.5px solid rgba(255,255,255,.09);border-radius:8px;padding:8px 12px;color:#e2e8f0;font-size:12px;min-width:140px;outline:none;"
                            onchange="renderMapData()">
                            <option value="">Semua Trayek</option>
                            @foreach ($trayeks as $trayek)
                                <option value="{{ $trayek->id }}">{{ $trayek->code }} - {{ $trayek->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button onclick="resetMapFilter()" class="btn-ghost" style="font-size:11px;padding:8px 14px;">
                        <i class="fas fa-rotate-left"></i> Reset
                    </button>
                    <div style="margin-left:auto;font-size:11px;color:#475569;white-space:nowrap;">
                        <span id="visible-count">{{ $total }}</span> aset
                    </div>
                </div>

                <div id="main-map"></div>
            </div>
        </div>
    </section>

    <div class="sec-divider"></div>

    {{-- ======================================================
     FEATURES
====================================================== --}}
    <section id="fitur" style="padding:80px 0;background:#07101f;position:relative;">
        <div class="grid-bg" style="position:absolute;inset:0;opacity:.14;"></div>
        <div style="max-width:1280px;margin:0 auto;padding:0 24px;position:relative;z-index:10;">

            <div class="text-center reveal" style="margin-bottom:48px;">
                <div class="sec-badge" style="justify-content:center;"><i class="fas fa-star"
                        style="color:#fbbf24;"></i> Kemampuan Sistem</div>
                <h2 style="font-size:clamp(1.8rem,3.5vw,2.8rem);font-weight:900;color:#fff;">Fitur Unggulan <span
                        class="text-gradient">SIPETA-TRANS</span></h2>
                <p style="color:#64748b;margin-top:10px;font-size:13.5px;">Platform GIS modern untuk pengelolaan aset
                    transportasi yang terintegrasi dan transparan.</p>
            </div>

            @php
                $feats = [
                    [
                        'icon' => 'fa-map-pin',
                        'color' => '#38bdf8',
                        'title' => 'Pemetaan GIS Digital',
                        'desc' => 'Aset terpetakan dengan koordinat GPS pada peta interaktif LeafletJS.',
                    ],
                    [
                        'icon' => 'fa-eye',
                        'color' => '#22c55e',
                        'title' => 'Monitoring Real-Time',
                        'desc' => 'Pantau kondisi aset transportasi dengan pembaruan status otomatis.',
                    ],
                    [
                        'icon' => 'fa-triangle-exclamation',
                        'color' => '#f59e0b',
                        'title' => 'Pelaporan Publik',
                        'desc' => 'Masyarakat lapor kerusakan langsung dari halaman ini tanpa login.',
                    ],
                    [
                        'icon' => 'fa-route',
                        'color' => '#a855f7',
                        'title' => 'Tracking Laporan',
                        'desc' => 'Status penanganan setiap laporan dapat dipantau secara transparan.',
                    ],
                    [
                        'icon' => 'fa-wrench',
                        'color' => '#ef4444',
                        'title' => 'Manajemen Pemeliharaan',
                        'desc' => 'Catat jadwal dan riwayat pemeliharaan aset beserta biayanya.',
                    ],
                    [
                        'icon' => 'fa-chart-bar',
                        'color' => '#38bdf8',
                        'title' => 'Dashboard Analitik',
                        'desc' => 'Statistik kondisi tersaji dalam dashboard visual yang informatif.',
                    ],
                    [
                        'icon' => 'fa-clock-rotate-left',
                        'color' => '#22c55e',
                        'title' => 'Riwayat Kondisi',
                        'desc' => 'Riwayat perubahan kondisi tercatat lengkap dengan foto dokumentasi.',
                    ],
                    [
                        'icon' => 'fa-shield-halved',
                        'color' => '#f59e0b',
                        'title' => 'Keamanan Berbasis Peran',
                        'desc' => 'Autentikasi multi-role memastikan akses sesuai tanggung jawab.',
                    ],
            ]; @endphp

            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;"
                class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($feats as $i => $f)
                    <div class="feat-card reveal" style="transition-delay:{{ $i * 0.06 }}s;">
                        <div
                            style="width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;font-size:18px;background:{{ $f['color'] }}16;">
                            <i class="fas {{ $f['icon'] }}" style="color:{{ $f['color'] }};"></i>
                        </div>
                        <h3 style="font-weight:700;color:#fff;font-size:13px;margin-bottom:8px;">{{ $f['title'] }}
                        </h3>
                        <p style="color:#64748b;font-size:12px;line-height:1.65;">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="sec-divider"></div>

    {{-- ======================================================
     REPORTING FORM
====================================================== --}}
    <section id="form-pengaduan" style="padding:80px 0;background:#060d1a;position:relative;">
        <div class="grid-bg" style="position:absolute;inset:0;opacity:.18;"></div>
        <div style="max-width:1280px;margin:0 auto;padding:0 24px;position:relative;z-index:10;">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:start;"
                class="grid grid-cols-1 lg:grid-cols-2 gap-14">

                {{-- LEFT --}}
                <div class="reveal">
                    <div class="sec-badge"><i class="fas fa-users" style="color:#4ade80;"></i> Partisipasi Publik
                    </div>
                    <h2 style="font-size:clamp(1.8rem,3vw,2.5rem);font-weight:900;color:#fff;line-height:1.15;">
                        Laporkan Kerusakan,<br>
                        <span style="color:#f59e0b;">Bantu Kota Kita</span>
                    </h2>
                    <p style="color:#94a3b8;font-size:13.5px;line-height:1.75;margin:20px 0 32px;">
                        Setiap laporan yang Anda kirimkan langsung masuk ke sistem dan ditindaklanjuti oleh petugas
                        Dinas Perhubungan Kota Bukittinggi. Partisipasi Anda sangat berarti untuk menjaga kualitas
                        fasilitas transportasi publik.
                    </p>

                    <div style="display:flex;flex-direction:column;gap:18px;margin-bottom:36px;">
                        @foreach ([['01', 'fa-file-pen', '#38bdf8', 'Isi Data Laporan', 'Masukkan nama, kontak, lokasi, dan deskripsi kerusakan yang Anda temukan.'], ['02', 'fa-camera', '#f59e0b', 'Upload Foto Bukti', 'Lampirkan foto kerusakan agar petugas dapat memverifikasi dan menentukan prioritas.'], ['03', 'fa-paper-plane', '#22c55e', 'Laporan Terkirim', 'Laporan masuk ke sistem dan petugas Dinas Perhubungan segera merespons.']] as $step)
                            <div style="display:flex;align-items:flex-start;gap:16px;" class="reveal">
                                <div
                                    style="width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;flex-shrink:0;background:{{ $step[2] }}14;border:1px solid {{ $step[2] }}28;color:{{ $step[2] }};">
                                    {{ $step[0] }}</div>
                                <div>
                                    <div class="tw"
                                        style="color:#fff;font-weight:700;font-size:13.5px;margin-bottom:4px;">
                                        {{ $step[3] }}</div>
                                    <div style="color:#64748b;font-size:12.5px;line-height:1.6;">{{ $step[4] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach ([['fa-shield-halved', '#4ade80', 'Data aman & terlindungi'], ['fa-bolt', '#fbbf24', 'Respon cepat petugas'], ['fa-lock-open', '#60a5fa', 'Tanpa perlu login']] as $b)
                            <div class="glass"
                                style="border-radius:10px;padding:8px 14px;display:inline-flex;align-items:center;gap:7px;font-size:11px;color:#94a3b8;">
                                <i class="fas {{ $b[0] }}" style="color:{{ $b[1] }};"></i>
                                {{ $b[2] }}
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- RIGHT — form --}}
                <div class="reveal reveal-delay-2">

                    @if (session('success'))
                        <div
                            style="margin-bottom:20px;display:flex;align-items:flex-start;gap:12px;padding:18px;border-radius:14px;background:rgba(34,197,94,.07);border:1px solid rgba(34,197,94,.22);">
                            <i class="fas fa-circle-check"
                                style="color:#4ade80;font-size:20px;margin-top:1px;flex-shrink:0;"></i>
                            <div>
                                <div style="font-weight:700;color:#4ade80;font-size:14px;">Pengaduan Berhasil Dikirim!
                                </div>
                                <div style="color:#94a3b8;font-size:12px;margin-top:4px;">{{ session('success') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div
                            style="margin-bottom:20px;display:flex;align-items:flex-start;gap:12px;padding:18px;border-radius:14px;background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.22);">
                            <i class="fas fa-circle-exclamation"
                                style="color:#f87171;font-size:20px;margin-top:1px;flex-shrink:0;"></i>
                            <ul
                                style="list-style:disc;padding-left:16px;color:#94a3b8;font-size:12px;line-height:1.8;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div id="form-card"
                        style="border-radius:20px;overflow:hidden;background:rgba(12,24,52,.7);border:1px solid rgba(255,255,255,.08);box-shadow:0 24px 60px rgba(0,0,0,.4);">
                        {{-- form header --}}
                        <div id="form-card-header"
                            style="padding:20px 24px;background:linear-gradient(135deg,rgba(26,86,219,.4),rgba(10,20,50,.6));border-bottom:1px solid rgba(255,255,255,.07);">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div
                                    style="width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:rgba(245,158,11,.18);border:1px solid rgba(245,158,11,.3);">
                                    <i class="fas fa-triangle-exclamation" style="color:#fbbf24;font-size:16px;"></i>
                                </div>
                                <div>
                                    <div class="tw" style="color:#fff;font-weight:700;font-size:14px;">Form
                                        Pengaduan Kerusakan Aset</div>
                                    <div style="color:#64748b;font-size:11px;margin-top:2px;">Publik · Gratis · Tanpa
                                        akun</div>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('damage-reports.store-public') }}"
                            enctype="multipart/form-data"
                            style="padding:24px;display:flex;flex-direction:column;gap:16px;">
                            @csrf

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div>
                                    <label class="field-label">Nama Pelapor <span
                                            style="color:#f87171;">*</span></label>
                                    <input type="text" name="nama_pelapor" value="{{ old('nama_pelapor') }}"
                                        placeholder="Nama lengkap" required class="field-input" />
                                </div>
                                <div>
                                    <label class="field-label">Kontak <span style="color:#f87171;">*</span></label>
                                    <input type="text" name="kontak" value="{{ old('kontak') }}"
                                        placeholder="HP / Email" required class="field-input" />
                                </div>
                            </div>

                            <div>
                                <label class="field-label">Lokasi Kerusakan <span
                                        style="color:#f87171;">*</span></label>
                                <div style="position:relative;">
                                    <i class="fas fa-location-dot"
                                        style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#475569;font-size:12px;"></i>
                                    <input type="text" name="lokasi" value="{{ old('lokasi') }}"
                                        placeholder="Nama jalan / area / titik lokasi..." required class="field-input"
                                        style="padding-left:32px;" />
                                </div>
                            </div>

                            <div>
                                <label class="field-label">Aset Terkait <span
                                        style="color:#475569;font-weight:400;text-transform:none;letter-spacing:0;">(opsional)</span></label>
                                <select name="asset_id" class="field-input">
                                    <option value="">— Pilih aset jika diketahui —</option>
                                    @foreach ($assets as $asset)
                                        <option value="{{ $asset->id }}" @selected(old('asset_id') == $asset->id)>
                                            {{ $asset->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="field-label">Keterangan Kerusakan <span
                                        style="color:#f87171;">*</span></label>
                                <textarea name="keterangan" rows="3" placeholder="Jelaskan kerusakan secara singkat dan jelas..." required
                                    class="field-input">{{ old('keterangan') }}</textarea>
                            </div>

                            <div>
                                <label class="field-label">Foto Kerusakan <span
                                        style="color:#f87171;">*</span></label>
                                <div id="upload-zone" class="upload-zone"
                                    onclick="document.getElementById('foto-input').click()"
                                    ondragover="event.preventDefault();this.classList.add('drag-over')"
                                    ondragleave="this.classList.remove('drag-over')" ondrop="handleDrop(event)">
                                    <i id="upload-icon" class="fas fa-cloud-arrow-up"
                                        style="color:#334155;font-size:32px;margin-bottom:8px;display:block;"></i>
                                    <p id="upload-text" style="font-size:13px;font-weight:600;color:#64748b;">Klik
                                        atau drag &amp; drop foto di sini</p>
                                    <p id="upload-hint" style="font-size:11px;color:#334155;margin-top:4px;">JPG, PNG
                                        — maksimal 5 MB</p>
                                </div>
                                <input id="foto-input" type="file" name="foto" accept="image/*" required
                                    style="display:none;" onchange="handleFileChange(this)" />
                                <div id="img-preview" style="display:none;margin-top:10px;position:relative;">
                                    <img id="preview-img" src="" alt="preview"
                                        style="width:100%;border-radius:12px;object-fit:cover;max-height:160px;" />
                                    <button type="button" onclick="clearFile()"
                                        style="position:absolute;top:8px;right:8px;width:28px;height:28px;border-radius:50%;background:rgba(239,68,68,.85);color:#fff;font-size:12px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn-primary"
                                style="width:100%;justify-content:center;padding:13px 24px;font-size:14px;">
                                <i class="fas fa-paper-plane"></i> Kirim Pengaduan Sekarang
                            </button>

                            <p style="text-align:center;font-size:11.5px;color:#334155;">
                                <i class="fas fa-shield-halved" style="color:#4ade80;margin-right:4px;"></i>
                                Data Anda dijaga dan hanya digunakan untuk keperluan pelayanan publik.
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="sec-divider"></div>

    {{-- ======================================================
     ALUR PENANGANAN
====================================================== --}}
    <section id="alur-sec" style="padding:72px 0;background:#07101f;">
        <div style="max-width:1280px;margin:0 auto;padding:0 24px;">
            <div class="text-center reveal" style="margin-bottom:44px;">
                <div class="sec-badge" style="justify-content:center;"><i class="fas fa-route"
                        style="color:#38bdf8;"></i> Alur Penanganan</div>
                <h2 style="font-size:clamp(1.7rem,3vw,2.5rem);font-weight:900;color:#fff;">Dari Laporan Hingga <span
                        style="color:#22c55e;">Penanganan</span></h2>
            </div>

            <div style="display:grid;grid-template-columns:repeat(5,1fr);align-items:center;gap:0;"
                class="grid grid-cols-1 md:grid-cols-5">
                @php $flow = [['icon' => 'fa-user', 'color' => '#38bdf8', 'label' => 'Masyarakat Melapor', 'sub' => 'Isi form online'], null, ['icon' => 'fa-user-shield', 'color' => '#f59e0b', 'label' => 'Admin Verifikasi', 'sub' => 'Review laporan'], null, ['icon' => 'fa-circle-check', 'color' => '#22c55e', 'label' => 'Selesai', 'sub' => 'Status diperbarui']]; @endphp

                @foreach ($flow as $i => $f)
                    @if ($f === null)
                        <div style="display:flex;align-items:center;justify-content:center;" class="py-3 md:py-0">
                            <i class="fas fa-chevron-right alur-arrow" style="color:#1e3a5f;font-size:20px;"></i>
                        </div>
                    @else
                        <div class="feat-card reveal text-center"
                            style="transition-delay:{{ $i * 0.08 }}s;padding:24px 16px;">
                            <div
                                style="width:54px;height:54px;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:20px;background:{{ $f['color'] }}14;border:1px solid {{ $f['color'] }}24;">
                                <i class="fas {{ $f['icon'] }}" style="color:{{ $f['color'] }};"></i>
                            </div>
                            <div class="tw" style="color:#fff;font-weight:700;font-size:12px;margin-bottom:4px;">
                                {{ $f['label'] }}</div>
                            <div style="color:#475569;font-size:11px;">{{ $f['sub'] }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <div class="sec-divider"></div>

    {{-- ======================================================
     ABOUT
====================================================== --}}
    <section id="tentang" style="padding:80px 0;background:#060d1a;position:relative;">
        <div class="grid-bg" style="position:absolute;inset:0;opacity:.14;"></div>
        <div style="max-width:1280px;margin:0 auto;padding:0 24px;position:relative;z-index:10;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;"
                class="grid grid-cols-1 lg:grid-cols-2 gap-14">

                <div class="reveal">
                    <div id="about-card"
                        style="border-radius:20px;padding:32px;background:linear-gradient(135deg,rgba(26,86,219,.12),rgba(8,18,50,.35));border:1px solid rgba(26,86,219,.18);">
                        <div style="display:flex;align-items:center;gap:16px;margin-bottom:28px;">
                            <div
                                style="width:60px;height:60px;border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:24px;background:linear-gradient(135deg,#1a56db,#0369a1);box-shadow:0 0 30px rgba(26,86,219,.35);">
                                <i class="fas fa-map-location-dot" style="color:#fff;"></i>
                            </div>
                            <div>
                                <div class="tw"
                                    style="color:#fff;font-weight:900;font-size:18px;letter-spacing:.05em;">
                                    SIPETA-TRANS</div>
                                <div style="color:#60a5fa;font-size:11px;font-weight:600;margin-top:3px;">Sistem
                                    Informasi Pemetaan Aset Transportasi</div>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:14px;">
                            @foreach ([['fa-building-columns', '#38bdf8', 'Dinas Perhubungan Kota Bukittinggi'], ['fa-map', '#22c55e', 'Berbasis GIS dengan LeafletJS & OpenStreetMap'], ['fa-users', '#a855f7', 'Transparansi pengelolaan aset untuk publik'], ['fa-shield-halved', '#f59e0b', 'Keamanan data dengan autentikasi berbasis peran'], ['fa-mobile-screen', '#ef4444', 'Responsif di desktop, tablet & smartphone']] as $a)
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <div
                                        style="width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $a[1] }}14;font-size:13px;">
                                        <i class="fas {{ $a[0] }}" style="color:{{ $a[1] }};"></i>
                                    </div>
                                    <span style="color:#94a3b8;font-size:13px;">{{ $a[2] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="reveal reveal-delay-2">
                    <div class="sec-badge"><i class="fas fa-info-circle" style="color:#60a5fa;"></i> Tentang Sistem
                    </div>
                    <h2
                        style="font-size:clamp(1.7rem,3vw,2.4rem);font-weight:900;color:#fff;line-height:1.2;margin-bottom:20px;">
                        Digitalisasi Pengelolaan Aset Transportasi Kota</h2>
                    <p style="color:#94a3b8;font-size:13.5px;line-height:1.8;margin-bottom:16px;">
                        SIPETA-TRANS hadir sebagai jawaban atas tantangan pengelolaan aset transportasi yang selama ini
                        dilakukan secara manual dan tidak terintegrasi. Sistem ini menyatukan seluruh proses — dari
                        inventarisasi, monitoring kondisi, pelaporan kerusakan, hingga pemeliharaan — dalam satu
                        platform digital modern berbasis GIS.
                    </p>
                    <p style="color:#64748b;font-size:13.5px;line-height:1.8;margin-bottom:28px;">
                        Dengan teknologi peta interaktif, pimpinan Dinas dapat memantau kondisi aset secara real-time,
                        sementara masyarakat berpartisipasi aktif menjaga kualitas fasilitas publik melalui fitur
                        pelaporan yang mudah diakses.
                    </p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        @foreach ([['fa-bullseye', '#38bdf8', 'Akurat', 'Data berbasis GPS'], ['fa-bolt', '#22c55e', 'Real-Time', 'Pembaruan langsung'], ['fa-eye', '#a855f7', 'Transparan', 'Akses publik terbuka'], ['fa-rocket', '#f59e0b', 'Efisien', 'Proses terdigitalisasi']] as $p)
                            <div class="about-pillar-row"
                                style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:12px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);">
                                <i class="fas {{ $p[0] }}"
                                    style="color:{{ $p[1] }};font-size:16px;"></i>
                                <div>
                                    <div class="tw" style="color:#fff;font-weight:700;font-size:13px;">
                                        {{ $p[2] }}</div>
                                    <div style="color:#475569;font-size:11px;">{{ $p[3] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ======================================================
     CTA CLOSING
====================================================== --}}
    <section id="cta-sec"
        style="padding:100px 0;background:linear-gradient(140deg,#030812 0%,#081527 35%,#0c1d42 65%,#040c1a 100%);position:relative;overflow:hidden;">
        <div class="grid-bg" style="position:absolute;inset:0;opacity:.28;"></div>
        <div class="orb"
            style="width:500px;height:500px;background:#1a56db;top:-25%;right:-12%;animation-delay:0s;"></div>
        <div class="orb"
            style="width:400px;height:400px;background:#0ea5e9;bottom:-15%;left:-8%;animation-delay:4s;"></div>

        <div style="max-width:800px;margin:0 auto;padding:0 24px;text-align:center;position:relative;z-index:10;"
            class="reveal">
            <div class="sec-badge" style="justify-content:center;"><i class="fas fa-city"
                    style="color:#60a5fa;"></i> Smart City Initiative</div>
            <h2
                style="font-size:clamp(2rem,4.5vw,3.4rem);font-weight:900;color:#fff;line-height:1.1;margin:16px 0 20px;">
                Mari Bersama Menjaga<br>
                <span class="text-gradient-green">Infrastruktur Transportasi Kota</span>
            </h2>
            <p
                style="color:#94a3b8;font-size:15px;line-height:1.75;margin-bottom:36px;max-width:520px;margin-left:auto;margin-right:auto;">
                Setiap laporan Anda adalah kontribusi nyata untuk kota yang lebih baik. Dinas Perhubungan siap
                menindaklanjuti setiap pengaduan yang masuk.
            </p>
            <div style="display:flex;flex-wrap:wrap;gap:14px;justify-content:center;">
                <a href="#peta-aset" class="btn-primary" style="font-size:14px;padding:14px 28px;">
                    <i class="fas fa-map"></i> Lihat Peta Aset
                </a>
                <a href="#form-pengaduan" class="btn-orange" style="font-size:14px;padding:14px 28px;">
                    <i class="fas fa-paper-plane"></i> Buat Pengaduan
                </a>
            </div>
        </div>
    </section>

    {{-- ======================================================
     FOOTER
====================================================== --}}
    <footer id="main-footer" style="background:#030810;border-top:1px solid rgba(255,255,255,.05);">
        <div style="max-width:1280px;margin:0 auto;padding:48px 24px 32px;">
            <div style="display:grid;grid-template-columns:1.5fr 1fr 1fr;gap:40px;margin-bottom:40px;"
                class="grid grid-cols-1 md:grid-cols-3 gap-10">

                <div>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                        <div
                            style="width:38px;height:38px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-map-location-dot" style="color:#fff;font-size:16px;"></i>
                        </div>
                        <div>
                            <div class="tw"
                                style="color:#fff;font-weight:900;font-size:13px;letter-spacing:.1em;text-transform:uppercase;">
                                SIPETA-TRANS</div>
                            <div class="footer-brand-sub" style="color:#334155;font-size:10px;margin-top:2px;">Sistem
                                Informasi Pemetaan Aset Transportasi</div>
                        </div>
                    </div>
                    <p class="footer-brand-desc" style="color:#334155;font-size:12px;line-height:1.7;">Platform GIS
                        digital untuk pengelolaan dan pemantauan aset transportasi Kota Bukittinggi secara terintegrasi
                        dan transparan.</p>
                </div>

                <div>
                    <h5
                        style="color:#fff;font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:.1em;margin-bottom:18px;">
                        Navigasi</h5>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:10px;">
                        @foreach ([['#peta-aset', 'fa-map', 'Peta Aset'], ['#statistik', 'fa-chart-bar', 'Statistik'], ['#fitur', 'fa-star', 'Fitur'], ['#form-pengaduan', 'fa-triangle-exclamation', 'Buat Laporan'], ['#tentang', 'fa-info-circle', 'Tentang']] as $l)
                            <li>
                                <a href="{{ $l[0] }}" class="footer-nav-link"
                                    style="display:inline-flex;align-items:center;gap:8px;color:#334155;font-size:12px;text-decoration:none;transition:color .2s;">
                                    <i class="fas {{ $l[1] }}"
                                        style="color:#1a56db;font-size:11px;width:14px;"></i>{{ $l[2] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h5
                        style="color:#fff;font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:.1em;margin-bottom:18px;">
                        Kontak</h5>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:12px;">
                        @foreach ([['fa-building-columns', 'Dinas Perhubungan Kota Bukittinggi'], ['fa-location-dot', 'Kota Bukittinggi, Sumatera Barat'], ['fa-globe', 'Pemerintah Kota Bukittinggi']] as $c)
                            <li style="display:flex;align-items:flex-start;gap:8px;">
                                <i class="fas {{ $c[0] }}"
                                    style="color:#1a56db;font-size:12px;margin-top:2px;width:14px;flex-shrink:0;"></i>
                                <span class="footer-contact-text"
                                    style="color:#334155;font-size:12px;">{{ $c[1] }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary"
                            style="margin-top:20px;font-size:11px;padding:8px 16px;"><i class="fas fa-th-large"></i>
                            Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost"
                            style="margin-top:20px;font-size:11px;padding:8px 16px;"><i class="fas fa-sign-in-alt"
                                style="color:#60a5fa;"></i> Login Petugas</a>
                    @endauth
                </div>
            </div>

            <div class="footer-copyright-bar"
                style="border-top:1px solid rgba(255,255,255,.04);padding-top:20px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px;">
                <span class="copyright-text" style="color:#1e293b;font-size:11px;">© {{ date('Y') }} SIPETA-TRANS
                    — Dinas Perhubungan Kota Bukittinggi. Hak cipta dilindungi.</span>
                <div class="copyright-text"
                    style="display:flex;align-items:center;gap:6px;font-size:11px;color:#1e293b;">
                    <span
                        style="width:6px;height:6px;border-radius:50%;background:#4ade80;animation:pulse-out 2s ease-out infinite;"></span>
                    Sistem Aktif
                </div>
            </div>
        </div>
    </footer>

    {{-- ======================================================
     SCRIPTS
====================================================== --}}
    <script>
        /* ---- Theme: light is default, dark is opt-in ---- */
        (function() {
            const saved = localStorage.getItem('sipeta-theme');
            if (saved === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();

        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('sipeta-theme', isDark ? 'dark' : 'light');
            document.getElementById('theme-icon').className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            const meta = document.getElementById('meta-theme');
            if (meta) meta.setAttribute('content', isDark ? '#0f1e3d' : '#f0f4ff');
        }
        /* Sync toggle icon on DOMContentLoaded */
        document.addEventListener('DOMContentLoaded', function() {
            const icon = document.getElementById('theme-icon');
            if (icon) icon.className = document.documentElement.classList.contains('dark') ? 'fas fa-sun' :
                'fas fa-moon';
        });

        const assetData = @json($assetData);
        const trayekData = @json($trayeks);

        const STATUS_COLORS = {
            baik: '#22c55e',
            perlu_perbaikan: '#f59e0b',
            rusak: '#ef4444',
            dalam_pemeliharaan: '#a855f7',
        };
        const STATUS_LABELS = {
            baik: 'Baik',
            perlu_perbaikan: 'Perlu Perbaikan',
            rusak: 'Rusak',
            dalam_pemeliharaan: 'Dalam Pemeliharaan',
        };

        // ---- Marker icon factory ----
        function makeIcon(color, faIcon) {
            return L.divIcon({
                html: `<div style="position:relative;width:34px;height:34px;">
            <div style="position:absolute;inset:-5px;border-radius:50%;background:${color}35;animation:pulse-out 2.2s ease-out infinite;"></div>
            <div style="width:34px;height:34px;border-radius:50%;background:${color};display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;border:2.5px solid rgba(255,255,255,.55);box-shadow:0 0 18px ${color}70,0 3px 10px rgba(0,0,0,.45);">
                <i class="fas ${faIcon}"></i>
            </div>
        </div>`,
                iconSize: [34, 34],
                iconAnchor: [17, 17],
                popupAnchor: [0, -20],
                className: '',
            });
        }

        // ---- Cluster factory ----
        function makeCluster() {
            return L.markerClusterGroup({
                maxClusterRadius: 60,
                iconCreateFunction(c) {
                    const n = c.getChildCount();
                    return L.divIcon({
                        html: `<div style="background:rgba(26,86,219,.82);backdrop-filter:blur(8px);border-radius:50%;width:42px;height:42px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:14px;border:2px solid rgba(255,255,255,.28);box-shadow:0 0 24px rgba(26,86,219,.55);">${n}</div>`,
                        iconSize: [42, 42],
                        className: '',
                    });
                },
            });
        }

        // ---- Popup html ----
        function makePopup(a) {
            const color = STATUS_COLORS[a.status] || '#3b82f6';
            const label = STATUS_LABELS[a.status] || a.status;
            const isDark = document.documentElement.classList.contains('dark');
            const tp = isDark ? '#f1f5f9' : '#0f172a';
            const ts = isDark ? '#94a3b8' : '#475569';
            const tdiv = isDark ? 'rgba(255,255,255,.07)' : 'rgba(26,86,219,.08)';
            return `<div style="min-width:240px;font-family:'Segoe UI',sans-serif;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
            <div style="width:38px;height:38px;border-radius:10px;background:${color}1e;border:1px solid ${color}35;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas ${a.icon||'fa-cube'}" style="color:${color};font-size:16px;"></i>
            </div>
            <div>
                <div style="font-weight:800;font-size:14px;color:${tp};line-height:1.2;">${a.name}</div>
                <div style="color:${ts};font-size:11px;margin-top:2px;">${a.type||'Aset'}</div>
            </div>
        </div>
        <div style="height:1px;background:${tdiv};margin:8px 0;"></div>
        <div style="font-size:12px;color:${ts};margin-bottom:8px;display:flex;align-items:flex-start;gap:6px;">
            <i class="fas fa-location-dot" style="color:${color};margin-top:2px;flex-shrink:0;"></i>
            ${a.location||'Lokasi tidak tersedia'}
        </div>
        <div style="font-size:12px;color:${ts};">
            Kondisi:&nbsp;
            <span style="padding:2px 10px;border-radius:999px;background:${color}1e;color:${color};font-weight:700;font-size:11px;">${label}</span>
        </div>
    </div>`;
        }

        // ---- Base map builder ----
        function buildMap(id) {
            const m = L.map(id, {
                center: [-0.3050688169603624, 100.3694228690046],
                zoom: 13,
                zoomControl: false,
            });
            L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '© Google Maps',
                maxZoom: 20,
            }).addTo(m);
            return m;
        }

        // ---- Hero map ----
        (function() {
            const hmap = buildMap('hero-map');
            L.control.zoom({
                position: 'bottomright'
            }).addTo(hmap);
            const cluster = makeCluster();
            const pts = [];

            assetData.forEach(a => {
                if (!a.coordinates) return;
                let mk;
                if (a.geometry === 'polygon') {
                    mk = L.polygon(a.coordinates, { color: a.color || '#3b82f6', weight: 2, fillColor: a.color || '#3b82f6', fillOpacity: 0.4 });
                    a.coordinates.forEach(c => pts.push(c));
                } else if (a.geometry === 'polyline') {
                    mk = L.polyline(a.coordinates, { color: a.color || '#3b82f6', weight: 3 });
                    a.coordinates.forEach(c => pts.push(c));
                } else {
                    let lat = a.coordinates[0];
                    let lng = a.coordinates[1];
                    mk = L.marker([lat, lng], {
                        icon: makeIcon(STATUS_COLORS[a.status] || '#3b82f6', a.icon || 'fa-cube'),
                        title: a.name,
                    });
                    pts.push([lat, lng]);
                }
                
                mk.bindPopup(makePopup(a), {
                    className: 'custom-popup',
                    maxWidth: 280
                });
                cluster.addLayer(mk);
            });

            hmap.addLayer(cluster);
            
            // Draw all trayek lines in hero map
            trayekData.forEach((trayek, index) => {
                if (!trayek.coordinate || !Array.isArray(trayek.coordinate)) return;
                const color = getTrayekColor(index);
                const polyline = L.polyline(trayek.coordinate, {
                    color: color,
                    weight: 4,
                    opacity: 0.7,
                    dashArray: '8, 8',
                    lineJoin: 'round'
                });
                polyline.bindPopup(`
                    <div style="font-family:'Segoe UI',sans-serif;font-size:13px;font-weight:700;">
                        ${trayek.name}
                    </div>
                `, {className: 'custom-popup'});
                polyline.addTo(hmap);
                trayek.coordinate.forEach(coord => pts.push(coord));
            });

            if (pts.length) hmap.fitBounds(pts, {
                padding: [20, 20]
            });
        })();

        // ---- Main map with filter ----
        let mainMap, mainCluster, allMarkers = [];
        let trayekLayers = [];
        let mapMode = 'aset';

        (function() {
            mainMap = buildMap('main-map');
            L.control.zoom({
                position: 'topright'
            }).addTo(mainMap);
            L.control.scale({
                imperial: false
            }).addTo(mainMap);
            mainCluster = makeCluster();

            assetData.forEach(a => {
                if (!a.coordinates) return;
                let mk;
                if (a.geometry === 'polygon') {
                    mk = L.polygon(a.coordinates, { color: a.color || '#3b82f6', weight: 3, fillColor: a.color || '#3b82f6', fillOpacity: 0.4 });
                } else if (a.geometry === 'polyline') {
                    mk = L.polyline(a.coordinates, { color: a.color || '#3b82f6', weight: 4 });
                } else {
                    let lat = a.coordinates[0];
                    let lng = a.coordinates[1];
                    mk = L.marker([lat, lng], {
                        icon: makeIcon(STATUS_COLORS[a.status] || '#3b82f6', a.icon || 'fa-cube'),
                        title: a.name,
                    });
                }
                mk.bindPopup(makePopup(a), {
                    className: 'custom-popup',
                    maxWidth: 280
                });
                mk._assetData = a;
                allMarkers.push(mk);
            });

            renderMapData();
        })();

        function toggleMapMode() {
            mapMode = document.getElementById('filter-mode').value;
            if (mapMode === 'aset') {
                document.getElementById('aset-filters').style.display = 'flex';
                document.getElementById('trayek-filters').style.display = 'none';
            } else {
                document.getElementById('aset-filters').style.display = 'none';
                document.getElementById('trayek-filters').style.display = 'block';
            }
            renderMapData();
        }

        // Generate dynamic distinct color from index
        function getTrayekColor(index) {
            const hue = (index * 137.508) % 360; 
            return `hsl(${hue}, 80%, 55%)`;
        }

        function renderMapData() {
            mainCluster.clearLayers();
            trayekLayers.forEach(layer => mainMap.removeLayer(layer));
            trayekLayers = [];
            
            if (mapMode === 'aset') {
                filterMarkers();
            } else if (mapMode === 'trayek') {
                filterTrayeks();
            }
        }

        function filterTrayeks() {
            const selectedTrayekId = document.getElementById('filter-trayek').value;
            let n = 0;
            const pts = [];

            trayekData.forEach((trayek, index) => {
                if (selectedTrayekId && trayek.id.toString() !== selectedTrayekId) return;
                if (!trayek.coordinate || !Array.isArray(trayek.coordinate)) return;
                
                const color = getTrayekColor(index);
                const polyline = L.polyline(trayek.coordinate, {
                    color: color,
                    weight: 6,
                    opacity: 0.8,
                    dashArray: '10, 10',
                    lineJoin: 'round'
                });
                
                polyline.bindPopup(`
                    <div style="min-width:200px;font-family:'Segoe UI',sans-serif;">
                        <div style="font-weight:800;font-size:14px;color:#fff;background:${color};padding:8px 12px;border-radius:6px;margin-bottom:8px;">
                            ${trayek.name}
                        </div>
                        <div style="font-size:12px;color:#94a3b8;">
                            Jarak: <strong style="color:#e2e8f0;">${trayek.distance} km</strong>
                        </div>
                    </div>
                `, {
                    className: 'custom-popup'
                });

                trayekLayers.push(polyline);
                polyline.addTo(mainMap);
                trayek.coordinate.forEach(coord => pts.push(coord));
                n++;
            });

            document.getElementById('visible-count').textContent = n;
            if (pts.length > 0) {
                mainMap.fitBounds(pts, { padding: [40, 40] });
            }
        }

        function filterMarkers() {
            const status = document.getElementById('filter-status').value;
            const type = document.getElementById('filter-type').value;
            
            let n = 0;
            const pts = [];

            allMarkers.forEach(mk => {
                const a = mk._assetData;
                const ok = (!status || a.status === status) &&
                    (!type || (a.type || '') === type);
                if (ok) {
                    mainCluster.addLayer(mk);
                    if (a.geometry === 'polygon' || a.geometry === 'polyline') {
                        if(Array.isArray(a.coordinates)) a.coordinates.forEach(c => pts.push(c));
                    } else if (Array.isArray(a.coordinates) && a.coordinates.length >= 2) {
                        pts.push([a.coordinates[0], a.coordinates[1]]);
                    }
                    n++;
                }
            });
            
            mainMap.addLayer(mainCluster);
            document.getElementById('visible-count').textContent = n;
            
            if (pts.length > 0) {
                mainMap.fitBounds(pts, { padding: [40, 40] });
            }
        }

        function resetMapFilter() {
            ['filter-status', 'filter-type', 'filter-trayek'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            
            // Map mode reset
            const modeEl = document.getElementById('filter-mode');
            if (modeEl) {
                modeEl.value = 'aset';
                toggleMapMode();
            } else {
                renderMapData();
            }
        }

        // ---- Upload handlers ----
        function handleFileChange(input) {
            if (!input.files || !input.files[0]) return;
            applyFile(input.files[0]);
        }

        function handleDrop(e) {
            e.preventDefault();
            document.getElementById('upload-zone').classList.remove('drag-over');
            const f = e.dataTransfer.files[0];
            if (!f) return;
            document.getElementById('foto-input').files = e.dataTransfer.files;
            applyFile(f);
        }

        function applyFile(file) {
            const zone = document.getElementById('upload-zone');
            zone.classList.add('has-file');
            document.getElementById('upload-icon').style.color = '#22c55e';
            document.getElementById('upload-text').textContent = file.name;
            document.getElementById('upload-hint').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('img-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }

        function clearFile() {
            document.getElementById('foto-input').value = '';
            const zone = document.getElementById('upload-zone');
            zone.classList.remove('has-file');
            document.getElementById('upload-icon').style.color = '#334155';
            document.getElementById('upload-text').textContent = 'Klik atau drag & drop foto di sini';
            document.getElementById('upload-hint').textContent = 'JPG, PNG — maksimal 5 MB';
            document.getElementById('img-preview').style.display = 'none';
        }

        // ---- Navbar scroll ----
        window.addEventListener('scroll', () => {
            document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
        });

        // ---- Scroll reveal ----
        const revealObs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) e.target.classList.add('visible');
            });
        }, {
            threshold: 0.1
        });
        document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

        // ---- Animated counters ----
        function animateCount(el) {
            const target = parseInt(el.dataset.target) || 0;
            if (!target) {
                el.textContent = 0;
                return;
            }
            const dur = 1600;
            const t0 = performance.now();

            function tick(now) {
                const p = Math.min((now - t0) / dur, 1);
                const ease = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(ease * target);
                if (p < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        }
        const cntObs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    animateCount(e.target);
                    cntObs.unobserve(e.target);
                }
            });
        }, {
            threshold: .5
        });
        document.querySelectorAll('.count-up').forEach(el => cntObs.observe(el));

        // ---- Toast ----
        @if (session('success'))
            (function() {
                const t = document.getElementById('toast');
                document.getElementById('toast-msg').textContent = "{{ session('success') }}";
                t.classList.add('show');
                setTimeout(() => t.classList.remove('show'), 5000);
            })();
        @endif
    </script>

</body>

</html>
