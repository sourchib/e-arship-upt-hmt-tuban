{{-- Dokumen Stats --}}
<div class="mini-stats-grid">
    <div class="mini-stat-card">
        <div class="mini-stat-icon blue-icon">
            <i data-lucide="file-text" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ number_format($totalDokumen) }}</div>
            <div class="mini-stat-label">Total Dokumen</div>
        </div>
    </div>
    <div class="mini-stat-card">
        <div class="mini-stat-icon" style="background:#fef2f2;color:#dc2626;">
            <i data-lucide="file-type-2" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ number_format($pdfCount) }}</div>
            <div class="mini-stat-label">File PDF</div>
        </div>
    </div>
    <div class="mini-stat-card">
        <div class="mini-stat-icon green-icon">
            <i data-lucide="file-spreadsheet" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ number_format($excelCount) }}</div>
            <div class="mini-stat-label">File Excel</div>
        </div>
    </div>
    <div class="mini-stat-card">
        <div class="mini-stat-icon orange-icon">
            <i data-lucide="layers" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ number_format($categoryCount) }}</div>
            <div class="mini-stat-label">Kategori</div>
        </div>
    </div>
</div>
