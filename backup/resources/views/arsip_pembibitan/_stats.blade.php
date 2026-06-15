{{-- Pembibitan Stats --}}
<div class="mini-stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="mini-stat-card">
        <div class="mini-stat-icon" style="background:#ede9fe;color:#7c3aed;">
            <i data-lucide="bar-chart-2" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ number_format($totalTernak) }}</div>
            <div class="mini-stat-label">Total Ekor Ternak</div>
        </div>
    </div>
    <div class="mini-stat-card">
        <div class="mini-stat-icon" style="background:#f0fdf4;color:#16a34a;">
            <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ number_format($terdistribusi) }}</div>
            <div class="mini-stat-label">Terdistribusi</div>
        </div>
    </div>
    <div class="mini-stat-card">
        <div class="mini-stat-icon" style="background:#fff7ed;color:#f97316;">
            <i data-lucide="loader" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ number_format($dalamProses) }}</div>
            <div class="mini-stat-label">Dalam Proses</div>
        </div>
    </div>
</div>
