<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>ELERA — Peta Fasilitas Umum | Sistem Informasi Geografis</title>
    <!-- Google Fonts: Poppins sebagai pengganti Michroma yang kaku, lebih manusiawi -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --bg: #fefcf5;       /* background utama kertas hangat */
            --surface: #ffffff;
            --border: #e2e4e8;
            --accent1: #2b7a4b;  /* hijau alami */
            --accent2: #d68c3c;  /* oranye hangat */
            --accent3: #c44536;
            --text: #1e2a2f;
            --muted: #5b6e6b;
            --shadow-sm: 0 8px 20px rgba(0,0,0,0.05);
            --shadow-md: 0 12px 28px rgba(0,0,0,0.08);
            --gradient-hero: linear-gradient(135deg, rgba(30,42,47,0.75) 0%, rgba(20,30,35,0.85) 100%);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', 'Plus Jakarta Sans', sans-serif;
            line-height: 1.5;
        }

        /* navigasi lebih bersih dan hangat */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            padding: 1rem 3rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255,252,245,0.94);
            backdrop-filter: blur(8px);
            box-shadow: var(--shadow-sm);
            z-index: 100;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            text-decoration: none;
            background: linear-gradient(120deg, var(--accent1), var(--accent2));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 2.5rem;
        }

        .nav-links {
            display: flex;
            gap: 1.8rem;
            align-items: center;
        }
        .nav-links a {
            font-size: 0.9rem;
            font-weight: 500;
            color: #2c3e3a;
            text-decoration: none;
            transition: color 0.2s;
            letter-spacing: 0.01em;
        }
        .nav-links a:hover { color: var(--accent1); }
        .btn-nav {
            background: var(--accent1);
            color: white !important;
            padding: 0.6rem 1.4rem;
            border-radius: 40px;
            font-weight: 600 !important;
            box-shadow: 0 2px 6px rgba(43,122,75,0.2);
            transition: all 0.2s;
        }
        .btn-nav:hover {
            background: #1f5f3a;
            transform: translateY(-2px);
        }

        /* Hero dengan background gambar nyata (kota/fasilitas umum) */
        .hero {
            position: relative;
            min-height: 90vh;
            display: flex;
            align-items: center;
            margin-top: 70px;
            background: url('<?= base_url('images/hero-bg.jpg') ?>') center/cover no-repeat;
            border-radius: 0 0 32px 32px;
            box-shadow: var(--shadow-md);
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--gradient-hero);
            border-radius: 0 0 32px 32px;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 750px;
            padding: 4rem 3rem;
            color: white;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,240,0.2);
            backdrop-filter: blur(4px);
            padding: 0.4rem 1rem;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
            width: fit-content;
            border: 1px solid rgba(255,255,240,0.3);
        }
        h1 {
            font-size: clamp(2.8rem, 7vw, 4.8rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }
        h1 .highlight { color: #ffe1a0; }
        h1 .highlight2 { color: #b5e3b5; }
        .hero-desc {
            font-size: 1.1rem;
            opacity: 0.92;
            line-height: 1.6;
            max-width: 520px;
            margin-bottom: 2rem;
        }
        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn-primary {
            background: var(--accent2);
            color: #1e2a2f;
            padding: 0.8rem 2rem;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-primary:hover {
            background: #c27a2e;
            transform: translateY(-3px);
        }
        .btn-outline {
            background: transparent;
            border: 1.5px solid rgba(255,255,240,0.7);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-outline:hover {
            background: rgba(255,255,240,0.15);
            border-color: white;
        }

        /* Stats card lebih natural */
        .stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            padding: 2rem 2rem;
            background: white;
            margin: -2rem 2rem 2rem 2rem;
            border-radius: 28px;
            box-shadow: var(--shadow-sm);
            position: relative;
            z-index: 5;
        }
        .stat-item {
            text-align: center;
            flex: 1;
            min-width: 140px;
        }
        .stat-num {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--accent1);
            line-height: 1;
        }
        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
        }

        /* Features & cards lebih ramah */
        .features {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .section-tag {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--accent2);
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
        }
        .section-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 2rem;
            color: #1e3a2f;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.8rem;
        }
        .feature-card {
            background: white;
            padding: 1.8rem;
            border-radius: 24px;
            box-shadow: var(--shadow-sm);
            transition: all 0.25s ease;
            border: 1px solid #f0ede8;
        }
        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-md);
            border-color: #dcd4c8;
        }
        .feature-icon {
            font-size: 2rem;
            color: var(--accent1);
            margin-bottom: 1rem;
        }
        .feature-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .feature-card p {
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .facilities {
            background: #f2efea;
            padding: 3rem 2rem;
            border-radius: 32px;
            margin: 1rem 1.5rem 2rem;
        }
        .fac-inner {
            max-width: 1100px;
            margin: 0 auto;
        }
        .fac-list {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }
        .fac-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.6rem 1.4rem;
            border-radius: 60px;
            font-weight: 600;
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            border: 1px solid #e2ddd6;
        }
        .fac-chip i { font-size: 1rem; }
        .fac-chip.puskesmas i { color: var(--accent1); }
        .fac-chip.damkar i { color: var(--accent3); }
        .fac-chip.taman i { color: var(--accent2); }

        /* Team section - hangat, foto realistic */
        .team {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.2rem;
            margin-top: 2rem;
        }
        .team-card {
            background: white;
            border-radius: 32px;
            padding: 2rem 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: 0.2s ease;
            border: 1px solid #edeae4;
        }
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        .avatar {
            width: 130px;
            height: 130px;
            margin: 0 auto 1.2rem;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border: 3px solid white;
            outline: 1px solid #e2dacf;
        }
        .team-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .team-name {
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 0.2rem;
            color: #1a2a27;
        }
        .team-role {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--accent2);
            letter-spacing: 0.02em;
            margin-bottom: 0.8rem;
        }
        .team-desc {
            font-size: 0.85rem;
            color: #586f6b;
            line-height: 1.5;
        }

        .cta {
            background: #e8e2d9;
            text-align: center;
            padding: 4rem 2rem;
            border-radius: 40px;
            margin: 2rem 2rem 3rem;
        }
        .cta h2 { font-size: 1.8rem; font-weight: 800; margin-bottom: 0.8rem; color: #1e3a2f; }
        .cta p { color: #4f665f; margin-bottom: 1.8rem; }

        footer {
            padding: 2rem 2rem;
            border-top: 1px solid #e2dbd2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            color: #6d7d78;
            font-size: 0.8rem;
        }
        @media (max-width: 768px) {
            nav { padding: 1rem 1.2rem; }
            .nav-left { gap: 1rem; }
            .nav-links { gap: 1rem; }
            .hero { margin-top: 65px; min-height: 75vh; }
            .hero-content { padding: 2rem 1.5rem; }
            .stats { margin: -1.5rem 1rem 1rem 1rem; flex-direction: column; gap: 1rem; }
            .features, .team { padding: 2rem 1.2rem; }
            .facilities { margin: 1rem; }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-left">
        <a href="/" class="logo">ELERA</a>
        <div class="nav-links">
            <a href="#home">Beranda</a>
            <a href="#peta">Peta</a>
            <a href="#fasilitas">Fasilitas</a>
            <a href="#about">Tentang</a>
            <a href="#kontak">Kontak</a>
        </div>
    </div>
    <a href="/map" class="btn-nav"><i class="fas fa-map-pin"></i> Buka Peta</a>
</nav>

<section class="hero" id="home">
    <div class="hero-content">
        <div class="badge"><i class="fas fa-satellite-dish"></i> SISTEM INFORMASI GEOGRAFIS</div>
        <h1>Peta <span class="highlight">Fasilitas</span><br>Umum <span class="highlight2">Kota</span></h1>
        <p class="hero-desc">
            Platform pemetaan berbasis web & Android untuk fasilitas publik — Puskesmas, Pemadam Kebakaran, dan Taman Kota — dengan data real-time dari GPS.
        </p>
        <div class="hero-actions">
            <a href="/map" class="btn-primary"><i class="fas fa-map-marked-alt"></i> Lihat Peta Interaktif</a>
            <a href="#fitur" class="btn-outline"><i class="fas fa-chevron-right"></i> Jelajahi Fitur</a>
        </div>
    </div>
</section>

<div class="stats">
    <div class="stat-item"><div class="stat-num">3+</div><div class="stat-label">Jenis Fasilitas</div></div>
    <div class="stat-item"><div class="stat-num"><i class="fas fa-satellite"></i> GPS</div><div class="stat-label">Input Lokasi Akurat</div></div>
    <div class="stat-item"><div class="stat-num">RESTful</div><div class="stat-label">API Modern</div></div>
</div>

<section class="features" id="fitur">
    <div class="section-tag">// LAYANAN UNGGULAN</div>
    <h2 class="section-title">Semua yang Anda butuhkan, dalam satu peta</h2>
    <div class="feature-grid">
        <div class="feature-card"><div class="feature-icon"><i class="fas fa-map"></i></div><h3>Peta Interaktif</h3><p>Marker fasilitas dengan popup detail & filter berdasarkan jenis — responsif dan real-time.</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="fab fa-android"></i></div><h3>Input via Android</h3><p>Tambah lokasi fasilitas langsung dari smartphone, didukung GPS Fused Location Provider.</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="fas fa-cloud-upload-alt"></i></div><h3>REST API</h3><p>Backend CodeIgniter 4 menyediakan API JSON cepat untuk web dan mobile.</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="fas fa-filter"></i></div><h3>Filter Cerdas</h3><p>Filter data berdasarkan jenis fasilitas secara instan tanpa reload halaman.</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="fas fa-check-circle"></i></div><h3>Validasi Aman</h3><p>Setiap data divalidasi di server sebelum disimpan ke database PostgreSQL.</p></div>
        <div class="feature-card"><div class="feature-icon"><i class="fas fa-globe"></i></div><h3>OpenStreetMap</h3><p>Gratis, bebas lisensi, dan terintegrasi dengan Leaflet.js.</p></div>
    </div>
</section>

<div class="facilities" id="fasilitas">
    <div class="fac-inner">
        <div class="section-tag">// LOKASI YANG DIPETAKAN</div>
        <h2 class="section-title" style="margin-bottom:0;">Service Area at Your Fingertips</h2>
        <div class="fac-list">
            <div class="fac-chip puskesmas"><i class="fas fa-hospital-user"></i> <span>Puskesmas & Klinik</span></div>
            <div class="fac-chip damkar"><i class="fas fa-fire-extinguisher"></i> <span>Pos Pemadam Kebakaran</span></div>
            <div class="fac-chip taman"><i class="fas fa-leaf"></i> <span>Taman Kota & Ruang Terbuka</span></div>
        </div>
    </div>
</div>

<!-- ABOUT US (tim dengan gambar asli yang bisa diisi) -->
<section class="team" id="about">
    <div class="section-tag">// TIM KAMI</div>
    <h2 class="section-title">Di balik ELERA</h2>
    <div class="team-grid">
        <!-- Member 1: Anri Petrus Simamora -->
        <div class="team-card">
            <div class="avatar">
                <img class="team-avatar-img" 
                     src="<?= base_url('images/4.jpg') ?>" 
                     alt="Foto Anri Petrus Simamora"
                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?background=2b7a4b&color=fff&name=Anri'">
            </div>
            <div class="team-name">Anri Petrus Simamora</div>
            <div class="team-role">UI/UX Designer & API Architect</div>
            <div class="team-desc">Merancang arsitektur REST API dan integrasi database PostgreSQL, serta pengalaman antarmuka yang intuitif.</div>
        </div>
        <!-- Member 2: Shiddiq Tarigan -->
        <div class="team-card">
            <div class="avatar">
                <img class="team-avatar-img" 
                     src="<?= base_url('images/2.jpg') ?>" 
                     alt="Foto Shiddiq Tarigan"
                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?background=d68c3c&color=fff&name=Shiddiq'">
            </div>
            <div class="team-name">Shiddiq Tarigan</div>
            <div class="team-role">Project Lead & Android Dev</div>
            <div class="team-desc">Implementasi GPS, ViewModel, konsumsi API, dan memastikan performa aplikasi mobile stabil.</div>
        </div>
        <!-- Member 3: Elsa Olivia Hutabarat -->
        <div class="team-card">
            <div class="avatar">
                <img class="team-avatar-img" 
                     src="<?= base_url('images/3.jpg') ?>" 
                     alt="Foto Elsa Olivia Hutabarat"
                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?background=2b7a4b&color=fff&name=Elsa'">
            </div>
            <div class="team-name">Elsa Olivia Hutabarat</div>
            <div class="team-role">Fullstack Developer</div>
            <div class="team-desc">Mengembangkan tampilan web yang humanis, responsif, dan integrasi frontend-backend.</div>
        </div>
    </div>
</section>

<section class="cta" id="kontak">
    <h2>Siap menjelajahi fasilitas kotamu?</h2>
    <p>Temukan Puskesmas, pos damkar, dan taman terdekat dengan mudah.</p>
    <a href="/map" class="btn-primary" style="background: #2b7a4b; color:white;"><i class="fas fa-map"></i> Buka Peta Sekarang →</a>
</section>

<footer>
    <p>ELERA — Efisien • Location • Easy Access • Responsive • Akses Layanan Publik</p>
    <p><i class="fas fa-database"></i> REST API v2.0 | <i class="fas fa-heart" style="color: #d68c3c;"></i> Kolaborasi Tim</p>
</footer>

<script>
    // Smooth scroll untuk anchor link dan tambahan navigasi halus
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
    // tambahan fallback untuk randomuser gambar, tetap bisa ganti ke foto real
    console.log("ELERA — Peta Fasilitas Umum dengan desain lebih humanis & background hero nyata");
</script>
</body>
</html>