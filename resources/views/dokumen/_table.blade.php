<div class="data-card" style="margin-top: 0; outline: none; border: none; box-shadow: none; background: transparent;">
    <div class="desktop-table" style="overflow-x: auto; background: #fff; border-radius: 16px; box-shadow: var(--card-shadow); border: 1px solid rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; min-width: 1000px;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 16px; text-align: center; width: 40px;">
                        <input type="checkbox" id="selectAllItems">
                    </th>
                    <th class="sortable-header hide-mobile" data-sort="kode_asc" style="padding: 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; width: 140px; cursor: pointer;">Kode <i data-lucide="chevrons-up-down" style="width:12px;height:12px;margin-left:4px;opacity:0.5;"></i></th>
                    <th class="sortable-header" data-sort="name_asc" style="padding: 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer;">Nama Dokumen <i data-lucide="chevrons-up-down" style="width:12px;height:12px;margin-left:4px;opacity:0.5;"></i></th>
                    <th class="sortable-header" data-sort="kategori_asc" style="padding: 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; width: 120px; cursor: pointer;">Kategori <i data-lucide="chevrons-up-down" style="width:12px;height:12px;margin-left:4px;opacity:0.5;"></i></th>
                    <th class="sortable-header hide-mobile" data-sort="folder_asc" style="padding: 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; width: 120px; cursor: pointer;">Folder <i data-lucide="chevrons-up-down" style="width:12px;height:12px;margin-left:4px;opacity:0.5;"></i></th>
                    <th class="sortable-header" data-sort="status_asc" style="padding: 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; width: 100px; cursor: pointer;">Status <i data-lucide="chevrons-up-down" style="width:12px;height:12px;margin-left:4px;opacity:0.5;"></i></th>
                    <th class="sortable-header hide-mobile" data-sort="lokasi_asc" style="padding: 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; width: 100px; cursor: pointer;">Lokasi <i data-lucide="chevrons-up-down" style="width:12px;height:12px;margin-left:4px;opacity:0.5;"></i></th>
                    <th class="sortable-header hide-mobile" data-sort="retensi_asc" style="padding: 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; width: 100px; cursor: pointer;">Retensi <i data-lucide="chevrons-up-down" style="width:12px;height:12px;margin-left:4px;opacity:0.5;"></i></th>
                    <th class="sortable-header" data-sort="latest" style="padding: 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; width: 120px; cursor: pointer;">Tgl Dokumen <i data-lucide="chevrons-up-down" style="width:12px;height:12px;margin-left:4px;opacity:0.5;"></i></th>
                    <th style="padding: 16px; text-align: right; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody style="font-size: 13.5px; color: #1e293b;" id="docsTableBody">
                {{-- Show Folders First --}}
                @foreach($allFolders ?? [] as $f)
                <tr class="folder-row" data-id="{{ $f->id }}" style="border-bottom: 1px solid #f1f5f9; background: #fffcf0; transition: background 0.2s; cursor: pointer;">
                    <td style="padding: 14px 16px; text-align: center;">
                        <input type="checkbox" class="folder-checkbox" data-id="{{ $f->id }}">
                    </td>
                    <td class="hide-mobile" style="padding: 14px 16px;">-</td>
                    <td style="padding: 14px 16px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; background: #fef3c7; color: #d97706; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i data-lucide="folder" style="width: 20px; height: 20px;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 800; color: #1e293b;">{{ $f->nama }}</div>
                                <div style="font-size: 11px; color: #94a3b8;">{{ $f->dokumen_count ?? $f->dokumen()->count() }} Item</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 14px 16px;">
                        <span style="padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
                            Folder
                        </span>
                    </td>
                    <td class="hide-mobile" style="padding: 14px 16px;">-</td>
                    <td style="padding: 14px 16px;">-</td>
                    <td class="hide-mobile" style="padding: 14px 16px;">-</td>
                    <td class="hide-mobile" style="padding: 14px 16px;">-</td>
                    <td style="padding: 14px 16px; color: #64748b;">{{ $f->created_at->format('d/m/Y') }}</td>
                    <td style="padding: 14px 16px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <button type="button" class="action-btn-table edit btn-rename-folder" data-id="{{ $f->id }}" data-nama="{{ $f->nama }}" title="Rename">
                                <i data-lucide="edit-2"></i>
                            </button>
                            <button type="button" class="action-btn-table delete btn-delete-folder" data-id="{{ $f->id }}" title="Hapus">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach

                {{-- Show Documents --}}
                @forelse($documents as $index => $doc)
                <tr class="doc-row" style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                    <td style="padding: 14px 16px; text-align: center;">
                        <input type="checkbox" class="doc-checkbox" data-id="{{ $doc->id }}">
                    </td>
                    <td class="hide-mobile" style="padding: 14px 16px;">
                        <span style="font-family: 'JetBrains Mono', monospace; font-size: 11.5px; font-weight: 700; color: #475569; background: #f1f5f9; padding: 3px 8px; border-radius: 6px; border: 1px solid #e2e8f0;">
                            {{ $doc->kode ?? '-' }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px;">
                        <div style="font-weight: 700; color: #1e293b;">{{ $doc->nama }}</div>
                        <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">{{ number_format($doc->ukuran / 1024, 1) }} KB • {{ strtoupper(pathinfo($doc->file_path, PATHINFO_EXTENSION)) }}</div>
                    </td>
                    <td style="padding: 14px 16px;">
                        @php
                            $catColor = match($doc->kategori) {
                                'Laporan' => 'background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe;',
                                'Dokumen Teknis' => 'background: #f0fdfa; color: #0d9488; border: 1px solid #ccfbf1;',
                                'Data' => 'background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;',
                                'Panduan' => 'background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5;',
                                'Surat' => 'background: #f5f3ff; color: #6d28d9; border: 1px solid #ede9fe;',
                                default => 'background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7;'
                            };
                        @endphp
                        <span style="padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; {{ $catColor }}">
                            {{ $doc->kategori }}
                        </span>
                    </td>
                    <td class="hide-mobile" style="padding: 14px 16px;">
                        <div style="font-weight: 600; color: #16a34a; font-size: 12.5px; display: flex; align-items: center; gap: 4px;">
                            @if($doc->folder_id)
                                <i data-lucide="folder" style="width: 12px; height: 12px;"></i>
                            @endif
                            {{ $doc->folder ?? '-' }}
                        </div>
                    </td>
                    <td style="padding: 14px 16px;">
                        <span style="padding: 4px 10px; border-radius: 8px; font-size: 10.5px; font-weight: 700; 
                            background: {{ $doc->status == 'Aktif' ? '#dcfce7' : '#fee2e2' }}; 
                            color: {{ $doc->status == 'Aktif' ? '#15803d' : '#991b1b' }};
                            border: 1px solid {{ $doc->status == 'Aktif' ? '#bbf7d0' : '#fecaca' }};">
                            {{ $doc->status }}
                        </span>
                    </td>
                    <td class="hide-mobile" style="padding: 14px 16px;">
                        <div style="display: flex; align-items: center; gap: 6px; color: #475569; font-weight: 600; font-size: 12.5px;">
                            <i data-lucide="archive" style="width: 13px; height: 13px; color: #16a34a;"></i>
                            {{ $doc->lokasi ?? '-' }}
                        </div>
                    </td>
                    <td class="hide-mobile" style="padding: 14px 16px; font-weight: 600; color: #64748b; font-size: 12.5px;">
                        {{ $doc->masa_retensi ?? '-' }}
                    </td>
                    <td style="padding: 14px 16px; color: #64748b; font-weight: 500;">
                        {{ $doc->tanggal ? $doc->tanggal->format('d/m/Y') : $doc->tanggal_upload->format('d/m/Y') }}
                    </td>
                    <td style="padding: 14px 16px; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('dokumen.preview', $doc->id) }}" target="_blank" class="action-btn-table view" title="Lihat">
                                <i data-lucide="eye"></i>
                            </a>
                            <a href="{{ route('dokumen.download', $doc->id) }}" class="action-btn-table download" title="Download">
                                <i data-lucide="download"></i>
                            </a>
                            @if(Auth::check() && Auth::user()->role === 'Admin')
                            <button type="button" class="action-btn-table edit btn-edit-dokumen" data-id="{{ $doc->id }}" title="Edit">
                                <i data-lucide="edit-3"></i>
                            </button>
                            <form id="delete-form-{{ $doc->id }}" action="{{ route('dokumen.destroy', $doc->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" class="action-btn-table delete btn-delete-confirm" data-form="delete-form-{{ $doc->id }}" title="Hapus">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="padding: 60px 0;">
                        <div style="text-align: center; color: #94a3b8;">
                            <i data-lucide="folder-x" style="width: 48px; height: 48px; margin: 0 auto 12px; opacity: 0.3;"></i>
                            <p style="font-weight: 500;">Belum ada dokumen yang diupload.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px;">
        {{ $documents->appends(request()->query())->links('vendor.pagination.custom') }}
    </div>
</div>

<style>
    .doc-row:hover {
        background: #f8fafc;
    }
    .action-btn-table {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .action-btn-table i {
        width: 14px;
        height: 14px;
    }
    .action-btn-table.view { background: #f0fdf4; color: #16a34a; }
    .action-btn-table.view:hover { background: #16a34a; color: #fff; }
    
    .action-btn-table.download { background: #eff6ff; color: #1d4ed8; }
    .action-btn-table.download:hover { background: #1d4ed8; color: #fff; }
    
    .action-btn-table.edit { background: #fff7ed; color: #c2410c; }
    .action-btn-table.edit:hover { background: #c2410c; color: #fff; }
    
    .action-btn-table.delete { background: #fef2f2; color: #b91c1c; }
    .action-btn-table.delete:hover { background: #b91c1c; color: #fff; }
</style>
