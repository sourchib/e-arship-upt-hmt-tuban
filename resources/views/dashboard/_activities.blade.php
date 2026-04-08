@foreach($recentActivities as $activity)
<div style="padding: 12px 16px; border-bottom: 1px solid #f8fafc; display: flex; gap: 12px; align-items: flex-start; transition: background 0.2s; cursor: default;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
    <div style="width: 32px; height: 32px; border-radius: 8px; background: {{ $activity->modul == 'Surat Masuk' ? '#eff6ff' : ($activity->modul == 'Surat Keluar' ? '#f0fdf4' : '#f5f3ff') }}; color: {{ $activity->modul == 'Surat Masuk' ? '#3b82f6' : ($activity->modul == 'Surat Keluar' ? '#16a34a' : '#7c3aed') }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <i data-lucide="{{ $activity->modul == 'Surat Masuk' ? 'mail' : ($activity->modul == 'Surat Keluar' ? 'send' : 'file-text') }}" style="width: 16px; height: 16px;"></i>
    </div>
    <div style="flex: 1; min-width: 0;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2px;">
            <p style="margin: 0; font-size: 13px; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $activity->user->nama ?? 'System' }}</p>
            <span style="font-size: 11px; color: #94a3b8; white-space: nowrap;">{{ $activity->created_at->diffForHumans() }}</span>
        </div>
        <p style="margin: 0; font-size: 12px; color: #64748b; line-height: 1.4;">{{ $activity->deskripsi }}</p>
        <span style="display: inline-block; margin-top: 4px; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; {{ $activity->modul == 'Surat Masuk' ? 'background: #eff6ff; color: #3b82f6;' : ($activity->modul == 'Surat Keluar' ? 'background: #f0fdf4; color: #16a34a;' : 'background: #f5f3ff; color: #7c3aed;') }}">
            {{ $activity->modul }}
        </span>
    </div>
</div>
@endforeach

@if($recentActivities->isEmpty())
<div style="padding: 30px 20px; text-align: center; color: #94a3b8;">
    <i data-lucide="info" style="width: 24px; height: 24px; margin-bottom: 8px; opacity: 0.5;"></i>
    <p style="margin: 0; font-size: 13px;">Belum ada aktivitas terbaru</p>
</div>
@endif
