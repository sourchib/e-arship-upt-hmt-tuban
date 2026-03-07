<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Keterangan</th>
                <th>Jumlah Dokumen</th>
                <th style="text-align:right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $item->nama }}</strong></td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>
                    <span class="status-badge bg-terarsip">{{ \App\Models\Dokumen::where('kategori', $item->nama)->count() }} Dokumen</span>
                </td>
                <td style="text-align:right;">
                    <div style="gap:4px; display:flex; justify-content:flex-end;">
                        <button type="button" class="action-btn action-btn-edit btn-edit" 
                            data-url="{{ route('kategori-dokumen.update', $item->id) }}" 
                            data-nama="{{ $item->nama }}" 
                            data-keterangan="{{ $item->keterangan }}">
                            <i data-lucide="edit-2"></i>
                        </button>
                        <button type="button" class="action-btn action-btn-delete btn-delete-confirm" 
                            data-url="{{ route('kategori-dokumen.destroy', $item->id) }}">
                            <i data-lucide="trash-2"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:40px 0;color:#94a3b8;">
                    Belum ada kategori dokumen.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
