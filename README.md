# ELERA — Sistem Informasi Geografis Fasilitas Umum Kota Medan

<p align="center">
  <img src="https://img.shields.io/badge/Platform-Web%20%7C%20Android-blue?style=flat-square" />
  <img src="https://img.shields.io/badge/Backend-CodeIgniter%204-orange?style=flat-square" />
  <img src="https://img.shields.io/badge/Database-PostgreSQL%2016-336791?style=flat-square" />
  <img src="https://img.shields.io/badge/Android-Kotlin%20%2B%20Jetpack%20Compose-3DDC84?style=flat-square" />
  <img src="https://img.shields.io/badge/Map-Leaflet.js%20%2B%20OpenStreetMap-7EBC6F?style=flat-square" />
  <img src="https://img.shields.io/badge/License-MIT-lightgrey?style=flat-square" />
</p>

> Sistem informasi geografis berbasis **web dan Android** untuk memetakan fasilitas umum (puskesmas, pemadam kebakaran, taman kota) di Kota Medan, Sumatera Utara. Dilengkapi fitur navigasi rute, filter peta, dan manajemen data berbasis peran (admin/user).

---

## Daftar Isi

- [Demo & Screenshot](#-demo--screenshot)
- [Fitur](#-fitur)
- [Arsitektur Sistem](#-arsitektur-sistem)
- [Tech Stack](#-tech-stack)
- [Struktur Proyek](#-struktur-proyek)
- [Prasyarat](#-prasyarat)
- [Instalasi Backend](#-instalasi-backend)
- [Instalasi Android](#-instalasi-android)
- [Konfigurasi Database](#-konfigurasi-database)
- [API Endpoints](#-api-endpoints)
- [Akun Default](#-akun-default)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

---

## Demo & Screenshot

| Landing Page | Peta Interaktif | Android — User Screen |
|:---:|:---:|:---:|
| *(screenshot)* | *(screenshot)* | *(screenshot)* |

| Android — Login | Admin — Tambah Data | Navigasi Rute |
|:---:|:---:|:---:|
| *(screenshot)* | *(screenshot)* | *(screenshot)* |

---

## Fitur

### Platform Web
- **Peta interaktif** berbasis Leaflet.js dengan tile OpenStreetMap
- **Marker berwarna** per jenis fasilitas (puskesmas, damkar, taman)
- **Popup informasi** — nama, jenis, alamat, koordinat GPS
- **Filter real-time** berdasarkan jenis fasilitas tanpa reload
- **Auto-fit bounds** — zoom otomatis menyesuaikan data yang ditampilkan
- **Dark mode** pada landing page

### Aplikasi Android
- **Autentikasi role-based** — tampilan berbeda untuk admin dan user biasa
- **GPS real-time** menggunakan Fused Location Provider API
- **Kalkulasi jarak** ke setiap fasilitas dengan formula Haversine (meter/km)
- **Urutan otomatis** — fasilitas diurutkan dari yang terdekat
- **Navigasi rute** full-screen via WebView menggunakan OSRM routing engine
- **Tile proxy** — peta tetap tampil di emulator tanpa akses internet langsung
- **UI Glassmorphism** dark mode dengan animasi GPS pulse

### Backend REST API
- Endpoint CRUD untuk data fasilitas umum
- Autentikasi dengan verifikasi password bcrypt
- Filter fasilitas berdasarkan jenis via query parameter
- CORS header global untuk akses dari Android
- Validasi input di level server

---

## Arsitektur Sistem

```
┌─────────────────────────────────────────────────────┐
│                    CLIENT LAYER                     │
│                                                     │
│   ┌──────────────────┐    ┌──────────────────────┐  │
│   │   Web Browser    │    │    Android App       │  │
│   │  Leaflet.js +    │    │  Kotlin + Jetpack    │  │
│   │  OpenStreetMap   │    │  Compose + Retrofit  │  │
│   └────────┬─────────┘    └──────────┬───────────┘  │
└────────────│─────────────────────────│──────────────┘
             │ HTTP GET                │ HTTP POST/GET
             ▼                         ▼
┌─────────────────────────────────────────────────────┐
│                    SERVER LAYER                     │
│                                                     │
│   ┌──────────────────────────┐  ┌────────────────┐  │
│   │      REST API            │  │  Tile Proxy    │  │
│   │   CodeIgniter 4 + PHP    │  │ tile-proxy.php │  │
│   │  /api/login              │  │ relay OSM tile │  │
│   │  /api/fasilitas          │  └───────┬────────┘  │
│   └────────────┬─────────────┘          │ fetch     │
└────────────────│────────────────────────│───────────┘
                 │ SQL query              │
                 ▼                        ▼
┌───────────────────────┐    ┌──────────────────────┐
│   PostgreSQL 16       │    │   OpenStreetMap      │
│   tabel: fasilitas    │    │   Tile server publik │
│   tabel: users        │    └──────────────────────┘
└───────────────────────┘
```

---

## Tech Stack

### Backend
| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| PHP | 8.1+ | Bahasa pemrograman server |
| CodeIgniter 4 | 4.6.x | Framework backend MVC |
| PostgreSQL | 16 | Database relasional |
| Apache | 2.4 | Web server (via Laragon) |

### Frontend Web
| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| Leaflet.js | 1.9.4 | Library peta interaktif |
| OpenStreetMap | — | Sumber tile peta (gratis) |
| OSRM | — | Routing engine navigasi |
| HTML5 / CSS3 / JS | — | UI dan logika client-side |

### Android
| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| Kotlin | — | Bahasa pemrograman Android |
| Jetpack Compose | — | Toolkit UI deklaratif |
| Retrofit 2 | 2.11.0 | HTTP client untuk API |
| OkHttp | 4.12.0 | Interceptor & logging |
| Fused Location Provider | — | GPS & lokasi akurat |
| Google Play Services Location | 21.3.0 | Provider lokasi |
| Kotlin Coroutines | 1.8.1 | Pemrograman asinkronus |

---

## Struktur Proyek

```
sig-backend/                        ← Proyek CodeIgniter 4
├── app/
│   ├── Config/
│   │   ├── Filters.php             ← Registrasi CORS filter global
│   │   └── Routes.php              ← Definisi semua route web & API
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── AuthController.php  ← POST /api/login
│   │   │   └── FasilitasController.php ← CRUD /api/fasilitas
│   │   └── HomeController.php      ← Halaman web + navigate()
│   ├── Filters/
│   │   └── CorsFilter.php          ← Middleware CORS
│   ├── Models/
│   │   ├── FasilitasModel.php      ← Validasi & query fasilitas
│   │   └── UserModel.php           ← Autentikasi bcrypt
│   ├── Database/
│   │   ├── Migrations/             ← Migration tabel
│   │   └── Seeds/                  ← Seeder data dummy
│   └── Views/
│       ├── landing.php             ← Landing page dark mode
│       ├── map.php                 ← Peta Leaflet.js interaktif
│       └── navigate.php            ← Navigasi rute (WebView Android)
├── public/
│   └── tile-proxy.php              ← Proxy tile OSM untuk Android
└── .env                            ← Konfigurasi database & app

SigFasilitas/                       ← Proyek Android Studio
└── app/src/main/java/com/example/sigfasilitas/
    ├── MainActivity.kt             ← Entry point + LoginScreen + AdminScreen
    ├── ui/
    │   ├── screen/
    │   │   └── UserScreen.kt       ← GPS banner, jarak, navigasi
    │   └── viewmodel/
    │       └── MainViewModel.kt    ← MVVM, StateFlow, authState
    ├── data/
    │   ├── model/
    │   │   └── Models.kt           ← Data class: Fasilitas, User, dll
    │   ├── remote/
    │   │   ├── FasilitasApiService.kt ← Retrofit interface
    │   │   └── RetrofitClient.kt   ← Singleton Retrofit + OkHttp
    │   └── repository/
    │       └── FasilitasRepository.kt ← ApiResult + error handling
    └── util/
        └── LocationHelper.kt       ← Fused Location suspend function
```

---

## Prasyarat

### Backend
- [Laragon](https://laragon.org/) (Apache + PHP 8.1)
- [PostgreSQL 16](https://www.postgresql.org/download/) + pgAdmin 4
- [Composer](https://getcomposer.org/)
- PHP extensions aktif: `pdo_pgsql`, `pgsql`

### Android
- [Android Studio](https://developer.android.com/studio) Giraffe ke atas
- Android SDK API level 26 (min) — 36 (target)
- Google Play Services (untuk Fused Location)
- Emulator Android atau device fisik

---

## Instalasi Backend

### 1. Clone dan setup

```bash
cd C:\laragon\www
git clone <repo-url> sig-backend
cd sig-backend
composer install
```

### 2. Konfigurasi `.env`

Salin file `.env.example` menjadi `.env`, lalu sesuaikan:

```env
app.baseURL = 'http://localhost/sig-backend/public/'

database.default.hostname = localhost
database.default.database = sig_fasilitas
database.default.username = postgres
database.default.password = your_password_here
database.default.DBDriver = Postgre
database.default.DBPrefix =
database.default.port     = 5432
database.default.charset  = utf8
database.default.DBCollat =
```

### 3. Aktifkan ekstensi PHP PostgreSQL

Buka `php.ini` di Laragon, uncomment baris berikut:

```ini
extension=pdo_pgsql
extension=pgsql
```

Restart Apache di Laragon.

### 4. Buat database di pgAdmin

```sql
CREATE DATABASE sig_fasilitas;
```

### 5. Jalankan migration dan seeder

```bash
php spark migrate
php spark db:seed FasilitasSeeder
php spark db:seed UsersSeeder
```

### 6. Test API

Buka browser:

```
http://localhost/sig-backend/public/api/fasilitas
```

Response JSON berhasil → backend siap

---

## Instalasi Android

### 1. Buka proyek di Android Studio

File → Open → pilih folder `SigFasilitas/`

### 2. Pastikan `BASE_URL` sesuai

Di `app/build.gradle.kts`:

```kotlin
// Untuk emulator (default)
buildConfigField("String", "BASE_URL", "\"http://10.0.2.2/sig-backend/public/\"")

// Untuk device fisik (sesuaikan IP komputer di jaringan yang sama)
// buildConfigField("String", "BASE_URL", "\"http://192.168.x.x/sig-backend/public/\"")
```

### 3. Set lokasi GPS emulator

Di emulator Android Studio:
1. Klik ikon `...` (Extended Controls)
2. Pilih **Location**
3. Set koordinat Kota Medan:
   - Latitude: `3.5952`
   - Longitude: `98.6722`
4. Klik **Set Location**

### 4. Build dan run

Klik **Run ▶** atau tekan `Shift+F10`

---

## Konfigurasi Database

### Struktur tabel

```sql
-- Tabel fasilitas
CREATE TABLE fasilitas (
    id        SERIAL PRIMARY KEY,
    nama      VARCHAR(100)  NOT NULL,
    jenis     VARCHAR(20)   NOT NULL CHECK (jenis IN ('puskesmas','damkar','taman')),
    alamat    TEXT,
    latitude  DECIMAL(10,8) NOT NULL,
    longitude DECIMAL(11,8) NOT NULL
);

-- Tabel users
CREATE TABLE users (
    id           SERIAL PRIMARY KEY,
    username     VARCHAR(50)  NOT NULL UNIQUE,
    password     VARCHAR(255) NOT NULL,
    role         VARCHAR(10)  NOT NULL DEFAULT 'user' CHECK (role IN ('admin','user')),
    nama_lengkap VARCHAR(100),
    created_at   TIMESTAMP
);
```

### Contoh insert data fasilitas

```sql
INSERT INTO fasilitas (nama, jenis, alamat, latitude, longitude) VALUES
('Puskesmas Padang Bulan', 'puskesmas', 'Medan, Sumatera Utara', 3.5607826995411918, 98.66211781005646),
('Puskesmas Polonia Medan', 'puskesmas', 'Medan, Sumatera Utara', 3.569433629119114, 98.66802482354746),
('Pemadam Kebakaran Kota Medan', 'damkar', 'Jl. Sudirman, Medan', 3.5923, 98.6634),
('Taman Ahmad Yani', 'taman', 'Jl. Ahmad Yani, Medan', 3.5812, 98.6712);
```

---

## API Endpoints

Base URL: `http://localhost/sig-backend/public`

| Method | Endpoint | Body | Deskripsi |
|--------|----------|------|-----------|
| `POST` | `/api/login` | `{"username":"...","password":"..."}` | Autentikasi, return role |
| `GET` | `/api/fasilitas` | — | Semua fasilitas |
| `GET` | `/api/fasilitas?jenis=puskesmas` | — | Filter per jenis |
| `GET` | `/api/fasilitas/{id}` | — | Detail satu fasilitas |
| `POST` | `/api/fasilitas` | `{"nama":"...","jenis":"...","alamat":"...","latitude":0.0,"longitude":0.0}` | Tambah fasilitas baru |

### Contoh response GET /api/fasilitas

```json
{
  "status": 200,
  "message": "Data fasilitas berhasil diambil.",
  "total": 4,
  "data": [
    {
      "id": "1",
      "nama": "Puskesmas Padang Bulan",
      "jenis": "puskesmas",
      "alamat": "Medan, Sumatera Utara",
      "latitude": "3.56078270",
      "longitude": "98.66211781"
    }
  ]
}
```

### Contoh response POST /api/login (berhasil)

```json
{
  "status": 200,
  "message": "Login berhasil.",
  "data": {
    "id": "1",
    "username": "admin",
    "nama_lengkap": "Administrator",
    "role": "admin"
  }
}
```

---

## Akun Default

| Username | Password | Role | Akses |
|----------|----------|------|-------|
| `admin` | `admin123` | admin | Lihat daftar + tambah fasilitas |
| `user1` | `user123` | user | Lihat peta + jarak + navigasi rute |

> **Ganti password default sebelum deploy ke production.**

---

## Cara Kerja Tile Proxy

Emulator Android tidak bisa mengakses server tile OSM (`tile.openstreetmap.org`) secara langsung dari WebView. Solusi yang digunakan:

```
Android WebView
    → request tile ke http://10.0.2.2/sig-backend/public/tile-proxy.php?z=14&x=...&y=...
    → PHP fetch tile dari OSM (server punya akses internet)
    → PHP return PNG ke WebView
    → WebView render peta
```

File `public/tile-proxy.php` harus ada dan dapat diakses. Test di browser:

```
http://localhost/sig-backend/public/tile-proxy.php?z=14&x=12928&y=8118
```

Harus muncul gambar potongan peta.

---

## Troubleshooting

### Backend

| Error | Penyebab | Solusi |
|-------|----------|--------|
| `invalid value for parameter "client_encoding": "utf8mb4"` | Charset MySQL di config PostgreSQL | Ubah `charset=utf8` dan kosongkan `DBCollat` di `.env` |
| `Unable to connect to the database` | PostgreSQL tidak berjalan / password salah | Pastikan service PostgreSQL aktif dan cek kredensial |
| `extension=pdo_pgsql not loaded` | Ekstensi PHP belum diaktifkan | Uncomment `extension=pdo_pgsql` di `php.ini`, restart Apache |

### Android

| Error | Penyebab | Solusi |
|-------|----------|--------|
| `Connection refused` ke API | `BASE_URL` masih pakai `localhost` | Ganti ke `10.0.2.2` untuk emulator |
| Peta blank / putih di WebView | Tile tidak bisa diakses | Pastikan `tile-proxy.php` ada di folder `public/` dan bisa diakses |
| GPS tidak dapat koordinat | Izin lokasi ditolak / GPS emulator tidak diset | Set lokasi di Extended Controls emulator |
| Build error `@Composable invocation` | `remember {}` di dalam `LazyListScope` | Pindahkan ke level fungsi `@Composable` menggunakan `derivedStateOf` |

---

## Kontribusi

Pull request sangat diterima. Untuk perubahan besar, buka issue terlebih dahulu untuk mendiskusikan perubahan yang diinginkan.

1. Fork repositori
2. Buat branch fitur: `git checkout -b feature/nama-fitur`
3. Commit perubahan: `git commit -m 'feat: tambah fitur X'`
4. Push ke branch: `git push origin feature/nama-fitur`
5. Buat Pull Request

---

## Lisensi

Proyek ini menggunakan lisensi [MIT](LICENSE).

---

## Tim Pengembang

> Proyek praktikum mata kuliah Sistem Informasi Geografis

---

*Dibuat dengan marah menggunakan CodeIgniter 4, Kotlin, dan Leaflet.js*