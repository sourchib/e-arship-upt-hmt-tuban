<div class="desktop-table" style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
        <thead>
            <tr style="background: #f8fafc;">
                <th style="padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; width: 50px;">No</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Kode</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Nama Dokumen</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Kategori</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Lokasi</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Retensi</th>
                <th style="padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Tgl Dokumen</th>
                <th style="padding: 14px 16px; text-align: right; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Aksi</th>
            </tr>
        </thead>
        <tbody style="font-size: 13px;">
            @forelse($docsTerbaru as $index => $item)
            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                <td style="padding: 16px 16px; color: #94a3b8; font-weight: 600;">{{ $docsTerbaru->firstItem() + $index }}</td>
                <td style="padding: 16px 16px;">
                    <span style="font-family: monospace; font-weight: 700; color: #475569; background: #f1f5f9; padding: 2px 6px; border-radius: 4px;">
                        {{ $item->kode ?? '-' }}
                    </span>
                </td>
                <td style="padding: 16px 16px;">
                    <div style="font-weight: 700; color: #1e293b;">{{ $item->nama }}</div>
                </td>
                <td style="padding: 16px 16px;">
                    <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7;">
                        {{ $item->kategori }}
                    </span>
                </td>
                <td style="padding: 16px 16px;">
                    <span style="padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; 
                        background: {{ $item->status == 'Aktif' ? '#dcfce7' : '#fee2e2' }}; 
                        color: {{ $item->status == 'Aktif' ? '#16a34a' : '#ef4444' }};">
                        {{ $item->status }}
                    </span>
                </td>
                <td style="padding: 16px 16px;">
                    <div style="display: flex; align-items: center; gap: 6px; color: #1e293b; font-weight: 600;">
                        <i data-lucide="archive" style="width: 14px; height: 14px; color: #16a34a;"></i>
                        {{ $item->lokasi ?? '-' }}
                    </div>
                </td>
                <td style="padding: 16px 16px; font-weight: 600; color: #475569;">
                    {{ $item->masa_retensi ?? '-' }}
                </td>
                <td style="padding: 16px 16px; color: #64748b;">
                    {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : \Carbon\Carbon::parse($item->tanggal_upload)->format('d/m/Y') }}
                </td>
                <td style="padding: 16px 16px; text-align: right;">
                    <div style="display: flex; justify-content: flex-end; gap: 6px;">
                        <button onclick="previewFile('{{ route('dokumen.preview', $item->id) }}', '{{ addslashes($item->nama) }}', '{{ route('dokumen.download', $item->id) }}', '{{ $item->mime_type }}')" style="width: 30px; height: 30px; background: #f5f3ff; color: #6366f1; border: none; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer;" title="Lihat">
                            <i data-lucide="eye" style="width:14px;height:14px;"></i>
                        </button>
                        <a href="{{ route('dokumen.download', $item->id) }}" style="width: 30px; height: 30px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; border-radius: 8px;" title="Download">
                            <i data-lucide="download" style="width:14px;height:14px;"></i>
                        </a>
                        @if(Auth::check() && Auth::user()->role === 'Admin')
                        <a href="{{ route('dokumen.index', ['edit' => $item->id]) }}" style="width: 30px; height: 30px; background: #fff7ed; color: #ea580c; display: flex; align-items: center; justify-content: center; border-radius: 8px;" title="Edit">
                            <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                        </a>
                        <form action="{{ route('dokumen.destroy', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus dokumen ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="width: 30px; height: 30px; background: #fef2f2; border: none; color: #ef4444; display: flex; align-items: center; justify-content: center; border-radius: 8px; cursor: pointer;" title="Hapus">
                                <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div style="text-align: center; padding: 40px; color: #94a3b8;">
                        <i data-lucide="file-x" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.5;"></i>
                        <p>Tidak ada data dokumen ditemukan.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
