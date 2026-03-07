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

        <div class="doc-meta">
            <div style="display:flex;align-items:center;gap:6px;">
                <i data-lucide="clock" style="width:14px;height:14px;"></i>
                <span>Diupload: {{ $doc->tanggal_upload->format('d/m/Y') }}</span>
            </div>
            <div style="display:flex;align-items:center;gap:6px;">
                <i data-lucide="user" style="width:14px;height:14px;"></i>
                <span>Oleh: {{ $doc->uploader->nama ?? 'Admin' }}</span>
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
            <form id="delete-form-{{ $doc->id }}" action="{{ route('dokumen.destroy', $doc->id) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="button" class="action-btn action-btn-delete btn-delete-confirm" data-form="delete-form-{{ $doc->id }}" title="Hapus">
                    <i data-lucide="trash-2"></i>
                </button>
            </form>
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
