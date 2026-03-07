@extends('layouts.app')

@section('title', 'Hasil Pencarian - E-Arsip')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Hasil Pencarian</h1>
        <p>Mencari: <strong>"{{ $q }}"</strong></p>
    </div>
</div>

<div class="section-card">
    @if(empty($q))
        <div style="text-align:center;padding:40px 0;color:#94a3b8;">
            <i data-lucide="search" style="width:40px;height:40px;opacity:0.4;margin-bottom:12px;display:block;margin-inline:auto;"></i>
            <p style="font-size:13px;">Silakan masukkan kata kunci pencarian pada kolom pencarian di atas.</p>
        </div>
    @elseif(count($results) === 0)
        <div style="text-align:center;padding:40px 0;color:#94a3b8;">
            <i data-lucide="inbox" style="width:40px;height:40px;opacity:0.4;margin-bottom:12px;display:block;margin-inline:auto;"></i>
            <p style="font-size:13px;">Tidak menemukan hasil untuk <strong>"{{ $q }}"</strong>. Coba kata kunci lain.</p>
        </div>
    @else
        <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 20px;">Ditemukan <strong>{{ count($results) }}</strong> hasil untuk pencarian Anda.</p>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            @foreach($results as $item)
            <a href="{{ $item->url }}" style="display:flex;align-items:center;padding:16px;border:1px solid #e2e8f0;border-radius:12px;text-decoration:none;color:inherit;transition:all 0.2s;background:#fff;" onmouseover="this.style.borderColor='#16a34a';this.style.boxShadow='0 4px 12px rgba(22,163,74,0.1)';" onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--bg-page); display: flex; align-items: center; justify-content: center; margin-right: 16px; color: var(--brand-primary); flex-shrink: 0;">
                    <i data-lucide="{{ $item->icon }}"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                        <span style="font-size:10px;font-weight:700;text-transform:uppercase;color:var(--text-secondary);background:#f1f5f9;padding:2px 8px;border-radius:20px;">
                            {{ $item->type }}
                        </span>
                    </div>
                    <h4 style="margin:0 0 4px;font-size:14.5px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $item->title }}
                    </h4>
                    <p style="margin:0;font-size:13px;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $item->desc }}
                    </p>
                </div>
                <div style="text-align:right;margin-left:16px;flex-shrink:0;">
                    <p style="margin:0 0 4px;font-size:12px;color:var(--text-secondary);font-weight:500;">
                        @if($item->date)
                            {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}
                        @else
                            -
                        @endif
                    </p>
                    <i data-lucide="chevron-right" style="width:16px;height:16px;color:var(--text-muted);"></i>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
