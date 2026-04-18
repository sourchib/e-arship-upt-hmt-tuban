@extends('layouts.app')

@section('title', 'Dashboard - E-Arsip UPT PT dan HMT Tuban')

@section('content')

{{-- ====== Dashboard Header ====== --}}
<div class="dashboard-header" style="margin-bottom: 28px;">
    <div class="header-title-area">
        <h2 style="margin: 0; font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em;">Dashboard</h2>
        <p style="margin: 4px 0 0; color: #64748b; font-size: 14px;">Selamat datang kembali, <strong>{{ Auth::user()->nama ?? 'Staff' }}</strong></p>
    </div>
</div>

{{-- ====== Welcome Banner ====== --}}
<div style="background: linear-gradient(135deg, #0f172a 0%, #16a34a 100%);
            border-radius: 18px;
            padding: 32px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.15);">
    {{-- decorative elements --}}
    <div style="position:absolute;width:300px;height:300px;background:rgba(255,255,255,0.03);border-radius:50%;top:-100px;right:-50px;"></div>
    
    <div style="position:relative;z-index:1;">
        <p style="margin:0 0 6px;font-size:12px;color:rgba(255,255,255,0.7);font-weight:600;letter-spacing:0.08em;text-transform:uppercase;">
            UPT PT dan HMT Tuban
        </p>
        <h3 style="margin:0 0 12px;font-size:24px;font-weight:800;color:#fff;line-height:1.2;">
            Sistem Elektronik Arsip Digital
        </h3>
        <p style="margin:0;font-size:14px;color:rgba(255,255,255,0.8);max-width:500px;line-height:1.6;">
            Kelola, simpan, dan telusuri dokumen dinas dengan sistem keamanan terstandarisasi secara efisien dan real-time.
        </p>
    </div>

    <div style="display:flex;gap:12px;flex-shrink:0;position:relative;z-index:1;">
        <a href="{{ route('surat-masuk.index') }}"
           style="padding:12px 24px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:12px;color:#fff;font-size:14px;font-weight:600;text-decoration:none;backdrop-filter:blur(10px);transition:all 0.2s;">
            <i data-lucide="mail" style="width:18px;height:18px;margin-bottom: -4px;"></i>
            Surat Masuk
        </a>
        <a href="{{ route('surat-keluar.index') }}"
           style="padding:12px 24px;background:#fff;border-radius:12px;color:#16a34a;font-size:14px;font-weight:700;text-decoration:none;box-shadow: 0 4px 12px rgba(0,0,0,0.1);transition:all 0.2s;">
            <i data-lucide="send" style="width:18px;height:18px;margin-bottom: -4px;"></i>
            Surat Keluar
        </a>
    </div>
</div>

{{-- ====== Stats Grid ====== --}}
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 32px;">
    
    {{-- Surat Masuk --}}
    <div class="stat-card" style="background: #fff; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 10px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 20px;">
        <div class="stat-icon" style="width: 56px; height: 56px; background: #eff6ff; color: #3b82f6; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="mail"></i>
        </div>
        <div>
            <div class="stat-value" id="stat-surat-masuk" style="font-size: 24px; font-weight: 800; color: #0f172a;">{{ number_format($stats['surat_masuk']) }}</div>
            <div class="stat-label" style="font-size: 14px; color: #64748b; font-weight: 500;">Surat Masuk</div>
        </div>
    </div>

    {{-- Surat Keluar --}}
    <div class="stat-card" style="background: #fff; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 10px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 20px;">
        <div class="stat-icon" style="width: 56px; height: 56px; background: #f0fdf4; color: #16a34a; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="send"></i>
        </div>
        <div>
            <div class="stat-value" id="stat-surat-keluar" style="font-size: 24px; font-weight: 800; color: #0f172a;">{{ number_format($stats['surat_keluar']) }}</div>
            <div class="stat-label" style="font-size: 14px; color: #64748b; font-weight: 500;">Surat Keluar</div>
        </div>
    </div>

    {{-- Arsip Aktif --}}
    <div class="stat-card" style="background: #fff; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 10px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 20px;">
        <div class="stat-icon" style="width: 56px; height: 56px; background: #f5f3ff; color: #7c3aed; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="check-circle"></i>
        </div>
        <div>
            <div class="stat-value" id="stat-arsip-aktif" style="font-size: 24px; font-weight: 800; color: #0f172a;">{{ number_format($stats['arsip_aktif']) }}</div>
            <div class="stat-label" style="font-size: 14px; color: #64748b; font-weight: 500;">Arsip Aktif</div>
        </div>
    </div>

    {{-- Arsip Inaktif --}}
    <div class="stat-card" style="background: #fff; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 10px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 20px;">
        <div class="stat-icon" style="width: 56px; height: 56px; background: #fff7ed; color: #ea580c; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="archive"></i>
        </div>
        <div>
            <div class="stat-value" id="stat-arsip-inaktif" style="font-size: 24px; font-weight: 800; color: #0f172a;">{{ number_format($stats['arsip_inaktif']) }}</div>
            <div class="stat-label" style="font-size: 14px; color: #64748b; font-weight: 500;">Arsip Inaktif</div>
        </div>
    </div>

