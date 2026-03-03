{{-- Hijauan Stats --}}
<div class="mini-stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="mini-stat-card">
        <div class="mini-stat-icon green-icon">
            <i data-lucide="map" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ number_format($totalLahan) }}</div>
            <div class="mini-stat-label">Total Lahan</div>
        </div>
    </div>
    <div class="mini-stat-card">
        <div class="mini-stat-icon teal-icon">
            <i data-lucide="package" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ number_format($totalProduksi, 0, ',', '.') }} Kg</div>
            <div class="mini-stat-label">Total Produksi</div>
        </div>
    </div>
    <div class="mini-stat-card">
        <div class="mini-stat-icon blue-icon">
            <i data-lucide="maximize-2" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ number_format($luasTotal, 1, ',', '.') }} Ha</div>
            <div class="mini-stat-label">Luas Total</div>
        </div>
    </div>
</div>
