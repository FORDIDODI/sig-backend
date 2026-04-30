<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>ELERA — Peta Fasilitas Umum | Eksplorasi Lokasi</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Google Fonts: Inter & Plus Jakarta Sans (modern & humanis) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            font-family: 'Inter', 'Plus Jakarta Sans', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Navbar modern & hangat (mirip halaman utama) */
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

        /* Filter bar (di bawah navbar) */
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
        }
        .filter-group {
            display: flex;
            gap: 0.7rem;
            flex-wrap: wrap;
            flex: 1;
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
        .filter-btn i { font-size: 0.85rem; }
        .filter-btn:hover { background: #e6e0d7; transform: translateY(-1px); }
        .filter-btn.active-all { background: var(--accent-green); color: white; }
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
        }

        /* Sidebar dengan desain bersih */
        .sidebar {
            width: 320px;
            background: white;
            border-radius: 28px;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid var(--border);
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
            color: var(--accent-green);
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
        .sidebar-list::-webkit-scrollbar-track { background: #e9e4dc; border-radius: 4px; }
        .sidebar-list::-webkit-scrollbar-thumb { background: #c0b7a8; border-radius: 4px; }

        .list-item {
            padding: 0.8rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            margin-bottom: 0.6rem;
            transition: all 0.2s;
            background: #fefcf8;
            border: 1px solid transparent;
        }
        .list-item:hover {
            background: #f6f2eb;
            border-color: var(--border);
            transform: translateX(2px);
        }
        .list-item.active {
            background: rgba(43,122,75,0.08);
            border-color: var(--accent-green);
        }
        .list-item-name {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            color: var(--text-dark);
        }
        .list-item-meta {
            font-size: 0.7rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .jenis-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        .dot-puskesmas { background: var(--accent-green); }
        .dot-damkar { background: var(--accent-red); }
        .dot-taman { background: var(--accent-orange); }

        .state-msg {
            text-align: center;
            padding: 2rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* Map container */
        #map {
            flex: 1;
            border-radius: 28px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            background: #eae5dd;
            z-index: 1;
        }

        /* Leaflet popup custom (tema hangat) */
        .leaflet-popup-content-wrapper {
            background: white !important;
            border-radius: 24px !important;
            box-shadow: var(--shadow-md) !important;
            border: 1px solid var(--border);
            font-family: 'Inter', sans-serif;
        }
        .leaflet-popup-tip { background: white !important; }
        .popup-content {
            padding: 0.2rem 0;
        }
        .popup-name {
            font-weight: 800;
            font-size: 1rem;
            margin-bottom: 0.2rem;
            color: var(--text-dark);
        }
        .popup-jenis {
            display: inline-block;
            padding: 0.2rem 0.8rem;
            border-radius: 40px;
            font-size: 0.65rem;
            font-weight: 700;
            margin-bottom: 0.7rem;
        }
        .popup-jenis.puskesmas { background: rgba(43,122,75,0.12); color: var(--accent-green); }
        .popup-jenis.damkar { background: rgba(196,69,54,0.12); color: var(--accent-red); }
        .popup-jenis.taman { background: rgba(214,140,60,0.12); color: var(--accent-orange); }
        .popup-row {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            display: flex;
            gap: 0.3rem;
            align-items: baseline;
        }
        .popup-row strong { color: var(--text-dark); font-weight: 600; }

        .toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background: #1e2a2f;
            color: #f5e7d9;
            padding: 0.7rem 1.2rem;
            border-radius: 60px;
            font-size: 0.75rem;
            z-index: 9999;
            display: none;
            backdrop-filter: blur(8px);
            font-family: 'Inter', sans-serif;
            box-shadow: var(--shadow-md);
        }

        @media (max-width: 768px) {
            .navbar { padding: 0.8rem 1rem; }
            .filter-bar { padding: 0.8rem 1rem; }
            .main { flex-direction: column; padding: 0.5rem; gap: 0.5rem; }
            .sidebar { width: 100%; max-height: 35vh; border-radius: 24px; }
            .btn-back { margin-left: auto; }
            .filter-group { overflow-x: auto; flex-wrap: nowrap; padding-bottom: 0.2rem; }
        }
    </style>
</head>
<body>

<!-- Navbar seperti halaman utama (humanis) -->
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

<!-- Filter bar & navigasi kembali -->
<div class="filter-bar">
    <div class="filter-group" id="filterGroup">
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
    <a href="/" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
</div>

<div class="main">
    <!-- Sidebar dengan pencarian -->
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
    <!-- Peta -->
    <div id="map"></div>
</div>

<div class="toast" id="toast"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // ─────────────────────────────────────────────
    // KONFIGURASI API
    // ─────────────────────────────────────────────
    const API_BASE = window.location.origin;
    const API_URL  = `${API_BASE}/api/fasilitas`;

    const JENIS_LABEL = {
        puskesmas: 'Puskesmas',
        damkar:    'Pemadam Kebakaran',
        taman:     'Taman Kota',
    };

    // Warna marker sesuai tema humanis
    const MARKER_COLORS = {
        puskesmas: '#2b7a4b',   // hijau
        damkar:    '#c44536',   // merah bata
        taman:     '#d68c3c',   // oranye
    };

    // State
    let allData     = [];
    let markers     = {};
    let activeFilter = 'all';
    let activeItem  = null;
    let searchQuery = '';

    // Inisialisasi peta dengan tile layer yang terang dan natural (OpenStreetMap standar)
    const map = L.map('map', { zoomControl: false }).setView([0.5071, 101.4478], 13);
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Tile layer yang bersih & ramah
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19,
        minZoom: 1
    }).addTo(map);

    // Custom marker icon bulat sederhana dengan warna solid (tanpa emoji)
    function createCustomIcon(jenis) {
        const color = MARKER_COLORS[jenis] || '#6c7a76';
        const svg = `
            <svg width="28" height="36" viewBox="0 0 28 36" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 0C6.27 0 0 6.27 0 14c0 9.5 14 22 14 22s14-12.5 14-22c0-7.73-6.27-14-14-14z" fill="${color}" stroke="#ffffff" stroke-width="1.5"/>
                <circle cx="14" cy="14" r="5" fill="white" opacity="0.8"/>
                <circle cx="14" cy="14" r="2.5" fill="${color}"/>
            </svg>`;
        return L.divIcon({
            html: svg,
            className: '',
            iconSize: [28, 36],
            iconAnchor: [14, 36],
            popupAnchor: [0, -28],
        });
    }

    // Membangun konten popup
    function buildPopup(f) {
        return `
            <div class="popup-content">
                <div class="popup-name">${escapeHtml(f.nama)}</div>
                <span class="popup-jenis ${f.jenis}">${JENIS_LABEL[f.jenis] || f.jenis}</span>
                <div class="popup-row"><i class="fas fa-location-dot"></i> <strong>Alamat:</strong> ${escapeHtml(f.alamat || '-')}</div>
                <div class="popup-row"><i class="fas fa-map-pin"></i> <strong>Koordinat:</strong> ${parseFloat(f.latitude).toFixed(5)}, ${parseFloat(f.longitude).toFixed(5)}</div>
            </div>`;
    }

    // Fetch data dari API
    async function fetchFasilitas() {
        try {
            const res = await fetch(API_URL);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const json = await res.json();
            if (json.status !== 200) throw new Error(json.message || 'Gagal mengambil data');
            allData = json.data || [];
            renderAll();
        } catch (err) {
            showToast('Gagal memuat data: ' + err.message);
            document.getElementById('sidebar-list').innerHTML = `<div class="state-msg"><i class="fas fa-exclamation-triangle"></i> ${err.message}</div>`;
        }
    }

    // Render semua marker & sidebar (filter + search)
    function renderAll() {
        // Hitung total tiap jenis (untuk badge)
        let counts = { all: allData.length, puskesmas: 0, damkar: 0, taman: 0 };
        allData.forEach(f => { if (counts[f.jenis] !== undefined) counts[f.jenis]++; });
        document.getElementById('count-all').textContent = counts.all;
        document.getElementById('count-puskesmas').textContent = counts.puskesmas;
        document.getElementById('count-damkar').textContent = counts.damkar;
        document.getElementById('count-taman').textContent = counts.taman;

        // Filter berdasarkan jenis
        let filtered = activeFilter === 'all' 
            ? [...allData] 
            : allData.filter(f => f.jenis === activeFilter);

        // Filter pencarian (nama)
        if (searchQuery.trim() !== '') {
            const q = searchQuery.trim().toLowerCase();
            filtered = filtered.filter(f => f.nama.toLowerCase().includes(q));
        }

        // Bersihkan marker lama
        Object.values(markers).forEach(m => map.removeLayer(m));
        markers = {};

        // Render sidebar
        const listEl = document.getElementById('sidebar-list');
        if (filtered.length === 0) {
            listEl.innerHTML = '<div class="state-msg"><i class="fas fa-map-marker-alt"></i> Tidak ada fasilitas yang cocok.</div>';
        } else {
            listEl.innerHTML = filtered.map(f => `
                <div class="list-item" id="item-${f.id}" onclick="focusMarker(${f.id})">
                    <div class="list-item-name">${escapeHtml(f.nama)}</div>
                    <div class="list-item-meta">
                        <span class="jenis-dot dot-${f.jenis}"></span>
                        ${JENIS_LABEL[f.jenis] || f.jenis}
                    </div>
                </div>
            `).join('');
        }

        // Tambahkan marker ke peta
        filtered.forEach(f => {
            const lat = parseFloat(f.latitude);
            const lng = parseFloat(f.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const marker = L.marker([lat, lng], { icon: createCustomIcon(f.jenis) })
                .addTo(map)
                .bindPopup(buildPopup(f), { maxWidth: 260, className: 'custom-popup' });
            
            marker.on('click', () => highlightSidebarItem(f.id));
            markers[f.id] = marker;
        });

        // Sesuaikan batas peta jika ada marker
        if (filtered.length > 0) {
            const latLngs = filtered.map(f => [parseFloat(f.latitude), parseFloat(f.longitude)]);
            map.fitBounds(latLngs, { padding: [35, 35] });
        } else {
            // fallback view default
            map.setView([0.5071, 101.4478], 12);
        }
    }

    // Fungsi filter dari tombol
    function setFilter(jenis) {
        activeFilter = jenis;
        // update class aktif pada tombol
        document.querySelectorAll('.filter-btn').forEach(btn => {
            const f = btn.dataset.filter;
            btn.classList.remove('active-all', 'active-puskesmas', 'active-damkar', 'active-taman');
            if (jenis === f) btn.classList.add(`active-${f}`);
        });
        renderAll();
    }

    // Pencarian realtime
    function filterSearch() {
        const input = document.getElementById('searchInput');
        searchQuery = input.value;
        renderAll();
    }

    // Fokus ke marker & buka popup, sorot sidebar
    function focusMarker(id) {
        const marker = markers[id];
        if (!marker) return;
        map.setView(marker.getLatLng(), 17, { animate: true });
        marker.openPopup();
        highlightSidebarItem(id);
    }

    function highlightSidebarItem(id) {
        if (activeItem) {
            const prev = document.getElementById(`item-${activeItem}`);
            if (prev) prev.classList.remove('active');
        }
        activeItem = id;
        const el = document.getElementById(`item-${id}`);
        if (el) {
            el.classList.add('active');
            el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    // Helper
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function showToast(msg) {
        const t = document.getElementById('toast');
        t.innerHTML = `<i class="fas fa-info-circle"></i> ${msg}`;
        t.style.display = 'block';
        setTimeout(() => { t.style.display = 'none'; }, 4000);
    }

    // Load data
    fetchFasilitas();

    // Jika ingin ada aksi smooth scroll untuk anchor (opsional)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if(href === '#') return;
            if(href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>
</body>
</html>