</div>

{{-- ====== Main Content ====== --}}
<div style="background: #fff; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03); overflow: hidden;">
    <div style="padding: 24px; border-bottom: 1px solid #f8fafc; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a;">Manajemen Dokumen</h3>
            <p style="margin: 4px 0 0; font-size: 13px; color: #94a3b8;">Daftar dokumen arsip digital terbaru</p>
        </div>
        
        <div style="display: flex; gap: 8px; align-items: center;">
            <div style="position: relative; width: 220px;">
                <i data-lucide="search" style="position: absolute; left: 14px; top: 12px; width: 16px; height: 16px; color: #94a3b8;"></i>
                <input type="text" id="dashSearchInput" name="search" value="{{ request('search') }}" 
                       placeholder="Cari..." 
                       style="width: 100%; padding: 10px 10px 10px 40px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 13px; color: #334155; height: 44px;">
            </div>

            <div class="sort-dropdown">
                <button type="button" class="btn-sort" id="dashFilterBtn" title="Urutkan & Filter" style="background: #fff; border: 1.5px solid #e2e8f0; width: 44px; height: 44px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 12px; color: #64748b;">
                    <i data-lucide="filter" style="width:20px;height:20px;"></i>
                </button>
                <div class="filter-sidebar" id="dashFilterMenu">
                    <div class="filter-sidebar-header">
                        <span style="font-size: 15px; font-weight: 800; color: #0f172a;">Filter Dashboard</span>
                        <button type="button" id="closeDashFilter" style="background:none; border:none; color:#64748b; cursor:pointer;">
                            <i data-lucide="x" style="width:20px;height:20px;"></i>
                        </button>
                    </div>
                    
                    <div style="padding: 20px; flex: 1; overflow-y: auto;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Aksi Cepat</span>
                            <span id="dashResetFilter" style="font-size: 11px; color: #ef4444; font-weight: 700; cursor: pointer;">Reset</span>
                        </div>

                        <div class="filter-section">
                            <label class="filter-label">Urutkan</label>
                            <div class="filter-options-grid">
                                <div class="filter-opt-box-dash {{ (request('sort') == 'latest' || !request('sort')) ? 'active' : '' }}" data-value="latest">
                                    <i data-lucide="clock"></i> Terbaru
                                </div>
                                <div class="filter-opt-box-dash {{ request('sort') == 'oldest' ? 'active' : '' }}" data-value="oldest">
                                    <i data-lucide="history"></i> Terlama
                                </div>
                                <div class="filter-opt-box-dash {{ request('sort') == 'no_asc' ? 'active' : '' }}" data-value="no_asc">
                                    <i data-lucide="hash"></i> No Dok
                                </div>
                                <div class="filter-opt-box-dash {{ request('sort') == 'name_asc' ? 'active' : '' }}" data-value="name_asc">
                                    <i data-lucide="sort-asc"></i> A-Z
                                </div>
                                <div class="filter-opt-box-dash {{ request('sort') == 'name_desc' ? 'active' : '' }}" data-value="name_desc">
                                    <i data-lucide="sort-desc"></i> Z-A
                                </div>
                            </div>
                        </div>

                        <div class="filter-section" style="margin-top: 24px;">
                            <label class="filter-label">Kategori</label>
                            <div class="dash-filter-list">
                                <div class="dash-filter-item {{ (request('kategori') == 'Semua' || !request('kategori')) ? 'active' : '' }}" data-cat="Semua">
                                    <i data-lucide="layers"></i> Semua Kategori
                                </div>
                                @foreach($categories as $cat)
                                    @if($cat != 'Semua')
                                    <div class="dash-filter-item {{ request('kategori') == $cat ? 'active' : '' }}" data-cat="{{ $cat }}">
                                        <i data-lucide="tag"></i> {{ $cat }}
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="filter-sidebar-footer">
                        <button type="button" class="btn btn-primary" id="applyDashFilter" style="width: 100%; justify-content: center; height: 48px;">Terapkan</button>
                    </div>
                </div>
            </div>
            
            <input type="hidden" id="dashSortValue" value="{{ request('sort', 'latest') }}">
            <input type="hidden" id="dashKategoriValue" value="{{ request('kategori', 'Semua') }}">
            <input type="hidden" id="dashParentId" value="{{ request('parent_id') }}">
            
            <a href="javascript:void(0)" onclick="const pid = document.getElementById('dashParentId').value; checkAdminAction('{{ route('dokumen.index') }}?create=true' + (pid ? '&parent_id=' + pid : ''))" class="btn btn-primary" style="padding: 0 16px; height: 44px; border-radius: 12px; font-weight: 700; gap: 6px; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="plus" style="width: 18px; height: 18px;"></i>
                <span>Baru</span>
            </a>
        </div>
    </div>

    {{-- ====== Dashboard Folder Explorer ====== --}}
    <div id="dashFolderExplorer" style="margin: 0 24px 20px 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <div id="dashBreadcrumbs" style="display: flex; align-items: center; gap: 4px; font-size: 13px; color: #64748b;">
                <a href="javascript:void(0)" class="dash-bc-item" data-id="" style="color: #3b82f6; font-weight: 600; text-decoration: none;">Utama</a>
                @foreach($breadcrumbs ?? [] as $bc)
                    <i data-lucide="chevron-right" style="width: 14px; height: 14px; color: #cbd5e1;"></i>
                    <a href="javascript:void(0)" class="dash-bc-item" data-id="{{ $bc['id'] }}" style="color: #3b82f6; font-weight: 600; text-decoration: none;">{{ $bc['nama'] }}</a>
                @endforeach
            </div>
        </div>
        
        <div class="folder-grid" id="dashFolderGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px;">
            {{-- Utama/Back Card --}}
            <div class="folder-card dash-folder-item {{ !$parentId ? 'active' : '' }}" data-id="" style="background: #fff; border: 1px solid {{ !$parentId ? '#3b82f6' : '#f1f5f9' }}; border-radius: 12px; padding: 12px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; background: #eff6ff; color: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="home" style="width: 16px; height: 16px;"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 13px; font-weight: 700; color: #1e293b;">Utama</div>
                    <div style="font-size: 10px; color: #94a3b8;">{{ $totalDokumen ?? 0 }} Dokumen</div>
                </div>
            </div>

            {{-- Dynamic Folder Cards removed from dashboard grid as requested. Navigation is handled via the table below. --}}
        </div>
    </div>

    <div id="docsTableContainer">
        @include('dashboard._table')
    </div>

    @if($docsTerbaru->hasPages())
    <div style="padding: 20px 24px; border-top: 1px solid #f8fafc; background: #fcfcfd;">
        {{ $docsTerbaru->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

{{-- Preview Modal --}}
<div id="previewModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(8px); z-index: 9999; justify-content: center; align-items: center; padding: 40px;">
    <div style="position: relative; background: #fff; width: 90%; height: 90%; border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
        <div style="padding: 20px 28px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fcfcfd;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width:36px; height:36px; background:#f0fdf4; color:#16a34a; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                    <i data-lucide="file-text" style="width:20px; height:20px;"></i>
                </div>
                <h4 id="previewTitle" style="margin: 0; color: #0f172a; font-weight: 700; font-size: 16px;">Preview Dokumen</h4>
            </div>
            <button onclick="closePreview()" style="background: #f1f5f9; border: none; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; transition: all 0.2s;">
                <i data-lucide="x" style="width:20px; height:20px;"></i>
            </button>
        </div>
        <div style="flex: 1; display: flex; justify-content: center; align-items: center; background: #f8fafc; position: relative;">
            <div id="previewLoading" style="text-align: center;">
                <div style="width: 40px; height: 40px; border: 3px solid #f1f5f9; border-top-color: #16a34a; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 12px;"></div>
                <p style="color: #64748b; font-size: 14px;">Menyiapkan dokumen...</p>
            </div>
            <iframe id="previewIframe" style="width: 100%; height: 100%; border: none; display: none;"></iframe>
            <img id="previewImage" style="max-width: 95%; max-height: 95%; object-fit: contain; display: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 8px;" src="" alt="Preview">
        </div>
        <div style="padding: 16px 28px; border-top: 1px solid #f1f5f9; text-align: right; background: #fcfcfd;">
            <a id="downloadButton" href="#" class="btn btn-primary" style="background: #16a34a; border: none; font-weight: 700; padding: 10px 24px; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;">
                <i data-lucide="download" style="width: 18px; height: 18px;"></i>
                Unduh Dokumen
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes spin { to { transform: rotate(360deg); } }
    .stat-card:hover { transform: translateY(-5px); transition: transform 0.2s; border-color: #16a34a !important; }
</style>

@endsection

@push('scripts')
<script>
    lucide.createIcons();

    function checkAdminAction(redirectUrl) {
        @if(Auth::check() && (Auth::user()->role === 'Admin' || Auth::user()->role === 'Staff'))
            window.location.href = redirectUrl;
        @else
            window.location.href = "{{ route('login') }}";
        @endif
    }

    // ====== Real-time Refresh ======
    function refreshDashboardData() {
        const url = new URL(window.location.href);
        if (document.getElementById('previewModal').style.display === 'none') {
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.json())
                .then(data => {
                    if (data.stats) {
                        if(document.getElementById('stat-surat-masuk')) document.getElementById('stat-surat-masuk').innerText = data.stats.surat_masuk.toLocaleString();
                        if(document.getElementById('stat-surat-keluar')) document.getElementById('stat-surat-keluar').innerText = data.stats.surat_keluar.toLocaleString();
                        if(document.getElementById('stat-arsip-aktif')) document.getElementById('stat-arsip-aktif').innerText = data.stats.arsip_aktif.toLocaleString();
                        if(document.getElementById('stat-arsip-inaktif')) document.getElementById('stat-arsip-inaktif').innerText = data.stats.arsip_inaktif.toLocaleString();
                    }
                    
                    // Don't auto-refresh table if browsing deep folders via AJAX to avoid race conditions
                    // or just ensure we use current parentId
                    if(!document.getElementById('dashParentId').value) {
                         if (data.docs_html) {
                            document.getElementById('docsTableContainer').innerHTML = data.docs_html;
                            lucide.createIcons();
                        }
                    }
                });
        }
    }

    // Initial fetch
    // refreshDashboardData(); // Disable auto-refresh initially to not overwrite breadcrumbs
    
    // Set interval for subsequent fetches
    setInterval(refreshDashboardData, 30000); // 30s is enough

    // ====== Live Search & Sort Table ======
    const dashSearchInput   = document.getElementById('dashSearchInput');
    const dashSortValue     = document.getElementById('dashSortValue');
    const dashKategoriValue = document.getElementById('dashKategoriValue');
    const dashFilterMenu    = document.getElementById('dashFilterMenu');
    const filterOverlay     = document.getElementById('filterOverlay');
    
    let dashSearchTimer;

    const performDashUpdate = () => {
        const url = new URL(window.location.href);
        const searchVal = dashSearchInput.value.trim();
        const sortVal = dashSortValue.value;
        const catVal = dashKategoriValue.value;
        const parentId = document.getElementById('dashParentId').value;
        
        if (searchVal) url.searchParams.set('search', searchVal);
        else url.searchParams.delete('search');
        
        url.searchParams.set('sort', sortVal);
        url.searchParams.set('kategori', catVal);
        if (parentId) url.searchParams.set('parent_id', parentId);
        else url.searchParams.delete('parent_id');
        
        window.history.pushState({}, '', url);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => {
                // Update Table
                if (data.docs_html) {
                    document.getElementById('docsTableContainer').innerHTML = data.docs_html;
                    lucide.createIcons();
                    bindDashSortHeaders();
                }

                // Update Breadcrumbs
                if (data.breadcrumbs) {
                    let bcHtml = '<a href="javascript:void(0)" class="dash-bc-item" data-id="" style="color: #3b82f6; font-weight: 600; text-decoration: none;">Utama</a>';
                    data.breadcrumbs.forEach(bc => {
                        bcHtml += `<i data-lucide="chevron-right" style="width: 14px; height: 14px; color: #cbd5e1;"></i>
                                   <a href="javascript:void(0)" class="dash-bc-item" data-id="${bc.id}" style="color: #3b82f6; font-weight: 600; text-decoration: none;">${bc.nama}</a>`;
                    });
                    document.getElementById('dashBreadcrumbs').innerHTML = bcHtml;
                    lucide.createIcons();
                }

                // Update Folder Grid
                if (data.folders) {
                    const grid = document.getElementById('dashFolderGrid');
                    let gridHtml = `
                    <div class="folder-card dash-folder-item ${!data.parentId ? 'active' : ''}" data-id="" style="background: #fff; border: 1px solid ${!data.parentId ? '#3b82f6' : '#f1f5f9'}; border-radius: 12px; padding: 12px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; background: #eff6ff; color: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="home" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-size: 13px; font-weight: 700; color: #1e293b;">Utama</div>
                            <div style="font-size: 10px; color: #94a3b8;">${data.totalDokumen} Dokumen</div>
                        </div>
                    </div>`;

                    // Dynamic Folder Items removed from dashboard grid

                    grid.innerHTML = gridHtml;
                    lucide.createIcons();
                }

                if (data.categories) {
                    const filterList = dashFilterMenu.querySelector('.dash-filter-list');
                    if (filterList) {
                        let contentHtml = `<div class="dash-filter-item ${dashKategoriValue.value == 'Semua' ? 'active' : ''}" data-cat="Semua"><i data-lucide="layers"></i> Semua Kategori</div>`;
                        data.categories.forEach(cat => {
                            if (cat !== 'Semua') {
                                contentHtml += `<div class="dash-filter-item ${dashKategoriValue.value == cat ? 'active' : ''}" data-cat="${cat}"><i data-lucide="tag"></i> ${cat}</div>`;
                            }
                        });
                        filterList.innerHTML = contentHtml;
                        bindDashFilterEvents();
                    }
                }
            });
    };

    // Dashboard navigation delegates
    document.addEventListener('click', function(e) {
        const folderRow = e.target.closest('.dash-folder-row');
        if(folderRow) {
            document.getElementById('dashParentId').value = folderRow.dataset.id;
            performDashUpdate();
            return;
        }

        const folderCard = e.target.closest('.dash-folder-item');
        if(folderCard) {
            if (folderCard.dataset.id === "") {
                window.location.href = "{{ route('dashboard') }}";
            } else {
                document.getElementById('dashParentId').value = folderCard.dataset.id;
                performDashUpdate();
            }
            return;
        }


        const bcItem = e.target.closest('.dash-bc-item');
        if(bcItem) {
            if (bcItem.dataset.id === "") {
                window.location.href = "{{ route('dashboard') }}";
            } else {
                document.getElementById('dashParentId').value = bcItem.dataset.id;
                performDashUpdate();
            }
            return;
        }

    });

    if (dashSearchInput) {
        dashSearchInput.addEventListener('input', () => {
            clearTimeout(dashSearchTimer);
            dashSearchTimer = setTimeout(performDashUpdate, 400);
        });
    }

    const dashFilterBtn     = document.getElementById('dashFilterBtn');
    const closeDashFilter   = document.getElementById('closeDashFilter');
    const applyDashFilter   = document.getElementById('applyDashFilter');
    const dashResetFilter   = document.getElementById('dashResetFilter');

    function toggleDashFilter(show) {
        if(show) {
            dashFilterMenu.classList.add('show');
            document.body.style.overflow = 'hidden';
            if(filterOverlay) filterOverlay.style.display = 'block';
        } else {
            dashFilterMenu.classList.remove('show');
            document.body.style.overflow = '';
            if(filterOverlay) filterOverlay.style.display = 'none';
        }
    }

    if(dashFilterBtn) {
        dashFilterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDashFilter(!dashFilterMenu.classList.contains('show'));
        });
    }

    if(closeDashFilter) closeDashFilter.onclick = () => toggleDashFilter(false);
    if(applyDashFilter) applyDashFilter.onclick = () => toggleDashFilter(false);
    if(filterOverlay) filterOverlay.onclick = () => toggleDashFilter(false);

    if(dashResetFilter) {
        dashResetFilter.onclick = () => {
            dashSortValue.value = 'latest';
            dashKategoriValue.value = 'Semua';
            dashSearchInput.value = '';
            
            // Visual reset
            dashFilterMenu.querySelectorAll('.filter-opt-box-dash').forEach(i => i.classList.remove('active'));
            dashFilterMenu.querySelectorAll('.dash-filter-item').forEach(i => i.classList.remove('active'));
            
            const latestBtn = dashFilterMenu.querySelector('.filter-opt-box-dash[data-value="latest"]');
            const allCatBtn = dashFilterMenu.querySelector('.dash-filter-item[data-cat="Semua"]');
            if(latestBtn) latestBtn.classList.add('active');
            if(allCatBtn) allCatBtn.classList.add('active');

            // URL reset
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            url.searchParams.delete('sort');
            url.searchParams.delete('kategori');
            window.history.pushState({}, '', url);

            toggleDashFilter(false);
            performDashUpdate();
        };
    }

    function bindDashFilterEvents() {
        dashFilterMenu.querySelectorAll('.filter-opt-box-dash').forEach(item => {
            item.onclick = function() {
                dashFilterMenu.querySelectorAll('.filter-opt-box-dash').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                dashSortValue.value = this.dataset.value;
                performDashUpdate();
            };
        });

        dashFilterMenu.querySelectorAll('.dash-filter-item').forEach(item => {
            item.onclick = function() {
                dashFilterMenu.querySelectorAll('.dash-filter-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                dashKategoriValue.value = this.dataset.cat;
                performDashUpdate();
            };
        });
    }
    bindDashFilterEvents();

    function bindDashSortHeaders() {
        document.querySelectorAll('.sortable-header').forEach(header => {
            header.onclick = function() {
                const sortKey = this.dataset.sort;
                dashSortValue.value = sortKey;
                performDashUpdate();
            };
        });
    }
    bindDashSortHeaders();

    // Dash Print All Handler
    const dashPrintAllBtn = document.getElementById('dashPrintAllBtn');
    if (dashPrintAllBtn) {
        dashPrintAllBtn.addEventListener('click', () => {
            const url = new URL("{{ route('dokumen.print') }}");
            url.searchParams.set('search', dashSearchInput.value);
            url.searchParams.set('sort', dashSortValue.value);
            url.searchParams.set('kategori', dashKategoriValue.value);
            window.open(url.href, '_blank');
        });
    }

    // ====== Preview Functionality ======
    function previewFile(url, title, downloadUrl, mimeType) {
        const modal = document.getElementById('previewModal');
        const iframe = document.getElementById('previewIframe');
        const img = document.getElementById('previewImage');
        const titleEl = document.getElementById('previewTitle');
        const loading = document.getElementById('previewLoading');
        const downloadBtn = document.getElementById('downloadButton');

        titleEl.innerText = title;
        downloadBtn.href = downloadUrl || url;
        loading.style.display = 'block';
        iframe.style.display = 'none';
        img.style.display = 'none';
        modal.style.display = 'flex';

        const isImage = mimeType && mimeType.startsWith('image/');
        if (isImage) {
            img.src = url;
            img.onload = () => {
                loading.style.display = 'none';
                img.style.display = 'block';
            };
        } else {
            iframe.src = url;
            iframe.onload = () => {
                loading.style.display = 'none';
                iframe.style.display = 'block';
            };
        }
    }

    function closePreview() {
        document.getElementById('previewModal').style.display = 'none';
        document.getElementById('previewIframe').src = '';
        document.getElementById('previewImage').src = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closePreview();
    });
</script>
@endpush
