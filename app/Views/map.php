<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>ELERA — Peta Fasilitas Umum | Eksplorasi Lokasi</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        :root {
            --bg: #fefcf5;
            --surface: #ffffff;
            --border: #e9e4dc;
            --accent-green: #2b7a4b;    /* puskesmas */
            --accent-orange: #d68c3c;   /* taman & aksen */
            --accent-red: #c44536;      /* damkar */
            --text-dark: #1e2a2f;
            --text-muted: #6c7a76;
            --shadow-sm: 0 8px 20px rgba(0,0,0,0.05);
            --shadow-md: 0 12px 28px rgba(0,0,0,0.08);
            --glass-bg: rgba(255, 252, 245, 0.92);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg);
            color: var(--text-dark);
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Navbar */
        .navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 0.8rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            z-index: 1000;
            flex-shrink: 0;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            text-decoration: none;
            background: linear-gradient(120deg, var(--accent-green), var(--accent-orange));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .nav-links {
            display: flex;
            gap: 1.8rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .nav-links a {
            font-size: 0.85rem;
            font-weight: 500;
            color: #3a4a45;
            text-decoration: none;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--accent-green); }
        .btn-nav {
            background: var(--accent-green);
            color: white !important;
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(43,122,75,0.2);
        }
        .btn-nav:hover { background: #1f5f3a; transform: translateY(-2px); }

        /* Filter bar */
        .filter-bar {
            background: white;
            padding: 0.9rem 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 1.2rem;
            flex-wrap: wrap;
            flex-shrink: 0;
            box-shadow: var(--shadow-sm);
            z-index: 999;
        }
        .filter-group {
            display: flex;
            gap: 0.7rem;
            flex-wrap: wrap;
            flex: 1;
            align-items: center;
        }
        
        /* Select Kecamatan Dropdown */
        .kecamatan-select-wrapper {
            position: relative;
            margin-right: 1rem;
        }
        .kecamatan-select {
            appearance: none;
            background: #f3f0ea;
            border: 1px solid var(--border);
            padding: 0.5rem 2rem 0.5rem 1rem;
            border-radius: 60px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--text-dark);
            cursor: pointer;
            outline: none;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .kecamatan-select:hover, .kecamatan-select:focus {
            background: #e6e0d7;
            border-color: var(--accent-green);
        }
        .kecamatan-select-wrapper::after {
            content: '\f107'; /* FontAwesome angle-down */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .filter-btn {
            padding: 0.5rem 1.2rem;
            border-radius: 60px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 0.8rem;
            border: none;
            background: #f3f0ea;
            color: #5e6e69;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .filter-btn:hover { background: #e6e0d7; transform: translateY(-1px); }
        .filter-btn.active-all { background: var(--text-dark); color: white; }
        .filter-btn.active-puskesmas { background: var(--accent-green); color: white; }
        .filter-btn.active-damkar { background: var(--accent-red); color: white; }
        .filter-btn.active-taman { background: var(--accent-orange); color: white; }
        
        .count-badge {
            background: rgba(255,255,255,0.25);
            padding: 2px 8px;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .btn-back {
            background: transparent;
            border: 1px solid var(--border);
            padding: 0.4rem 1rem;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s;
        }
        .btn-back:hover { background: #f0ece5; border-color: var(--accent-green); color: var(--accent-green); }

        /* Main area: sidebar + peta */
        .main {
            display: flex;
            flex: 1;
            overflow: hidden;
            padding: 0 0.5rem 0.5rem 0.5rem;
            gap: 0.5rem;
            position: relative;
        }

        /* Sidebar Kiri */
        .sidebar {
            width: 340px;
            background: white;
            border-radius: 28px;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid var(--border);
            z-index: 10;
        }
        .sidebar-header {
            padding: 1.2rem;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-header h2 {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-dark);
            margin-bottom: 0.8rem;
        }
        .search-box {
            display: flex;
            align-items: center;
            background: #f8f6f2;
            border: 1px solid var(--border);
            border-radius: 60px;
            padding: 0.5rem 1rem;
            margin-top: 0.2rem;
        }
        .search-box i { color: var(--text-muted); font-size: 0.85rem; }
        .search-box input {
            background: transparent;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            width: 100%;
            padding: 0 0.6rem;
            outline: none;
            color: var(--text-dark);
        }
        .search-box input::placeholder { color: #b9b0a4; }

        .sidebar-list {
            flex: 1;
            overflow-y: auto;
            padding: 0.8rem;
        }
        .sidebar-list::-webkit-scrollbar { width: 4px; }
        .sidebar-list::-webkit-scrollbar-track { background: transparent; }
        .sidebar-list::-webkit-scrollbar-thumb { background: #e0dcd3; border-radius: 4px; }
        .sidebar-list::-webkit-scrollbar-thumb:hover { background: #c0b7a8; }

        .list-item {
            padding: 0.9rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            margin-bottom: 0.6rem;
            transition: all 0.2s;
            background: #fefcf8;
            border: 1px solid var(--border);
        }
        .list-item:hover {
            background: #f6f2eb;
            border-color: #d1cbc1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
        .list-item.active {
            background: rgba(43,122,75,0.04);
            border-color: var(--accent-green);
        }
        .list-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.3rem;
        }
        .list-item-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--text-dark);
            line-height: 1.3;
            flex: 1;
        }
        .list-item-rating {
            font-size: 0.75rem;
            font-weight: 600;
            color: #d68c3c;
            display: flex;
            align-items: center;
            gap: 0.2rem;
            background: rgba(214,140,60,0.1);
            padding: 2px 6px;
            border-radius: 8px;
        }
        .list-item-meta {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.4rem;
        }
        .badge-jenis {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
        }
        .badge-puskesmas { background: rgba(43,122,75,0.1); color: var(--accent-green); }
        .badge-damkar { background: rgba(196,69,54,0.1); color: var(--accent-red); }
        .badge-taman { background: rgba(214,140,60,0.1); color: var(--accent-orange); }

        .state-msg {
            text-align: center;
            padding: 2rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Map container */
        .map-container {
            flex: 1;
            position: relative;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
        }
        #map {
            width: 100%;
            height: 100%;
            background: #eae5dd;
            z-index: 1;
        }

        /* Panel Slide Kanan (Detail Panel) */
        .detail-panel {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 400px;
            background: var(--surface);
            box-shadow: -4px 0 24px rgba(0,0,0,0.08);
            z-index: 1000;
            transform: translateX(105%);
            transition: transform 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
            display: flex;
            flex-direction: column;
            border-left: 1px solid var(--border);
        }
        .detail-panel.open {
            transform: translateX(0);
        }
        
        .dp-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            position: relative;
        }
        .btn-close-panel {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #f3f0ea;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.2s;
        }
        .btn-close-panel:hover { background: #e6e0d7; color: var(--text-dark); }
        
        .dp-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            padding-right: 2rem;
            line-height: 1.3;
        }
        .dp-meta {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            flex-wrap: wrap;
        }
        .dp-rating-summary {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .dp-rating-summary i { color: #f5b041; }
        .dp-rating-count { color: var(--text-muted); font-weight: 400; }

        /* Tabs */
        .dp-tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
            background: #faf8f5;
        }
        .dp-tab {
            flex: 1;
            text-align: center;
            padding: 0.8rem 0;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }
        .dp-tab:hover { color: var(--text-dark); background: #f3f0ea; }
        .dp-tab.active {
            color: var(--accent-green);
            border-bottom-color: var(--accent-green);
            background: white;
        }

        /* Tab Content Area */
        .dp-body {
            flex: 1;
            overflow-y: auto;
            position: relative;
        }
        .dp-content {
            padding: 1.5rem;
            display: none;
            animation: fadeIn 0.3s ease;
        }
        .dp-content.active { display: block; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* Overview Tab */
        .dp-img-container {
            width: 100%;
            height: 200px;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            background: #eae5dd;
            border: 1px solid var(--border);
        }
        .dp-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .dp-info-list {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }
        .dp-info-item {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }
        .dp-info-icon {
            width: 24px;
            color: var(--accent-green);
            font-size: 1.1rem;
            margin-top: 0.1rem;
            text-align: center;
        }
        .dp-info-text h4 {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.2rem;
            letter-spacing: 0.05em;
        }
        .dp-info-text p {
            font-size: 0.9rem;
            color: var(--text-dark);
            line-height: 1.5;
        }
        .dp-desc {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.6;
        }
        .btn-rute {
            display: block;
            width: 100%;
            padding: 0.8rem;
            background: #f3f0ea;
            border: 1px solid var(--border);
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            color: var(--text-dark);
            text-decoration: none;
            margin-top: 1.5rem;
            transition: all 0.2s;
        }
        .btn-rute:hover {
            background: var(--accent-green);
            color: white;
            border-color: var(--accent-green);
        }

        /* Ulasan Tab */
        .rating-form {
            background: #faf8f5;
            padding: 1.2rem;
            border-radius: 16px;
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }
        .rating-form h3 {
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .form-group { margin-bottom: 1rem; }
        .form-control {
            width: 100%;
            padding: 0.6rem 0.8rem;
            border: 1px solid #d1cbc1;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            outline: none;
        }
        .form-control:focus { border-color: var(--accent-green); box-shadow: 0 0 0 3px rgba(43,122,75,0.1); }
        
        .star-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 0.3rem;
        }
        .star-input input { display: none; }
        .star-input label {
            color: #d1cbc1;
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.2s;
        }
        .star-input label:hover,
        .star-input label:hover ~ label,
        .star-input input:checked ~ label {
            color: #f5b041;
        }

        .btn-submit {
            width: 100%;
            padding: 0.7rem;
            background: var(--text-dark);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-submit:hover { background: black; }
        .btn-submit:disabled { background: #b9b0a4; cursor: not-allowed; }

        /* List Ulasan */
        .ulasan-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .ulasan-item {
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .ulasan-item:last-child { border-bottom: none; }
        .ulasan-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.3rem;
        }
        .ulasan-author { font-weight: 700; font-size: 0.85rem; color: var(--text-dark); }
        .ulasan-date { font-size: 0.7rem; color: var(--text-muted); }
        .ulasan-stars { color: #f5b041; font-size: 0.7rem; margin-bottom: 0.5rem; }
        .ulasan-stars i.fa-star { color: #f5b041; }
        .ulasan-stars i.fa-star.empty { color: #e9e4dc; }
        .ulasan-text { font-size: 0.85rem; color: var(--text-dark); line-height: 1.5; }
        .ulasan-platform {
            font-size: 0.65rem;
            color: var(--text-muted);
            margin-top: 0.4rem;
            display: inline-block;
            background: #f3f0ea;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Leaflet popup custom */
        .leaflet-popup-content-wrapper {
            background: white !important;
            border-radius: 16px !important;
            box-shadow: var(--shadow-md) !important;
            border: 1px solid var(--border);
            font-family: 'Inter', sans-serif;
            padding: 0;
        }
        .leaflet-popup-tip { background: white !important; }
        .leaflet-popup-content { margin: 0; width: 220px !important; }
        .popup-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
        }
        .popup-name {
            font-weight: 800;
            font-size: 0.95rem;
            margin-bottom: 0.2rem;
            color: var(--text-dark);
            line-height: 1.3;
        }
        .popup-body { padding: 12px 16px; }
        .popup-row {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
            display: flex;
            gap: 0.4rem;
        }
        .popup-row i { color: var(--accent-green); width: 14px; text-align: center; }

        /* Floating Map Controls */
        .map-controls {
            position: absolute;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            z-index: 1000;
        }
        .control-btn {
            width: 48px;
            height: 48px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
            cursor: pointer;
            transition: all 0.2s;
            color: var(--text-dark);
            font-size: 1.2rem;
        }
        .control-btn:hover {
            background: #f3f0ea;
            transform: scale(1.05);
            color: var(--accent-green);
        }
        .control-btn.active {
            background: var(--accent-green);
            color: white;
            border-color: var(--accent-green);
        }

        /* Routing Info Overlay */
        .routing-info-bar {
            position: absolute;
            top: 1.5rem;
            left: 50%;
            transform: translateX(-50%);
            background: var(--text-dark);
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 60px;
            box-shadow: var(--shadow-md);
            z-index: 1001;
            display: none;
            align-items: center;
            gap: 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .routing-info-bar.active { display: flex; }
        .btn-clear-route {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-clear-route:hover { background: rgba(255,50,50,0.8); }
        .route-dest { color: #f5b041; }
        .btn-popup-detail {
            display: block;
            width: 100%;
            padding: 0.5rem;
            text-align: center;
            background: #f3f0ea;
            color: var(--text-dark);
            border: none;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
            cursor: pointer;
        }
        .btn-popup-detail:hover { background: #e6e0d7; }

        .toast {
            position: fixed;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%);
            background: #1e2a2f;
            color: #f5e7d9;
            padding: 0.8rem 1.5rem;
            border-radius: 60px;
            font-size: 0.85rem;
            z-index: 9999;
            display: none;
            backdrop-filter: blur(8px);
            font-family: 'Inter', sans-serif;
            box-shadow: var(--shadow-md);
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar { padding: 0.8rem 1rem; }
            .filter-bar { padding: 0.8rem 1rem; flex-direction: column; align-items: stretch; }
            .filter-group { overflow-x: auto; flex-wrap: nowrap; padding-bottom: 0.5rem; }
            .btn-back { align-self: flex-start; }
            
            .main { flex-direction: column; padding: 0; gap: 0; }
            .sidebar { width: 100%; max-height: 40vh; border-radius: 24px 24px 0 0; border-bottom: none; order: 2; z-index: 1001; box-shadow: 0 -4px 20px rgba(0,0,0,0.1); }
            .map-container { border-radius: 0; border: none; order: 1; }
            
            .detail-panel {
                width: 100%;
                border-left: none;
                z-index: 1002; /* di atas sidebar */
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">ELERA</div>
    <div class="nav-links">
        <a href="/">Beranda</a>
        <a href="#" style="color: var(--accent-green); font-weight: 600;">Peta</a>
        <a href="#fasilitas">Fasilitas</a>
        <a href="#about">Tentang</a>
        <a href="/" class="btn-nav"><i class="fas fa-map-pin"></i> Eksplorasi</a>
    </div>
</div>

<!-- Filter bar -->
<div class="filter-bar">
    <div class="filter-group" id="filterGroup">
        <div class="kecamatan-select-wrapper">
            <select class="kecamatan-select" id="kecamatanSelect" onchange="filterKecamatan()">
                <option value="">Semua Kecamatan</option>
                <!-- Opsi akan diisi via JS -->
            </select>
        </div>

        <button class="filter-btn active-all" data-filter="all" onclick="setFilter('all')">
            <i class="fas fa-globe"></i> Semua <span class="count-badge" id="count-all">0</span>
        </button>
        <button class="filter-btn" data-filter="puskesmas" onclick="setFilter('puskesmas')">
            <i class="fas fa-hospital"></i> Puskesmas <span class="count-badge" id="count-puskesmas">0</span>
        </button>
        <button class="filter-btn" data-filter="damkar" onclick="setFilter('damkar')">
            <i class="fas fa-fire-extinguisher"></i> Damkar <span class="count-badge" id="count-damkar">0</span>
        </button>
        <button class="filter-btn" data-filter="taman" onclick="setFilter('taman')">
            <i class="fas fa-tree"></i> Taman Kota <span class="count-badge" id="count-taman">0</span>
        </button>
    </div>
    <a href="/" class="btn-back"><i class="fas fa-arrow-left"></i> Beranda</a>
</div>

<div class="main">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-list-ul"></i> Daftar Fasilitas</h2>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama fasilitas..." onkeyup="filterSearch()">
            </div>
        </div>
        <div class="sidebar-list" id="sidebar-list">
            <div class="state-msg">
                <i class="fas fa-spinner fa-pulse"></i> Memuat data...
            </div>
        </div>
    </div>

    <!-- Map Area -->
    <div class="map-container">
        <div id="map"></div>

        <!-- Routing Info Bar -->
        <div class="routing-info-bar" id="routingInfo">
            <span><i class="fas fa-directions"></i> Menuju <span class="route-dest" id="routeDestName">Tujuan</span></span>
            <span id="routeStats"><i class="fas fa-road"></i> -- km</span>
            <button class="btn-clear-route" onclick="clearRoute()" title="Hapus Rute"><i class="fas fa-times"></i></button>
        </div>

        <!-- Floating Controls -->
        <div class="map-controls">
            <button class="control-btn" id="btnLocate" onclick="toggleLocation()" title="Deteksi Lokasi Saya">
                <i class="fas fa-location-crosshairs"></i>
            </button>
        </div>

        <!-- Detail Panel (Slide Kanan) -->
        <div class="detail-panel" id="detailPanel">
            <div class="dp-header">
                <button class="btn-close-panel" onclick="closeDetailPanel()"><i class="fas fa-times"></i></button>
                <div class="dp-title" id="dpName">Nama Fasilitas</div>
                <div class="dp-meta">
                    <span class="badge-jenis badge-puskesmas" id="dpJenis"><i class="fas fa-hospital"></i> Puskesmas</span>
                    <div class="dp-rating-summary">
                        <i class="fas fa-star"></i> <span id="dpAvgRating">0.0</span> 
                        <span class="dp-rating-count">(<span id="dpTotalUlasan">0</span> ulasan)</span>
                    </div>
                </div>
            </div>
            
            <div class="dp-tabs">
                <div class="dp-tab active" onclick="switchTab('overview', this)">Overview</div>
                <div class="dp-tab" onclick="switchTab('ulasan', this)">Ulasan & Rating</div>
            </div>

            <div class="dp-body">
                <!-- Tab Overview -->
                <div class="dp-content active" id="tab-overview">
                    <div class="dp-img-container">
                        <img id="dpFoto" src="" alt="Foto Fasilitas" onerror="this.src='https://placehold.co/400x200?text=Tidak+Ada+Foto'">
                    </div>
                    
                    <div class="dp-info-list">
                        <div class="dp-info-item">
                            <div class="dp-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="dp-info-text">
                                <h4>Alamat</h4>
                                <p id="dpAlamat">-</p>
                                <p style="font-size:0.8rem; color:var(--text-muted); margin-top:0.2rem;">Kec. <span id="dpKecamatan">-</span></p>
                            </div>
                        </div>
                        <div class="dp-info-item">
                            <div class="dp-info-icon"><i class="fas fa-clock"></i></div>
                            <div class="dp-info-text">
                                <h4>Jam Operasional</h4>
                                <p id="dpJam">-</p>
                            </div>
                        </div>
                        <div class="dp-info-item">
                            <div class="dp-info-icon"><i class="fas fa-phone"></i></div>
                            <div class="dp-info-text">
                                <h4>Telepon</h4>
                                <p id="dpTelepon">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="dp-desc" id="dpDesc">
                        Tidak ada deskripsi.
                    </div>

                    <button class="btn-rute" id="btnRute" onclick="startRoutingFromPanel()">
                <i class="fas fa-route"></i> Lihat Rute Sekarang
            </button>
                </div>

                <!-- Tab Ulasan -->
                <div class="dp-content" id="tab-ulasan">
                    <div class="rating-form">
                        <h3>Tulis Ulasan Anda</h3>
                        <form id="formUlasan" onsubmit="submitUlasan(event)">
                            <div class="form-group">
                                <input type="text" id="inputNama" class="form-control" placeholder="Nama Anda" required minlength="2" maxlength="100">
                            </div>
                            <div class="form-group">
                                <div class="star-input">
                                    <input type="radio" id="star5" name="rating" value="5" required/><label for="star5" class="fas fa-star"></label>
                                    <input type="radio" id="star4" name="rating" value="4"/><label for="star4" class="fas fa-star"></label>
                                    <input type="radio" id="star3" name="rating" value="3"/><label for="star3" class="fas fa-star"></label>
                                    <input type="radio" id="star2" name="rating" value="2"/><label for="star2" class="fas fa-star"></label>
                                    <input type="radio" id="star1" name="rating" value="1"/><label for="star1" class="fas fa-star"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea id="inputKomentar" class="form-control" rows="3" placeholder="Bagaimana pengalaman Anda? (Opsional)"></textarea>
                            </div>
                            <button type="submit" class="btn-submit" id="btnSubmitUlasan">Kirim Ulasan</button>
                        </form>
                    </div>

                    <div class="ulasan-list" id="listUlasan">
                        <!-- List ulasan via JS -->
                        <div class="state-msg">Memuat ulasan...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // ─────────────────────────────────────────────
    // KONFIGURASI API & TEMA
    // ─────────────────────────────────────────────
    const API_BASE = '<?= rtrim(base_url(), "/") ?>';
    const API_FASILITAS = `${API_BASE}/api/fasilitas`;
    const API_KECAMATAN = `${API_BASE}/api/kecamatan`;

    const JENIS_LABEL = {
        puskesmas: 'Puskesmas',
        damkar:    'Pemadam Kebakaran',
        taman:     'Taman Kota',
    };

    const THEME = {
        puskesmas: { color: '#2b7a4b', icon: 'fa-hospital' },
        damkar:    { color: '#c44536', icon: 'fa-fire-extinguisher' },
        taman:     { color: '#d68c3c', icon: 'fa-tree' },
        polygon:   { color: '#2b7a4b', fillColor: '#2b7a4b', fillOpacity: 0.08, weight: 1.5 },
        polygonHover: { fillOpacity: 0.15, weight: 2 }
    };

    // ─────────────────────────────────────────────
    // STATE APLIKASI
    // ─────────────────────────────────────────────
    let map;
    let allData = [];          // Data fasilitas lengkap
    let allKecamatan = [];     // Data kecamatan lengkap + polygon
    let markers = {};          // { id: L.marker }
    let polygonLayers = {};    // { kecamatan_id: L.polygon }
    
    let routeLayer = null;
    let userMarker = null;

    let state = {
        filterJenis: 'all',
        filterKecamatan: '',
        searchQuery: '',
        activeFasilitasId: null,
        activeKecamatanId: null,
        userLocation: null,
        isLocating: false
    };

    // ─────────────────────────────────────────────
    // INISIALISASI PETA
    // ─────────────────────────────────────────────
    function initMap() {
        // Peta terfokus di tengah Kota Medan
        map = L.map('map', { zoomControl: false }).setView([3.5952, 98.6722], 13);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OSM &copy; CARTO',
            maxZoom: 19, minZoom: 11
        }).addTo(map);
    }

    function createCustomIcon(jenis) {
        const t = THEME[jenis] || { color: '#6c7a76' };
        const svg = `
            <svg width="28" height="36" viewBox="0 0 28 36" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 0C6.27 0 0 6.27 0 14c0 9.5 14 22 14 22s14-12.5 14-22c0-7.73-6.27-14-14-14z" fill="${t.color}" stroke="#ffffff" stroke-width="1.5"/>
                <circle cx="14" cy="14" r="5" fill="white" opacity="0.9"/>
                <circle cx="14" cy="14" r="2.5" fill="${t.color}"/>
            </svg>`;
        return L.divIcon({
            html: svg,
            className: '',
            iconSize: [28, 36],
            iconAnchor: [14, 36],
            popupAnchor: [0, -28],
        });
    }

    // ─────────────────────────────────────────────
    // FETCH DATA
    // ─────────────────────────────────────────────
    async function loadData() {
        try {
            // Fetch Kecamatan & Fasilitas secara paralel
            const [resKec, resFas] = await Promise.all([
                fetch(API_KECAMATAN),
                fetch(API_FASILITAS)
            ]);

            if (!resKec.ok) throw new Error('Gagal load kecamatan');
            if (!resFas.ok) throw new Error('Gagal load fasilitas');

            const jsonKec = await resKec.json();
            const jsonFas = await resFas.json();

            allKecamatan = jsonKec.data || [];
            allData = jsonFas.data || [];

            renderKecamatanOptions();
            renderPolygons();
            renderAll();
        } catch (err) {
            showToast('Error: ' + err.message);
            document.getElementById('sidebar-list').innerHTML = `<div class="state-msg"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data.</div>`;
        }
    }

    // ─────────────────────────────────────────────
    // RENDER KECAMATAN & POLYGON
    // ─────────────────────────────────────────────
    function renderKecamatanOptions() {
        const select = document.getElementById('kecamatanSelect');
        allKecamatan.forEach(k => {
            const opt = document.createElement('option');
            opt.value = k.id;
            opt.textContent = k.nama;
            select.appendChild(opt);
        });
    }

    function renderPolygons() {
        // Hapus layer lama jika ada
        Object.values(polygonLayers).forEach(layer => map.removeLayer(layer));
        polygonLayers = {};

        allKecamatan.forEach(k => {
            if (k.geojson && k.geojson.type === "Polygon") {
                // GeoJSON dari array biasanya [lng, lat], Leaflet butuh [lat, lng]
                // Layer GeoJSON otomatis handle ini
                const layer = L.geoJSON(k.geojson, {
                    style: THEME.polygon
                });
                
                // Simpan reference
                polygonLayers[k.id] = layer;
                
                // Hanya tampilkan jika state activeKecamatanId cocok
                if (state.activeKecamatanId == k.id) {
                    layer.addTo(map);
                    layer.setStyle(THEME.polygonHover);
                }
            }
        });
    }

    // ─────────────────────────────────────────────
    // FILTER & SEARCH HANDLERS
    // ─────────────────────────────────────────────
    function setFilter(jenis) {
        state.filterJenis = jenis;
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.className = 'filter-btn'; // reset
            if (btn.dataset.filter === jenis) btn.classList.add(`active-${jenis}`);
        });
        renderAll();
    }

    function filterKecamatan() {
        state.activeKecamatanId = document.getElementById('kecamatanSelect').value;
        renderPolygons(); // Update tampilan polygon
        renderAll();
        
        // Fit bounds to polygon jika kecamatan dipilih
        if (state.activeKecamatanId && polygonLayers[state.activeKecamatanId]) {
            map.flyToBounds(polygonLayers[state.activeKecamatanId].getBounds(), { padding: [50, 50], duration: 1.5 });
        } else {
            // Jika reset (Semua Kecamatan), reset view
            map.flyTo([3.5952, 98.6722], 13, { duration: 1 });
        }
    }

    function filterSearch() {
        state.searchQuery = document.getElementById('searchInput').value.toLowerCase().trim();
        renderAll();
    }

    // ─────────────────────────────────────────────
    // MAIN RENDER (Marker & Sidebar List)
    // ─────────────────────────────────────────────
    function renderAll() {
        // 1. Terapkan semua filter
        let filtered = allData;

        // Filter Jenis
        if (state.filterJenis !== 'all') {
            filtered = filtered.filter(f => f.jenis === state.filterJenis);
        }

        // Filter Kecamatan
        if (state.activeKecamatanId) {
            filtered = filtered.filter(f => f.kecamatan_id == state.activeKecamatanId);
        }

        // Filter Search
        if (state.searchQuery) {
            filtered = filtered.filter(f => f.nama.toLowerCase().includes(state.searchQuery));
        }

        // 2. Update Badges (Hitung dari data asli TANPA filter yang sedang aktif, atau dengan context yang pas)
        // Hitung count berdasarkan filter kecamatan & search saja (agar badge jenis tetap akurat)
        let baseForCount = allData;
        if (state.activeKecamatanId) baseForCount = baseForCount.filter(f => f.kecamatan_id == state.activeKecamatanId);
        if (state.searchQuery) baseForCount = baseForCount.filter(f => f.nama.toLowerCase().includes(state.searchQuery));

        let counts = { all: baseForCount.length, puskesmas: 0, damkar: 0, taman: 0 };
        baseForCount.forEach(f => counts[f.jenis]++);
        
        document.getElementById('count-all').textContent = counts.all;
        document.getElementById('count-puskesmas').textContent = counts.puskesmas;
        document.getElementById('count-damkar').textContent = counts.damkar;
        document.getElementById('count-taman').textContent = counts.taman;

        // 3. Render Markers
        Object.values(markers).forEach(m => map.removeLayer(m));
        markers = {};

        filtered.forEach(f => {
            const lat = parseFloat(f.latitude);
            const lng = parseFloat(f.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const t = THEME[f.jenis] || THEME.taman;
            
            // Popup sederhana
            const popupHtml = `
                <div class="popup-header">
                    <div class="popup-name">${escapeHtml(f.nama)}</div>
                    <div class="popup-row"><i class="fas fa-map-marker-alt"></i> ${escapeHtml(f.nama_kecamatan || 'Medan')}</div>
                </div>
                <div class="popup-body">
                    <div class="dp-rating-summary" style="margin-bottom:0.5rem; font-size:0.8rem;">
                        <i class="fas fa-star" style="color:#f5b041;"></i> ${f.avg_rating || '0.0'} (${f.total_ulasan} ulasan)
                    </div>
                    <button class="btn-popup-detail" onclick="openDetailPanel(${f.id})">Lihat Detail Lengkap</button>
                </div>
            `;

            const marker = L.marker([lat, lng], { icon: createCustomIcon(f.jenis) })
                .addTo(map)
                .bindPopup(popupHtml);
            
            marker.on('click', () => {
                highlightSidebarItem(f.id);
            });
            
            markers[f.id] = marker;
        });

        // 4. Render Sidebar List
        const listEl = document.getElementById('sidebar-list');
        if (filtered.length === 0) {
            listEl.innerHTML = '<div class="state-msg"><i class="fas fa-map-marker-alt"></i> Tidak ada fasilitas yang cocok.</div>';
        } else {
            listEl.innerHTML = filtered.map(f => {
                const t = THEME[f.jenis] || THEME.taman;
                const rating = f.avg_rating || '0.0';
                
                return `
                <div class="list-item" id="item-${f.id}" onclick="openDetailPanel(${f.id})">
                    <div class="list-item-header">
                        <div class="list-item-name">${escapeHtml(f.nama)}</div>
                        <div class="list-item-rating"><i class="fas fa-star"></i> ${rating}</div>
                    </div>
                    <div class="list-item-meta">
                        <span class="badge-jenis badge-${f.jenis}"><i class="fas ${t.icon}"></i> ${JENIS_LABEL[f.jenis]}</span>
                        <span>•</span>
                        <span><i class="fas fa-map-pin"></i> ${escapeHtml(f.nama_kecamatan || 'Medan')}</span>
                    </div>
                </div>
            `}).join('');
        }
    }

    function highlightSidebarItem(id) {
        if (state.activeFasilitasId) {
            const prev = document.getElementById(`item-${state.activeFasilitasId}`);
            if (prev) prev.classList.remove('active');
        }
        state.activeFasilitasId = id;
        const el = document.getElementById(`item-${id}`);
        if (el) {
            el.classList.add('active');
            el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    // ─────────────────────────────────────────────
    // DETAIL PANEL & RATING
    // ─────────────────────────────────────────────
    async function openDetailPanel(id) {
        // Fokus marker
        const marker = markers[id];
        if (marker) {
            map.flyTo(marker.getLatLng(), 16, { duration: 1 });
            marker.openPopup();
        }
        highlightSidebarItem(id);

        const panel = document.getElementById('detailPanel');
        panel.classList.add('open');

        // Setup UI loading
        document.getElementById('dpName').textContent = 'Memuat...';
        
        try {
            // Fetch detail lengkap
            const res = await fetch(`${API_FASILITAS}/${id}`);
            const json = await res.json();
            if (json.status !== 200) throw new Error('Gagal muat detail');
            
            const f = json.data;
            const t = THEME[f.jenis];

            document.getElementById('dpName').textContent = f.nama;
            
            const dpJenis = document.getElementById('dpJenis');
            dpJenis.className = `badge-jenis badge-${f.jenis}`;
            dpJenis.innerHTML = `<i class="fas ${t.icon}"></i> ${JENIS_LABEL[f.jenis]}`;

            document.getElementById('dpAvgRating').textContent = f.avg_rating || '0.0';
            document.getElementById('dpTotalUlasan').textContent = f.total_ulasan || 0;

            document.getElementById('dpFoto').src = f.foto_url || '';
            document.getElementById('dpAlamat').textContent = f.alamat || '-';
            document.getElementById('dpKecamatan').textContent = f.nama_kecamatan || '-';
            document.getElementById('dpJam').textContent = f.jam_operasional || '-';
            document.getElementById('dpTelepon').textContent = f.telepon || '-';
            document.getElementById('dpDesc').textContent = f.deskripsi || 'Tidak ada deskripsi.';
            
            // Simpan data untuk routing
            state.activeFasilitas = f;
            
            // Tombol Rute - panggil fungsi lokal
            document.getElementById('btnRute').onclick = () => startRouting(f.latitude, f.longitude, f.nama);

            // Reset form ulasan
            document.getElementById('formUlasan').reset();
            
            // Load ulasan
            switchTab('overview', document.querySelector('.dp-tab:first-child'));
            loadUlasan(id);

        } catch (err) {
            showToast(err.message);
        }
    }

    function closeDetailPanel() {
        document.getElementById('detailPanel').classList.remove('open');
    }

    function switchTab(tabId, el) {
        document.querySelectorAll('.dp-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.dp-content').forEach(c => c.classList.remove('active'));
        
        if (el) {
            el.classList.add('active');
        } else {
            const targetTab = document.querySelector(`.dp-tab[onclick*="'${tabId}'"]`);
            if (targetTab) targetTab.classList.add('active');
        }
        document.getElementById(`tab-${tabId}`).classList.add('active');
    }

    async function loadUlasan(fasilitasId) {
        const listEl = document.getElementById('listUlasan');
        listEl.innerHTML = '<div class="state-msg">Memuat ulasan...</div>';
        
        try {
            const res = await fetch(`${API_FASILITAS}/${fasilitasId}/ratings`);
            const json = await res.json();
            
            if (json.data && json.data.length > 0) {
                listEl.innerHTML = json.data.map(r => {
                    const stars = Array(5).fill(0).map((_, i) => 
                        `<i class="fas fa-star ${i < r.rating ? '' : 'empty'}"></i>`
                    ).join('');
                    
                    // Format tanggal sederhana
                    const d = new Date(r.created_at);
                    const tgl = isNaN(d.getTime()) ? '-' : d.toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'});

                    return `
                    <div class="ulasan-item">
                        <div class="ulasan-header">
                            <div class="ulasan-author">${escapeHtml(r.nama_pengguna)}</div>
                            <div class="ulasan-date">${tgl}</div>
                        </div>
                        <div class="ulasan-stars">${stars}</div>
                        <div class="ulasan-text">${escapeHtml(r.ulasan || '')}</div>
                        <div class="ulasan-platform"><i class="fas fa-${r.platform === 'android' ? 'mobile-alt' : 'globe'}"></i> ${r.platform}</div>
                    </div>
                    `;
                }).join('');
            } else {
                listEl.innerHTML = '<div class="state-msg">Belum ada ulasan. Jadilah yang pertama!</div>';
            }
        } catch (err) {
            listEl.innerHTML = '<div class="state-msg">Gagal memuat ulasan.</div>';
        }
    }

    async function submitUlasan(e) {
        e.preventDefault();
        if (!state.activeFasilitasId) return;

        const btn = document.getElementById('btnSubmitUlasan');
        btn.disabled = true;
        btn.textContent = 'Mengirim...';

        const rating = document.querySelector('input[name="rating"]:checked');
        
        const payload = {
            nama_pengguna: document.getElementById('inputNama').value,
            rating: rating ? parseInt(rating.value) : 5,
            ulasan: document.getElementById('inputKomentar').value,
            platform: 'web'
        };

        try {
            const res = await fetch(`${API_FASILITAS}/${state.activeFasilitasId}/ratings`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            
            if (res.status === 201) {
                showToast(json.message);
                document.getElementById('formUlasan').reset();
                // Reload data
                await loadUlasan(state.activeFasilitasId);
                // Update avg rating global secara background (re-fetch fasilitas)
                loadData(); 
            } else {
                throw new Error(json.message || 'Gagal mengirim ulasan');
            }
        } catch (err) {
            showToast(err.message);
        } finally {
            btn.disabled = false;
            btn.textContent = 'Kirim Ulasan';
        }
    }

    // ─────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────
    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function showToast(msg) {
        const t = document.getElementById('toast');
        t.innerHTML = msg;
        t.style.display = 'block';
        setTimeout(() => { t.style.display = 'none'; }, 4000);
    }

    // ─────────────────────────────────────────────
    // GEOLOKASI & ROUTING
    // ─────────────────────────────────────────────
    function toggleLocation() {
        if (state.isLocating) {
            // Stop locating
            state.isLocating = false;
            document.getElementById('btnLocate').classList.remove('active');
            if (userMarker) map.removeLayer(userMarker);
            state.userLocation = null;
            return;
        }

        if (!navigator.geolocation) {
            showToast("Browser Anda tidak mendukung geolokasi.");
            return;
        }

        document.getElementById('btnLocate').innerHTML = '<i class="fas fa-spinner fa-pulse"></i>';
        
        navigator.geolocation.getCurrentPosition(pos => {
            state.userLocation = { lat: pos.coords.latitude, lng: pos.coords.longitude };
            state.isLocating = true;
            
            document.getElementById('btnLocate').classList.add('active');
            document.getElementById('btnLocate').innerHTML = '<i class="fas fa-location-crosshairs"></i>';

            if (userMarker) map.removeLayer(userMarker);
            userMarker = L.circleMarker([pos.coords.latitude, pos.coords.longitude], {
                radius: 8, color: '#fff', weight: 3, fillOpacity: 1, fillColor: '#3b82f6'
            }).addTo(map).bindPopup('Lokasi Anda');
            
            map.flyTo([pos.coords.latitude, pos.coords.longitude], 15);
        }, err => {
            document.getElementById('btnLocate').innerHTML = '<i class="fas fa-location-crosshairs"></i>';
            showToast("Gagal mendapatkan lokasi: " + err.message);
        });
    }

    async function startRouting(destLat, destLng, destName) {
        if (!state.userLocation) {
            // Jika belum ada lokasi, minta dulu
            showToast("Mendapatkan lokasi Anda...");
            navigator.geolocation.getCurrentPosition(pos => {
                state.userLocation = { lat: pos.coords.latitude, lng: pos.coords.longitude };
                // Setelah dapat lokasi, panggil routing lagi
                doRouting(state.userLocation.lat, state.userLocation.lng, destLat, destLng, destName);
            }, err => {
                showToast("Berikan izin lokasi untuk menggunakan fitur rute.");
            });
        } else {
            doRouting(state.userLocation.lat, state.userLocation.lng, destLat, destLng, destName);
        }
    }

    async function doRouting(fromLat, fromLng, toLat, toLng, toName) {
        if (routeLayer) map.removeLayer(routeLayer);
        
        showToast("Menghitung rute...");
        
        try {
            const url = `https://router.project-osrm.org/route/v1/driving/${fromLng},${fromLat};${toLng},${toLat}?overview=full&geometries=geojson`;
            const res = await fetch(url);
            const data = await res.json();

            if (data.code !== 'Ok') throw new Error("Rute tidak ditemukan");

            const route = data.routes[0];
            const coordinates = route.geometry.coordinates.map(c => [c[1], c[0]]);

            routeLayer = L.polyline(coordinates, {
                color: '#3b82f6',
                weight: 6,
                opacity: 0.8,
                lineJoin: 'round'
            }).addTo(map);

            // Zoom out to fit route
            map.fitBounds(routeLayer.getBounds(), { padding: [50, 50] });

            // Show Info Bar
            const dist = (route.distance / 1000).toFixed(1);
            const time = Math.round(route.duration / 60);
            
            document.getElementById('routeDestName').textContent = toName;
            document.getElementById('routeStats').innerHTML = `<i class="fas fa-road"></i> ${dist} km &nbsp; <i class="fas fa-clock"></i> ${time} mnt`;
            document.getElementById('routingInfo').classList.add('active');
            
            closeDetailPanel();
        } catch (err) {
            showToast("Gagal memuat rute: " + err.message);
        }
    }

    function clearRoute() {
        if (routeLayer) map.removeLayer(routeLayer);
        document.getElementById('routingInfo').classList.remove('active');
        routeLayer = null;
    }

    initMap();
    loadData();

</script>
</body>
</html>