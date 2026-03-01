@extends('layouts.app')

@section('title', 'Dashboard - E-Arsip UPT PT dan HMT Tuban')

@section('content')
<div class="dashboard-header">
    <h2 style="margin: 0; font-size: 28px; font-weight: 700;">Dashboard</h2>
    <p style="color: #78909c; margin-top: 5px;">Selamat datang di Sistem E-Arsip UPT PT dan HMT Tuban</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid" style="margin-top: 30px;">
    <!-- Surat Masuk -->
    <div class="stat-card">
        <div class="stat-trend">
            <i data-lucide="trending-up"></i>
            +{{ $trends['surat_masuk'] }}%
        </div>
        <div class="stat-icon blue-icon">
            <i data-lucide="mail"></i>
        </div>
        <div class="stat-value">{{ $stats['surat_masuk'] }}</div>
        <div class="stat-label">Surat Masuk</div>
    </div>

    <!-- Surat Keluar -->
    <div class="stat-card">
        <div class="stat-trend" style="color: #4caf50;">
            <i data-lucide="trending-up"></i>
            +{{ $trends['surat_keluar'] }}%
        </div>
        <div class="stat-icon green-icon">
            <i data-lucide="send"></i>
        </div>
        <div class="stat-value">{{ $stats['surat_keluar'] }}</div>
        <div class="stat-label">Surat Keluar</div>
    </div>

    <!-- Arsip Pembibitan -->
    <div class="stat-card">
        <div class="stat-trend" style="color: #ff9800;">
            <i data-lucide="trending-up"></i>
            +{{ $trends['arsip_pembibitan'] }}%
        </div>
        <div class="stat-icon orange-icon">
            <i data-lucide="book-open"></i>
        </div>
        <div class="stat-value">{{ $stats['arsip_pembibitan'] }}</div>
        <div class="stat-label">Arsip Pembibitan</div>
    </div>

    <!-- Arsip Hijauan -->
    <div class="stat-card">
        <div class="stat-trend" style="color: #009688;">
            <i data-lucide="trending-up"></i>
            +{{ $trends['arsip_hijauan'] }}%
        </div>
        <div class="stat-icon teal-icon">
            <i data-lucide="leaf"></i>
        </div>
        <div class="stat-value">{{ $stats['arsip_hijauan'] }}</div>
        <div class="stat-label">Arsip Hijauan</div>
    </div>
</div>

<div class="dashboard-sections">
    <!-- Quick Actions -->
    <div class="section-card">
        <div class="section-title">Aksi Cepat</div>
        <div class="actions-grid">
            <a href="{{ route('surat-masuk.index', ['create' => 'true']) }}" class="action-item">
                <div class="stat-icon blue-icon" style="margin-bottom: 0;">
                    <i data-lucide="upload"></i>
                </div>
                <div class="action-info">
                    <h4>Upload Surat Masuk</h4>
                    <p>Tambah surat masuk baru</p>
                </div>
                <i data-lucide="arrow-up-right" style="margin-left: auto; color: #ccc;" size="16"></i>
            </a>

            <a href="{{ route('surat-keluar.index', ['create' => 'true']) }}" class="action-item">
                <div class="stat-icon green-icon" style="margin-bottom: 0;">
                    <i data-lucide="file-plus"></i>
                </div>
                <div class="action-info">
                    <h4>Buat Surat Keluar</h4>
                    <p>Buat dan kirim surat keluar</p>
                </div>
                <i data-lucide="arrow-up-right" style="margin-left: auto; color: #ccc;" size="16"></i>
            </a>

            <a href="{{ route('arsip-pembibitan.index', ['create' => 'true']) }}" class="action-item">
                <div class="stat-icon orange-icon" style="margin-bottom: 0;">
                    <i data-lucide="database"></i>
                </div>
                <div class="action-info">
                    <h4>Input Data Pembibitan</h4>
                    <p>Tambah data ternak baru</p>
                </div>
                <i data-lucide="arrow-up-right" style="margin-left: auto; color: #ccc;" size="16"></i>
            </a>

            <a href="{{ route('arsip-hijauan.index', ['create' => 'true']) }}" class="action-item">
                <div class="stat-icon teal-icon" style="margin-bottom: 0;">
                    <i data-lucide="plus-circle"></i>
                </div>
                <div class="action-info">
                    <h4>Input Data Hijauan</h4>
                    <p>Catat produksi hijauan</p>
                </div>
                <i data-lucide="arrow-up-right" style="margin-left: auto; color: #ccc;" size="16"></i>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="section-card">
        <div class="section-title">Aktivitas Terbaru</div>
        <ul class="activity-list">
            @foreach($recentActivities as $activity)
            <li class="activity-item">
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
    </div>
</div>
@endsection
