{{-- Desktop Table --}}
<div class="data-card desktop-table">
    <div class="data-card-header">
        <span class="data-card-title">Daftar Data Hijauan</span>
        <span style="font-size:12px;color:#94a3b8;">Total: {{ $arsipHijauan->total() }} data</span>
    </div>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:48px;">No</th>
                    <th>Kode Lahan</th>
                    <th>Jenis Hijauan</th>
                    <th style="text-align:right;">Luas (Ha)</th>
                    <th style="text-align:right;">Produksi (Kg)</th>
                    <th>Tanggal Panen</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsipHijauan as $index => $item)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">{{ $arsipHijauan->firstItem() + $index }}</td>
                    <td><span style="font-weight:600;font-size:13.5px;">{{ $item->kode_lahan }}</span></td>
                    <td><span style="color:#475569;font-size:13px;">{{ $item->jenis_hijauan }}</span></td>
                    <td style="text-align:right;"><span style="color:#475569;font-size:13px;font-weight:600;">{{ $item->luas }}</span></td>
                    <td style="text-align:right;"><span style="color:#475569;font-size:13px;font-weight:600;">{{ number_format($item->produksi, 0, ',', '.') }}</span></td>
                    <td>
                        <span style="color:#64748b;font-size:13px;display:flex;align-items:center;gap:6px;">
                            <i data-lucide="calendar" style="width:14px;height:14px;color:#94a3b8;"></i>
                            {{ $item->tanggal_panen->format('d/m/Y') }}
                        </span>
                    </td>
                    <td>
                        <span style="color:#64748b;font-size:13px;display:flex;align-items:center;gap:6px;">
                            <i data-lucide="map-pin" style="width:14px;height:14px;color:#94a3b8;"></i>
                            {{ Str::limit($item->lokasi, 20) }}
                        </span>
                    </td>
                    <td>
                        @php
                            $sc = $item->status == 'Tersedia' ? 'bg-terkirim' : 'bg-diproses';
                        @endphp
                        <span class="status-badge {{ $sc }}">{{ $item->status }}</span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <button type="button" class="action-btn action-btn-view btn-view-detail" 
                                    data-kode="{{ $item->kode_lahan }}"
                                    data-jenis="{{ $item->jenis_hijauan }}"
                                    data-luas="{{ $item->luas }}"
                                    data-produksi="{{ number_format($item->produksi, 0, ',', '.') }}"
                                    data-tanggal="{{ $item->tanggal_panen->format('d/m/Y') }}"
                                    data-lokasi="{{ $item->lokasi }}"
                                    data-status="{{ $item->status }}"
                                    data-status-class="{{ $sc }}"
                                    title="Detail">
                                <i data-lucide="eye"></i>
                            </button>
                            <button type="button" class="action-btn action-btn-edit btn-edit-arsip"
                                    data-kode="{{ $item->kode_lahan }}"
                                    data-jenis="{{ $item->jenis_hijauan }}"
                                    data-luas="{{ $item->luas }}"
                                    data-produksi="{{ $item->produksi }}"
                                    data-tanggal="{{ $item->tanggal_panen->format('Y-m-d') }}"
                                    data-lokasi="{{ $item->lokasi }}"
                                    data-status="{{ $item->status }}"
                                    data-url="{{ route('arsip-hijauan.update', $item->id) }}"
                                    title="Edit">
                                <i data-lucide="edit-3"></i>
                            </button>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('arsip-hijauan.destroy', $item->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" class="action-btn action-btn-delete btn-delete-confirm" data-form="delete-form-{{ $item->id }}" title="Hapus">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i data-lucide="leaf" style="display:block;margin:0 auto 14px;"></i>
                            <p>Belum ada data hijauan.</p>
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
    @forelse($arsipHijauan as $index => $item)
    <div class="mobile-card">
        <div class="mobile-card-header">
            <span style="font-size:11px;color:#94a3b8;font-weight:600;">#{{ $arsipHijauan->firstItem() + $index }}</span>
            @php $sc = $item->status == 'Tersedia' ? 'bg-terkirim' : 'bg-diproses'; @endphp
            <span class="status-badge {{ $sc }}">{{ $item->status }}</span>
        </div>
        <div class="mobile-card-field">
            <div class="mobile-card-label">Kode Lahan</div>
            <div class="mobile-card-value" style="font-weight:700;">{{ $item->kode_lahan }}</div>
        </div>
        <div class="mobile-card-field">
            <div class="mobile-card-label">Jenis Hijauan</div>
            <div class="mobile-card-value">{{ $item->jenis_hijauan }}</div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
            <div>
                <div class="mobile-card-label">Luas / Produksi</div>
                <div class="mobile-card-value" style="font-size:12px;font-weight:600;">
                    {{ $item->luas }} Ha / {{ number_format($item->produksi, 0, ',', '.') }} Kg
                </div>
            </div>
            <div>
                <div class="mobile-card-label">Tanggal Panen</div>
                <div class="mobile-card-value" style="font-size:12px;display:flex;align-items:center;gap:4px;">
                    <i data-lucide="calendar" style="width:12px;height:12px;color:#94a3b8;"></i>
                    {{ $item->tanggal_panen->format('d/m/Y') }}
                </div>
            </div>
        </div>
        <div class="mobile-card-field" style="margin-bottom: 0;">
            <div class="mobile-card-label">Lokasi</div>
            <div class="mobile-card-value" style="font-size:12px;display:flex;align-items:center;gap:4px;">
                <i data-lucide="map-pin" style="width:12px;height:12px;color:#94a3b8;"></i>
                {{ $item->lokasi }}
            </div>
        </div>
        <div class="mobile-card-footer mt-2">
            <div class="action-btns">
                <button type="button" class="action-btn action-btn-view btn-view-detail" 
                        data-kode="{{ $item->kode_lahan }}"
                        data-jenis="{{ $item->jenis_hijauan }}"
                        data-luas="{{ $item->luas }}"
                        data-produksi="{{ number_format($item->produksi, 0, ',', '.') }}"
                        data-tanggal="{{ $item->tanggal_panen->format('d/m/Y') }}"
                        data-lokasi="{{ $item->lokasi }}"
                        data-status="{{ $item->status }}"
                        data-status-class="{{ $sc }}"
                        title="Detail">
                    <i data-lucide="eye"></i>
                </button>
                <button type="button" class="action-btn action-btn-edit btn-edit-arsip"
                        data-kode="{{ $item->kode_lahan }}"
                        data-jenis="{{ $item->jenis_hijauan }}"
                        data-luas="{{ $item->luas }}"
                        data-produksi="{{ $item->produksi }}"
                        data-tanggal="{{ $item->tanggal_panen->format('Y-m-d') }}"
                        data-lokasi="{{ $item->lokasi }}"
                        data-status="{{ $item->status }}"
                        data-url="{{ route('arsip-hijauan.update', $item->id) }}"
                        title="Edit">
                    <i data-lucide="edit-3"></i>
                </button>
            </div>
            <button type="button" class="action-btn action-btn-delete btn-delete-confirm" data-form="delete-form-{{ $item->id }}"><i data-lucide="trash-2"></i></button>
        </div>
    </div>
    @empty
    <div class="mobile-card" style="text-align:center;padding:40px;">
        <p style="color:#94a3b8;font-size:13px;">Belum ada data hijauan.</p>
    </div>
    @endforelse
</div>

{{ $arsipHijauan->links('vendor.pagination.custom') }}
