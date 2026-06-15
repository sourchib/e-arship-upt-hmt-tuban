{{-- Users Stats --}}
<div class="mini-stats-grid">
    <div class="mini-stat-card">
        <div class="mini-stat-icon" style="background:#ede9fe;color:#7c3aed;">
            <i data-lucide="users" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ $stats['total'] }}</div>
            <div class="mini-stat-label">Total Pengguna</div>
        </div>
    </div>
    <div class="mini-stat-card">
        <div class="mini-stat-icon green-icon">
            <i data-lucide="user-check" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ $stats['aktif'] }}</div>
            <div class="mini-stat-label">Pengguna Aktif</div>
        </div>
    </div>
    <div class="mini-stat-card">
        <div class="mini-stat-icon orange-icon">
            <i data-lucide="clock" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ $stats['pending'] }}</div>
            <div class="mini-stat-label">Menunggu Persetujuan</div>
        </div>
    </div>
    <div class="mini-stat-card">
        <div class="mini-stat-icon" style="background:#fef2f2;color:#dc2626;">
            <i data-lucide="shield" style="width:20px;height:20px;"></i>
        </div>
        <div class="mini-stat-body">
            <div class="mini-stat-value">{{ $stats['admin'] }}</div>
            <div class="mini-stat-label">Admin</div>
        </div>
    </div>
</div>
