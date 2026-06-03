# motocare

Aplikasi Laravel 12 untuk manajemen servis atau perawatan motor. Repository ini mencakup peningkatan antarmuka Filament, autentikasi, dan fitur admin khusus untuk mengelola layanan kendaraan, pelanggan, serta alur kerja pemeliharaan.

## Fitur

- Backend Laravel 12 dengan Livewire dan Filament
- Dukungan autentikasi dan panel admin
- Pipeline frontend Tailwind CSS + Vite
- Plugin Filament untuk UI yang lebih baik dan pengeditan profil
- Dukungan pembuatan kode QR

## Persyaratan

- PHP ^8.2
- Composer
- Node.js dan npm
- MySQL atau basis data kompatibel

## Instalasi

1. Clone repository
   ```bash
   git clone https://github.com/LuSiNa03/motocare.git
   cd motocare/src
   ```

2. Install dependency PHP
   ```bash
   composer install
   ```

3. Install dependency frontend
   ```bash
   npm install
   ```

4. Salin file environment dan buat key aplikasi
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Konfigurasi kredensial database di `.env`

6. Jalankan migrasi dan seeder
   ```bash
   php artisan migrate --seed
   ```

7. Build aset atau jalankan server development
   ```bash
   npm run build
   npm run dev
   ```

## Pengembangan

- `npm run dev` — jalankan server Vite untuk development
- `npm run build` — build aset produksi
- `php artisan serve` — jalankan server Laravel lokal

## Kontribusi

Kontribusi, issue, dan permintaan fitur sangat diterima. Silakan fork repository ini dan kirimkan pull request.

## Lisensi

Proyek ini dilisensikan di bawah MIT License. Lihat `LICENSE` untuk detail.

## Default Credentials

Akun berikut dibuat otomatis oleh seeder saat menjalankan `php artisan migrate --seed`:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | superadmin@gmail.com | password |
| Pegawai | pegawai@gmail.com | password |
| User | bagas@gmail.com | password |
| User | tupen@gmail.com | password |
| User | farel@gmail.com | password |
| User | iis@gmail.com | password |

Anda dapat menyesuaikan nilai ini melalui file `.env` sebelum menjalankan seeder.
