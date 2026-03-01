# E-Arsip UPT PT dan HMT Tuban

[![Laravel Framework](https://img.shields.io/badge/Framework-Laravel%2010-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-Proprietary-gray.svg)](#)

Sistem Informasi Kearsipan Digital Terpadu untuk Unit Pelaksana Teknis Pembibitan Ternak dan Hijauan Makanan Ternak (UPT PT dan HMT) Tuban. Dirancang untuk efisiensi pengelolaan surat-menyurat, arsip pembibitan, dan produksi hijauan secara real-time.

---

## 🚀 Fitur Utama

- **📊 Dashboard Interaktif**: Visualisasi data real-time, tren bulanan, dan log aktivitas terbaru.
- **✉️ Manajemen Surat (Masuk & Keluar)**: Pencatatan, unggah lampiran PDF, disposisi, dan pelacakan status surat (Draft, Terkirim, Pending).
- **🐄 Arsip Pembibitan**: Monitoring data ternak Sapi Peranakan Ongole (PO) beserta status distribusinya.
- **🌿 Arsip Hijauan**: Pencatatan produksi hijauan makanan ternak berdasarkan luas lahan dan lokasi.
- **📁 Manajemen Dokumen**: Sistem penyimpanan berkas umum yang terorganisir dengan fitur unduhan langsung.
- **👥 Manajemen Pengguna**: Kontrol akses berbasis peran (Admin, Operator, Pimpinan) dengan sistem persetujuan (Approval) akun baru.
- **🔍 Quick Navigation**: Fitur "Aksi Cepat" di dashboard untuk penginputan data instan.
- **⚡ Live Search & Filter**: Pencarian data tanpa reload menggunakan teknologi AJAX.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Frontend**: Blade Templating, Tailwind CSS (Design System kustom)
- **Database**: MySQL / MariaDB
- **Icons**: Lucide Icons
- **Components**: SweetAlert2 (Notifikasi & Konfirmasi), Custom Modal System

---

## ⚙️ Instalasi & Setup (Lokal)

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd laravel_mvc_earsip
   ```

2. **Install Dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` ke `.env` dan sesuaikan pengaturan database Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database & Seeding**
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Akses di: `http://localhost:8000`

---

## 🏗️ Persiapan Produksi (Production Ready)

Untuk deployment ke server produksi, pastikan langkah-langkah optimasi berikut dilakukan:

1. **Optimasi Cache**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **File Permissions**
   Pastikan folder `storage` dan `bootstrap/cache` dapat ditulisi oleh web server:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data .
   ```

3. **Symlink Storage**
   Untuk akses file unggahan:
   ```bash
   php artisan storage:link
   ```

4. **Environment Check**
   Pastikan `APP_DEBUG=false` dan `APP_ENV=production` pada file `.env`.

---

## 🔒 Keamanan & Role

| Role | Deskripsi |
| :--- | :--- |
| **Admin** | Akses penuh seluruh sistem, manajemen pengguna, dan penghapusan data. |

---

## 📞 Kontak & Support

Untuk pertanyaan lebih lanjut terkait penggunaan atau pemeliharaan sistem, silakan hubungi tim IT Internal UPT PT dan HMT Tuban.

---
© 2026 E-Arsip UPT PT dan HMT Tuban. All rights reserved.
