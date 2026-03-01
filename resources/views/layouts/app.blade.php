<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Arsip UPT PT dan HMT Tuban')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2ecc71',
                    }
                }
            }
        }
    </script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/upt_logo.png') }}">
    @stack('css')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('assets/img/upt_logo.png') }}" alt="Logo">
                <div class="sidebar-brand-name">
                    E-Arsip
                    <small>UPT PT HMT Tuban</small>
                </div>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-grid"></i>
                    Dashboard
                </a>
                <a href="{{ route('surat-masuk.index') }}" class="menu-item {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}">
                    <i data-lucide="mail"></i>
                    Surat Masuk
                </a>
                <a href="{{ route('surat-keluar.index') }}" class="menu-item {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}">
                    <i data-lucide="send"></i>
                    Surat Keluar
                </a>
                <a href="{{ route('arsip-pembibitan.index') }}" class="menu-item {{ request()->routeIs('arsip-pembibitan.*') ? 'active' : '' }}">
                    <i data-lucide="book-open"></i>
                    Arsip Pembibitan
                </a>
                <a href="{{ route('arsip-hijauan.index') }}" class="menu-item {{ request()->routeIs('arsip-hijauan.*') ? 'active' : '' }}">
                    <i data-lucide="leaf"></i>
                    Arsip Hijauan
                </a>
                <a href="{{ route('dokumen.index') }}" class="menu-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                    <i data-lucide="file-text"></i>
                    Manajemen Dokumen
                </a>
                <a href="{{ route('users.index') }}" class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i data-lucide="users"></i>
                    Manajemen Pengguna
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="#" class="menu-item">
                    <i data-lucide="settings"></i>
                    Pengaturan
                </a>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout" style="color: #ef4444;">
                        <i data-lucide="log-out"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Content Area -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="d-flex align-items-center gap-3">
                    <button class="menu-toggle" id="sidebarToggle">
                        <i data-lucide="menu"></i>
                    </button>
                    <div class="search-bar">
                        <i data-lucide="search" class="search-icon"></i>
                        <input type="text" placeholder="Cari dokumen, surat, arsip...">
                    </div>
                </div>

                <div class="user-nav">
                    <div class="nav-icon">
                        <i data-lucide="bell"></i>
                        <span class="badge-notif"></span>
                    </div>
                    <div class="user-profile">
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->nama ?? 'admin' }}</span>
                            <span class="user-role">{{ Auth::user()->role ?? 'Operator' }}</span>
                        </div>
                        <div class="avatar">
                            {{ substr(Auth::user()->nama ?? 'Admin', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Scripts -->
    <script>
        lucide.createIcons();

        // Sidebar Toggle for Mobile
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        if(toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
        }

        if(overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        // SweetAlert2 Alerts
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#2ecc71',
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444',
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
