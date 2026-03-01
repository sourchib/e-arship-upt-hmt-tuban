<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Arsip UPT PT dan HMT Tuban - Digitalisasi Arsip Pembibitan Ternak</title>
    <meta name="description"
        content="Sistem Informasi Karsipan Modern UPT PT dan HMT Tuban for efficiency in managing breeding archives and forage.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/upt_logo.png') }}">
</head>

<body>

    <!-- Header / Navbar -->
    <header>
        <div class="container navbar">
            <div class="logo-container">
                <img src="{{ asset('assets/img/logo.png') }}" alt="E-Arsip Logo">
                <div class="logo-text">E-Arsip UPT PT & HMT</div>
            </div>
            <ul class="nav-links">
                <li><a href="#" class="nav-link active">Beranda</a></li>
                <li><a href="#tentang" class="nav-link">Tentang</a></li>
                <li><a href="#fitur" class="nav-link">Fitur</a></li>
                <li><a href="#kontak" class="nav-link">Kontak</a></li>
                <li><a href="{{ route('login') }}" class="btn-login">Login</a></li>
            </ul>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-tag">
                    <i data-lucide="sparkles" size="16"></i>
                    Sistem Informasi Kearsipan Digital Modern
                </div>
                <h1>Digitalisasi Arsip <br> <span style="color: var(--primary-light);">Pembibitan Ternak & Hijauan
                        Makanan Ternak</span></h1>
                <p>Wujudkan Kelola Modern Cepat Aman dan Terintegrasi dalam satu platform yang efisien.</p>
                <div class="hero-btns">
                    <a href="#" class="btn btn-primary">Mulai Sekarang <i data-lucide="arrow-right" size="18"></i></a>
                    <a href="#" class="btn btn-outline"><i data-lucide="play-circle" size="18"></i> Lihat Demo Video</a>
                    <a href="#" class="btn btn-outline"><i data-lucide="file-text" size="18"></i> Panduan Fitur</a>
                </div>
                <div class="hero-image-container">
                    <img src="{{ asset('assets/img/hero.png') }}" alt="Digital Dashboard Overview">
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="tentang" class="section-padding">
            <div class="container">
                <div class="about-grid">
                    <div class="about-img">
                        <img src="{{ asset('assets/img/about.png') }}" alt="Livestock Farm">
                    </div>
                    <div class="about-content">
                        <span class="section-tag">Tentang Instansi</span>
                        <h2 class="section-title">UPT PT dan HMT Tuban</h2>
                        <p>Unit Pelaksana Teknis Pembibitan Ternak dan Hijauan Makanan Ternak Tuban berfokus pada
                            pengembangan kualitas bibit ternak unggul dan ketersediaan pakan berkualitas.</p>
                        <ul class="checklist">
                            <li><i data-lucide="check-circle-2" class="check-icon" size="20"></i> Pendataan Masuk Hewan
                                Secara Digital</li>
                            <li><i data-lucide="check-circle-2" class="check-icon" size="20"></i> Monitoring Kesehatan
                                Ternak Terintegrasi</li>
                            <li><i data-lucide="check-circle-2" class="check-icon" size="20"></i> Pengelolaan Lahan
                                Hijauan Pakan Secara Efektif</li>
                        </ul>
                        <div class="location-box">
                            <i data-lucide="map-pin" class="info-icon" size="24"></i>
                            <div>
                                <strong>Lokasi Kami</strong>
                                <p>Jl. Tuban-Lamongan KM 07, Desa Widang, Kec. Widang, Kabupaten Tuban, Jawa Timur</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why E-Arsip? -->
        <section class="section-padding" style="background-color: var(--bg-light);">
            <div class="container features-container">
                <span class="section-tag">Manfaat</span>
                <h2 class="section-title" style="margin: 0 auto 12px; display: table;">Apa itu E-Arsip?</h2>
                <div class="features-grid">
                    <div class="feature-card blue">
                        <div class="feature-icon-box"><i data-lucide="cloud" size="24"></i></div>
                        <h3>Penyimpanan Aman</h3>
                        <p>Dokumen tersimpan aman di cloud dan mudah diakses kapan saja dan di mana saja.</p>
                    </div>
                    <div class="feature-card green">
                        <div class="feature-icon-box"><i data-lucide="search" size="24"></i></div>
                        <h3>Pencarian Cepat</h3>
                        <p>Temukan dokumen yang Anda cari dalam hitungan detik dengan fitur pencarian pintar.</p>
                    </div>
                    <div class="feature-card teal">
                        <div class="feature-icon-box"><i data-lucide="users" size="24"></i></div>
                        <h3>Kolaborasi Mudah</h3>
                        <p>Berbagi dokumen antar tim menjadi lebih mudah dan terorganisir dalam satu sistem.</p>
                    </div>
                    <div class="feature-card orange">
                        <div class="feature-icon-box"><i data-lucide="shield-check" size="24"></i></div>
                        <h3>Kontrol Akses</h3>
                        <p>Atur siapa saja yang bisa melihat dan mengelola dokumen sesuai dengan tingkatan jabatan.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Features -->
        <section id="fitur" class="section-padding">
            <div class="container">
                <div style="text-align: center; margin-bottom: 60px;">
                    <span class="section-tag">Keunggulan</span>
                    <h2 class="section-title" style="margin: 0 auto 12px; display: table;">Fitur Utama</h2>
                    <p>Fasilitas terbaik untuk kemudahan anda.</p>
                </div>
                <div class="main-features-grid">
                    <div class="main-feature-item">
                        <div class="main-feature-icon"><i data-lucide="layout" size="20"></i></div>
                        <div>
                            <h4>Dashboard Informatif</h4>
                            <p>Tampilan ringkas untuk memantau aktivitas dan status arsip secara real-time.</p>
                        </div>
                    </div>
                    <div class="main-feature-item">
                        <div class="main-feature-icon"><i data-lucide="folder-open" size="20"></i></div>
                        <div>
                            <h4>Klasifikasi Teratur</h4>
                            <p>Pengelompokan dokumen berdasarkan kategori untuk kemudahan manajemen arsip.</p>
                        </div>
                    </div>
                    <div class="main-feature-item">
                        <div class="main-feature-icon"><i data-lucide="message-square" size="20"></i></div>
                        <div>
                            <h4>Arsip Chat & Riwayat</h4>
                            <p>Catat setiap komunikasi dan perubahan pada dokumen secara kronologis.</p>
                        </div>
                    </div>
                    <div class="main-feature-item">
                        <div class="main-feature-icon"><i data-lucide="bell" size="20"></i></div>
                        <div>
                            <h4>Notifikasi Real-time</h4>
                            <p>Dapatkan pemberitahuan langsung untuk setiap dokumen baru atau pembaruan.</p>
                        </div>
                    </div>
                    <div class="main-feature-item">
                        <div class="main-feature-icon"><i data-lucide="user-check" size="20"></i></div>
                        <div>
                            <h4>Verifikasi Dokumen</h4>
                            <p>Alur persetujuan dan verifikasi dokumen yang jelas dan transparan.</p>
                        </div>
                    </div>
                    <div class="main-feature-item">
                        <div class="main-feature-icon"><i data-lucide="database" size="20"></i></div>
                        <div>
                            <h4>Backup Otomatis</h4>
                            <p>Sistem pencadangan berkala untuk menjamin keamanan data dari kehilangan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Workflow Section -->
        <section class="section-padding workflow">
            <div class="container">
                <span class="section-tag" style="color: rgba(255,255,255,0.8);">Proses</span>
                <h2 class="section-title">Alur Kerja</h2>
                <p>Bagaimana cara kerjanya?</p>
                <div class="workflow-grid">
                    <div class="workflow-step">
                        <div class="step-number">1</div>
                        <h4>Login</h4>
                        <p>Masuk ke akun Anda via portal resmi.</p>
                    </div>
                    <div class="workflow-step">
                        <div class="step-number">2</div>
                        <h4>Pilih Menu</h4>
                        <p>Pilih kategori dokumen yang ingin dikelola.</p>
                    </div>
                    <div class="workflow-step">
                        <div class="step-number">3</div>
                        <h4>Upload/Lihat</h4>
                        <p>Unggah file baru atau lihat dokumen yang ada.</p>
                    </div>
                    <div class="workflow-step">
                        <div class="step-number">4</div>
                        <h4>Selesai</h4>
                        <p>Tugas kearsipan Anda selesai dengan sukses.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="container" style="position: relative; z-index: 10;">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">1.500+</div>
                    <div class="stat-label">Total Arsip Digital</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">85</div>
                    <div class="stat-label">Pengguna Aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">240</div>
                    <div class="stat-label">Arsip Bulan Ini</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Kategori Arsip</div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="kontak" class="section-padding">
            <div class="container">
                <div style="text-align: center; margin-bottom: 60px;">
                    <span class="section-tag">Hubungi Kami</span>
                    <h2 class="section-title" style="margin: 0 auto 12px; display: table;">Ada Pertanyaan?</h2>
                </div>
                <div class="contact-grid">
                    <div class="contact-info">
                        <div class="info-item">
                            <i data-lucide="map-pin" class="info-icon" size="24"></i>
                            <div>
                                <strong>Alamat</strong>
                                <p>Jl. Tuban-Lamongan KM 07, Desa Widang, Kab. Tuban, Jawa Timur</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i data-lucide="phone" class="info-icon" size="24"></i>
                            <div>
                                <strong>Telepon</strong>
                                <p>(0356) 123456</p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i data-lucide="mail" class="info-icon" size="24"></i>
                            <div>
                                <strong>Email</strong>
                                <p>uptpt.hmt.tuban@jatimprov.go.id</p>
                            </div>
                        </div>
                    </div>
                    <form class="contact-form">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" placeholder="Masukkan nama Anda">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" placeholder="Masukkan email aktif Anda">
                        </div>
                        <div class="form-group">
                            <label>Pesan</label>
                            <textarea rows="4" placeholder="Tuliskan pesan atau pertanyaan Anda"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"
                            style="width: 100%; justify-content: center;">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container footer-grid">
            <div>
                <div class="footer-logo">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="E-Arsip Logo" style="height: 40px; filter: brightness(0) invert(1);">
                    <div style="font-weight: 700; font-size: 1.25rem;">E-Arsip UPT PT & HMT</div>
                </div>
                <p>Digitalisasi arsip pembibitan ternak untuk masa depan pertanian Indonesia yang lebih maju dan
                    terintegrasi.</p>
            </div>
            <div class="footer-links">
                <h4>Menu</h4>
                <ul>
                    <li><a href="#">Beranda</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="#fitur">Fitur Utama</a></li>
                    <li><a href="#kontak">Kontak</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Hubungi</h4>
                <ul>
                    <li><i data-lucide="map-pin" size="14" style="margin-right: 8px;"></i> Tuban, Jawa Timur</li>
                    <li><i data-lucide="mail" size="14" style="margin-right: 8px;"></i> uptpt.hmt.tuban@jatimprov.go.id
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                &copy; 2026 UPT PT dan HMT Tuban. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>
