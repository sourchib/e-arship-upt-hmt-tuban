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
        
        <div style="display: flex; gap: 12px;">
            <form action="{{ route('dashboard') }}" method="GET" style="position: relative; width: 220px;">
                <i data-lucide="search" style="position: absolute; left: 14px; top: 12px; width: 16px; height: 16px; color: #94a3b8;"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari..." 
                       style="width: 100%; padding: 10px 10px 10px 40px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 13px; color: #334155;">
            </form>
            
            <a href="javascript:void(0)" onclick="checkAdminAction('{{ route('dokumen.index', ['create' => 'true']) }}')" class="btn btn-primary" style="padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700; background: #16a34a; border: none; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                Baru
            </a>
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
        @if(Auth::check() && Auth::user()->role === 'Admin')
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
                        document.getElementById('stat-surat-masuk').innerText = data.stats.surat_masuk.toLocaleString();
                        document.getElementById('stat-surat-keluar').innerText = data.stats.surat_keluar.toLocaleString();
                        document.getElementById('stat-arsip-aktif').innerText = data.stats.arsip_aktif.toLocaleString();
                        document.getElementById('stat-arsip-inaktif').innerText = data.stats.arsip_inaktif.toLocaleString();
                    }
                    if (data.docs_html) {
                        document.getElementById('docsTableContainer').innerHTML = data.docs_html;
                    }
                    if (data.docs_html) {
                        lucide.createIcons();
                    }
                });
        }
    }

    // Initial fetch
    refreshDashboardData();
    
    // Set interval for subsequent fetches
    setInterval(refreshDashboardData, 10000);

    // ====== Preview Functionality ======
    function previewFile(url, title) {
        const modal = document.getElementById('previewModal');
        const iframe = document.getElementById('previewIframe');
        const img = document.getElementById('previewImage');
        const titleEl = document.getElementById('previewTitle');
        const loading = document.getElementById('previewLoading');
        const downloadBtn = document.getElementById('downloadButton');

        titleEl.innerText = title;
        downloadBtn.href = url;
        loading.style.display = 'block';
        iframe.style.display = 'none';
        img.style.display = 'none';
        modal.style.display = 'flex';

        const ext = url.split('.').pop().toLowerCase();
        if (['jpg', 'jpeg', 'png', 'gif', 'svg'].includes(ext)) {
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
