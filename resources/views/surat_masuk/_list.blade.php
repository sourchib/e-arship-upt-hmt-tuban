{{-- Desktop Table --}}
<div class="data-card desktop-table">
    <div class="data-card-header">
        <span class="data-card-title">Daftar Surat Masuk</span>
        <span style="font-size:12px;color:#94a3b8;">Total: {{ $suratMasuk->total() }} surat</span>
    </div>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:48px;">No</th>
                    <th>Nomor Surat</th>
                    <th>Perihal</th>
                    <th>Pengirim</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratMasuk as $index => $surat)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">{{ $suratMasuk->firstItem() + $index }}</td>
                    <td>
                        <span style="font-weight:600;font-size:13.5px;">{{ $surat->nomor_surat }}</span>
                    </td>
                    <td>
                        <span style="color:#475569;font-size:13.5px;max-width:260px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $surat->perihal }}</span>
                    </td>
                    <td>
                        <span style="color:#64748b;font-size:13px;">{{ $surat->pengirim }}</span>
                    </td>
                    <td>
                        <span style="color:#64748b;font-size:13px;display:flex;align-items:center;gap:6px;">
                            <i data-lucide="calendar" style="width:14px;height:14px;color:#94a3b8;"></i>
                            {{ $surat->tanggal_surat->format('d/m/Y') }}
                        </span>
                    </td>
                    <td>
                        @php
                            $sc = 'bg-pending';
                            if($surat->status == 'Diproses') $sc = 'bg-diproses';
                            if($surat->status == 'Terarsip') $sc = 'bg-terarsip';
                        @endphp
                        <span class="status-badge {{ $sc }}">{{ $surat->status }}</span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('surat-masuk.show', $surat->id) }}" class="action-btn action-btn-view" title="Detail">
                                <i data-lucide="eye"></i>
                            </a>
                            <a href="{{ $surat->file_path ? asset('storage/'.$surat->file_path) : '#' }}"
                               class="action-btn action-btn-dl" title="Download" target="_blank">
                                <i data-lucide="download"></i>
                            </a>
                            <a href="{{ route('surat-masuk.edit', $surat->id) }}" class="action-btn action-btn-edit" title="Edit">
                                <i data-lucide="edit-3"></i>
                            </a>
                            <form id="delete-form-{{ $surat->id }}" action="{{ route('surat-masuk.destroy', $surat->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" class="action-btn action-btn-delete btn-delete-confirm"
                                        data-form="delete-form-{{ $surat->id }}" title="Hapus">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i data-lucide="inbox" style="display:block;margin:0 auto 14px;"></i>
                            <p>Belum ada surat masuk.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $suratMasuk->links('vendor.pagination.custom') }}
</div>

{{-- Mobile Cards --}}
<div class="mobile-card-list">
    @forelse($suratMasuk as $index => $surat)
    <div class="mobile-card">
        <div class="mobile-card-header">
            <span style="font-size:11px;color:#94a3b8;font-weight:600;">#{{ $suratMasuk->firstItem() + $index }}</span>
            @php $sc = 'bg-pending'; if($surat->status=='Diproses') $sc='bg-diproses'; if($surat->status=='Terarsip') $sc='bg-terarsip'; @endphp
            <span class="status-badge {{ $sc }}">{{ $surat->status }}</span>
        </div>
        <div class="mobile-card-field">
            <div class="mobile-card-label">Nomor Surat</div>
            <div class="mobile-card-value">{{ $surat->nomor_surat }}</div>
        </div>
        <div class="mobile-card-field">
            <div class="mobile-card-label">Perihal</div>
            <div class="mobile-card-value">{{ $surat->perihal }}</div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
            <div>
                <div class="mobile-card-label">Pengirim</div>
                <div class="mobile-card-value" style="font-size:12px;">{{ $surat->pengirim }}</div>
            </div>
            <div>
                <div class="mobile-card-label">Tanggal</div>
                <div class="mobile-card-value" style="font-size:12px;">{{ $surat->tanggal_surat->format('d/m/Y') }}</div>
            </div>
        </div>
        <div class="mobile-card-footer">
            <div class="action-btns">
                <a href="{{ route('surat-masuk.show', $surat->id) }}" class="action-btn action-btn-view"><i data-lucide="eye"></i></a>
                <a href="{{ $surat->file_path ? asset('storage/'.$surat->file_path) : '#' }}" class="action-btn action-btn-dl" target="_blank"><i data-lucide="download"></i></a>
                <a href="{{ route('surat-masuk.edit', $surat->id) }}" class="action-btn action-btn-edit"><i data-lucide="edit-3"></i></a>
            </div>
            <button type="button" class="action-btn action-btn-delete btn-delete-confirm" data-form="delete-form-{{ $surat->id }}">
                <i data-lucide="trash-2"></i>
            </button>
        </div>
    </div>
    @empty
    <div class="mobile-card" style="text-align:center;padding:40px;">
        <p style="color:#94a3b8;font-size:13px;">Belum ada surat masuk.</p>
    </div>
    @endforelse
    {{ $suratMasuk->links('vendor.pagination.custom') }}
</div>
