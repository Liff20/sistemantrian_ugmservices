# Sistem Antrian Humas UGM Services

Sistem antrian digital untuk Unit Layanan Terpadu UGM. Pengunjung mengambil nomor antrian melalui halaman web, petugas memanggil dan melayani antrian melalui dashboard operator, sedangkan nomor yang sedang dipanggil ditampilkan pada TV atau monitor secara real-time.

Semua loket dapat melayani semua jenis layanan. Jenis layanan bawaan aplikasi adalah Pengaduan, Permohonan Informasi, dan Konsultasi.

## Fitur

- Pengunjung mengambil nomor antrian berdasarkan jenis layanan.
- Sebelum nomor terbit, pengunjung wajib mengisi **email** dan **nomor WhatsApp** untuk keperluan pengiriman Survei Kepuasan Masyarakat (SKM).
- Setiap jenis layanan memiliki prefix nomor sendiri: A, B, dan C.
- Operator dapat memanggil, memulai layanan, menyelesaikan, atau melewati antrian.
- Semua loket dapat memanggil antrian dari semua layanan.
- TV display menampilkan nomor yang sedang dipanggil, loket, dan daftar antrian.
- TV display memutar video iklan secara bergantian dari folder `public/videos`.
- Pembaruan antarklien menggunakan Laravel Reverb WebSocket.
- Browser dapat membacakan nomor antrian melalui notifikasi suara (Text-to-Speech, Bahasa Indonesia).
- Operator dapat mengontrol suara panggilan antrian dan suara video TV dari halaman operator (tersinkron ke TV secara real-time).
- Admin dapat memantau statistik, mereset data operasional, dan mengunduh rekap CSV.
- Background halaman dan logo dapat dikustomisasi melalui folder `public/images`.

## Persyaratan

- PHP 8.2 atau lebih baru
- Composer
- Node.js 18 atau lebih baru dan NPM
- Ekstensi PHP SQLite dan PDO SQLite aktif
- Windows PowerShell, Command Prompt, atau terminal Laragon

## Instalasi di Windows

### 1. Siapkan PHP dan Composer

