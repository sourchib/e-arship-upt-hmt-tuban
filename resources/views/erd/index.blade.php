@extends('layouts.app')

@section('title', 'ERD — Struktur Database | E-Arsip UPT PT dan HMT Tuban')

@push('css')
<style>
    /* ===== ERD PAGE STYLES ===== */
    .erd-page {
        min-height: calc(100vh - 80px);
        background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 50%, #f0f9ff 100%);
    }

    /* Header Section */
    .erd-hero {
        background: linear-gradient(135deg, #0f4c2a 0%, #166534 50%, #15803d 100%);
        border-radius: 20px;
        padding: 36px 40px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .erd-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
    }
    .erd-hero::after {
        content: '';
        position: absolute;
        bottom: -40px; left: -40px;
        width: 150px; height: 150px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }
    .erd-hero-title {
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        margin: 0 0 6px 0;
    }
    .erd-hero-subtitle {
        font-size: 14px;
        color: rgba(255,255,255,0.75);
        margin: 0;
    }
    .erd-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        color: #bbf7d0;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 14px;
    }

    /* Stats Row */
    .erd-stats {
        display: flex;
        gap: 16px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }
    .erd-stat-card {
        flex: 1;
        min-width: 140px;
        background: #fff;
        border-radius: 14px;
        padding: 18px 20px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }
    .erd-stat-card:hover {
        border-color: #16a34a;
        box-shadow: 0 4px 16px rgba(22,163,74,0.1);
        transform: translateY(-2px);
    }
    .erd-stat-icon {
        width: 42px; height: 42px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
    }
    .erd-stat-icon svg { width: 20px; height: 20px; }
    .erd-stat-num { font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1; }
    .erd-stat-label { font-size: 12px; color: #64748b; font-weight: 500; margin-top: 2px; }

    /* ERD Canvas Wrapper */
    .erd-canvas-wrapper {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        overflow: hidden;
        margin-bottom: 28px;
    }
    .erd-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid #f1f5f9;
        background: #fafafa;
        flex-wrap: wrap;
        gap: 10px;
    }
    .erd-toolbar-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .erd-toolbar-actions {
        display: flex;
        gap: 8px;
    }
    .erd-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .erd-btn:hover { background: #f1f5f9; border-color: #94a3b8; color: #0f172a; }
    .erd-btn.primary { background: #16a34a; border-color: #16a34a; color: #fff; }
    .erd-btn.primary:hover { background: #15803d; border-color: #15803d; }
    .erd-btn svg { width: 14px; height: 14px; }

    /* SVG Diagram */
    #erd-svg-container {
        width: 100%;
        overflow: auto;
        padding: 24px;
        background: #f8fafc;
        min-height: 560px;
        cursor: grab;
        user-select: none;
    }
    #erd-svg-container:active { cursor: grabbing; }
    #erd-svg {
        min-width: 1400px;
        height: 700px;
        display: block;
        margin: 0 auto;
    }

    /* Table Nodes */
    .table-node {
        cursor: pointer;
        transition: filter 0.2s;
    }
    .table-node:hover { filter: drop-shadow(0 8px 24px rgba(0,0,0,0.15)); }
    .table-header-rect {
        rx: 8;
        ry: 8;
    }
    .table-body-rect {
        fill: #fff;
        stroke: #e2e8f0;
        stroke-width: 1.5;
    }
    .table-col-row:hover { fill: #f0fdf4; }

    /* Zoom Controls */
    .zoom-controls {
        position: absolute;
        bottom: 20px;
        right: 20px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        z-index: 10;
    }
    .zoom-btn {
        width: 32px; height: 32px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        font-size: 16px;
        font-weight: 700;
        color: #374151;
        transition: all 0.2s;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .zoom-btn:hover { background: #f0fdf4; border-color: #16a34a; color: #16a34a; }

    /* Table Detail Cards */
    .table-detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }
    .table-detail-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: all 0.25s;
    }
    .table-detail-card:hover {
        border-color: var(--card-color, #16a34a);
        box-shadow: 0 6px 24px rgba(0,0,0,0.09);
        transform: translateY(-2px);
    }
    .card-table-header {
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card-table-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,0.25);
    }
    .card-table-icon svg { width: 18px; height: 18px; color: #fff; }
    .card-table-name {
        font-size: 14px;
        font-weight: 800;
        color: #fff;
        letter-spacing: 0.3px;
    }
    .card-table-subtitle {
        font-size: 11px;
        color: rgba(255,255,255,0.8);
        margin-top: 1px;
    }
    .card-col-list { padding: 4px 0 8px; }
    .card-col-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 7px 18px;
        border-bottom: 1px solid #f8fafc;
        transition: background 0.15s;
    }
    .card-col-row:last-child { border-bottom: none; }
    .card-col-row:hover { background: #f8fafc; }
    .col-badge {
        font-size: 9px;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 4px;
        letter-spacing: 0.5px;
        min-width: 28px;
        text-align: center;
    }
    .col-badge.pk { background: #fef3c7; color: #92400e; }
    .col-badge.fk { background: #dbeafe; color: #1e40af; }
    .col-badge.idx { background: #f3e8ff; color: #6b21a8; }
    .col-badge.col { background: #f1f5f9; color: #64748b; }
    .col-name { font-size: 13px; font-weight: 600; color: #0f172a; flex: 1; }
    .col-type { font-size: 11px; color: #94a3b8; font-weight: 500; }
    .col-nullable { font-size: 10px; color: #cbd5e1; margin-left: 4px; }

    /* Legend */
    .erd-legend {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        margin-bottom: 28px;
    }
    .legend-title { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 14px; }
    .legend-items { display: flex; gap: 20px; flex-wrap: wrap; }
    .legend-item { display: flex; align-items: center; gap: 8px; }
    .legend-dot {
        width: 12px; height: 12px;
        border-radius: 3px;
    }
    .legend-line {
        width: 30px; height: 2px;
        border-radius: 1px;
    }
    .legend-label { font-size: 12px; color: #64748b; font-weight: 500; }
</style>
@endpush

@section('content')
<div class="erd-page">

    {{-- Hero --}}
    <div class="erd-hero">
        <div class="erd-hero-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="7" height="7"/><rect x="15" y="3" width="7" height="7"/><rect x="2" y="14" width="7" height="7"/><rect x="15" y="14" width="7" height="7"/></svg>
            Entity Relationship Diagram
        </div>
        <h1 class="erd-hero-title">Struktur Database E-Arsip</h1>
        <p class="erd-hero-subtitle">Visualisasi relasi antar tabel — UPT Pembibitan Ternak dan Hijauan Makanan Ternak Tuban</p>
    </div>

    {{-- Stats --}}
    <div class="erd-stats">
        <div class="erd-stat-card">
            <div class="erd-stat-icon" style="background:#f0fdf4;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14a9 3 0 0 0 18 0V5"/><path d="M3 12a9 3 0 0 0 18 0"/></svg>
            </div>
            <div>
                <div class="erd-stat-num">10</div>
                <div class="erd-stat-label">Total Tabel</div>
            </div>
        </div>
        <div class="erd-stat-card">
            <div class="erd-stat-icon" style="background:#fef3c7;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            </div>
            <div>
                <div class="erd-stat-num">8</div>
                <div class="erd-stat-label">Foreign Key</div>
            </div>
        </div>
        <div class="erd-stat-card">
            <div class="erd-stat-icon" style="background:#dbeafe;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div>
                <div class="erd-stat-num">8</div>
                <div class="erd-stat-label">Relasi Aktif</div>
            </div>
        </div>
        <div class="erd-stat-card">
            <div class="erd-stat-icon" style="background:#fce7f3;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#db2777" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="erd-stat-num">3</div>
                <div class="erd-stat-label">Role Pengguna</div>
            </div>
        </div>
        <div class="erd-stat-card">
            <div class="erd-stat-icon" style="background:#f3e8ff;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div>
                <div class="erd-stat-num">55</div>
                <div class="erd-stat-label">Total Kolom</div>
            </div>
        </div>
    </div>

    {{-- Legend --}}
    <div class="erd-legend">
        <div class="legend-title">Keterangan</div>
        <div class="legend-items">
            <div class="legend-item">
                <div class="legend-dot" style="background:#fef3c7;border:1.5px solid #92400e;"></div>
                <span class="legend-label"><strong>PK</strong> — Primary Key</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background:#dbeafe;border:1.5px solid #1e40af;"></div>
                <span class="legend-label"><strong>FK</strong> — Foreign Key</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background:#f3e8ff;border:1.5px solid #6b21a8;"></div>
                <span class="legend-label"><strong>UQ</strong> — Unique Index</span>
            </div>
            <div class="legend-item">
                <div class="legend-line" style="background:#16a34a;"></div>
                <span class="legend-label">Relasi One-to-Many (1:N)</span>
            </div>
            <div class="legend-item">
                <div class="legend-line" style="background:#94a3b8; height:2px; border-top: 2px dashed #94a3b8;background:transparent;"></div>
                <span class="legend-label">Relasi Opsional (nullable FK)</span>
            </div>
            <div class="legend-item">
                <div class="legend-line" style="background:#0ea5e9;"></div>
                <span class="legend-label">Self-referencing (parent_id)</span>
            </div>
        </div>
    </div>

    {{-- ERD Canvas --}}
    <div class="erd-canvas-wrapper">
        <div class="erd-toolbar">
            <div class="erd-toolbar-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="7" height="7"/><rect x="15" y="3" width="7" height="7"/><rect x="2" y="14" width="7" height="7"/><rect x="15" y="14" width="7" height="7"/></svg>
                Diagram ERD Interaktif
                <span style="font-size:11px;font-weight:500;color:#94a3b8;margin-left:4px;">(scroll untuk zoom, drag untuk geser)</span>
            </div>
            <div class="erd-toolbar-actions">
                <button class="erd-btn" id="btn-zoom-in">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                    Zoom In
                </button>
                <button class="erd-btn" id="btn-zoom-out">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                    Zoom Out
                </button>
                <button class="erd-btn" id="btn-reset-view">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset
                </button>
                <div style="width: 1px; height: 24px; background: #e2e8f0; margin: 0 4px;"></div>
                <button class="erd-btn" id="btn-download-png" style="background: #f0fdf4; border-color: #bbf7d0; color: #166534;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download PNG
                </button>
                <button class="erd-btn" id="btn-download-svg">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    SVG
                </button>
                <a href="#table-details" class="erd-btn primary">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    Detail Tabel
                </a>
            </div>
        </div>

        <div style="position:relative;">
            <div id="erd-svg-container">
                <svg id="erd-svg" viewBox="0 0 1400 700" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <!-- Arrow markers -->
                        <marker id="arrow-green" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                            <path d="M0,0 L0,6 L8,3 z" fill="#16a34a"/>
                        </marker>
                        <marker id="arrow-blue" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                            <path d="M0,0 L0,6 L8,3 z" fill="#0ea5e9"/>
                        </marker>
                        <marker id="arrow-slate" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                            <path d="M0,0 L0,6 L8,3 z" fill="#94a3b8"/>
                        </marker>
                        <!-- Glow filter -->
                        <filter id="glow">
                            <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                            <feMerge><feMergeNode in="coloredBlur"/><feMergeNode in="SourceGraphic"/></feMerge>
                        </filter>
                    </defs>

                    <!-- ===== RELATIONSHIP LINES ===== -->
                    <!-- users → surat_masuk (created_by) -->
                    <line x1="200" y1="220" x2="440" y2="100" stroke="#16a34a" stroke-width="1.8" stroke-dasharray="5,3" marker-end="url(#arrow-green)" opacity="0.7"/>
                    <!-- users → surat_keluar (created_by) -->
                    <line x1="200" y1="220" x2="440" y2="310" stroke="#16a34a" stroke-width="1.8" stroke-dasharray="5,3" marker-end="url(#arrow-green)" opacity="0.7"/>
                    <!-- users → arsip_pembibitan (created_by) -->
                    <line x1="200" y1="220" x2="730" y2="90" stroke="#16a34a" stroke-width="1.8" stroke-dasharray="5,3" marker-end="url(#arrow-green)" opacity="0.7"/>
                    <!-- users → arsip_hijauan (created_by) -->
                    <line x1="200" y1="220" x2="730" y2="310" stroke="#16a34a" stroke-width="1.8" stroke-dasharray="5,3" marker-end="url(#arrow-green)" opacity="0.7"/>
                    <!-- users → dokumen (uploaded_by) -->
                    <line x1="200" y1="220" x2="1000" y2="220" stroke="#16a34a" stroke-width="1.8" stroke-dasharray="5,3" marker-end="url(#arrow-green)" opacity="0.7"/>
                    <!-- users → log_aktivitas (user_id) -->
                    <line x1="200" y1="320" x2="440" y2="530" stroke="#16a34a" stroke-width="1.8" stroke-dasharray="5,3" marker-end="url(#arrow-green)" opacity="0.7"/>
                    <!-- folders → dokumen (folder_id nullable) -->
                    <line x1="1030" y1="500" x2="1030" y2="420" stroke="#94a3b8" stroke-width="1.8" stroke-dasharray="5,3" marker-end="url(#arrow-slate)" opacity="0.7"/>
                    <!-- folders self ref (parent_id) -->
                    <path d="M 1070 500 Q 1120 480 1120 530 Q 1120 580 1070 570" stroke="#0ea5e9" stroke-width="1.8" stroke-dasharray="5,3" fill="none" marker-end="url(#arrow-blue)" opacity="0.8"/>
                    <!-- kategori_dokumen (standalone ref) -->
                    <line x1="730" y1="500" x2="1000" y2="280" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="3,3" opacity="0.45"/>

                    <!-- ===== TABLE: users ===== -->
                    <g class="table-node" id="node-users" transform="translate(20, 80)">
                        <rect x="0" y="0" width="200" height="42" rx="10" ry="10" fill="#166534"/>
                        <rect x="0" y="34" width="200" height="310" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="310" width="200" height="40" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="310" width="200" height="44" rx="0" ry="10" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <!-- Header -->
                        <text x="14" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">👤</text>
                        <text x="34" y="17" font-family="Inter,sans-serif" font-size="12" font-weight="800" fill="#ffffff">users</text>
                        <text x="14" y="32" font-family="Inter,sans-serif" font-size="9" fill="rgba(255,255,255,0.7)">Tabel Pengguna Sistem</text>
                        <!-- Divider -->
                        <line x1="0" y1="42" x2="200" y2="42" stroke="#e2e8f0" stroke-width="1"/>
                        <!-- Rows -->
                        <rect x="0" y="42" width="200" height="28" fill="#fef9e7" class="table-col-row"/>
                        <text x="10" y="61" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#92400e">PK</text>
                        <text x="34" y="61" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">id</text>
                        <text x="140" y="61" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>

                        <rect x="0" y="70" width="200" height="28" fill="#fff" class="table-col-row"/>
                        <text x="10" y="89" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#64748b">COL</text>
                        <text x="40" y="89" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">nama</text>
                        <text x="140" y="89" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>

                        <rect x="0" y="98" width="200" height="28" fill="#f8f9ff" class="table-col-row"/>
                        <text x="10" y="117" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#6b21a8">UQ</text>
                        <text x="36" y="117" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">email</text>
                        <text x="140" y="117" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>

                        <rect x="0" y="126" width="200" height="28" fill="#fff" class="table-col-row"/>
                        <text x="10" y="145" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#64748b">COL</text>
                        <text x="40" y="145" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">password</text>
                        <text x="140" y="145" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>

                        <rect x="0" y="154" width="200" height="28" fill="#fff" class="table-col-row"/>
                        <text x="10" y="173" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#64748b">COL</text>
                        <text x="40" y="173" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">role</text>
                        <text x="110" y="173" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">enum(Admin,Op.)</text>

                        <rect x="0" y="182" width="200" height="28" fill="#fff" class="table-col-row"/>
                        <text x="10" y="201" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#64748b">COL</text>
                        <text x="40" y="201" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">instansi</text>
                        <text x="140" y="201" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar?</text>

                        <rect x="0" y="210" width="200" height="28" fill="#fff" class="table-col-row"/>
                        <text x="10" y="229" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#64748b">COL</text>
                        <text x="40" y="229" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">status</text>
                        <text x="110" y="229" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">enum(Aktif...)</text>

                        <rect x="0" y="238" width="200" height="28" fill="#fff" class="table-col-row"/>
                        <text x="10" y="257" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#64748b">COL</text>
                        <text x="40" y="257" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tanggal_daftar</text>
                        <text x="155" y="257" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">date?</text>

                        <rect x="0" y="266" width="200" height="28" fill="#f8fafc" class="table-col-row"/>
                        <text x="10" y="285" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">timestamps</text>
                    </g>

                    <!-- ===== TABLE: surat_masuk ===== -->
                    <g class="table-node" id="node-surat-masuk" transform="translate(260, 28)">
                        <rect x="0" y="0" width="195" height="42" rx="10" ry="10" fill="#0f766e"/>
                        <rect x="0" y="34" width="195" height="300" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="300" width="195" height="34" rx="0" ry="10" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <text x="12" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">📥</text>
                        <text x="32" y="17" font-family="Inter,sans-serif" font-size="12" font-weight="800" fill="#ffffff">surat_masuk</text>
                        <text x="12" y="32" font-family="Inter,sans-serif" font-size="9" fill="rgba(255,255,255,0.7)">Surat Masuk</text>
                        <line x1="0" y1="42" x2="195" y2="42" stroke="#e2e8f0" stroke-width="1"/>
                        <!-- Rows -->
                        <rect x="0" y="42" width="195" height="26" fill="#fef9e7" class="table-col-row"/>
                        <text x="8" y="59" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#92400e">PK</text>
                        <text x="30" y="59" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">id</text>
                        <text x="140" y="59" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>
                        <rect x="0" y="68" width="195" height="26" fill="#f8f9ff" class="table-col-row"/>
                        <text x="8" y="85" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#6b21a8">UQ</text>
                        <text x="30" y="85" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">nomor_surat</text>
                        <text x="140" y="85" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="94" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="111" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="34" y="111" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">pengirim</text>
                        <text x="140" y="111" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="120" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="137" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="34" y="137" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">perihal</text>
                        <text x="140" y="137" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="146" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="163" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="34" y="163" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tanggal_surat</text>
                        <text x="155" y="163" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">date</text>
                        <rect x="0" y="172" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="189" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="34" y="189" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tanggal_terima</text>
                        <text x="155" y="189" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">date</text>
                        <rect x="0" y="198" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="215" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="34" y="215" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">prioritas</text>
                        <text x="125" y="215" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">enum(T,S,R)</text>
                        <rect x="0" y="224" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="241" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="34" y="241" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">status</text>
                        <text x="125" y="241" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">enum(...)</text>
                        <rect x="0" y="250" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="267" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="34" y="267" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">file_path</text>
                        <text x="140" y="267" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="276" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="293" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#1e40af">FK</text>
                        <text x="28" y="293" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">created_by</text>
                        <text x="140" y="293" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">→users</text>
                        <rect x="0" y="302" width="195" height="26" fill="#f8fafc" class="table-col-row"/>
                        <text x="10" y="320" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">timestamps</text>
                    </g>

                    <!-- ===== TABLE: surat_keluar ===== -->
                    <g class="table-node" id="node-surat-keluar" transform="translate(260, 260)">
                        <rect x="0" y="0" width="195" height="42" rx="10" ry="10" fill="#b45309"/>
                        <rect x="0" y="34" width="195" height="300" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="300" width="195" height="34" rx="0" ry="10" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <text x="12" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">📤</text>
                        <text x="32" y="17" font-family="Inter,sans-serif" font-size="12" font-weight="800" fill="#ffffff">surat_keluar</text>
                        <text x="12" y="32" font-family="Inter,sans-serif" font-size="9" fill="rgba(255,255,255,0.7)">Surat Keluar</text>
                        <line x1="0" y1="42" x2="195" y2="42" stroke="#e2e8f0" stroke-width="1"/>
                        <rect x="0" y="42" width="195" height="26" fill="#fef9e7" class="table-col-row"/>
                        <text x="8" y="59" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#92400e">PK</text>
                        <text x="28" y="59" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">id</text>
                        <text x="140" y="59" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>
                        <rect x="0" y="68" width="195" height="26" fill="#f8f9ff" class="table-col-row"/>
                        <text x="8" y="85" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#6b21a8">UQ</text>
                        <text x="28" y="85" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">nomor_surat</text>
                        <text x="140" y="85" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="94" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="111" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="111" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tujuan</text>
                        <text x="140" y="111" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="120" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="137" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="137" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">perihal</text>
                        <text x="140" y="137" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="146" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="163" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="163" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tanggal_surat</text>
                        <text x="155" y="163" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">date</text>
                        <rect x="0" y="172" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="189" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="189" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tanggal_kirim</text>
                        <text x="150" y="189" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">date?</text>
                        <rect x="0" y="198" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="215" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="215" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">prioritas</text>
                        <text x="125" y="215" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">enum(T,S,R)</text>
                        <rect x="0" y="224" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="241" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="241" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">status</text>
                        <text x="115" y="241" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">enum(Draft...)</text>
                        <rect x="0" y="250" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="267" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="267" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">file_path</text>
                        <text x="140" y="267" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="276" width="195" height="26" fill="#f0f9ff" class="table-col-row"/>
                        <text x="8" y="293" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#1e40af">FK</text>
                        <text x="28" y="293" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">created_by</text>
                        <text x="140" y="293" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">→users</text>
                        <rect x="0" y="302" width="195" height="26" fill="#f8fafc" class="table-col-row"/>
                        <text x="10" y="320" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">timestamps</text>
                    </g>

                    <!-- ===== TABLE: arsip_pembibitan ===== -->
                    <g class="table-node" id="node-arsip-pembibitan" transform="translate(500, 28)">
                        <rect x="0" y="0" width="200" height="42" rx="10" ry="10" fill="#7c3aed"/>
                        <rect x="0" y="34" width="200" height="260" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="260" width="200" height="34" rx="0" ry="10" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <text x="12" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">🐄</text>
                        <text x="32" y="17" font-family="Inter,sans-serif" font-size="12" font-weight="800" fill="#ffffff">arsip_pembibitan</text>
                        <text x="12" y="32" font-family="Inter,sans-serif" font-size="9" fill="rgba(255,255,255,0.7)">Arsip Pembibitan Ternak</text>
                        <line x1="0" y1="42" x2="200" y2="42" stroke="#e2e8f0" stroke-width="1"/>
                        <rect x="0" y="42" width="200" height="26" fill="#fef9e7" class="table-col-row"/>
                        <text x="8" y="59" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#92400e">PK</text>
                        <text x="28" y="59" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">id</text>
                        <text x="148" y="59" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>
                        <rect x="0" y="68" width="200" height="26" fill="#f8f9ff" class="table-col-row"/>
                        <text x="8" y="85" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#6b21a8">UQ</text>
                        <text x="28" y="85" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">kode</text>
                        <text x="148" y="85" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="94" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="111" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="111" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">jenis_ternak</text>
                        <text x="148" y="111" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="120" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="137" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="137" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">jumlah</text>
                        <text x="148" y="137" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">integer</text>
                        <rect x="0" y="146" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="163" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="163" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">umur</text>
                        <text x="148" y="163" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="172" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="189" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="189" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tujuan</text>
                        <text x="148" y="189" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="198" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="215" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="215" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tanggal</text>
                        <text x="148" y="215" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">date</text>
                        <rect x="0" y="224" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="241" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="241" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">status</text>
                        <text x="110" y="241" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">enum(Terdistr.)</text>
                        <rect x="0" y="250" width="200" height="26" fill="#f0f9ff" class="table-col-row"/>
                        <text x="8" y="267" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#1e40af">FK</text>
                        <text x="26" y="267" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">created_by</text>
                        <text x="148" y="267" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">→users</text>
                        <rect x="0" y="276" width="200" height="26" fill="#f8fafc" class="table-col-row"/>
                        <text x="10" y="294" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">timestamps</text>
                    </g>

                    <!-- ===== TABLE: arsip_hijauan ===== -->
                    <g class="table-node" id="node-arsip-hijauan" transform="translate(500, 270)">
                        <rect x="0" y="0" width="200" height="42" rx="10" ry="10" fill="#15803d"/>
                        <rect x="0" y="34" width="200" height="265" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="265" width="200" height="34" rx="0" ry="10" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <text x="12" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">🌿</text>
                        <text x="32" y="17" font-family="Inter,sans-serif" font-size="12" font-weight="800" fill="#ffffff">arsip_hijauan</text>
                        <text x="12" y="32" font-family="Inter,sans-serif" font-size="9" fill="rgba(255,255,255,0.7)">Arsip Hijauan Makanan Ternak</text>
                        <line x1="0" y1="42" x2="200" y2="42" stroke="#e2e8f0" stroke-width="1"/>
                        <rect x="0" y="42" width="200" height="26" fill="#fef9e7" class="table-col-row"/>
                        <text x="8" y="59" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#92400e">PK</text>
                        <text x="28" y="59" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">id</text>
                        <text x="148" y="59" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>
                        <rect x="0" y="68" width="200" height="26" fill="#f8f9ff" class="table-col-row"/>
                        <text x="8" y="85" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#6b21a8">UQ</text>
                        <text x="28" y="85" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">kode_lahan</text>
                        <text x="148" y="85" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="94" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="111" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="111" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">jenis_hijauan</text>
                        <text x="148" y="111" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="120" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="137" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="137" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">luas</text>
                        <text x="148" y="137" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">float</text>
                        <rect x="0" y="146" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="163" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="163" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">produksi</text>
                        <text x="148" y="163" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">float</text>
                        <rect x="0" y="172" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="189" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="189" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tanggal_panen</text>
                        <text x="148" y="189" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">date</text>
                        <rect x="0" y="198" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="215" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="215" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">lokasi</text>
                        <text x="148" y="215" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="224" width="200" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="241" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="241" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">status</text>
                        <text x="110" y="241" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">enum(Tersedia)</text>
                        <rect x="0" y="250" width="200" height="26" fill="#f0f9ff" class="table-col-row"/>
                        <text x="8" y="267" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#1e40af">FK</text>
                        <text x="26" y="267" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">created_by</text>
                        <text x="148" y="267" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">→users</text>
                        <rect x="0" y="276" width="200" height="26" fill="#f8fafc" class="table-col-row"/>
                        <text x="10" y="294" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">timestamps</text>
                    </g>

                    <!-- ===== TABLE: dokumen ===== -->
                    <g class="table-node" id="node-dokumen" transform="translate(760, 60)">
                        <rect x="0" y="0" width="215" height="42" rx="10" ry="10" fill="#be123c"/>
                        <rect x="0" y="34" width="215" height="380" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="380" width="215" height="34" rx="0" ry="10" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <text x="12" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">📄</text>
                        <text x="32" y="17" font-family="Inter,sans-serif" font-size="12" font-weight="800" fill="#ffffff">dokumen</text>
                        <text x="12" y="32" font-family="Inter,sans-serif" font-size="9" fill="rgba(255,255,255,0.7)">Dokumen Arsip Digital</text>
                        <line x1="0" y1="42" x2="215" y2="42" stroke="#e2e8f0" stroke-width="1"/>
                        <rect x="0" y="42" width="215" height="26" fill="#fef9e7" class="table-col-row"/>
                        <text x="8" y="59" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#92400e">PK</text>
                        <text x="28" y="59" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">id</text>
                        <text x="163" y="59" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>
                        <rect x="0" y="68" width="215" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="85" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="85" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">nama</text>
                        <text x="163" y="85" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="94" width="215" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="111" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="111" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">kategori</text>
                        <text x="163" y="111" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="120" width="215" height="26" fill="#f0f9ff" class="table-col-row"/>
                        <text x="8" y="137" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#1e40af">FK</text>
                        <text x="26" y="137" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">folder_id</text>
                        <text x="145" y="137" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">→folders?</text>
                        <rect x="0" y="146" width="215" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="163" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="163" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">sifat_arsip</text>
                        <text x="163" y="163" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar?</text>
                        <rect x="0" y="172" width="215" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="189" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="189" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">is_public</text>
                        <text x="163" y="189" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bool</text>
                        <rect x="0" y="198" width="215" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="215" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="215" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tanggal / kode / lokasi</text>
                        <rect x="0" y="224" width="215" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="241" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="241" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">file_path</text>
                        <text x="163" y="241" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="250" width="215" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="267" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="267" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">ukuran / mime_type</text>
                        <rect x="0" y="276" width="215" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="293" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="293" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tanggal_upload</text>
                        <text x="163" y="293" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">date</text>
                        <rect x="0" y="302" width="215" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="319" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="319" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">deskripsi / masa_retensi</text>
                        <rect x="0" y="328" width="215" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="345" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="345" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">status / download_counter</text>
                        <rect x="0" y="354" width="215" height="26" fill="#f0f9ff" class="table-col-row"/>
                        <text x="8" y="371" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#1e40af">FK</text>
                        <text x="26" y="371" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">uploaded_by</text>
                        <text x="145" y="371" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">→users</text>
                        <rect x="0" y="380" width="215" height="26" fill="#f8fafc" class="table-col-row"/>
                        <text x="10" y="398" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">timestamps</text>
                    </g>

                    <!-- ===== TABLE: folders ===== -->
                    <g class="table-node" id="node-folders" transform="translate(760, 490)">
                        <rect x="0" y="0" width="195" height="42" rx="10" ry="10" fill="#0369a1"/>
                        <rect x="0" y="34" width="195" height="165" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="165" width="195" height="34" rx="0" ry="10" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <text x="12" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">📁</text>
                        <text x="32" y="17" font-family="Inter,sans-serif" font-size="12" font-weight="800" fill="#ffffff">folders</text>
                        <text x="12" y="32" font-family="Inter,sans-serif" font-size="9" fill="rgba(255,255,255,0.7)">Folder Hierarki Dokumen</text>
                        <line x1="0" y1="42" x2="195" y2="42" stroke="#e2e8f0" stroke-width="1"/>
                        <rect x="0" y="42" width="195" height="26" fill="#fef9e7" class="table-col-row"/>
                        <text x="8" y="59" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#92400e">PK</text>
                        <text x="28" y="59" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">id</text>
                        <text x="148" y="59" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>
                        <rect x="0" y="68" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="85" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="85" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">nama</text>
                        <text x="148" y="85" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="94" width="195" height="26" fill="#e0f2fe" class="table-col-row"/>
                        <text x="8" y="111" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#0369a1">FK</text>
                        <text x="26" y="111" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">parent_id</text>
                        <text x="130" y="111" font-family="Inter,sans-serif" font-size="10" fill="#0369a1">→self?</text>
                        <rect x="0" y="120" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="137" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="137" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">deskripsi</text>
                        <text x="148" y="137" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">text?</text>
                        <rect x="0" y="146" width="195" height="26" fill="#f8fafc" class="table-col-row"/>
                        <text x="10" y="163" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">timestamps</text>
                    </g>

                    <!-- ===== TABLE: log_aktivitas ===== -->
                    <g class="table-node" id="node-log" transform="translate(260, 520)">
                        <rect x="0" y="0" width="195" height="42" rx="10" ry="10" fill="#475569"/>
                        <rect x="0" y="34" width="195" height="220" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="220" width="195" height="34" rx="0" ry="10" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <text x="12" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">📋</text>
                        <text x="32" y="17" font-family="Inter,sans-serif" font-size="12" font-weight="800" fill="#ffffff">log_aktivitas</text>
                        <text x="12" y="32" font-family="Inter,sans-serif" font-size="9" fill="rgba(255,255,255,0.7)">Log Audit Trail</text>
                        <line x1="0" y1="42" x2="195" y2="42" stroke="#e2e8f0" stroke-width="1"/>
                        <rect x="0" y="42" width="195" height="26" fill="#fef9e7" class="table-col-row"/>
                        <text x="8" y="59" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#92400e">PK</text>
                        <text x="28" y="59" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">id</text>
                        <text x="148" y="59" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>
                        <rect x="0" y="68" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="85" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="85" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">jenis_aktivitas</text>
                        <text x="148" y="85" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">enum</text>
                        <rect x="0" y="94" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="111" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="111" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">modul</text>
                        <text x="148" y="111" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="120" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="137" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="137" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">deskripsi</text>
                        <text x="148" y="137" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">text</text>
                        <rect x="0" y="146" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="163" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="163" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">ip_address</text>
                        <text x="148" y="163" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="172" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="189" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="189" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">user_agent</text>
                        <text x="148" y="189" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="198" width="195" height="26" fill="#f0f9ff" class="table-col-row"/>
                        <text x="8" y="215" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#1e40af">FK</text>
                        <text x="26" y="215" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">user_id</text>
                        <text x="148" y="215" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">→users</text>
                        <rect x="0" y="224" width="195" height="26" fill="#f8fafc" class="table-col-row"/>
                        <text x="10" y="242" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">timestamps</text>
                    </g>

                    <!-- ===== TABLE: kategori_dokumen ===== -->
                    <g class="table-node" id="node-kategori-dok" transform="translate(500, 520)">
                        <rect x="0" y="0" width="190" height="42" rx="10" ry="10" fill="#c2410c"/>
                        <rect x="0" y="34" width="190" height="140" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="140" width="190" height="34" rx="0" ry="10" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <text x="12" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">🏷️</text>
                        <text x="30" y="17" font-family="Inter,sans-serif" font-size="12" font-weight="800" fill="#ffffff">kategori_dokumen</text>
                        <text x="12" y="32" font-family="Inter,sans-serif" font-size="9" fill="rgba(255,255,255,0.7)">Kategori Referensi Dokumen</text>
                        <line x1="0" y1="42" x2="190" y2="42" stroke="#e2e8f0" stroke-width="1"/>
                        <rect x="0" y="42" width="190" height="26" fill="#fef9e7" class="table-col-row"/>
                        <text x="8" y="59" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#92400e">PK</text>
                        <text x="28" y="59" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">id</text>
                        <text x="143" y="59" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>
                        <rect x="0" y="68" width="190" height="26" fill="#f8f9ff" class="table-col-row"/>
                        <text x="8" y="85" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#6b21a8">UQ</text>
                        <text x="28" y="85" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">nama</text>
                        <text x="143" y="85" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="94" width="190" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="111" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="111" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">keterangan</text>
                        <text x="143" y="111" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">text?</text>
                        <rect x="0" y="120" width="190" height="26" fill="#f8fafc" class="table-col-row"/>
                        <text x="10" y="138" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">timestamps</text>
                    </g>

                    <!-- ===== TABLE: kategori ===== -->
                    <g class="table-node" id="node-kategori" transform="translate(1030, 530)">
                        <rect x="0" y="0" width="175" height="42" rx="10" ry="10" fill="#d97706"/>
                        <rect x="0" y="34" width="175" height="112" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="112" width="175" height="34" rx="0" ry="10" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <text x="12" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">🗂️</text>
                        <text x="30" y="17" font-family="Inter,sans-serif" font-size="12" font-weight="800" fill="#ffffff">kategori</text>
                        <text x="12" y="32" font-family="Inter,sans-serif" font-size="9" fill="rgba(255,255,255,0.7)">Kategori Surat/Arsip</text>
                        <line x1="0" y1="42" x2="175" y2="42" stroke="#e2e8f0" stroke-width="1"/>
                        <rect x="0" y="42" width="175" height="26" fill="#fef9e7" class="table-col-row"/>
                        <text x="8" y="59" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#92400e">PK</text>
                        <text x="28" y="59" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">id</text>
                        <text x="130" y="59" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>
                        <rect x="0" y="68" width="175" height="26" fill="#f8f9ff" class="table-col-row"/>
                        <text x="8" y="85" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#6b21a8">UQ</text>
                        <text x="28" y="85" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">nama</text>
                        <text x="130" y="85" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar(100)</text>
                        <rect x="0" y="94" width="175" height="26" fill="#f8fafc" class="table-col-row"/>
                        <text x="10" y="112" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">timestamps</text>
                    </g>

                    <!-- ===== TABLE: personal_access_tokens ===== -->
                    <g class="table-node" id="node-pat" transform="translate(1100, 60)">
                        <rect x="0" y="0" width="195" height="42" rx="10" ry="10" fill="#64748b"/>
                        <rect x="0" y="34" width="195" height="200" rx="0" ry="0" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <rect x="0" y="200" width="195" height="34" rx="0" ry="10" fill="#fff" stroke="#e2e8f0" stroke-width="1.5"/>
                        <text x="12" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">🔑</text>
                        <text x="30" y="17" font-family="Inter,sans-serif" font-size="11" font-weight="800" fill="#ffffff">personal_access_tokens</text>
                        <text x="12" y="32" font-family="Inter,sans-serif" font-size="9" fill="rgba(255,255,255,0.7)">API Token Sanctum</text>
                        <line x1="0" y1="42" x2="195" y2="42" stroke="#e2e8f0" stroke-width="1"/>
                        <rect x="0" y="42" width="195" height="26" fill="#fef9e7" class="table-col-row"/>
                        <text x="8" y="59" font-family="Inter,sans-serif" font-size="10" font-weight="700" fill="#92400e">PK</text>
                        <text x="28" y="59" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">id</text>
                        <text x="148" y="59" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>
                        <rect x="0" y="68" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="85" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="85" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tokenable_type</text>
                        <text x="148" y="85" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="94" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="111" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="111" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">tokenable_id</text>
                        <text x="148" y="111" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">bigint</text>
                        <rect x="0" y="120" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="137" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="137" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">name</text>
                        <text x="148" y="137" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar</text>
                        <rect x="0" y="146" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="163" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="163" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">token</text>
                        <text x="148" y="163" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">varchar UQ</text>
                        <rect x="0" y="172" width="195" height="26" fill="#fff" class="table-col-row"/>
                        <text x="8" y="189" font-family="Inter,sans-serif" font-size="10" fill="#64748b">COL</text>
                        <text x="32" y="189" font-family="Inter,sans-serif" font-size="11" font-weight="600" fill="#1e293b">abilities / last_used_at</text>
                        <rect x="0" y="198" width="195" height="26" fill="#f8fafc" class="table-col-row"/>
                        <text x="10" y="216" font-family="Inter,sans-serif" font-size="10" fill="#94a3b8">timestamps</text>
                    </g>

                </svg>
            </div>
        </div>
    </div>

    {{-- Table Detail Cards --}}
    <div id="table-details">
        <h2 style="font-size:18px;font-weight:800;color:#0f172a;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            Detail Struktur Tabel
        </h2>

        <div class="table-detail-grid">

            {{-- users --}}
            <div class="table-detail-card" style="--card-color:#166534;">
                <div class="card-table-header" style="background:linear-gradient(135deg,#166534,#15803d);">
                    <div class="card-table-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
                    <div><div class="card-table-name">users</div><div class="card-table-subtitle">7 kolom + timestamps</div></div>
                </div>
                <div class="card-col-list">
                    <div class="card-col-row"><span class="col-badge pk">PK</span><span class="col-name">id</span><span class="col-type">bigint AUTO_INCREMENT</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">nama</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge idx">UQ</span><span class="col-name">email</span><span class="col-type">varchar(255) UNIQUE</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">password</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">role</span><span class="col-type">enum(Admin, Operator, Pimpinan)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">instansi <span class="col-nullable">nullable</span></span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">status</span><span class="col-type">enum(Aktif, Pending, Nonaktif)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">tanggal_daftar <span class="col-nullable">nullable</span></span><span class="col-type">date</span></div>
                </div>
            </div>

            {{-- dokumen --}}
            <div class="table-detail-card" style="--card-color:#be123c;">
                <div class="card-table-header" style="background:linear-gradient(135deg,#be123c,#e11d48);">
                    <div class="card-table-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
                    <div><div class="card-table-name">dokumen</div><div class="card-table-subtitle">14 kolom + timestamps · FK: uploaded_by, folder_id</div></div>
                </div>
                <div class="card-col-list">
                    <div class="card-col-row"><span class="col-badge pk">PK</span><span class="col-name">id</span><span class="col-type">bigint AUTO_INCREMENT</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">nama</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">kategori</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge fk">FK</span><span class="col-name">folder_id <span class="col-nullable">nullable</span></span><span class="col-type">→ folders.id</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">sifat_arsip <span class="col-nullable">nullable</span></span><span class="col-type">varchar(50)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">is_public</span><span class="col-type">boolean DEFAULT false</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">tanggal <span class="col-nullable">nullable</span></span><span class="col-type">date</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">kode <span class="col-nullable">nullable</span></span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">lokasi <span class="col-nullable">nullable</span></span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">file_path</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">ukuran</span><span class="col-type">bigint (bytes)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">mime_type</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">tanggal_upload</span><span class="col-type">date</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">deskripsi <span class="col-nullable">nullable</span></span><span class="col-type">text</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">masa_retensi <span class="col-nullable">nullable</span></span><span class="col-type">varchar(100)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">status</span><span class="col-type">enum(Aktif, Inaktif)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">download_counter</span><span class="col-type">integer DEFAULT 0</span></div>
                    <div class="card-col-row"><span class="col-badge fk">FK</span><span class="col-name">uploaded_by</span><span class="col-type">→ users.id</span></div>
                </div>
            </div>

            {{-- surat_masuk --}}
            <div class="table-detail-card" style="--card-color:#0f766e;">
                <div class="card-table-header" style="background:linear-gradient(135deg,#0f766e,#0d9488);">
                    <div class="card-table-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg></div>
                    <div><div class="card-table-name">surat_masuk</div><div class="card-table-subtitle">9 kolom + timestamps · FK: created_by</div></div>
                </div>
                <div class="card-col-list">
                    <div class="card-col-row"><span class="col-badge pk">PK</span><span class="col-name">id</span><span class="col-type">bigint AUTO_INCREMENT</span></div>
                    <div class="card-col-row"><span class="col-badge idx">UQ</span><span class="col-name">nomor_surat</span><span class="col-type">varchar(255) UNIQUE</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">pengirim</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">perihal</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">tanggal_surat</span><span class="col-type">date</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">tanggal_terima</span><span class="col-type">date</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">prioritas</span><span class="col-type">enum(Tinggi, Sedang, Rendah)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">status</span><span class="col-type">enum(Pending, Diproses, Terarsip)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">file_path</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">keterangan <span class="col-nullable">nullable</span></span><span class="col-type">text</span></div>
                    <div class="card-col-row"><span class="col-badge fk">FK</span><span class="col-name">created_by</span><span class="col-type">→ users.id</span></div>
                </div>
            </div>

            {{-- surat_keluar --}}
            <div class="table-detail-card" style="--card-color:#b45309;">
                <div class="card-table-header" style="background:linear-gradient(135deg,#b45309,#d97706);">
                    <div class="card-table-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></div>
                    <div><div class="card-table-name">surat_keluar</div><div class="card-table-subtitle">9 kolom + timestamps · FK: created_by</div></div>
                </div>
                <div class="card-col-list">
                    <div class="card-col-row"><span class="col-badge pk">PK</span><span class="col-name">id</span><span class="col-type">bigint AUTO_INCREMENT</span></div>
                    <div class="card-col-row"><span class="col-badge idx">UQ</span><span class="col-name">nomor_surat</span><span class="col-type">varchar(255) UNIQUE</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">tujuan</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">perihal</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">tanggal_surat</span><span class="col-type">date</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">tanggal_kirim <span class="col-nullable">nullable</span></span><span class="col-type">date</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">prioritas</span><span class="col-type">enum(Tinggi, Sedang, Rendah)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">status</span><span class="col-type">enum(Draft, Terkirim, Selesai)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">file_path</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">keterangan <span class="col-nullable">nullable</span></span><span class="col-type">text</span></div>
                    <div class="card-col-row"><span class="col-badge fk">FK</span><span class="col-name">created_by</span><span class="col-type">→ users.id</span></div>
                </div>
            </div>

            {{-- arsip_pembibitan --}}
            <div class="table-detail-card" style="--card-color:#7c3aed;">
                <div class="card-table-header" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);">
                    <div class="card-table-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
                    <div><div class="card-table-name">arsip_pembibitan</div><div class="card-table-subtitle">8 kolom + timestamps · FK: created_by</div></div>
                </div>
                <div class="card-col-list">
                    <div class="card-col-row"><span class="col-badge pk">PK</span><span class="col-name">id</span><span class="col-type">bigint AUTO_INCREMENT</span></div>
                    <div class="card-col-row"><span class="col-badge idx">UQ</span><span class="col-name">kode</span><span class="col-type">varchar(255) UNIQUE</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">jenis_ternak</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">jumlah</span><span class="col-type">integer</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">umur</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">tujuan</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">tanggal</span><span class="col-type">date</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">status</span><span class="col-type">enum(Terdistribusi, Proses)</span></div>
                    <div class="card-col-row"><span class="col-badge fk">FK</span><span class="col-name">created_by</span><span class="col-type">→ users.id</span></div>
                </div>
            </div>

            {{-- arsip_hijauan --}}
            <div class="table-detail-card" style="--card-color:#15803d;">
                <div class="card-table-header" style="background:linear-gradient(135deg,#15803d,#16a34a);">
                    <div class="card-table-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                    <div><div class="card-table-name">arsip_hijauan</div><div class="card-table-subtitle">8 kolom + timestamps · FK: created_by</div></div>
                </div>
                <div class="card-col-list">
                    <div class="card-col-row"><span class="col-badge pk">PK</span><span class="col-name">id</span><span class="col-type">bigint AUTO_INCREMENT</span></div>
                    <div class="card-col-row"><span class="col-badge idx">UQ</span><span class="col-name">kode_lahan</span><span class="col-type">varchar(255) UNIQUE</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">jenis_hijauan</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">luas</span><span class="col-type">float (hektar)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">produksi</span><span class="col-type">float (ton)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">tanggal_panen</span><span class="col-type">date</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">lokasi</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">status</span><span class="col-type">enum(Tersedia, Terdistribusi)</span></div>
                    <div class="card-col-row"><span class="col-badge fk">FK</span><span class="col-name">created_by</span><span class="col-type">→ users.id</span></div>
                </div>
            </div>

            {{-- folders --}}
            <div class="table-detail-card" style="--card-color:#0369a1;">
                <div class="card-table-header" style="background:linear-gradient(135deg,#0369a1,#0ea5e9);">
                    <div class="card-table-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg></div>
                    <div><div class="card-table-name">folders</div><div class="card-table-subtitle">4 kolom + timestamps · Self-referencing FK</div></div>
                </div>
                <div class="card-col-list">
                    <div class="card-col-row"><span class="col-badge pk">PK</span><span class="col-name">id</span><span class="col-type">bigint AUTO_INCREMENT</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">nama</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge fk">FK</span><span class="col-name">parent_id <span class="col-nullable">nullable</span></span><span class="col-type">→ folders.id (self ref)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">deskripsi <span class="col-nullable">nullable</span></span><span class="col-type">text</span></div>
                </div>
            </div>

            {{-- log_aktivitas --}}
            <div class="table-detail-card" style="--card-color:#475569;">
                <div class="card-table-header" style="background:linear-gradient(135deg,#475569,#64748b);">
                    <div class="card-table-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></div>
                    <div><div class="card-table-name">log_aktivitas</div><div class="card-table-subtitle">6 kolom + timestamps · FK: user_id</div></div>
                </div>
                <div class="card-col-list">
                    <div class="card-col-row"><span class="col-badge pk">PK</span><span class="col-name">id</span><span class="col-type">bigint AUTO_INCREMENT</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">jenis_aktivitas</span><span class="col-type">enum(Login, Logout, Create, Update, Delete, Download)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">modul</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">deskripsi</span><span class="col-type">text</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">ip_address</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">user_agent</span><span class="col-type">varchar(255)</span></div>
                    <div class="card-col-row"><span class="col-badge fk">FK</span><span class="col-name">user_id</span><span class="col-type">→ users.id</span></div>
                </div>
            </div>

            {{-- kategori_dokumen --}}
            <div class="table-detail-card" style="--card-color:#c2410c;">
                <div class="card-table-header" style="background:linear-gradient(135deg,#c2410c,#ea580c);">
                    <div class="card-table-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></div>
                    <div><div class="card-table-name">kategori_dokumen</div><div class="card-table-subtitle">3 kolom + timestamps · Lookup table</div></div>
                </div>
                <div class="card-col-list">
                    <div class="card-col-row"><span class="col-badge pk">PK</span><span class="col-name">id</span><span class="col-type">bigint AUTO_INCREMENT</span></div>
                    <div class="card-col-row"><span class="col-badge idx">UQ</span><span class="col-name">nama</span><span class="col-type">varchar(255) UNIQUE</span></div>
                    <div class="card-col-row"><span class="col-badge col">COL</span><span class="col-name">keterangan <span class="col-nullable">nullable</span></span><span class="col-type">text</span></div>
                </div>
            </div>

            {{-- kategori --}}
            <div class="table-detail-card" style="--card-color:#d97706;">
                <div class="card-table-header" style="background:linear-gradient(135deg,#d97706,#f59e0b);">
                    <div class="card-table-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></div>
                    <div><div class="card-table-name">kategori</div><div class="card-table-subtitle">2 kolom + timestamps · Lookup table</div></div>
                </div>
                <div class="card-col-list">
                    <div class="card-col-row"><span class="col-badge pk">PK</span><span class="col-name">id</span><span class="col-type">bigint AUTO_INCREMENT</span></div>
                    <div class="card-col-row"><span class="col-badge idx">UQ</span><span class="col-name">nama</span><span class="col-type">varchar(100) UNIQUE</span></div>
                </div>
            </div>

        </div>
    </div>

    {{-- Relasi Summary --}}
    <div style="background:#fff;border-radius:16px;border:1px solid #e2e8f0;padding:24px;margin-bottom:20px;">
        <h3 style="font-size:15px;font-weight:800;color:#0f172a;margin:0 0 16px;">Ringkasan Relasi Antar Tabel</h3>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f8fafc;">
                        <th style="padding:10px 14px;text-align:left;font-weight:700;color:#374151;border-bottom:2px solid #e2e8f0;">Tabel Anak</th>
                        <th style="padding:10px 14px;text-align:left;font-weight:700;color:#374151;border-bottom:2px solid #e2e8f0;">Foreign Key</th>
                        <th style="padding:10px 14px;text-align:left;font-weight:700;color:#374151;border-bottom:2px solid #e2e8f0;">Tabel Induk</th>
                        <th style="padding:10px 14px;text-align:left;font-weight:700;color:#374151;border-bottom:2px solid #e2e8f0;">Tipe Relasi</th>
                        <th style="padding:10px 14px;text-align:left;font-weight:700;color:#374151;border-bottom:2px solid #e2e8f0;">On Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $relations = [
                        ['surat_masuk', 'created_by', 'users', 'Many-to-One (N:1)', 'CASCADE'],
                        ['surat_keluar', 'created_by', 'users', 'Many-to-One (N:1)', 'CASCADE'],
                        ['arsip_pembibitan', 'created_by', 'users', 'Many-to-One (N:1)', 'CASCADE'],
                        ['arsip_hijauan', 'created_by', 'users', 'Many-to-One (N:1)', 'CASCADE'],
                        ['dokumen', 'uploaded_by', 'users', 'Many-to-One (N:1)', 'CASCADE'],
                        ['dokumen', 'folder_id (nullable)', 'folders', 'Many-to-One (N:1)', 'SET NULL'],
                        ['folders', 'parent_id (nullable)', 'folders', 'Self-referencing (Hirearki)', 'CASCADE'],
                        ['log_aktivitas', 'user_id', 'users', 'Many-to-One (N:1)', 'CASCADE'],
                    ];
                    @endphp
                    @foreach($relations as $i => $rel)
                    <tr style="background: {{ $i % 2 === 0 ? '#fff' : '#fafafa' }}; border-bottom: 1px solid #f1f5f9;">
                        <td style="padding:10px 14px;font-weight:600;color:#0f172a;">{{ $rel[0] }}</td>
                        <td style="padding:10px 14px;"><span style="background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;">{{ $rel[1] }}</span></td>
                        <td style="padding:10px 14px;font-weight:600;color:#16a34a;">{{ $rel[2] }}</td>
                        <td style="padding:10px 14px;color:#64748b;">{{ $rel[3] }}</td>
                        <td style="padding:10px 14px;"><span style="background:{{ $rel[4] === 'CASCADE' ? '#fef3c7' : '#f3e8ff' }};color:{{ $rel[4] === 'CASCADE' ? '#92400e' : '#6b21a8' }};padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700;">{{ $rel[4] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // ====== Zoom & Pan on ERD SVG ======
    const svg = document.getElementById('erd-svg');
    const container = document.getElementById('erd-svg-container');
    let scale = 1;
    const SCALE_STEP = 0.15;
    const SCALE_MIN = 0.4;
    const SCALE_MAX = 2.5;

    function applyScale() {
        svg.style.transform = `scale(${scale})`;
        svg.style.transformOrigin = 'top left';
    }

    document.getElementById('btn-zoom-in').onclick = () => {
        scale = Math.min(scale + SCALE_STEP, SCALE_MAX);
        applyScale();
    };
    document.getElementById('btn-zoom-out').onclick = () => {
        scale = Math.max(scale - SCALE_STEP, SCALE_MIN);
        applyScale();
    };
    document.getElementById('btn-reset-view').onclick = () => {
        scale = 1;
        applyScale();
        container.scrollLeft = 0;
        container.scrollTop = 0;
    };

    // Scroll-wheel zoom
    container.addEventListener('wheel', (e) => {
        e.preventDefault();
        if (e.deltaY < 0) scale = Math.min(scale + 0.08, SCALE_MAX);
        else scale = Math.max(scale - 0.08, SCALE_MIN);
        applyScale();
    }, { passive: false });

    // Drag Pan
    let isDragging = false, startX, startY, scrollLeft, scrollTop;
    container.addEventListener('mousedown', (e) => {
        if (e.target.tagName === 'text' || e.target.tagName === 'rect') return;
        isDragging = true;
        startX = e.pageX - container.offsetLeft;
        startY = e.pageY - container.offsetTop;
        scrollLeft = container.scrollLeft;
        scrollTop = container.scrollTop;
    });
    container.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        const x = e.pageX - container.offsetLeft;
        const y = e.pageY - container.offsetTop;
        container.scrollLeft = scrollLeft - (x - startX);
        container.scrollTop = scrollTop - (y - startY);
    });
    container.addEventListener('mouseup', () => isDragging = false);
    container.addEventListener('mouseleave', () => isDragging = false);

    // Hover highlight for table nodes
    document.querySelectorAll('.table-node').forEach(node => {
        node.addEventListener('mouseenter', () => {
            node.style.filter = 'drop-shadow(0 8px 24px rgba(0,0,0,0.18))';
        });
        node.addEventListener('mouseleave', () => {
            node.style.filter = '';
        });
    });

    // ====== DOWNLOAD LOGIC ======
    
    // Function to Download as SVG
    document.getElementById('btn-download-svg').onclick = () => {
        const svgData = new XMLSerializer().serializeToString(svg);
        const svgBlob = new Blob([svgData], {type: "image/svg+xml;charset=utf-8"});
        const svgUrl = URL.createObjectURL(svgBlob);
        const downloadLink = document.createElement("a");
        downloadLink.href = svgUrl;
        downloadLink.download = "ERD_E-Arsip_UPT_Tuban.svg";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    };

    // Function to Download as PNG
    document.getElementById('btn-download-png').onclick = () => {
        const svgData = new XMLSerializer().serializeToString(svg);
        const canvas = document.createElement("canvas");
        const svgSize = svg.getBBox();
        
        // Add padding to export
        canvas.width = 1400; 
        canvas.height = 750;
        
        const ctx = canvas.getContext("2d");
        const img = new Image();
        
        const svgBlob = new Blob([svgData], {type: "image/svg+xml;charset=utf-8"});
        const url = URL.createObjectURL(svgBlob);
        
        img.onload = () => {
            // Fill background
            ctx.fillStyle = "#f8fafc";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            // Draw SVG
            ctx.drawImage(img, 0, 0);
            
            const pngUrl = canvas.toDataURL("image/png");
            const downloadLink = document.createElement("a");
            downloadLink.href = pngUrl;
            downloadLink.download = "ERD_E-Arsip_UPT_Tuban.png";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
            URL.revokeObjectURL(url);
        };
        img.src = url;
    };

    // Animate cards in on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.table-detail-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        observer.observe(card);
    });
</script>
@endpush
