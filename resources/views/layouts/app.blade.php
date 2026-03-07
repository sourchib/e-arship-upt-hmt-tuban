<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Arsip — Sistem Manajemen Arsip UPT PT dan HMT Tuban">
    <title>@yield('title', 'E-Arsip UPT PT dan HMT Tuban')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    @stack('css')
</head>
<body>
    <div class="wrapper">

        <!-- ====== SIDEBAR ====== -->
        <aside class="sidebar" id="appSidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo UPT">
                <div class="sidebar-brand-name">
                    E-Arsip
                    <small>UPT PT HMT Tuban</small>
                </div>
            </div>

            <nav class="sidebar-menu">
                <span class="menu-section-label">Menu Utama</span>

                <a href="{{ route('dashboard') }}"
                   class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   id="nav-dashboard">
                    <i data-lucide="layout-grid"></i>
                    Dashboard
                </a>

                <span class="menu-section-label" style="margin-top:8px;">Persuratan</span>

                <a href="{{ route('surat-masuk.index') }}"
                   class="menu-item {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}"
                   id="nav-surat-masuk">
                    <i data-lucide="mail"></i>
                    Surat Masuk
                </a>
                <a href="{{ route('surat-keluar.index') }}"
                   class="menu-item {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}"
                   id="nav-surat-keluar">
                    <i data-lucide="send"></i>
                    Surat Keluar
                </a>

                <span class="menu-section-label" style="margin-top:8px;">Arsip</span>

                <a href="{{ route('arsip-pembibitan.index') }}"
                   class="menu-item {{ request()->routeIs('arsip-pembibitan.*') ? 'active' : '' }}"
                   id="nav-arsip-pembibitan">
                    <i data-lucide="book-open"></i>
                    Arsip Pembibitan
                </a>
                <a href="{{ route('arsip-hijauan.index') }}"
                   class="menu-item {{ request()->routeIs('arsip-hijauan.*') ? 'active' : '' }}"
                   id="nav-arsip-hijauan">
                    <i data-lucide="leaf"></i>
                    Arsip Hijauan
                </a>

                <span class="menu-section-label" style="margin-top:8px;">Administrasi</span>

                <a href="{{ route('dokumen.index') }}"
                   class="menu-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}"
                   id="nav-dokumen">
                    <i data-lucide="file-text"></i>
                    Manajemen Dokumen
                </a>
                @if(Auth::check() && Auth::user()->role === 'Admin')
                <a href="{{ route('users.index') }}"
                   class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}"
                   id="nav-users">
                    <i data-lucide="users"></i>
                    Manajemen Pengguna
                </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <a href="#" class="menu-item" id="nav-settings">
                    <i data-lucide="settings"></i>
                    Pengaturan
                </a>
                @auth
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i data-lucide="log-out"></i>
                        Logout
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="menu-item" id="nav-login">
                    <i data-lucide="log-in"></i>
                    Login Admin
                </a>
                @endauth
            </div>
        </aside>

        <!-- ====== MAIN CONTENT ====== -->
        <main class="main-content" id="mainContent">

            <!-- Sticky Header -->
            <header class="header">
                <div class="d-flex align-items-center gap-3">
                    <button class="menu-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                        <i data-lucide="menu"></i>
                    </button>
                    <div class="search-bar">
                        <i data-lucide="search" class="search-icon"></i>
                        <input type="text" id="globalSearch" placeholder="Cari dokumen, surat, arsip...">
                    </div>
                </div>

                <div class="user-nav">
                    <div class="nav-icon" id="notifBtn" title="Notifikasi">
                        <i data-lucide="bell"></i>
                        <span class="badge-notif"></span>
                    </div>
                    <div class="user-profile">
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->nama ?? 'Staff' }}</span>
                            <span class="user-role">{{ Auth::user()->role ?? 'Umum' }}</span>
                        </div>
                        <div class="avatar">
                            {{ strtoupper(substr(Auth::user()->nama ?? 'S', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                @yield('content')
            </div>

        </main>
    </div>

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Scripts -->
    <script>
        // Init Lucide icons
        lucide.createIcons();

        // ---- Sidebar toggle ----
        const sidebar    = document.getElementById('appSidebar');
        const overlay    = document.getElementById('sidebarOverlay');
        const toggleBtn  = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('show');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        if (toggleBtn)  toggleBtn.addEventListener('click', openSidebar);
        if (overlay)    overlay.addEventListener('click', closeSidebar);

        // Close on resize back to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) closeSidebar();
        });

        // ---- SweetAlert2 flash messages ----
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#16a34a',
                timer: 3500,
                timerProgressBar: true,
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#dc2626',
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
