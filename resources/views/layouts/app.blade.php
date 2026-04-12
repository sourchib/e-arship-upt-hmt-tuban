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
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png?v=1.1') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/logo.png?v=1.1') }}">

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

        /* Top Navigation Buttons */
        .header-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-left: 20px;
        }
        .nav-btn-header {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 10px;
            color: #64748b;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.2s;
            border: 1.5px solid transparent;
        }
        .nav-btn-header i {
            width: 16px;
            height: 16px;
        }
        .nav-btn-header:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
        .nav-btn-header.active {
            background: #f0fdf4;
            color: #16a34a;
            border-color: #dcfce7;
        }

        /* Header UI Refinements */
        .header {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #f1f5f9;
            z-index: 1000;
            padding: 10px 24px !important;
        }

        .header-search-desktop {
            flex: 1;
            max-width: 400px;
            margin: 0 20px;
        }

        @media (max-width: 768px) {
            .header-nav span { display: none; }
            .header-search-desktop { display: none; }
        }
        /* Search Suggestions Header */
        .search-bar { position: relative; }
        .search-suggestions-header {
            position: absolute;
            top: 110%;
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            z-index: 1500;
            max-height: 400px;
            overflow-y: auto;
            display: none;
        }
        .suggestion-item {
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
            border-bottom: 1px solid #f8fafc;
        }
        .suggestion-item:last-child { border-bottom: none; }
        .suggestion-item:hover { background: #f0fdf4; }
        .suggestion-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #64748b;
            display: flex; align-items: center; justify-content: center;
        }
        .suggestion-item:hover .suggestion-icon { background: #16a34a; color: #fff; }
        .suggestion-text { font-size: 13.5px; font-weight: 600; color: #1e293b; }
        .suggestion-type { font-size: 10px; font-weight: 700; text-transform: uppercase; color: #94a3b8; }
    </style>
    @stack('css')
</head>
<body>
    <div class="wrapper no-sidebar">


        <!-- ====== MAIN CONTENT ====== -->
        <main class="main-content no-sidebar" id="mainContent">

            <!-- Sticky Header -->
            <header class="header">
                <div class="d-flex align-items-center justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        
                        <div class="top-brand d-flex align-items-center gap-2">
                            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 32px;">
                            <span class="brand-text" style="font-weight: 800; color: #1e293b; font-size: 16px;">E-Arsip</span>
                        </div>

                        <div class="header-nav">
                            <a href="{{ route('dashboard') }}" class="nav-btn-header {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i data-lucide="layout-grid"></i>
                                <span>Dashboard</span>
                            </a>
                            @if(Auth::check() && Auth::user()->role === 'Admin')
                            <a href="{{ route('users.index') }}" class="nav-btn-header {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i data-lucide="users"></i>
                                <span>Pengguna</span>
                            </a>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('search') }}" method="GET" class="header-search-desktop search-bar" id="globalSearchForm">
                        <i data-lucide="search" class="search-icon"></i>
                        <input type="text" name="q" id="globalSearchInput" placeholder="Cari arsip, surat, atau dokumen..." value="{{ request('q') }}" autocomplete="off">
                        <div id="globalSearchSuggestions" class="search-suggestions-header"></div>
                    </form>

                    <div class="user-nav d-flex align-items-center gap-3">
                        <div class="date-display d-none d-md-flex align-items-center gap-1" style="font-size: 13px; font-weight: 600; color: #64748b;">
                            <i data-lucide="calendar" style="width: 14px; height: 14px; color: #16a34a;"></i>
                            {{ date('d M Y') }}
                        </div>

                        <div class="nav-icon" id="notifBtn" title="Notifikasi" style="position:relative; cursor: pointer;">
                            <i data-lucide="bell"></i>
                            <span class="badge-notif"></span>
                            
                            <!-- Notification Dropdown -->
                            <div class="notif-dropdown" id="notifDropdown" style="display:none; position:absolute; top:120%; right:0; width:300px; background:#fff; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.1); border:1px solid #e2e8f0; z-index:1100;">
                                <div style="padding:14px 16px; border-bottom:1px solid #f1f5f9;">
                                    <h4 style="margin:0; font-size:14px; font-weight:700; color:#0f172a;">Aktivitas Terbaru</h4>
                                </div>
                                <div id="notifContent" style="max-height:300px; overflow-y:auto;">
                                    {{-- Will be populated by JS or simple loop --}}
                                </div>
                            </div>
                        </div>

                        @auth
                        <div class="user-profile d-flex align-items-center gap-2">
                            <div class="user-info d-none d-sm-flex flex-column text-end">
                                <span class="user-name" style="font-weight: 700; color: #1e293b; font-size: 13px;">{{ Auth::user()->nama }}</span>
                                <span class="user-role" style="font-size: 11px; color: #94a3b8;">{{ Auth::user()->role }}</span>
                            </div>
                            <div class="avatar" style="width: 36px; height: 36px; background: #16a34a; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                            </div>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" style="background: #f1f5f9; border: none; width: 36px; height: 36px; border-radius: 10px; color: #ef4444; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;" title="Logout">
                                    <i data-lucide="log-out" style="width: 18px; height: 18px;"></i>
                                </button>
                            </form>
                        </div>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content" style="padding: 24px;">
                @yield('content')
            </div>

        </main>
    </div>

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Scripts -->
    <script>
        lucide.createIcons();

        // Sidebar Toggle
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        if (toggleBtn) {
            toggleBtn.onclick = () => {
                sidebar.classList.add('show');
                overlay.classList.add('show');
            };
        }
        if (overlay) {
            overlay.onclick = () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            };
        }

        // ====== Real-time Notifications ======
        let lastSeenId = localStorage.getItem('lastSeenActivityId') || 0;
        let currentLatestId = 0;

        function refreshNotifications() {
            fetch("{{ route('notifications') }}", { 
                headers: { 'X-Requested-With': 'XMLHttpRequest' } 
            })
            .then(res => res.json())
            .then(data => {
                const notifContent = document.getElementById('notifContent');
                if (notifContent && data.activities_html) {
                    notifContent.innerHTML = data.activities_html;
                    currentLatestId = data.latest_id;
                    
                    // Update badge
                    const badge = document.querySelector('.badge-notif');
                    if (badge) {
                        // Only show badge if there's a new ID the user hasn't "read" (clicked)
                        if (currentLatestId > lastSeenId) {
                            badge.innerText = data.count > 9 ? '9+' : data.count;
                            badge.style.display = 'flex';
                            badge.style.position = 'absolute';
                            badge.style.top = '-5px';
                            badge.style.right = '-5px';
                            badge.style.width = '18px';
                            badge.style.height = '18px';
                            badge.style.background = '#ef4444';
                            badge.style.color = '#fff';
                            badge.style.fontSize = '10px';
                            badge.style.fontWeight = '800';
                            badge.style.borderRadius = '50%';
                            badge.style.border = '2px solid #fff';
                            badge.style.alignItems = 'center';
                            badge.style.justifyContent = 'center';
                            badge.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                    lucide.createIcons();
                }
            })
            .catch(err => console.error('Error fetching notifications:', err));
        }

        // Notification Toggle
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        if (notifBtn) {
            notifBtn.onclick = (e) => {
                e.stopPropagation();
                notifDropdown.style.display = notifDropdown.style.display === 'none' ? 'block' : 'none';
                
                // Mark as read when opened
                if (notifDropdown.style.display === 'block' && currentLatestId > 0) {
                    lastSeenId = currentLatestId;
                    localStorage.setItem('lastSeenActivityId', lastSeenId);
                    const badge = document.querySelector('.badge-notif');
                    if (badge) badge.style.display = 'none';
                }
            };
            document.onclick = (e) => {
                if (!notifBtn.contains(e.target)) notifDropdown.style.display = 'none';
            };
        }

        // Initial fetch
        refreshNotifications();
        // Set interval
        setInterval(refreshNotifications, 15000); // Check every 15s to be efficient

        // ====== Global Search Suggestions ======
        const globalSearchInput = document.getElementById('globalSearchInput');
        const globalSearchSuggestions = document.getElementById('globalSearchSuggestions');
        let searchTimer;

        if (globalSearchInput) {
            globalSearchInput.addEventListener('input', () => {
                const q = globalSearchInput.value.trim();
                clearTimeout(searchTimer);
                
                if (q.length < 2) {
                    globalSearchSuggestions.style.display = 'none';
                    return;
                }

                searchTimer = setTimeout(() => {
                    fetch(`{{ route('dashboard.suggestions') }}?q=${encodeURIComponent(q)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.length > 0) {
                                globalSearchSuggestions.innerHTML = '';
                                data.forEach(item => {
                                    const div = document.createElement('div');
                                    div.className = 'suggestion-item';
                                    div.innerHTML = `
                                        <div class="suggestion-icon"><i data-lucide="${item.icon}"></i></div>
                                        <div style="flex:1; min-width:0;">
                                            <div class="suggestion-type">${item.type}</div>
                                            <div class="suggestion-text" style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${item.text}</div>
                                        </div>
                                    `;
                                    div.onclick = () => window.location.href = item.url;
                                    globalSearchSuggestions.appendChild(div);
                                });
                                lucide.createIcons();
                                globalSearchSuggestions.style.display = 'block';
                            } else {
                                globalSearchSuggestions.style.display = 'none';
                            }
                        });
                }, 300);
            });

            document.addEventListener('click', (e) => {
                if (!globalSearchInput.contains(e.target) && !globalSearchSuggestions.contains(e.target)) {
                    globalSearchSuggestions.style.display = 'none';
                }
            });

            // Handle focus back
            globalSearchInput.addEventListener('focus', () => {
                if (globalSearchInput.value.trim().length >= 2 && globalSearchSuggestions.children.length > 0) {
                    globalSearchSuggestions.style.display = 'block';
                }
            });
        }

        // SweetAlert2
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}" });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
