@extends('layouts.app')

@section('title', 'Dashboard - E-Arsip UPT PT dan HMT Tuban')

@section('content')

{{-- ====== Page Header ====== --}}
<div class="dashboard-header">
    <h2>Dashboard</h2>
    <p>Selamat datang, <strong>{{ Auth::user()->nama ?? 'Staff' }}</strong> — Sistem E-Arsip UPT PT dan HMT Tuban</p>
</div>

{{-- ====== Welcome Banner ====== --}}
<div style="background: linear-gradient(135deg, #0f172a 0%, #16a34a 100%);
            border-radius: 18px;
            padding: 28px 32px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            overflow: hidden;
            position: relative;">
    {{-- decorative circles --}}
    <div style="position:absolute;width:200px;height:200px;background:rgba(255,255,255,0.05);border-radius:50%;top:-60px;right:180px;"></div>
    <div style="position:absolute;width:140px;height:140px;background:rgba(255,255,255,0.05);border-radius:50%;bottom:-40px;right:80px;"></div>

    <div style="position:relative;z-index:1;">
        <p style="margin:0 0 6px;font-size:13px;color:rgba(255,255,255,0.65);font-weight:500;letter-spacing:0.05em;text-transform:uppercase;">
            UPT PT dan HMT Tuban
        </p>
        <h3 style="margin:0 0 10px;font-size:22px;font-weight:800;color:#fff;line-height:1.2;">
            Sistem Elektronik Arsip
        </h3>
        <p style="margin:0;font-size:13.5px;color:rgba(255,255,255,0.7);max-width:460px;line-height:1.6;">
            Kelola dan pantau seluruh dokumen, surat, dan arsip secara terpusat dan real-time.
        </p>
    </div>

    <div style="display:flex;gap:10px;flex-shrink:0;position:relative;z-index:1;flex-wrap:wrap;">
        <a href="{{ route('surat-masuk.index') }}"
           style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.25);border-radius:10px;color:#fff;font-size:13px;font-weight:600;text-decoration:none;backdrop-filter:blur(6px);transition:background 0.2s;">
            <i data-lucide="mail" style="width:16px;height:16px;"></i>
            Surat Masuk
        </a>
        <a href="{{ route('surat-keluar.index') }}"
           style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#fff;border-radius:10px;color:#16a34a;font-size:13px;font-weight:700;text-decoration:none;transition:opacity 0.2s;">
            <i data-lucide="send" style="width:16px;height:16px;"></i>
            Surat Keluar
        </a>
    </div>
</div>

{{-- ====== Stats Grid ====== --}}
<div class="stats-grid">

    {{-- Surat Masuk --}}
    <div class="stat-card">
        <div class="stat-trend">
            <i data-lucide="trending-up" style="width:13px;height:13px;"></i>
            +{{ $trends['surat_masuk'] }}%
        </div>
        <div class="stat-icon blue-icon">
            <i data-lucide="mail" style="width:20px;height:20px;"></i>
        </div>
        <div class="stat-value">{{ number_format($stats['surat_masuk']) }}</div>
        <div class="stat-label">Surat Masuk</div>
    </div>

    {{-- Surat Keluar --}}
    <div class="stat-card">
        <div class="stat-trend" style="background:#dcfce7;color:#16a34a;">
            <i data-lucide="trending-up" style="width:13px;height:13px;"></i>
            +{{ $trends['surat_keluar'] }}%
        </div>
        <div class="stat-icon green-icon">
            <i data-lucide="send" style="width:20px;height:20px;"></i>
        </div>
        <div class="stat-value">{{ number_format($stats['surat_keluar']) }}</div>
        <div class="stat-label">Surat Keluar</div>
    </div>

    {{-- Arsip Pembibitan --}}
    <div class="stat-card">
        <div class="stat-trend" style="background:#fff7ed;color:#ea580c;">
            <i data-lucide="trending-up" style="width:13px;height:13px;"></i>
            +{{ $trends['arsip_pembibitan'] }}%
        </div>
        <div class="stat-icon orange-icon">
            <i data-lucide="book-open" style="width:20px;height:20px;"></i>
        </div>
        <div class="stat-value">{{ number_format($stats['arsip_pembibitan']) }}</div>
        <div class="stat-label">Arsip Pembibitan</div>
    </div>

    {{-- Arsip Hijauan --}}
    <div class="stat-card">
        <div class="stat-trend" style="background:#f0fdfa;color:#0d9488;">
            <i data-lucide="trending-up" style="width:13px;height:13px;"></i>
            +{{ $trends['arsip_hijauan'] }}%
        </div>
        <div class="stat-icon teal-icon">
            <i data-lucide="leaf" style="width:20px;height:20px;"></i>
        </div>
        <div class="stat-value">{{ number_format($stats['arsip_hijauan']) }}</div>
        <div class="stat-label">Arsip Hijauan</div>
    </div>

