<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ELERA — Peta Fasilitas Umum</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Michroma&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons (optional, untuk ikon pencarian) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --bg: #0a0f1e;
            --surface: #111827;
            --border: #1e2d40;
            --accent1: #00e5ff;
            --accent2: #39ff6e;
            --accent3: #ff6b35;
            --text: #e8eaf0;
            --muted: #6b7a99;
            --glass: rgba(255,255,255,0.05);
            --glass-border: rgba(255,255,255,0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Michroma', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Topbar dengan efek glass */
        .topbar {
            height: 60px;
            background: rgba(17,24,39,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            flex-shrink: 0;
            z-index: 1000;
        }

        .logo {
            font-family: 'Michroma', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent1), var(--accent2));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.02em;
            white-space: nowrap;
        }

        .divider { width: 1px; height: 24px; background: var(--glass-border); }

        .filter-group {
            display: flex;
            gap: 0.5rem;
            flex: 1;
            overflow-x: auto;
        }

        .filter-btn {
            padding: 0.4rem 1rem;
            border-radius: 100px;
            font-family: 'Michroma', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--glass-border);
            background: var(--glass);
            color: var(--muted);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            white-space: nowrap;
        }

        .filter-btn:hover { color: var(--text); border-color: var(--text); background: rgba(255,255,255,0.1); }

        .filter-btn.active-all      { background: rgba(0,229,255,0.15); border-color: var(--accent1); color: var(--accent1); }
        .filter-btn.active-puskesmas { background: rgba(0,229,255,0.15); border-color: var(--accent1); color: var(--accent1); }
        .filter-btn.active-damkar    { background: rgba(255,107,53,0.15); border-color: var(--accent3); color: var(--accent3); }
        .filter-btn.active-taman     { background: rgba(57,255,110,0.15); border-color: var(--accent2); color: var(--accent2); }

        .count-badge {
            background: rgba(255,255,255,0.15);
            padding: 2px 6px;
            border-radius: 100px;
            font-family: 'Michroma', sans-serif;
            font-size: 0.6rem;
        }

        .btn-back {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: var(--muted);
            text-decoration: none;
            padding: 0.4rem 0.9rem;
            border-radius: 6px;
            border: 1px solid var(--glass-border);
            transition: all 0.2s;
            background: var(--glass);
        }
        .btn-back:hover { color: var(--text); border-color: var(--accent1); background: rgba(0,229,255,0.1); }

        /* Main layout */
        .main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Sidebar dengan efek glass */
        .sidebar {
            width: 320px;
            background: rgba(17,24,39,0.7);
            backdrop-filter: blur(8px);
            border-right: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            flex-shrink: 0;
        }

        .sidebar-header {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--glass-border);
        }

        .sidebar-header h2 {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--accent2);
            margin-bottom: 0.8rem;
        }

        /* Search box */
        .search-box {
            display: flex;
            align-items: center;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 40px;
            padding: 0.4rem 0.8rem;
            margin-top: 0.5rem;
        }
        .search-box i {
            color: var(--muted);
            font-size: 0.8rem;
        }
        .search-box input {
            background: transparent;
            border: none;
            color: var(--text);
            font-family: 'Michroma', sans-serif;
            font-size: 0.75rem;
            width: 100%;
            padding: 0.3rem 0.5rem;
            outline: none;
        }
        .search-box input::placeholder {
            color: var(--muted);
            font-size: 0.7rem;
        }

        .sidebar-list {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem;
        }

        .sidebar-list::-webkit-scrollbar { width: 4px; }
        .sidebar-list::-webkit-scrollbar-track { background: transparent; }
        .sidebar-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .list-item {
            padding: 0.8rem 1rem;
            border-radius: 12px;
            cursor: pointer;
            margin-bottom: 0.5rem;
            border: 1px solid transparent;
            transition: all 0.2s;
            background: rgba(255,255,255,0.02);
        }

        .list-item:hover { background: rgba(255,255,255,0.06); border-color: var(--glass-border); }
        .list-item.active { background: rgba(0,229,255,0.08); border-color: rgba(0,229,255,0.3); }

        .list-item-name {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .list-item-meta {
            font-size: 0.7rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .jenis-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .dot-puskesmas { background: var(--accent1); }
        .dot-damkar    { background: var(--accent3); }
        .dot-taman     { background: var(--accent2); }

        .state-msg {
            padding: 2rem 1rem;
            text-align: center;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .loading-spinner {
            width: 24px; height: 24px;
            border: 2px solid var(--border);
            border-top-color: var(--accent1);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 0.8rem;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Map */
        #map {
            flex: 1;
            background: #0a0f1e;
        }

        /* Custom Leaflet popup dengan tema gelap */
        .leaflet-popup-content-wrapper {
            background: var(--surface) !important;
            border: 1px solid var(--glass-border) !important;
            border-radius: 16px !important;
            box-shadow: 0 8px 30px rgba(0,0,0,0.5) !important;
            color: var(--text) !important;
            font-family: 'Michroma', sans-serif;
        }
        .leaflet-popup-tip { background: var(--surface) !important; }

        .popup-content { min-width: 200px; }
        .popup-name { font-size: 0.9rem; font-weight: 700; margin-bottom: 0.3rem; }
        .popup-jenis {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 100px;
            font-size: 0.65rem;
            font-weight: 600;
            margin-bottom: 0.7rem;
        }
        .popup-jenis.puskesmas { background: rgba(0,229,255,0.15); color: var(--accent1); }
        .popup-jenis.damkar    { background: rgba(255,107,53,0.15); color: var(--accent3); }
        .popup-jenis.taman     { background: rgba(57,255,110,0.15); color: var(--accent2); }

        .popup-row { font-size: 0.7rem; color: var(--muted); margin-bottom: 0.3rem; line-height: 1.4; }
        .popup-row strong { color: var(--text); font-weight: 600; }

        .toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background: var(--surface);
            border: 1px solid #ff4444;
            color: #ff6666;
            padding: 0.8rem 1.2rem;
            border-radius: 12px;
            font-size: 0.75rem;
            z-index: 9999;
            display: none;
            backdrop-filter: blur(8px);
            font-family: 'Michroma', sans-serif;
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .filter-group { overflow-x: auto; }
            .topbar { padding: 0 1rem; gap: 0.5rem; }
        }
    </style>
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <div class="logo">ELERA</div>
    <div class="divider"></div>
    <div class="filter-group">
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
            <i class="fas fa-tree"></i> Taman <span class="count-badge" id="count-taman">0</span>
        </button>
    </div>
    <a href="/" class="btn-back"><i class="fas fa-arrow-left"></i> Beranda</a>
</div>

<!-- Main -->
<div class="main">
    <!-- Sidebar dengan pencarian -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-list"></i> Daftar Fasilitas</h2>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari fasilitas..." onkeyup="filterSearch()">
            </div>
        </div>
        <div class="sidebar-list" id="sidebar-list">
            <div class="state-msg">
                <div class="loading-spinner"></div>
                Memuat data...
            </div>
        </div>
    </div>

    <!-- Map -->
    <div id="map"></div>
</div>

<!-- Error Toast -->
<div class="toast" id="toast"></div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // ─────────────────────────────────────────────
    // CONFIG
    // ─────────────────────────────────────────────
    const API_BASE = window.location.origin;
    const API_URL  = `${API_BASE}/api/fasilitas`;

    const JENIS_LABEL = {
        puskesmas: 'Puskesmas',
        damkar:    'Pemadam Kebakaran',
        taman:     'Taman Kota',
    };

    const ICON_COLORS = {
        puskesmas: '#00e5ff',
        damkar:    '#ff6b35',
        taman:     '#39ff6e',
    };

    // ─────────────────────────────────────────────
    // STATE
    // ─────────────────────────────────────────────
    let allData     = [];
    let markers     = {};
    let activeFilter = 'all';
    let activeItem  = null;
    let searchQuery = '';

    // ─────────────────────────────────────────────
    // MAP INIT - Dark tile layer (CartoDB Dark Matter)
    // ─────────────────────────────────────────────
    const map = L.map('map', { zoomControl: false }).setView([0.5071, 101.4478], 13);
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Tile layer gelap
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19,
        minZoom: 1
    }).addTo(map);

    // ─────────────────────────────────────────────
    // CUSTOM MARKER ICON (tanpa emoji, biar clean)
    // ─────────────────────────────────────────────
    function createIcon(jenis) {
        const color = ICON_COLORS[jenis] || '#ffffff';
        // Ikon sederhana: lingkaran dengan warna aksen
        const svg = `
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="40" viewBox="0 0 32 40">
                <path d="M16 0C7.16 0 0 7.16 0 16c0 12 16 24 16 24s16-12 16-24c0-8.84-7.16-16-16-16z"
                      fill="${color}" fill-opacity="0.85" stroke="#fff" stroke-width="1.2"/>
                <circle cx="16" cy="16" r="6" fill="rgba(0,0,0,0.4)"/>
                <circle cx="16" cy="16" r="3" fill="${color}"/>
            </svg>`;
        return L.divIcon({
            html: svg,
            className: '',
            iconSize: [32, 40],
            iconAnchor: [16, 40],
            popupAnchor: [0, -32],
        });
    }

    // ─────────────────────────────────────────────
    // POPUP CONTENT
    // ─────────────────────────────────────────────
    function buildPopup(f) {
        return `
            <div class="popup-content">
                <div class="popup-name">${escHtml(f.nama)}</div>
                <span class="popup-jenis ${f.jenis}">${JENIS_LABEL[f.jenis] || f.jenis}</span>
                <div class="popup-row"><i class="fas fa-location-dot"></i> <strong>Alamat:</strong> ${escHtml(f.alamat || '-')}</div>
                <div class="popup-row"><i class="fas fa-map-pin"></i> <strong>Koordinat:</strong> ${parseFloat(f.latitude).toFixed(6)}, ${parseFloat(f.longitude).toFixed(6)}</div>
            </div>`;
    }

    // ─────────────────────────────────────────────
    // FETCH DATA
    // ─────────────────────────────────────────────
    async function fetchFasilitas() {
        try {
            const res = await fetch(API_URL);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const json = await res.json();
            if (json.status !== 200) throw new Error(json.message || 'Gagal mengambil data');
            allData = json.data || [];
            renderAll();
        } catch (err) {
            showToast('⚠️ Gagal memuat data: ' + err.message);
            document.getElementById('sidebar-list').innerHTML =
                `<div class="state-msg">Gagal memuat data.<br><small>${err.message}</small></div>`;
        }
    }

    // ─────────────────────────────────────────────
    // RENDER MARKERS + SIDEBAR (dengan filter dan search)
    // ─────────────────────────────────────────────
    function renderAll() {
        // Bersihkan marker lama
        Object.values(markers).forEach(m => map.removeLayer(m));
        markers = {};

        // Hitung counts (untuk semua data, tanpa filter search)
        const counts = { all: allData.length, puskesmas: 0, damkar: 0, taman: 0 };
        allData.forEach(f => { if (counts[f.jenis] !== undefined) counts[f.jenis]++; });
        document.getElementById('count-all').textContent = counts.all;
        document.getElementById('count-puskesmas').textContent = counts.puskesmas;
        document.getElementById('count-damkar').textContent = counts.damkar;
        document.getElementById('count-taman').textContent = counts.taman;

        // Filter berdasarkan jenis dan query pencarian
        let filtered = activeFilter === 'all'
            ? allData
            : allData.filter(f => f.jenis === activeFilter);

        if (searchQuery.trim() !== '') {
            const q = searchQuery.trim().toLowerCase();
            filtered = filtered.filter(f => f.nama.toLowerCase().includes(q));
        }

        // Render sidebar
        const listEl = document.getElementById('sidebar-list');
        if (filtered.length === 0) {
            listEl.innerHTML = '<div class="state-msg"><i class="fas fa-search"></i> Tidak ada fasilitas yang cocok.</div>';
        } else {
            listEl.innerHTML = filtered.map(f => `
                <div class="list-item" id="item-${f.id}" onclick="focusMarker(${f.id})">
                    <div class="list-item-name">${escHtml(f.nama)}</div>
                    <div class="list-item-meta">
                        <span class="jenis-dot dot-${f.jenis}"></span>
                        ${JENIS_LABEL[f.jenis] || f.jenis}
                    </div>
                </div>`).join('');
        }

        // Render markers
        filtered.forEach(f => {
            const lat = parseFloat(f.latitude);
            const lng = parseFloat(f.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const marker = L.marker([lat, lng], { icon: createIcon(f.jenis) })
                .addTo(map)
                .bindPopup(buildPopup(f), { maxWidth: 280 });

            marker.on('click', () => highlightItem(f.id));
            markers[f.id] = marker;
        });

        // Auto-fit bounds jika ada marker
        if (filtered.length > 0) {
            const latLngs = filtered.map(f => [parseFloat(f.latitude), parseFloat(f.longitude)]);
            map.fitBounds(latLngs, { padding: [40, 40] });
        }
    }

    // ─────────────────────────────────────────────
    // FILTER
    // ─────────────────────────────────────────────
    function setFilter(jenis) {
        activeFilter = jenis;
        document.querySelectorAll('.filter-btn').forEach(btn => {
            const f = btn.dataset.filter;
            btn.className = `filter-btn${jenis === f ? ` active-${f}` : ''}`;
        });
        renderAll();
    }

    // ─────────────────────────────────────────────
    // SEARCH
    // ─────────────────────────────────────────────
    function filterSearch() {
        const input = document.getElementById('searchInput');
        searchQuery = input.value;
        renderAll();
    }

    // ─────────────────────────────────────────────
    // FOCUS MARKER
    // ─────────────────────────────────────────────
    function focusMarker(id) {
        const marker = markers[id];
        if (!marker) return;
        map.setView(marker.getLatLng(), 16, { animate: true });
        marker.openPopup();
        highlightItem(id);
    }

    function highlightItem(id) {
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

    // ─────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────
    function escHtml(str) {
        if (!str) return '';
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.style.display = 'block';
        setTimeout(() => { t.style.display = 'none'; }, 4000);
    }

    // ─────────────────────────────────────────────
    // INIT
    // ─────────────────────────────────────────────
    fetchFasilitas();
</script>
</body>
</html>