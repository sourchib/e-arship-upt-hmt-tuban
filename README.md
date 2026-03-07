# E-Arsip UPT PT dan HMT Tuban

[![Laravel Framework](https://img.shields.io/badge/Framework-Laravel%2010-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-Proprietary-gray.svg)](#)

Sistem Informasi Kearsipan Digital Terpadu untuk Unit Pelaksana Teknis Pembibitan Ternak dan Hijauan Makanan Ternak (UPT PT dan HMT) Tuban. Dirancang untuk efisiensi pengelolaan surat-menyurat, arsip pembibitan, dan produksi hijauan secara real-time.

---

## 🚀 Fitur Utama

- **📊 Dashboard Interaktif**: Visualisasi resolusi tinggi, tren, aksi cepat (*quick actions*), dan log aktivitas terbaru.
- **✉️ Manajemen Surat (Masuk & Keluar)**: Pencatatan, unggah lampiran, disposisi, dan pelacakan status surat (Draft, Terkirim, Pending).
- **🐄 Arsip Pembibitan**: Monitoring data ternak Sapi Peranakan Ongole (PO) beserta perihal pendistribusian.
- **🌿 Arsip Hijauan**: Pencatatan data lahan dan hasil panen pakan ternak.
- **📁 Manajemen Dokumen & Kategori**: Sistem pengunggahan dan direktori dokumen dengan pengarsipan ekstensi otomatis, serta *Manajemen Kategori Dokumen* kustom yang terintegrasi (Admin Only).
- **🔍 Pencarian Global (Global Search)**: Telusuri seluruh entri data (Surat, Arsip, Dokumen) dengan cepat melalui bilah navigasi utama melalui algoritma pencarian lintasan database.
- **🔔 Notifikasi Dinamis (Live Dropdown)**: Panel notifikasi seketika di sudut kanan atas seluruh pengguna yang terhubung langsung dengan catatan rekam aktivitas data baru.
- **👥 Single-Account Administration (Admin-Centric)**: Keamanan ekstra di mana hanya satu akun (yaitu Admin) yang memiliki hak wewenang akses dalam menambah, mengedit, atau menghapus seluruh catatan dan data arsip.
- **👀 Akses Penuh Publik/Staf (View-Only)**: Pengguna umum (Staf Umum/Publik) yang tidak memiliki kata sandi tetap dapat mencari, memfilter, melihat daftar data, serta mendownload seluruh dokumen/arsip.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 10 (PHP 8.1+)
- **Frontend**: Blade Templating, Vanilla CSS / Tailwind (Design System kustom terintegrasi UI Modern)
- **Database**: MySQL / MariaDB
- **Icons**: Lucide Icons
- **Components**: SweetAlert2 (Notifikasi & Konfirmasi Data)

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

## 🔒 Keamanan & Role

| Role | Deskripsi |
| :--- | :--- |
| **Admin** | Akses penuh. Dapat mengelola surat, arsip ternak/hijauan, manajemen dokumen, menambah kategori, sistem *login*, menghapus data, dsb. |
| **Staf Umum (Guest)** | Akses baca (*Read-only*). Dapat mengeksplorasi dashboard, melakukan *"Global Search"*, melihat daftar tabel data, mendownload file, tanpa perizinan merubah data di *database*. |

---

## 🖼️ Preview Tampilan

> Landing page resmi E-Arsip UPT PT dan HMT Tuban. Mencakup hero section, fitur utama, statistik, form kontak, dan pratinjau dashboard penuh.
<img width="983" height="6515" alt="landing page" src="https://github.com/user-attachments/assets/6a97b978-9009-42a0-b6f6-ef1883a7ff95" />

---

## 🗄️ Database & Mockup Data

Aplikasi ini menyertakan `Factory` dan `Seeder` untuk menyuntikkan puluhan data bayangan (*dummy data*) guna demonstrasi sistem. 

Untuk merekonstruksi *database* secara otomatis dengan susunan data uji coba:
```bash
php artisan migrate:fresh --seed
```

> **Akun Default Administrator:**
> - Email: `admin@earsip.id`
> - Password: `admin123`
> *(Satu-satunya akun log masuk yang tersedia di sistem untuk menjamin otorisasi tersentralisasi)*

---

## 📞 Kontak & Support

Untuk pertanyaan lebih lanjut terkait penggunaan atau pemeliharaan sistem pangkalan data E-Arsip ini beserta kode internalnya, silakan hubungi tim IT Internal UPT PT dan HMT Tuban.

---
© 2026 E-Arsip. All rights reserved.