</div>

{{-- ====== Dashboard Sections ====== --}}
<div class="dashboard-sections">

    {{-- Quick Actions --}}
    @if(Auth::check() && Auth::user()->role === 'Admin')
    <div class="section-card">
        <div class="section-header">
            <span class="section-title">⚡ Aksi Cepat</span>
            <span style="font-size:12px;color:#94a3b8;">Pintasan menu utama</span>
        </div>
        <div class="actions-grid">

            <a href="{{ route('surat-masuk.index', ['create' => 'true']) }}" class="action-item">
                <div class="stat-icon blue-icon">
                    <i data-lucide="upload" style="width:20px;height:20px;"></i>
                </div>
                <div class="action-info">
                    <h4>Upload Surat Masuk</h4>
                    <p>Tambah surat masuk baru</p>
                </div>
                <i data-lucide="arrow-up-right" class="action-arrow" style="width:16px;height:16px;"></i>
            </a>

            <a href="{{ route('surat-keluar.index', ['create' => 'true']) }}" class="action-item">
                <div class="stat-icon green-icon">
                    <i data-lucide="file-plus" style="width:20px;height:20px;"></i>
                </div>
                <div class="action-info">
                    <h4>Buat Surat Keluar</h4>
                    <p>Buat dan kirim surat keluar</p>
                </div>
                <i data-lucide="arrow-up-right" class="action-arrow" style="width:16px;height:16px;"></i>
            </a>

            <a href="{{ route('arsip-pembibitan.index', ['create' => 'true']) }}" class="action-item">
                <div class="stat-icon orange-icon">
                    <i data-lucide="database" style="width:20px;height:20px;"></i>
                </div>
                <div class="action-info">
                    <h4>Input Arsip Pembibitan</h4>
                    <p>Tambah data ternak baru</p>
                </div>
                <i data-lucide="arrow-up-right" class="action-arrow" style="width:16px;height:16px;"></i>
            </a>

            <a href="{{ route('arsip-hijauan.index', ['create' => 'true']) }}" class="action-item">
                <div class="stat-icon teal-icon">
                    <i data-lucide="plus-circle" style="width:20px;height:20px;"></i>
                </div>
                <div class="action-info">
                    <h4>Input Arsip Hijauan</h4>
                    <p>Catat produksi hijauan</p>
                </div>
                <i data-lucide="arrow-up-right" class="action-arrow" style="width:16px;height:16px;"></i>
            </a>

        </div>
    </div>
    @endif

    {{-- Recent Activity --}}
    <div class="section-card">
        <div class="section-header">
            <span class="section-title">🕐 Aktivitas Terbaru</span>
        </div>

        @if($recentActivities->isEmpty())
            <div style="text-align:center;padding:40px 0;color:#94a3b8;">
                <i data-lucide="inbox" style="width:40px;height:40px;opacity:0.4;margin-bottom:12px;display:block;margin-inline:auto;"></i>
                <p style="font-size:13px;">Belum ada aktivitas tercatat.</p>
            </div>
        @else
            <ul class="activity-list">
                @foreach($recentActivities as $activity)
                <li class="activity-item">
                    <div class="activity-dot"></div>
                    <div class="activity-main">
                        <span class="activity-title">{{ $activity->modul }}</span>
                        <span class="activity-desc">{{ $activity->deskripsi }}</span>
                    </div>
                    <div class="activity-meta">
                        <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                        <span class="status-badge {{ $activity->badge_class }}">{{ $activity->status }}</span>
                    </div>
                </li>
                @endforeach
            </ul>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Re-init icons after Blade renders
    lucide.createIcons();
</script>
@endpush
