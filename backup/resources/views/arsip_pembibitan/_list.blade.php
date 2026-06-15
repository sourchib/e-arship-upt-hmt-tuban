{{-- Desktop Table --}}
<div class="data-card desktop-table">
    <div class="data-card-header">
        <span class="data-card-title">Daftar Data Pembibitan</span>
        <span style="font-size:12px;color:#94a3b8;">Total: {{ $arsipPembibitan->total() }} data</span>
    </div>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:48px;">No</th>
                    <th>Kode</th>
                    <th>Jenis Ternak</th>
                    <th style="text-align:center;">Jumlah</th>
                    <th>Umur</th>
                    <th>Tujuan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsipPembibitan as $index => $item)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">{{ $arsipPembibitan->firstItem() + $index }}</td>
                    <td><span style="font-weight:600;font-size:13.5px;">{{ $item->kode }}</span></td>
                    <td><span style="color:#475569;font-size:13px;">{{ $item->jenis_ternak }}</span></td>
                    <td style="text-align:center;"><span style="color:#475569;font-size:13px;font-weight:600;">{{ $item->jumlah }} ekor</span></td>
                    <td><span style="color:#64748b;font-size:13px;">{{ $item->umur }}</span></td>
                    <td><span style="color:#64748b;font-size:13px;">{{ $item->tujuan }}</span></td>
                    <td>
                        <span style="color:#64748b;font-size:13px;display:flex;align-items:center;gap:6px;">
                            <i data-lucide="calendar" style="width:14px;height:14px;color:#94a3b8;"></i>
                            {{ $item->tanggal->format('d/m/Y') }}
                        </span>
                    </td>
                    <td>
                        @php
                            $sc = $item->status == 'Terdistribusi' ? 'bg-terkirim' : 'bg-pending';
                        @endphp
                        <span class="status-badge {{ $sc }}">{{ $item->status }}</span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <button type="button" class="action-btn action-btn-view btn-view-detail" 
                                    data-kode="{{ $item->kode }}"
                                    data-jenis="{{ $item->jenis_ternak }}"
                                    data-jumlah="{{ $item->jumlah }}"
                                    data-umur="{{ $item->umur }}"
                                    data-tujuan="{{ $item->tujuan }}"
                                    data-tanggal="{{ $item->tanggal->format('d/m/Y') }}"
                                    data-status="{{ $item->status }}"
                                    data-status-class="{{ $sc }}"
                                    title="Detail">
                                <i data-lucide="eye"></i>
                            </button>
                            @if(Auth::check() && Auth::user()->role === 'Admin')
                            <button type="button" class="action-btn action-btn-edit btn-edit-arsip"
                                    data-kode="{{ $item->kode }}"
                                    data-jenis="{{ $item->jenis_ternak }}"
                                    data-jumlah="{{ $item->jumlah }}"
                                    data-umur="{{ $item->umur }}"
                                    data-tujuan="{{ $item->tujuan }}"
                                    data-tanggal="{{ $item->tanggal->format('Y-m-d') }}"
                                    data-status="{{ $item->status }}"
                                    data-url="{{ route('arsip-pembibitan.update', $item->id) }}"
                                    title="Edit">
                                <i data-lucide="edit-3"></i>
                            </button>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('arsip-pembibitan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" class="action-btn action-btn-delete btn-delete-confirm" data-form="delete-form-{{ $item->id }}" title="Hapus">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i data-lucide="database" style="display:block;margin:0 auto 14px;"></i>
                            <p>Belum ada data pembibitan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Cards --}}
<div class="mobile-card-list">
    @forelse($arsipPembibitan as $index => $item)
    <div class="mobile-card">
        <div class="mobile-card-header">
            <span style="font-size:11px;color:#94a3b8;font-weight:600;">#{{ $arsipPembibitan->firstItem() + $index }}</span>
            @php $sc = $item->status == 'Terdistribusi' ? 'bg-terkirim' : 'bg-pending'; @endphp
            <span class="status-badge {{ $sc }}">{{ $item->status }}</span>
        </div>
        <div class="mobile-card-field">
            <div class="mobile-card-label">Kode</div>
            <div class="mobile-card-value" style="font-weight:700;">{{ $item->kode }}</div>
        </div>
        <div class="mobile-card-field">
            <div class="mobile-card-label">Jenis Ternak</div>
            <div class="mobile-card-value">{{ $item->jenis_ternak }}</div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
            <div>
                <div class="mobile-card-label">Jumlah / Umur</div>
                <div class="mobile-card-value" style="font-size:12px;font-weight:600;">
                    {{ $item->jumlah }} ekor ({{ $item->umur }})
                </div>
            </div>
            <div>
                <div class="mobile-card-label">Tujuan</div>
                <div class="mobile-card-value" style="font-size:12px;">{{ $item->tujuan }}</div>
            </div>
        </div>
        <div class="mobile-card-field" style="margin-bottom: 0;">
            <div class="mobile-card-label">Tanggal</div>
            <div class="mobile-card-value" style="font-size:12px;display:flex;align-items:center;gap:4px;">
                <i data-lucide="calendar" style="width:12px;height:12px;color:#94a3b8;"></i>
                {{ $item->tanggal->format('d/m/Y') }}
            </div>
        </div>
        <div class="mobile-card-footer mt-2">
            <div class="action-btns">
                <button type="button" class="action-btn action-btn-view btn-view-detail" 
                        data-kode="{{ $item->kode }}"
                        data-jenis="{{ $item->jenis_ternak }}"
                        data-jumlah="{{ $item->jumlah }}"
                        data-umur="{{ $item->umur }}"
                        data-tujuan="{{ $item->tujuan }}"
                        data-tanggal="{{ $item->tanggal->format('d/m/Y') }}"
                        data-status="{{ $item->status }}"
                        data-status-class="{{ $sc }}"
                        title="Detail">
                    <i data-lucide="eye"></i>
                </button>
                @if(Auth::check() && Auth::user()->role === 'Admin')
                <button type="button" class="action-btn action-btn-edit btn-edit-arsip"
                        data-kode="{{ $item->kode }}"
                        data-jenis="{{ $item->jenis_ternak }}"
                        data-jumlah="{{ $item->jumlah }}"
                        data-umur="{{ $item->umur }}"
                        data-tujuan="{{ $item->tujuan }}"
                        data-tanggal="{{ $item->tanggal->format('Y-m-d') }}"
                        data-status="{{ $item->status }}"
                        data-url="{{ route('arsip-pembibitan.update', $item->id) }}"
                        title="Edit">
                    <i data-lucide="edit-3"></i>
                </button>
                @endif
            </div>
            @if(Auth::check() && Auth::user()->role === 'Admin')
            <button type="button" class="action-btn action-btn-delete btn-delete-confirm" data-form="delete-form-{{ $item->id }}"><i data-lucide="trash-2"></i></button>
            @endif
        </div>
    </div>
    @empty
    <div class="mobile-card" style="text-align:center;padding:40px;">
        <p style="color:#94a3b8;font-size:13px;">Belum ada data pembibitan.</p>
    </div>
    @endforelse
</div>

{{ $arsipPembibitan->links('vendor.pagination.custom') }}
