{{-- Desktop Table --}}
<div class="data-card desktop-table">
    <div class="data-card-header">
        <span class="data-card-title">Daftar Surat Keluar</span>
        <span style="font-size:12px;color:#94a3b8;">Total: {{ $suratKeluar->total() }} surat</span>
    </div>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:48px;">No</th>
                    <th>Nomor Surat</th>
                    <th>Perihal</th>
                    <th>Tujuan</th>
                    <th class="sortable-col" data-sort="tanggal_surat" style="cursor:pointer; white-space:nowrap;" title="Klik untuk mengurutkan">
                        Tanggal Surat
                        @if(request('sort_by') == 'tanggal_surat')
                            @if(request('sort_order') == 'asc')
                                <i data-lucide="arrow-up" style="width:14px;height:14px;margin-left:4px;display:inline-block;vertical-align:middle;color:#3b82f6;"></i>
                            @else
                                <i data-lucide="arrow-down" style="width:14px;height:14px;margin-left:4px;display:inline-block;vertical-align:middle;color:#3b82f6;"></i>
                            @endif
                        @else
                            <i data-lucide="arrow-up-down" style="width:14px;height:14px;margin-left:4px;display:inline-block;vertical-align:middle;color:#94a3b8;"></i>
                        @endif
                    </th>
                    <th>Tanggal Kirim</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratKeluar as $index => $surat)
                    <tr>
                        <td style="color:#94a3b8;font-size:12px;">{{ $suratKeluar->firstItem() + $index }}</td>
                        <td>
                            <span style="font-weight:600;font-size:13.5px;">{{ $surat->nomor_surat }}</span>
                        </td>
                        <td>
                            <span style="color:#475569;font-size:13.5px;max-width:220px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $surat->perihal }}">
                                {{ $surat->perihal }}
                            </span>
                        </td>
                        <td>
                            <span style="color:#64748b;font-size:13px;max-width:180px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:normal;" title="{{ $surat->tujuan }}">
                                {{ $surat->tujuan }}
                            </span>
                        </td>
                        <td>
                            <span style="color:#64748b;font-size:13px;display:flex;align-items:center;gap:6px;">
                                <i data-lucide="calendar" style="width:14px;height:14px;color:#94a3b8;"></i>
                                {{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            <span style="color:#64748b;font-size:13px;display:flex;align-items:center;gap:6px;">
                                <i data-lucide="send" style="width:14px;height:14px;color:#94a3b8;"></i>
                                {{ $surat->tanggal_kirim ? $surat->tanggal_kirim->format('d/m/Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button type="button" class="action-btn action-btn-view btn-view-detail"
                                    data-nomor="{{ $surat->nomor_surat }}"
                                    data-perihal="{{ $surat->perihal }}"
                                    data-tujuan="{{ $surat->tujuan }}"
                                    data-tanggal-surat="{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-' }}"
                                    data-tanggal-kirim="{{ $surat->tanggal_kirim ? $surat->tanggal_kirim->format('d/m/Y') : '-' }}"
                                    data-prioritas="{{ $surat->prioritas }}"
                                    data-file-path="{{ $surat->file_path }}"
                                    title="Detail">
                                    <i data-lucide="eye"></i>
                                </button>

                                <a href="{{ $surat->file_path ? route('download.file', str_replace('/', '|', $surat->file_path)) : '#' }}"
                                   class="action-btn action-btn-dl" title="Download" target="_blank">
                                    <i data-lucide="download"></i>
                                </a>

                                @if(Auth::check() && Auth::user()->role === 'Admin')
                                    <button type="button" class="action-btn action-btn-edit btn-edit-surat"
                                        data-id="{{ $surat->id }}"
                                        data-nomor="{{ $surat->nomor_surat }}"
                                        data-perihal="{{ $surat->perihal }}"
                                        data-tujuan="{{ $surat->tujuan }}"
                                        data-tanggal="{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('Y-m-d') : '' }}"
                                        data-tanggal-kirim="{{ $surat->tanggal_kirim ? $surat->tanggal_kirim->format('Y-m-d') : '' }}"
                                        data-prioritas="{{ $surat->prioritas }}"
                                        data-url="{{ route('surat-keluar.update', $surat->id) }}"
                                        title="Edit">
                                        <i data-lucide="edit-3"></i>
                                    </button>

                                    <form id="delete-form-{{ $surat->id }}"
                                          action="{{ route('surat-keluar.destroy', $surat->id) }}"
                                          method="POST"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="action-btn action-btn-delete btn-delete-confirm"
                                            data-form="delete-form-{{ $surat->id }}" title="Hapus">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i data-lucide="inbox" style="display:block;margin:0 auto 14px;"></i>
                                <p>Belum ada surat keluar.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $suratKeluar->links('vendor.pagination.custom') }}
</div>

{{-- Mobile Cards --}}
<div class="mobile-card-list">
    @forelse($suratKeluar as $index => $surat)
        <div class="mobile-card">
            <div class="mobile-card-header">
                <span style="font-size:11px;color:#94a3b8;font-weight:600;">#{{ $suratKeluar->firstItem() + $index }}</span>
            </div>
            <div class="mobile-card-title">{{ $surat->nomor_surat }}</div>
            <div class="mobile-card-subtitle">{{ $surat->perihal }}</div>

            <div class="mobile-card-info">
                <div><strong>Tujuan:</strong> {{ $surat->tujuan }}</div>
                <div><strong>Tgl Surat:</strong> {{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-' }}</div>
                <div><strong>Tgl Kirim:</strong> {{ $surat->tanggal_kirim ? $surat->tanggal_kirim->format('d/m/Y') : '-' }}</div>
            </div>

            <div class="mobile-card-actions">
                <button type="button" class="action-btn action-btn-view btn-view-detail"
                    data-nomor="{{ $surat->nomor_surat }}"
                    data-perihal="{{ $surat->perihal }}"
                    data-tujuan="{{ $surat->tujuan }}"
                    data-tanggal-surat="{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-' }}"
                    data-tanggal-kirim="{{ $surat->tanggal_kirim ? $surat->tanggal_kirim->format('d/m/Y') : '-' }}"
                    data-prioritas="{{ $surat->prioritas }}"
                    data-file-path="{{ $surat->file_path }}"
                    title="Detail">
                    <i data-lucide="eye"></i>
                </button>

                <a href="{{ $surat->file_path ? route('download.file', str_replace('/', '|', $surat->file_path)) : '#' }}"
                   class="action-btn action-btn-dl" title="Download" target="_blank">
                    <i data-lucide="download"></i>
                </a>

                @if(Auth::check() && Auth::user()->role === 'Admin')
                    <button type="button" class="action-btn action-btn-edit btn-edit-surat"
                        data-id="{{ $surat->id }}"
                        data-nomor="{{ $surat->nomor_surat }}"
                        data-perihal="{{ $surat->perihal }}"
                        data-tujuan="{{ $surat->tujuan }}"
                        data-tanggal="{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('Y-m-d') : '' }}"
                        data-tanggal-kirim="{{ $surat->tanggal_kirim ? $surat->tanggal_kirim->format('Y-m-d') : '' }}"
                        data-prioritas="{{ $surat->prioritas }}"
                        data-url="{{ route('surat-keluar.update', $surat->id) }}"
                        title="Edit">
                        <i data-lucide="edit-3"></i>
                    </button>

                    <form id="delete-form-mobile-{{ $surat->id }}" action="{{ route('surat-keluar.destroy', $surat->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="action-btn action-btn-delete btn-delete-confirm" data-form="delete-form-mobile-{{ $surat->id }}" title="Hapus">
                            <i data-lucide="trash-2"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i data-lucide="inbox" style="display:block;margin:0 auto 14px;"></i>
            <p>Belum ada surat keluar.</p>
        </div>
    @endforelse
</div>
