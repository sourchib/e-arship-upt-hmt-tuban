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

## �️ Preview Tampilan

> Landing page resmi E-Arsip UPT PT dan HMT Tuban. Mencakup hero section, fitur utama, statistik, dan form kontak.

---

## 🗄️ Mockup Data (Dummy Data)

Data dummy disediakan untuk keperluan testing dan demonstrasi sistem. Jalankan perintah berikut untuk mengisi database secara otomatis:

```bash
php artisan migrate:fresh --seed
```

Data yang di-generate mencakup semua tabel, sebagai berikut:

### 👤 Users (11 records)
| Field | Contoh Nilai |
|:---|:---|
| nama | Administrator |
| email | admin@earsip.id |
| password | admin123 |
| role | Admin / Operator / Pimpinan |
| instansi | UPT PT dan HMT Tuban |
| status | Aktif / Pending / Nonaktif |
| tanggal_daftar | 2024-01-15 |

### ✉️ Surat Masuk (20 records)
| Field | Contoh Nilai |
|:---|:---|
| nomor_surat | SM/001/2025 |
| pengirim | Dinas Peternakan Jawa Timur |
| perihal | Permintaan Laporan Bulanan |
| tanggal_surat | 2025-11-10 |
| tanggal_terima | 2025-11-12 |
| prioritas | Tinggi / Sedang / Rendah |
| status | Pending / Diproses / Terarsip |
| file_path | uploads/surat_masuk/dummy.pdf |

### 📤 Surat Keluar (20 records)
| Field | Contoh Nilai |
|:---|:---|
| nomor_surat | SK/001/2025 |
| tujuan | Balai Besar Veteriner Wates |
| perihal | Pengiriman Data Produksi Ternak |
| tanggal_surat | 2025-11-15 |
| tanggal_kirim | 2025-11-16 |
| prioritas | Tinggi / Sedang / Rendah |
| status | Draft / Terkirim / Selesai |

### 🐄 Arsip Pembibitan (15 records)
| Field | Contoh Nilai |
|:---|:---|
| kode | AP-001 |
| jenis_ternak | Sapi / Kambing / Domba |
| jumlah | 25 ekor |
| umur | 18 Bulan |
| tujuan | Kab. Bojonegoro |
| tanggal | 2025-08-20 |
| status | Terdistribusi / Proses |

### 🌿 Arsip Hijauan (15 records)
| Field | Contoh Nilai |
|:---|:---|
| kode_lahan | LH-001 |
| jenis_hijauan | Rumput Gajah / Odot / Indigofera |
| luas | 2.5 ha |
| produksi | 3750.00 kg |
| tanggal_panen | 2025-09-05 |
| lokasi | Blok A3 |
| status | Tersedia / Terdistribusi |

### 📁 Dokumen (25 records)
| Field | Contoh Nilai |
|:---|:---|
| nama | laporan_bulanan.pdf |
| kategori | Laporan / SK / Nota Dinas / Lainnya |
| ukuran | 512000 bytes (512 KB) |
| mime_type | application/pdf |
| tanggal_upload | 2025-10-01 |
| download_counter | 47 |

### 🕓 Log Aktivitas (50 records)
| Field | Contoh Nilai |
|:---|:---|
| jenis_aktivitas | Login / Logout / Create / Update / Delete / Download |
| modul | Surat Masuk / Surat Keluar / Arsip Pembibitan |
| deskripsi | User melakukan login ke sistem. |
| ip_address | 192.168.1.10 |

> **Akun Default:**
> - Email: `admin@earsip.id`
> - Password: `admin123`
> - Role: Admin (akses penuh)

---




## 📞 Kontak & Support

Untuk pertanyaan lebih lanjut terkait penggunaan atau pemeliharaan sistem, silakan hubungi tim IT Internal UPT PT dan HMT Tuban.

---
© 2026 E-Arsip UPT PT dan HMT Tuban. All rights reserved.
