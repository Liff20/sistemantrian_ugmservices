# Sistem Antrian Humas — UGM Super App

Sistem antrian digital untuk bagian Humas (Hubungan Masyarakat). Pengguna dapat mengambil nomor antrian melalui website, petugas memanggil melalui dashboard operator, dan nomor muncul di layar TV/monitor secara real-time. Semua loket dapat melayani seluruh jenis layanan.

## Fitur

- **Ambil Antrian** — Pengguna memilih layanan (Pengaduan, Permohonan Informasi, Konsultasi) dan mendapatkan nomor antrian
- **Dashboard Operator** — Petugas memanggil, melayani, dan menyelesaikan antrian. Semua loket melayani semua layanan
- **TV Display** — Tampilan monitor real-time dengan nomor yang dipanggil dan daftar antrian
- **Real-time** — Update langsung menggunakan Laravel Reverb WebSocket
- **Sound Notification** — Suara otomatis saat nomor dipanggil (via browser)
- **Panel Admin** — Reset sistem, statistik per layanan, monitoring antrian

## Persyaratan

- PHP 8.2+
- Composer
- Node.js 18+
- NPM

## Instalasi PHP & Composer (Windows)

### Opsi 1: Laragon (Rekomendasi)

[Download Laragon](https://laragon.org/download/) → Install → Selesai. Laragon sudah include PHP, Composer, MySQL, Nginx, dan semuanya terkonfigurasi otomatis.

Setelah install Laragon:
1. Buka **Laragon** → klik **Start All**
2. Buka terminal Laragon: **Menu → Terminal**
3. Lanjut ke tahap **Instalasi Aplikasi** di bawah

### Opsi 2: Manual

1. **Download PHP 8.2+** dari https://windows.php.net/download/
   - Pilih file `php-8.2.x-nts-Win32-vs16-x64.zip`
   - Extract ke `C:\php`
   - Tambahkan `C:\php` ke **Environment Variables > PATH**

2. **Download Composer** dari https://getcomposer.org/download/
   - Jalankan `Composer-Setup.exe`
   - Saat instalasi, arahkan ke folder PHP (`C:\php`)

3. Verifikasi instalasi:
   ```cmd
   php -v
   composer --version
   ```

## Instalasi Aplikasi

```bash
# Pindah ke folder project
cd "d:\AI & Plugin Experiment\sistem_antrian_humas"

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database
copy nul database\database.sqlite

# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed --class=QueueSeeder
```

## Menjalankan Aplikasi

Buka **3 terminal terpisah**:

### Terminal 1 — Web Server
```bash
cd "d:\AI & Plugin Experiment\sistem_antrian_humas"
php artisan serve
```
Akses: `http://localhost:8000`

### Terminal 2 — Vite (Hot Reload untuk CSS/JS)
```bash
cd "d:\AI & Plugin Experiment\sistem_antrian_humas"
npm run dev
```

### Terminal 3 — WebSocket (Real-time)
```bash
cd "d:\AI & Plugin Experiment\sistem_antrian_humas"
php artisan reverb:start --host=0.0.0.0 --port=8080
```

## Akses Aplikasi

| Halaman | URL | Deskripsi |
|---------|-----|-----------|
| Ambil Antrian | `http://localhost:8000` | Pengguna memilih layanan & mendapat nomor |
| Operator Loket 1 | `http://localhost:8000/operator/1` | Dashboard petugas loket 1 |
| Operator Loket 2 | `http://localhost:8000/operator/2` | Dashboard petugas loket 2 |
| TV Display | `http://localhost:8000/tv` | Tampilan monitor/LED |
| Admin Panel | `http://localhost:8000/admin` | Reset sistem & statistik |

## Alur Kerja

1. **Pengunjung** datang dan memilih layanan di halaman utama → mendapat nomor antrian (contoh: A001, B001)
2. **Petugas** di loket manapun klik **"Panggil Berikutnya"** → nomor antrian paling lama menunggu dari layanan manapun akan dipanggil
3. **TV Display** menampilkan nomor yang dipanggil beserta loket tujuannya
4. **Petugas** klik **"Mulai Layanan"** → status berubah menjadi "Sedang Dilayani"
5. **Petugas** klik **"Selesai"** → antrian selesai, siap memanggil nomor berikutnya
6. **Semua loket** dapat memanggil nomor dari layanan **Pengaduan (A), Permohonan Informasi (B), Konsultasi (C)** manapun

## Struktur Database

- **services** — Jenis layanan (Pendaftaran, Pengaduan, Informasi, Konsultasi)
- **counters** — Loket/petugas (universal — semua loket melayani semua layanan)
- **queues** — Data antrian (nomor, status, waktu, layanan, loket)
- **queue_logs** — Riwayat aksi pada antrian

## Status Antrian

| Status | Deskripsi |
|--------|-----------|
| `waiting` | Menunggu dipanggil |
| `called` | Sudah dipanggil, menunggu dilayani |
| `serving` | Sedang dilayani petugas |
| `completed` | Selesai dilayani |
| `skipped` | Dilewati (tidak datang) |

## Teknologi

| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| Laravel | 11 | Framework PHP |
| Livewire | 3 | Komponen interaktif |
| Volt | 1 | API Laravel Reverb |
| Laravel Reverb | 1 | WebSocket server |
| Tailwind CSS | 3 | Styling |
| Alpine.js | 3 | Interaktivitas frontend |
| SQLite | - | Database |
| Vite | 5 | Build tool |

## Color Scheme

| Token | HEX | Penggunaan |
|-------|-----|------------|
| Primary | `#0B457F` | Tombol utama, Header |
| Primary Hover | `#083764` | Hover Button |
| Primary Light | `#EAF2FB` | Background Card |
| Secondary | `#FFD42B` | Accent, Highlight |
| Secondary Hover | `#F4C400` | Hover Accent |
| Success | `#16A34A` | Status berhasil |
| Warning | `#F59E0B` | Peringatan |
| Error | `#DC2626` | Error |
| Info | `#0284C7` | Informasi |
| Background | `#F8FAFC` | Halaman |
| Surface | `#FFFFFF` | Card |
| Border | `#E5E7EB` | Garis |
| Text Primary | `#1F2937` | Judul |
| Text Secondary | `#6B7280` | Deskripsi |

## Lisensi

Hak Cipta © 2024 UGM Super App