Cara paling mudah adalah menggunakan [Laragon](https://laragon.org/download/). Setelah Laragon terpasang:

1. Buka Laragon dan pilih **Start All**, atau buka terminal Laragon.
2. Pastikan PHP dan Composer tersedia:

```powershell
php -v
composer --version
```

Jika `php` tidak dikenali dan PHP Laragon berada di lokasi standar, tambahkan folder berikut ke **Environment Variables > Path**:

```text
C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64
```

Versi folder PHP dapat berbeda. Gunakan folder yang berisi file `php.exe`.

Jika Composer Laragon tidak dikenali, tambahkan folder berikut ke `Path`:

```text
C:\laragon\bin\composer
```

Tutup dan buka kembali terminal setelah mengubah `Path`.

### 2. Masuk ke folder proyek

Gunakan path proyek yang benar:

```powershell
cd "D:\Prototype System\sistemantrian_ugmservices"
```

### 3. Pasang dependensi PHP dan JavaScript

```powershell
composer install
npm install
```

Perintah `composer install` membuat folder `vendor`, sedangkan `npm install` membuat folder `node_modules`.

### 4. Buat file konfigurasi environment

Salin `.env.example` menjadi `.env`:

```powershell
Copy-Item .env.example .env
```

Jika `.env` sudah ada, jangan menyalinnya lagi karena konfigurasi lokal dapat tertimpa.

Generate application key:

```powershell
php artisan key:generate
```

Konfigurasi bawaan proyek menggunakan SQLite dan Laravel Reverb pada port 8080. Nilai pentingnya berada di `.env`:

```dotenv
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
BROADCAST_CONNECTION=reverb
REVERB_HOST=localhost
REVERB_PORT=8080
```

### 5. Siapkan database SQLite

Jika file database belum ada, buat file kosong berikut:

```powershell
New-Item -ItemType File -Path database\database.sqlite -Force
```

Jalankan migration:

```powershell
php artisan migrate
```

Isi data awal berupa tiga layanan dan dua loket:

```powershell
php artisan db:seed --class=QueueSeeder
```

Seeder ini mengosongkan data layanan dan loket lama sebelum membuat data awal. Gunakan hanya saat instalasi awal atau saat memang ingin membuat ulang data master tersebut.

## Menjalankan Aplikasi

Aplikasi membutuhkan server Laravel, Vite, dan Reverb. Jalankan masing-masing pada terminal terpisah.

### Terminal 1: Laravel web server

```powershell
cd "D:\Prototype System\sistemantrian_ugmservices"
php artisan serve
```

Buka aplikasi di [http://localhost:8000](http://localhost:8000).

### Terminal 2: Vite development server

```powershell
cd "D:\Prototype System\sistemantrian_ugmservices"
npm run dev
```

Vite menyediakan hot reload untuk CSS dan JavaScript. Biarkan terminal ini tetap berjalan selama pengembangan.

### Terminal 3: Laravel Reverb

```powershell
cd "D:\Prototype System\sistemantrian_ugmservices"
php artisan reverb:start --host=0.0.0.0 --port=8080
```

Reverb diperlukan agar perubahan nomor antrian dapat diteruskan secara real-time ke dashboard operator dan TV display.

Untuk menjalankan aplikasi tanpa hot reload, buat asset production terlebih dahulu:

```powershell
npm run build
```

Setelah itu cukup jalankan web server dan Reverb sesuai kebutuhan.

## Halaman Aplikasi

| Halaman | URL | Kegunaan |
| --- | --- | --- |
| Ambil Antrian | `http://localhost:8000/` | Pengunjung memilih layanan dan mengambil nomor |
| Operator Loket 1 | `http://localhost:8000/operator/1` | Mengelola antrian di Loket 1 |
| Operator Loket 2 | `http://localhost:8000/operator/2` | Mengelola antrian di Loket 2 |
| TV Display | `http://localhost:8000/tv` | Menampilkan panggilan untuk ruang tunggu |
| Admin | `http://localhost:8000/admin` | Monitoring, reset, dan rekap harian |

## Alur Operasional

1. Pengunjung membuka halaman utama dan memilih Pengaduan, Permohonan Informasi, atau Konsultasi.
2. Pengunjung mengisi email dan nomor WhatsApp, lalu menekan **Ambil Nomor Antrian**.
3. Sistem membuat nomor berikutnya berdasarkan prefix layanan, misalnya `A001`, `B001`, atau `C001`.
4. Operator membuka URL loketnya lalu menekan **Panggil Berikutnya**.
5. Sistem memilih antrian berstatus `waiting` yang paling lama dari semua layanan.
6. Nomor dan loket tujuan tampil pada dashboard operator dan TV display, dan nomor dibacakan melalui suara (jika suara panggilan aktif).
7. Operator menekan **Mulai Layanan** ketika pengunjung sudah dilayani.
8. Operator menekan **Selesai** setelah layanan selesai, atau **Lewati** jika nomor tidak datang.
9. Admin dapat melihat aktivitas dan mengunduh rekap berdasarkan tanggal.

## Status Antrian

| Status | Arti |
| --- | --- |
| `waiting` | Menunggu dipanggil |
| `called` | Sudah dipanggil dan menunggu dilayani |
| `serving` | Sedang dilayani |
| `completed` | Layanan selesai |
| `skipped` | Nomor dilewati karena pengunjung tidak datang |

## Struktur Data

- `services`: master jenis layanan, prefix, dan nomor terakhir.
- `counters`: daftar loket yang dapat melayani semua layanan.
- `queues`: nomor antrian, layanan, status, waktu proses, loket, serta email dan nomor WhatsApp pengunjung.
- `queue_logs`: catatan aktivitas seperti panggil, mulai layanan, selesai, dan lewati.

Migration database berada di `database/migrations`, sedangkan data awal berada di `database/seeders/QueueSeeder.php`.

## Rekap CSV

Admin dapat mengunduh rekap dari halaman `/admin`. Endpoint download menggunakan format:

```text
/admin/download/{tanggal}
```

Contoh:

```text
http://localhost:8000/admin/download/2026-08-19
```

File CSV berisi:

- daftar nomor antrian pada tanggal tersebut;
- layanan, loket, status, dan waktu setiap tahap;
- email dan nomor WhatsApp pengunjung; dan
- log aktivitas antrian.

File menggunakan BOM UTF-8 agar dapat dibuka dengan baik di Microsoft Excel.

## Kustomisasi Tampilan

### Background

Letakkan gambar background di:

```text
public/images/background.png
```

Overlay dan opacity diatur pada `resources/views/layouts/app.blade.php`.

### Logo

Logo header dapat diganti dengan file:

```text
public/images/logo_ugm_putih.png
```

### Video Iklan TV

Letakkan file video (`.mp4`, `.webm`, atau `.ogg`) di folder:

```text
public/videos
```

Semua file video di folder tersebut akan diputar bergantian secara otomatis di TV display.

### Suara Panggilan & Suara Video

Kontrol suara berada di halaman **operator** (kanan atas):

- **Suara Panggilan** — mengaktifkan/menonaktifkan pembacaan nomor antrian di TV.
- **Suara Video** — mengaktifkan/menonaktifkan suara video iklan di TV.

Kedua tombol tersedia di operator mana pun dan tersinkron secara real-time ke TV melalui Reverb. Suara panggilan menggunakan Text-to-Speech Bahasa Indonesia, dengan preferensi suara perempuan bila tersedia di perangkat TV. Pada layar TV, audio perlu diaktifkan sekali dengan menekan **"Klik untuk mengaktifkan suara"** (syarat autoplay browser).

### Styling

Style utama berada di `resources/css/app.css`. View Livewire berada di:

```text
resources/views/livewire
```

Setelah mengubah CSS atau JavaScript, biarkan `npm run dev` berjalan atau jalankan `npm run build` untuk asset production.

## Perintah Pemeliharaan

```powershell
# Membersihkan cache konfigurasi, route, dan view
php artisan optimize:clear

# Melihat daftar route
php artisan route:list

# Menjalankan ulang migration dari awal - menghapus seluruh data database
php artisan migrate:fresh

# Menjalankan ulang migration dan data master
php artisan migrate:fresh --seed --seeder=QueueSeeder
```

Perintah `migrate:fresh` bersifat destruktif. Jangan menjalankannya pada database yang berisi data operasional penting.

## Troubleshooting

### `php` tidak dikenali

Pastikan folder yang berisi `php.exe` sudah masuk `Path`, lalu buka terminal baru. Untuk instalasi Laragon, lokasi umumnya adalah:

```text
C:\laragon\bin\php\<versi-php>
```

Verifikasi dengan:

```powershell
Get-Command php
php -v
```

### `composer` tidak dikenali

Tambahkan folder Composer ke `Path`, atau gunakan terminal Laragon. Verifikasi dengan:

```powershell
Get-Command composer
composer --version
```

### `vendor/autoload.php` tidak ditemukan

Jalankan dari folder proyek:

```powershell
composer install
```

### Database SQLite gagal dibuka

Pastikan file berikut ada dan dapat ditulis:

```text
database/database.sqlite
```

Setelah itu jalankan:

```powershell
php artisan migrate
```

### Perubahan real-time tidak muncul

Pastikan tiga hal berikut berjalan:

1. `php artisan serve` pada port 8000;
2. `npm run dev`; dan
3. `php artisan reverb:start --host=0.0.0.0 --port=8080`.

Periksa juga nilai `REVERB_HOST`, `REVERB_PORT`, dan variabel `VITE_REVERB_*` pada `.env`.

### Suara panggilan atau kontrol suara tidak berfungsi

Pastikan Laravel Reverb berjalan (poin 3 di atas), karena kontrol suara dan pembacaan nomor disinkronkan ke TV melalui Reverb. Jika tombol suara di operator tampak menyala tetapi TV tidak berbunyi:

1. Pastikan `php artisan reverb:start --host=0.0.0.0 --port=8080` berjalan.
2. Pastikan aset JavaScript sudah ter-build (`npm run build`) setelah `.env` diatur.
3. Pada layar TV, klik **"Klik untuk mengaktifkan suara"** satu kali.
4. Jika suara panggilan terdengar berbahasa Inggris/beraksen asing, pasang voice **Bahasa Indonesia** di perangkat TV (mis. Chrome/Edge Windows: Settings → Time & Language → Speech → Add voices → Bahasa Indonesia).

## Teknologi

| Teknologi | Kegunaan |
| --- | --- |
| Laravel 11 | Framework aplikasi PHP |
| Livewire 3 | Komponen antarmuka interaktif |
| Laravel Reverb 1 | WebSocket real-time |
| Tailwind CSS 3 | Styling antarmuka |
| Alpine.js 3 | Interaktivitas sisi browser |
| SQLite | Database lokal |
| Vite 5 | Build dan hot reload asset |

## Lisensi

Hak Cipta (C) 2025 UGM University Service - Sistem Antrian Layanan.
