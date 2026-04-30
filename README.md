# ELERA — Sistem Informasi Geografis Fasilitas Umum Kota Medan
## Backend & Web (CodeIgniter 4)

![Platform](https://img.shields.io/badge/Platform-Web-blue?style=flat-square)
![Backend](https://img.shields.io/badge/Framework-CodeIgniter%204-orange?style=flat-square)
![Database](https://img.shields.io/badge/Database-PostgreSQL%2016-336791?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-lightgrey?style=flat-square)

Repositori ini berisi kode backend REST API dan frontend web untuk sistem ELERA — aplikasi pemetaan fasilitas umum (puskesmas, pemadam kebakaran, taman kota) di Kota Medan berbasis CodeIgniter 4 dan PostgreSQL.

Repositori Android terpisah: [ELERA Android](../SigFasilitas/)

---

## Daftar Isi

- [Fitur](#fitur)
- [Arsitektur](#arsitektur)
- [Tech Stack](#tech-stack)
- [Struktur Direktori](#struktur-direktori)
- [Prasyarat](#prasyarat)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Database](#database)
- [API Endpoints](#api-endpoints)
- [Tile Proxy](#tile-proxy)
- [Troubleshooting](#troubleshooting)
- [Lisensi](#lisensi)

---

## Fitur

**REST API**
- Endpoint GET untuk mengambil seluruh data fasilitas
- Filter fasilitas berdasarkan jenis via query parameter `?jenis=`
- Endpoint GET untuk detail satu fasilitas berdasarkan ID
- Endpoint POST untuk menambahkan fasilitas baru dengan validasi input
- Endpoint POST untuk autentikasi pengguna dengan verifikasi bcrypt
- Response JSON terstandarisasi dengan HTTP status code yang sesuai
- CORS header global untuk akses lintas origin dari aplikasi Android

**Frontend Web**
- Landing page dengan desain dark mode
- Peta interaktif menggunakan Leaflet.js dan tile OpenStreetMap
- Marker berbeda warna per jenis fasilitas
- Popup informasi nama, jenis, alamat, dan koordinat GPS saat marker diklik
- Filter real-time berdasarkan jenis tanpa reload halaman
- Auto-fit bounds — zoom peta menyesuaikan data yang ditampilkan
- Sidebar daftar fasilitas yang terintegrasi dengan marker di peta

**Tile Proxy**
- File `public/tile-proxy.php` sebagai perantara tile OpenStreetMap
- Digunakan oleh Android WebView yang tidak bisa akses tile server eksternal secara langsung
- Cache header untuk mengurangi request berulang ke OSM

**Halaman Navigasi**
- `app/Views/navigate.php` untuk WebView Android
- Render peta menggunakan canvas HTML5
- Tile dimuat melalui tile proxy dan di-cache per zoom level
- Kalkulasi rute menggunakan OSRM routing engine
- Fallback garis lurus jika OSRM tidak tersedia
- Marker lokasi asal dan tujuan dengan label
- Gesture drag untuk pan dan tombol zoom

---

## Arsitektur

```
Client (browser / Android WebView)
        |
        | HTTP request
        v
Apache (Laragon)
        |
        v
CodeIgniter 4 Router  -->  CorsFilter (middleware)
        |
        +---> HomeController        --> Views (landing, map, navigate)
        |
        +---> Api/AuthController    --> UserModel    --> PostgreSQL
        |
        +---> Api/FasilitasController --> FasilitasModel --> PostgreSQL
        |
        +---> public/tile-proxy.php --> OpenStreetMap tile server
```

---

## Tech Stack

| Komponen | Teknologi | Versi |
|----------|-----------|-------|
| Framework backend | CodeIgniter 4 | 4.6.x |
| Bahasa server | PHP | 8.1+ |
| Database | PostgreSQL | 16 |
| Web server | Apache | 2.4 (via Laragon) |
| Library peta | Leaflet.js | 1.9.4 |
| Sumber tile peta | OpenStreetMap | — |
| Routing navigasi | OSRM (Project OSRM) | — |
| Dependency manager | Composer | 2.x |

---

## Struktur Direktori

```
sig-backend/
├── app/
│   ├── Config/
│   │   ├── Filters.php             # Registrasi CorsFilter secara global
│   │   └── Routes.php              # Definisi semua route web dan API
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── AuthController.php  # POST /api/login
│   │   │   └── FasilitasController.php  # GET dan POST /api/fasilitas
│   │   └── HomeController.php      # Halaman web dan redirect navigate
│   ├── Filters/
│   │   └── CorsFilter.php          # Middleware CORS untuk Android
│   ├── Models/
│   │   ├── FasilitasModel.php      # Validasi input dan query builder
│   │   └── UserModel.php           # Verifikasi password bcrypt
│   ├── Database/
│   │   ├── Migrations/
│   │   │   ├── CreateFasilitasTable.php
│   │   │   └── CreateUsersTable.php
│   │   └── Seeds/
│   │       ├── FasilitasSeeder.php # Data dummy fasilitas
│   │       └── UsersSeeder.php     # Akun admin dan user default
│   └── Views/
│       ├── landing.php             # Halaman landing page
│       ├── map.php                 # Peta Leaflet.js interaktif
│       └── navigate.php           # Halaman navigasi untuk Android WebView
├── public/
│   ├── index.php                   # Entry point CI4
│   └── tile-proxy.php              # Proxy tile OSM untuk Android WebView
├── .env                            # Konfigurasi lokal (tidak di-commit)
├── .env.example                    # Template konfigurasi
└── composer.json
```

---

## Prasyarat

- [Laragon](https://laragon.org/) versi Full (Apache + PHP 8.1+)
- [PostgreSQL 16](https://www.postgresql.org/download/windows/) + pgAdmin 4
- [Composer](https://getcomposer.org/)
- PHP extensions yang harus aktif:
  - `pdo_pgsql`
  - `pgsql`
  - `intl`
  - `mbstring`

---

## Instalasi

### 1. Clone repositori

```bash
cd C:\laragon\www
git clone <url-repositori> sig-backend
cd sig-backend
```

### 2. Install dependensi

```bash
composer install
```

### 3. Salin file konfigurasi

```bash
cp .env.example .env
```

### 4. Aktifkan ekstensi PHP PostgreSQL

Buka Laragon, klik menu **PHP** > **php.ini**, lalu cari dan uncomment:

```ini
extension=pdo_pgsql
extension=pgsql
```

Simpan, lalu klik **Stop All** dan **Start All** di Laragon.

### 5. Buat database

Buka pgAdmin, hubungkan ke server PostgreSQL lokal, lalu jalankan:

```sql
CREATE DATABASE sig_fasilitas;
```

### 6. Jalankan migration dan seeder

```bash
php spark migrate
php spark db:seed FasilitasSeeder
php spark db:seed UsersSeeder
```

### 7. Verifikasi instalasi

Buka browser dan akses:

```
http://localhost/sig-backend/public/api/fasilitas
```

Jika muncul response JSON dengan data fasilitas, instalasi berhasil.

---

## Konfigurasi

Edit file `.env` sesuai dengan konfigurasi lokal:

```env
# URL aplikasi
app.baseURL = 'http://localhost/sig-backend/public/'
app.forceGlobalSecureRequests = false

# Database PostgreSQL
database.default.hostname = localhost
database.default.database = sig_fasilitas
database.default.username = postgres
database.default.password = ganti_dengan_password_anda
database.default.DBDriver = Postgre
database.default.DBPrefix =
database.default.port     = 5432
database.default.charset  = utf8
database.default.DBCollat =

# Mode aplikasi (development / production)
CI_ENVIRONMENT = development
```

> Perhatian: PostgreSQL tidak mendukung charset `utf8mb4`. Pastikan nilai `charset` adalah `utf8` dan `DBCollat` dikosongkan.

---

## Database

### Struktur tabel

```sql
CREATE TABLE fasilitas (
    id        SERIAL PRIMARY KEY,
    nama      VARCHAR(100)  NOT NULL,
    jenis     VARCHAR(20)   NOT NULL CHECK (jenis IN ('puskesmas', 'damkar', 'taman')),
    alamat    TEXT,
    latitude  DECIMAL(10,8) NOT NULL,
    longitude DECIMAL(11,8) NOT NULL
);

CREATE TABLE users (
    id           SERIAL PRIMARY KEY,
    username     VARCHAR(50)  NOT NULL UNIQUE,
    password     VARCHAR(255) NOT NULL,
    role         VARCHAR(10)  NOT NULL DEFAULT 'user' CHECK (role IN ('admin', 'user')),
    nama_lengkap VARCHAR(100),
    created_at   TIMESTAMP
);
```

### Akun default (dari seeder)

| Username | Password | Role |
|----------|----------|------|
| admin | admin123 | admin |
| user1 | user123 | user |

Password disimpan sebagai hash bcrypt. Ganti sebelum deploy ke production.

---

## API Endpoints

Base URL: `http://localhost/sig-backend/public`

### Autentikasi

```
POST /api/login
Content-Type: application/json

{
  "username": "admin",
  "password": "admin123"
}
```

Response berhasil (200):

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

Response gagal (401):

```json
{
  "status": 401,
  "message": "Username atau password salah."
}
```

### Fasilitas

**GET semua fasilitas**
```
GET /api/fasilitas
```

**GET dengan filter jenis**
```
GET /api/fasilitas?jenis=puskesmas
GET /api/fasilitas?jenis=damkar
GET /api/fasilitas?jenis=taman
```

**GET detail satu fasilitas**
```
GET /api/fasilitas/{id}
```

**POST tambah fasilitas**
```
POST /api/fasilitas
Content-Type: application/json

{
  "nama": "Puskesmas Contoh",
  "jenis": "puskesmas",
  "alamat": "Jl. Contoh No. 1, Medan",
  "latitude": 3.5952,
  "longitude": 98.6722
}
```

Response sukses (201):

```json
{
  "status": 201,
  "message": "Data fasilitas berhasil ditambahkan.",
  "data": {
    "id": 25
  }
}
```

Response validasi gagal (400):

```json
{
  "status": 400,
  "message": "Validasi gagal.",
  "errors": {
    "nama": "Nama fasilitas wajib diisi.",
    "jenis": "Jenis harus salah satu dari: puskesmas, damkar, taman."
  }
}
```

---

## Tile Proxy

Android WebView tidak dapat mengakses tile server OpenStreetMap secara langsung karena emulator tidak punya koneksi ke internet publik. File `public/tile-proxy.php` menjadi perantara.

**Cara kerja:**

```
Android WebView
  --> GET http://10.0.2.2/sig-backend/public/tile-proxy.php?z=14&x=12928&y=8118
      --> PHP fetch https://a.tile.openstreetmap.org/14/12928/8118.png
      --> PHP return image/png ke WebView
```

**Verifikasi tile proxy berjalan:**

```
http://localhost/sig-backend/public/tile-proxy.php?z=14&x=12928&y=8118
```

Harus menampilkan gambar potongan peta (bukan error atau halaman kosong).

---

## Troubleshooting

| Error | Penyebab | Solusi |
|-------|----------|--------|
| `invalid value for parameter "client_encoding": "utf8mb4"` | Nilai charset salah untuk PostgreSQL | Ubah `charset=utf8` dan kosongkan `DBCollat` di `.env` |
| `Unable to connect to the database` | PostgreSQL tidak berjalan atau kredensial salah | Pastikan service PostgreSQL aktif, cek username dan password |
| `Call to undefined function pg_connect()` | Ekstensi pgsql belum aktif | Uncomment `extension=pdo_pgsql` dan `extension=pgsql` di `php.ini`, restart Apache |
| Tile proxy mengembalikan error 502 | Server tidak bisa fetch dari OSM | Pastikan komputer punya koneksi internet |
| Seeder gagal dengan error charset | Konfigurasi database belum diperbarui | Jalankan ulang setelah `.env` diperbaiki |
| Route tidak ditemukan (404) | `.htaccess` tidak terbaca | Pastikan `mod_rewrite` aktif di Apache |

---

## Lisensi

MIT License. Lihat file [LICENSE](LICENSE) untuk detail.