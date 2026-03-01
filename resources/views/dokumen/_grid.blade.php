<div class="row g-4">
    @forelse($documents as $doc)
    <div class="col-md-6 col-lg-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all h-100 group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ str_contains($doc->mime_type, 'pdf') ? 'bg-red-50 text-red-500' : (str_contains($doc->mime_type, 'spreadsheet') || str_contains($doc->mime_type, 'excel') ? 'bg-green-50 text-green-500' : 'bg-blue-50 text-blue-500') }}">
                        @if(str_contains($doc->mime_type, 'pdf'))
                            <i data-lucide="file-type-2" class="w-6 h-6"></i>
                        @elseif(str_contains($doc->mime_type, 'spreadsheet') || str_contains($doc->mime_type, 'excel'))
                            <i data-lucide="file-spreadsheet" class="w-6 h-6"></i>
                        @else
                            <i data-lucide="file-text" class="w-6 h-6"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mb-0">
                            {{ str_contains($doc->mime_type, 'pdf') ? 'PDF' : (str_contains($doc->mime_type, 'spreadsheet') || str_contains($doc->mime_type, 'excel') ? 'Excel' : 'File') }}
                        </p>
                        <p class="text-[10px] text-slate-400 font-medium mb-0">{{ number_format($doc->ukuran / 1024, 1) }} KB</p>
                    </div>
                </div>
                @php
                    $catColor = match($doc->kategori) {
                        'Laporan' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'Dokumen Teknis' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                        'Data' => 'bg-cyan-50 text-cyan-600 border-cyan-100',
                        'Panduan' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'Surat' => 'bg-rose-50 text-rose-600 border-rose-100',
                        default => 'bg-slate-50 text-slate-600 border-slate-100'
                    };
                @endphp
                <span class="px-2 py-1 rounded-lg text-[10px] font-bold border {{ $catColor }}">
                    {{ $doc->kategori }}
                </span>
            </div>

            <h5 class="fw-bold text-slate-800 text-sm mb-3 group-hover:text-green-600 transition-colors line-clamp-1" title="{{ $doc->nama }}">
                {{ $doc->nama }}
            </h5>

            <div class="space-y-1 mb-4">
                <div class="flex items-center gap-2 text-[11px] text-slate-400">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    <span>Diupload: {{ $doc->tanggal_upload->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center gap-2 text-[11px] text-slate-400">
                    <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    <span>Oleh: {{ $doc->uploader->nama ?? 'Admin' }}</span>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-50 flex items-center justify-between">
                <div class="flex gap-1">
                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg flex items-center gap-1.5 text-xs font-semibold" title="Lihat">
                        <i data-lucide="eye" class="w-4 h-4"></i> Lihat
                    </a>
                    <a href="{{ route('dokumen.download', $doc->id) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg flex items-center gap-1.5 text-xs font-semibold" title="Download">
                        <i data-lucide="download" class="w-4 h-4"></i> Download
                    </a>
                </div>
                <form id="delete-form-{{ $doc->id }}" action="{{ route('dokumen.destroy', $doc->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg btn-delete-confirm" data-form="delete-form-{{ $doc->id }}" title="Hapus">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="bg-white p-20 rounded-2xl text-center border border-dashed border-slate-200">
            <i data-lucide="file-question" class="w-12 h-12 text-slate-200 mx-auto mb-3"></i>
            <p class="text-slate-400 text-sm italic">Belum ada dokumen yang sesuai.</p>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($documents->hasPages())
<div class="mt-8 flex justify-center">
    {{ $documents->links() }}
</div>
@endif
