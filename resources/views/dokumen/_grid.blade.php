<div class="doc-grid">
    @forelse($documents as $doc)
    <div class="doc-card">
        <div class="doc-card-header">
            <div style="display:flex;gap:12px;">
                @php
                    $isPdf = str_contains($doc->mime_type, 'pdf');
                    $isExcel = str_contains($doc->mime_type, 'spreadsheet') || str_contains($doc->mime_type, 'excel');
                    $iconClass = $isPdf ? 'doc-icon-pdf' : ($isExcel ? 'doc-icon-excel' : 'doc-icon-word');
                    $iconName = $isPdf ? 'file-type-2' : ($isExcel ? 'file-spreadsheet' : 'file-text');
                    $labelText = $isPdf ? 'PDF' : ($isExcel ? 'Excel' : 'File');
                @endphp
                <div class="doc-icon {{ $iconClass }}">
                    <i data-lucide="{{ $iconName }}"></i>
                </div>
                <div>
                    <div style="font-size:10px;font-weight:700;letter-spacing:0.5px;color:#94a3b8;text-transform:uppercase;">{{ $labelText }}</div>
                    <div style="font-size:11px;font-weight:600;color:#64748b;margin-top:2px;">{{ number_format($doc->ukuran / 1024, 1) }} KB</div>
                </div>
            </div>
            
            @php
                $catColor = match($doc->kategori) {
                    'Laporan' => 'bg-terkirim',
                    'Dokumen Teknis' => 'bg-diproses',
                    'Data' => 'bg-terarsip',
                    'Panduan' => 'bg-pending',
                    'Surat' => 'bg-terarsip',
                    default => 'bg-terarsip'
                };
            @endphp
            <div class="status-badge {{ $catColor }}" style="align-self:flex-start;">
                {{ $doc->kategori }}
            </div>
        </div>

        <h5 class="doc-title" title="{{ $doc->nama }}">
            {{ $doc->nama }}
        </h5>

        <div class="doc-meta" style="background: #f8fafc; padding: 12px; border-radius: 10px; margin: 12px 0;">
            <div style="margin-bottom: 8px; border-bottom: 1px solid #edf2f7; padding-bottom: 6px;">
                <div style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Kode Arsip:</div>
                <div style="font-size: 13px; font-weight: 700; color: #1e293b;">{{ $doc->kode ?? '-' }}</div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                <div>
                    <div style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Tanggal:</div>
                    <div style="font-size: 12px; font-weight: 600; color: #475569;">{{ $doc->tanggal ? $doc->tanggal->format('d/m/Y') : $doc->tanggal_upload->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Masa Retensi:</div>
                    <div style="font-size: 12px; font-weight: 600; color: #475569;">{{ $doc->masa_retensi ?? '-' }}</div>
                </div>
            </div>

            <div style="margin-top: 8px; display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <div style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Rak / Lokasi:</div>
                    <div style="font-size: 12px; font-weight: 600; color: #475569; display: flex; align-items: center; gap: 4px;">
                        <i data-lucide="archive" style="width: 12px; height: 12px; color: #16a34a;"></i>
                        {{ $doc->lokasi ?? '-' }}
                    </div>
                </div>
                @if($doc->sifat_arsip)
                <div style="text-align: right;">
                    <div style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Sifat Arsip:</div>
                    <div style="margin-top: 2px;">
                        <span style="padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; background: {{ $doc->sifat_arsip == 'Dirahasiakan' ? '#fef2f2' : '#f0fdf4' }}; color: {{ $doc->sifat_arsip == 'Dirahasiakan' ? '#dc2626' : '#15803d' }}; border: 1px solid {{ $doc->sifat_arsip == 'Dirahasiakan' ? '#fecaca' : '#bbf7d0' }};">
                            {{ strtoupper($doc->sifat_arsip) }}
                        </span>
                    </div>
                </div>
                @endif
            </div>
            <div style="margin-top: 8px; font-size: 10px; color: #94a3b8; display: flex; align-items: center; justify-content: space-between; gap: 4px;">
                <div style="display: flex; align-items: center; gap: 4px;">
                    <i data-lucide="user" style="width: 12px; height: 12px;"></i> Diupload: {{ $doc->uploader->nama ?? 'Admin' }}
                </div>
                <div>
                    @if($doc->is_public)
                        <span style="color: #16a34a; display: flex; align-items: center; gap: 4px; font-weight: 600;" title="Bisa dilihat Staf Umum"><i data-lucide="globe" style="width: 12px; height: 12px;"></i> Publik</span>
                    @else
                        <span style="color: #dc2626; display: flex; align-items: center; gap: 4px; font-weight: 600;" title="Hanya dilihat Admin"><i data-lucide="lock" style="width: 12px; height: 12px;"></i> Private</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="doc-footer">
            <div style="gap:4px; display:flex;">
                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="action-btn action-btn-view" title="Lihat">
                    <i data-lucide="eye"></i>
                </a>
                <a href="{{ route('dokumen.download', $doc->id) }}" class="action-btn action-btn-dl" title="Download">
                    <i data-lucide="download"></i>
                </a>
            </div>
            @if(Auth::check() && Auth::user()->role === 'Admin')
            <div style="gap:4px; display:flex;">
                <button type="button" class="action-btn action-btn-edit btn-edit-dokumen" data-id="{{ $doc->id }}" title="Edit">
                    <i data-lucide="edit-3"></i>
                </button>
                <form id="delete-form-{{ $doc->id }}" action="{{ route('dokumen.destroy', $doc->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="button" class="action-btn action-btn-delete btn-delete-confirm" data-form="delete-form-{{ $doc->id }}" title="Hapus">
                        <i data-lucide="trash-2"></i>
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1;">
        <div class="empty-state">
            <i data-lucide="folder-open" style="display:block;margin:0 auto 14px;"></i>
            <p>Belum ada dokumen yang diupload.</p>
        </div>
    </div>
    @endforelse
</div>

{{ $documents->links('vendor.pagination.custom') }}
