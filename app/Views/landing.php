<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>ELERA — Peta Fasilitas Umum | Sistem Informasi Geografis</title>
    <!-- Google Fonts + Font Awesome 6 (ikon profesional tanpa emoji) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Michroma&display=swap" rel="stylesheet">
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
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,229,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,229,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            padding: 1.2rem 3rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(10,15,30,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            z-index: 100;
        }

        .logo {
            font-family: 'Michroma', sans-serif;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            text-decoration: none;
            background: linear-gradient(135deg, var(--accent1), var(--accent2));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            transition: opacity 0.2s;
        }
        .logo:hover {
            opacity: 0.85;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        .nav-links a {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
            text-decoration: none;
            letter-spacing: 0.08em;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--text); }
        .btn-nav {
            background: var(--accent1);
            color: var(--bg) !important;
            padding: 0.5rem 1.2rem;
            border-radius: 4px;
            font-weight: 700 !important;
        }

        /* Hero */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 8rem 3rem 4rem;
            z-index: 1;
        }
        .hero-content { max-width: 700px; }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(0,229,255,0.08);
            border: 1px solid rgba(0,229,255,0.2);
            padding: 0.35rem 0.9rem;
            border-radius: 100px;
            font-family: 'Michroma', sans-serif;
            font-size: 0.72rem;
            color: var(--accent1);
            letter-spacing: 0.1em;
            margin-bottom: 2rem;
            animation: fadeUp 0.6s ease both;
        }
        .badge::before {
            content: '';
            width: 6px; height: 6px;
            background: var(--accent2);
            border-radius: 50%;
            animation: pulse 1.5s ease infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        h1 {
            font-size: clamp(2.8rem, 6vw, 5rem);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
            animation: fadeUp 0.6s 0.1s ease both;
        }
        h1 .highlight { color: var(--accent1); }
        h1 .highlight2 { color: var(--accent2); }
        .hero-desc {
            font-size: 1.1rem;
            color: var(--muted);
            line-height: 1.7;
            max-width: 520px;
            margin-bottom: 2.5rem;
            animation: fadeUp 0.6s 0.2s ease both;
        }
        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeUp 0.6s 0.3s ease both;
        }
        .btn-primary {
            background: var(--accent1);
            color: var(--bg);
            padding: 0.85rem 2rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.05em;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary i {
            font-size: 0.9rem;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,229,255,0.3);
        }
        .btn-outline {
            border: 1px solid var(--border);
            color: var(--text);
            padding: 0.85rem 2rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.05em;
            transition: border-color 0.2s, color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-outline:hover { border-color: var(--accent1); color: var(--accent1); }

        /* Stats */
        .stats {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;
            background: var(--border);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .stat-item {
            background: var(--bg);
            padding: 2rem 3rem;
            text-align: center;
        }
        .stat-num {
            font-family: 'Michroma', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--accent1);
            line-height: 1;
            margin-bottom: 0.4rem;
        }
        .stat-label {
            font-size: 0.8rem;
            color: var(--muted);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* Features */
        .features {
            position: relative;
            z-index: 1;
            padding: 5rem 3rem;
            max-width: 1100px;
            margin: 0 auto;
        }
        .section-tag {
            font-family: 'Michroma', sans-serif;
            font-size: 0.72rem;
            color: var(--accent2);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 3rem;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .feature-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.8rem;
            transition: border-color 0.2s, transform 0.2s;
        }
        .feature-card:hover {
            border-color: var(--accent1);
            transform: translateY(-4px);
        }
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--accent1);
        }
        .feature-card h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text);
        }
        .feature-card p {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* Facility types */
        .facilities {
            position: relative;
            z-index: 1;
            padding: 3rem;
            background: var(--surface);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .fac-inner {
            max-width: 1100px;
            margin: 0 auto;
        }
        .fac-list {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        .fac-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.8rem 1.4rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid transparent;
            transition: transform 0.1s;
        }
        .fac-chip i {
            font-size: 1.1rem;
        }
        .fac-chip.puskesmas { background: rgba(0,229,255,0.08); border-color: rgba(0,229,255,0.2); color: var(--accent1); }
        .fac-chip.damkar    { background: rgba(255,107,53,0.08); border-color: rgba(255,107,53,0.2); color: var(--accent3); }
        .fac-chip.taman     { background: rgba(57,255,110,0.08); border-color: rgba(57,255,110,0.2); color: var(--accent2); }

        /* --- ABOUT US (Team Section) --- Support gambar real / foto --- */
        .team {
            position: relative;
            z-index: 1;
            padding: 5rem 3rem;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            margin-top: 3rem;
        }
        .team-card {
            background: var(--glass);
            backdrop-filter: blur(8px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 2rem 1.8rem;
            transition: all 0.3s ease;
            text-align: center;
        }
        .team-card:hover {
            border-color: var(--accent1);
            transform: translateY(-8px);
            background: rgba(255,255,255,0.08);
        }
        /* Avatars bisa diisi gambar langsung */
        .avatar {
            width: 130px;
            height: 130px;
            margin: 0 auto 1.5rem;
            background: var(--surface);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--accent1);
            overflow: hidden;
            transition: 0.2s;
        }
        .team-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        /* fallback jika gambar gagal load - tidak pakai emoji */
        .avatar-fallback-icon {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1e2a3a;
            color: var(--accent1);
            font-size: 3rem;
        }
        .team-name {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
            color: var(--text);
        }
        .team-role {
            font-size: 0.8rem;
            font-family: 'Michroma', sans-serif;
            color: var(--accent2);
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }
        .team-desc {
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.6;
        }

        .cta {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 5rem 3rem;
        }
        .cta h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: 1rem; }
        .cta p { color: var(--muted); margin-bottom: 2rem; }

        footer {
            position: relative;
            z-index: 1;
            padding: 1.5rem 3rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        footer p { font-size: 0.8rem; color: var(--muted); font-family: 'Michroma', sans-serif; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            nav { padding: 1rem 1.5rem; }
            .nav-left { gap: 1rem; }
            .nav-links { gap: 1rem; }
            .hero { padding: 7rem 1.5rem 3rem; }
            .stats { grid-template-columns: 1fr; }
            .features, .facilities, .team { padding: 3rem 1.5rem; }
            h1 { font-size: 2.5rem; }
            .team-grid { gap: 1.5rem; }
            .avatar { width: 110px; height: 110px; }
            .team-name { font-size: 1.2rem; }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-left">
        <a href="/" class="logo">ELERA</a>
        <div class="nav-links">
            <a href="#about">About Us</a>
        </div>
    </div>
    <a href="/map" class="btn-nav"><i class="fas fa-map-marked-alt" style="margin-right: 6px;"></i> Buka Peta →</a>
</nav>

<section class="hero">
    <div class="hero-content">
        <div class="badge">SISTEM INFORMASI GEOGRAFIS</div>
        <h1>Peta <span class="highlight">Fasilitas</span><br>Umum <span class="highlight2">Kota</span></h1>
        <p class="hero-desc">
            Platform pemetaan berbasis web & Android untuk fasilitas publik — Puskesmas, Pemadam Kebakaran, dan Taman Kota — dengan data real-time dari GPS.
        </p>
        <div class="hero-actions">
            <a href="/map" class="btn-primary"><i class="fas fa-map"></i> Lihat Peta</a>
            <a href="#fitur" class="btn-outline"><i class="fas fa-arrow-right"></i> Pelajari Lebih Lanjut</a>
        </div>
    </div>
</section>

<div class="stats">
    <div class="stat-item"><div class="stat-num">3</div><div class="stat-label">Jenis Fasilitas</div></div>
    <div class="stat-item"><div class="stat-num">GPS</div><div class="stat-label">Input Lokasi Akurat</div></div>
    <div class="stat-item"><div class="stat-num">REST</div><div class="stat-label">API Architecture</div></div>
</div>

<section class="features" id="fitur">
    <div class="section-tag">// FITUR SISTEM</div>
    <h2 class="section-title">Semua yang Anda butuhkan</h2>
    <div class="feature-grid">
        <div class="feature-card"><div class="feature-icon"><i class="fas fa-map"></i></div><h3>Peta Interaktif</h3><p>Visualisasi marker fasilitas menggunakan Leaflet.js dengan popup informasi lengkap dan filter berdasarkan jenis.</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="fas fa-mobile-alt"></i></div><h3>Input via Android</h3><p>Tambah lokasi fasilitas langsung dari smartphone menggunakan GPS Fused Location Provider untuk akurasi tinggi.</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="fas fa-bolt"></i></div><h3>REST API</h3><p>Backend CodeIgniter 4 menyediakan API JSON yang konsisten untuk komunikasi antara web dan Android.</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="fas fa-sliders-h"></i></div><h3>Filter Cerdas</h3><p>Filter data berdasarkan jenis fasilitas secara real-time tanpa reload halaman.</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="fas fa-shield-alt"></i></div><h3>Validasi Input</h3><p>Semua data divalidasi di sisi server sebelum disimpan ke database PostgreSQL.</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="fas fa-globe-asia"></i></div><h3>Open Source Map</h3><p>Menggunakan OpenStreetMap via Leaflet.js — gratis, tanpa API key berbayar.</p></div>
    </div>
</section>

<div class="facilities">
    <div class="fac-inner">
        <div class="section-tag">// JENIS FASILITAS</div>
        <h2 class="section-title" style="margin-bottom:0;">Yang Terpetakan</h2>
        <div class="fac-list">
            <div class="fac-chip puskesmas"><i class="fas fa-hospital"></i> <span>Puskesmas</span></div>
            <div class="fac-chip damkar"><i class="fas fa-fire-extinguisher"></i> <span>Pemadam Kebakaran</span></div>
            <div class="fac-chip taman"><i class="fas fa-tree"></i> <span>Taman Kota</span></div>
        </div>
    </div>
</div>

<!-- ABOUT US (Team Section) - support langsung gambar real / foto, tanpa emoji -->
<section class="team" id="about">
    <div class="section-tag">// TIM PENGEMBANG</div>
    <h2 class="section-title">About Us</h2>
    <div class="team-grid">
        <!-- Member 1 : Anri Petrus Simamora - bisa ganti foto dengan replace src gambar -->
        <div class="team-card">
            <div class="avatar">
                <img class="team-avatar-img" 
                    src="<?= base_url('images/4.jpg') ?>"   
                     alt="Foto Anri Petrus Simamora"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'avatar-fallback-icon\'><i class=\'fas fa-user-astronaut\'></i></div>'">
            </div>
            <div class="team-name">Anri Petrus Simamora</div>
            <div class="team-role">UI/UX Designer</div>
            <div class="team-desc">Merancang arsitektur REST API dan integrasi database PostgreSQL.</div>
        </div>
        <!-- Member 2 : Shiddiq Tarigan - ganti foto di src -->
        <div class="team-card">
            <div class="avatar">
                <img class="team-avatar-img" 
                    src="<?= base_url('images/2.jpg') ?>"   
                     alt="Foto Shiddiq Tarigan"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'avatar-fallback-icon\'><i class=\'fas fa-user-astronaut\'></i></div>'">
            </div>
            <div class="team-name">Shiddiq Tarigan</div>
            <div class="team-role">Project Lead & Android Developer</div>
            <div class="team-desc">Implementasi GPS, ViewModel, dan komunikasi dengan API.</div>
        </div>
        <!-- Member 3 : Elsa Olivia Hutabarat - replace foto disini -->
        <div class="team-card">
            <div class="avatar">
                <img class="team-avatar-img" 
                     src="<?= base_url('images/3.jpg') ?>"   
                     alt="Foto Elsa Olivia Hutabarat"
                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'avatar-fallback-icon\'><i class=\'fas fa-laptop-code\'></i></div>'">
            </div>
            <div class="team-name">Elsa Olivia Hutabarat</div>
            <div class="team-role">Fullstack Developer</div>
            <div class="team-desc">Membangun tampilan web glassmorphism dan user interface.</div>
        </div>
    </div>
    <!-- catatan: setiap avatar gambar bisa diubah dengan mengganti atribut 'src' ke file gambar real (jpg/png) -->
</section>

<section class="cta">
    <h2>Siap menjelajahi peta?</h2>
    <p>Lihat semua fasilitas umum yang sudah terdaftar di sistem.</p>
    <a href="/map" class="btn-primary"><i class="fas fa-map"></i> Buka Peta Sekarang →</a>
</section>

<footer>
    <p>ELERA — Efisien • Location • Easy Access • Responsive • Access Layanan</p>
    <p><i class="fas fa-cloud-upload-alt" style="margin-right: 5px;"></i> REST API v1.0</p>
</footer>

<script>
    // Smooth scroll untuk anchor link
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if(target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
    
    // Opsional: tambahkan instruksi untuk pengguna bahwa foto bisa diganti (tidak mengganggu)
    // (Dibiarkan saja agar user dapat langsung edit src gambar di tag <img>)
</script>
</body>
</html>