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
    <style>
        .no-sidebar .main-content {
            margin-left: 0 !important;
        }
        .no-sidebar .header {
            padding-left: 36px;
        }
        .no-sidebar .menu-toggle {
            display: none !important;
        }
        @media (max-width: 768px) {
            .no-sidebar .header {
                padding-left: 18px;
            }
        }
    </style>
    @stack('css')
</head>
<body>
    <div class="wrapper {{ (Auth::check() && Auth::user()->role === 'Admin') ? '' : 'no-sidebar' }}">

        @if(Auth::check() && Auth::user()->role === 'Admin')
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
                <!-- <a href="{{ route('arsip-hijauan.index') }}"
                   class="menu-item {{ request()->routeIs('arsip-hijauan.*') ? 'active' : '' }}"
                   id="nav-arsip-hijauan">
                    <i data-lucide="leaf"></i>
                    Arsip Hijauan
                </a> -->

                <span class="menu-section-label" style="margin-top:8px;">Administrasi</span>

                <!-- <a href="{{ route('dokumen.index') }}"
                   class="menu-item {{ request()->routeIs('dokumen.*') ? 'active' : '' }}"
                   id="nav-dokumen">
                    <i data-lucide="file-text"></i>
                    Manajemen Dokumen
                </a> -->
                @if(Auth::check() && Auth::user()->role === 'Admin')
                <a href="{{ route('kategori-dokumen.index') }}"
                   class="menu-item {{ request()->routeIs('kategori-dokumen.*') ? 'active' : '' }}"
                   id="nav-kategori-dokumen">
                    <i data-lucide="folder-tree"></i>
                    Kategori Dokumen
                </a>
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
        @endif

        <!-- ====== MAIN CONTENT ====== -->
        <main class="main-content" id="mainContent">

            <!-- Sticky Header -->
            <header class="header">
                <div class="d-flex align-items-center gap-3">
                    <button class="menu-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                        <i data-lucide="menu"></i>
                    </button>
                    <form action="{{ route('search') }}" method="GET" class="search-bar">
                        <i data-lucide="search" class="search-icon"></i>
                        <input type="text" name="q" id="globalSearch" placeholder="Cari dokumen, surat, arsip..." value="{{ request('q') }}">
                    </form>
                </div>

                <div class="user-nav">
                    <div class="nav-icon" id="notifBtn" title="Notifikasi" style="position:relative;">
                        <i data-lucide="bell"></i>
                        <span class="badge-notif"></span>
                        
                        <!-- Notification Dropdown -->
                        <div class="notif-dropdown" id="notifDropdown" style="display:none; position:absolute; top:120%; right:0; width:300px; background:#fff; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.1); border:1px solid #e2e8f0; z-index:100; text-align:left; cursor:default;">
                            @php
                                $recentNotifs = \App\Models\LogAktivitas::where('jenis_aktivitas', 'Create')->latest()->take(5)->get();
                                $notifCount = $recentNotifs->count();
                            @endphp
                            <div style="padding:14px 16px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                                <h4 style="margin:0; font-size:14px; font-weight:700; color:#0f172a;">Notifikasi Terbaru</h4>
                                @if($notifCount > 0)
                                <span style="font-size:11px; color:#16a34a; background:#dcfce7; padding:2px 8px; border-radius:10px; font-weight:600;">{{ $notifCount }} Baru</span>
                                @endif
                            </div>
                            <div style="max-height:300px; overflow-y:auto; padding:8px 0;">
                                @forelse($recentNotifs as $notif)
                                <div style="display:flex; gap:12px; padding:12px 16px; transition:background 0.2s; cursor:pointer;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                    <div style="width:32px; height:32px; background:#e0f2fe; color:#0284c7; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        @if($notif->modul == 'Surat Masuk' || $notif->modul == 'Surat Keluar')
                                            <i data-lucide="mail" style="width:16px; height:16px;"></i>
                                        @elseif($notif->modul == 'Kategori Dokumen')
                                            <i data-lucide="folder-tree" style="width:16px; height:16px;"></i>
                                        @elseif($notif->modul == 'Manajemen Dokumen')
                                            <i data-lucide="file-text" style="width:16px; height:16px;"></i>
                                        @else
                                            <i data-lucide="bell" style="width:16px; height:16px;"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p style="margin:0; font-size:13px; font-weight:600; color:#0f172a;">Data Baru: {{ $notif->modul }}</p>
                                        <p style="margin:2px 0 0; font-size:12px; color:#64748b; line-height:1.4;">{{ $notif->deskripsi }}</p>
                                        <span style="font-size:10px; color:#94a3b8; display:block; margin-top:4px;">{{ $notif->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                @empty
                                <div style="padding: 20px; text-align: center; color: #94a3b8; font-size: 13px;">
                                    Belum ada pemberitahuan data baru.
                                </div>
                                @endforelse
                            </div>
                            <div style="padding:10px; text-align:center; border-top:1px solid #f1f5f9;">
                                <a href="#" style="font-size:12px; color:#16a34a; font-weight:600; text-decoration:none;">Tandai semua sudah dibaca</a>
                            </div>
                        </div>
                    </div>
                    @auth
                        <div class="user-profile">
                            <div class="user-info">
                                <span class="user-name">{{ Auth::user()->nama ?? 'Staff' }}</span>
                                <span class="user-role">{{ Auth::user()->role ?? 'Umum' }}</span>
                            </div>
                            <div class="avatar">
                                {{ strtoupper(substr(Auth::user()->nama ?? 'S', 0, 1)) }}
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">
                            <i data-lucide="log-in" style="width:16px;height:16px;"></i>
                            Login Admin
                        </a>
                    @endauth
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

        // ---- Notification Dropdown Toggle ----
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        if (notifBtn && notifDropdown) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.style.display = notifDropdown.style.display === 'none' ? 'block' : 'none';
            });
            
            // Close when clicking outside
            document.addEventListener('click', (e) => {
                if (!notifBtn.contains(e.target)) {
                    notifDropdown.style.display = 'none';
                }
            });
        }

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
