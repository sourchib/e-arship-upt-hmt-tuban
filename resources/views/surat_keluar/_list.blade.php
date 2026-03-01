<!-- Desktop Table View -->
<div class="hidden md:block bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 border-0">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="ps-4 py-4 text-slate-700 font-semibold text-sm border-0">No</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Nomor Surat</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Perihal</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Tujuan</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Tanggal</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Status</th>
                    <th class="pe-4 py-4 text-center text-slate-700 font-semibold text-sm border-0">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($suratKeluar as $index => $surat)
                <tr>
                    <td class="ps-4 text-slate-600 text-sm">{{ $suratKeluar->firstItem() + $index }}</td>
                    <td>
                        <div class="text-slate-700 font-medium text-sm">{{ $surat->nomor_surat }}</div>
                    </td>
                    <td>
                        <div class="text-slate-700 text-sm max-w-xs leading-relaxed">{{ $surat->perihal }}</div>
                    </td>
                    <td>
                        <div class="text-slate-500 text-sm">{{ $surat->tujuan }}</div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2 text-slate-500 text-sm">
                            <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                            {{ $surat->tanggal_surat->format('d/m/Y') }}
                        </div>
                    </td>
                    <td>
                       @php
                            $statusClass = 'bg-slate-100 text-slate-600'; // Draft
                            if($surat->status == 'Terkirim') $statusClass = 'bg-green-100 text-green-700';
                            if($surat->status == 'Selesai') $statusClass = 'bg-blue-100 text-blue-700';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                            {{ $surat->status }}
                        </span>
                    </td>
                    <td class="pe-4">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('surat-keluar.show', $surat->id) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="Detail">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                            @if($surat->status == 'Draft')
                            <form id="send-form-{{ $surat->id }}" action="{{ route('surat-keluar.send', $surat->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="button" class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors btn-send-confirm" data-form="send-form-{{ $surat->id }}" title="Kirim">
                                    <i data-lucide="send" class="w-5 h-5"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ $surat->file_path ? asset('storage/' . $surat->file_path) : '#' }}" class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors" title="Download" target="_blank">
                                <i data-lucide="download" class="w-5 h-5"></i>
                            </a>
                            <a href="{{ route('surat-keluar.edit', $surat->id) }}" class="p-2 text-orange-500 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                <i data-lucide="edit-3" class="w-5 h-5"></i>
                            </a>
                            <form id="delete-form-{{ $surat->id }}" action="{{ route('surat-keluar.destroy', $surat->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors btn-delete-confirm" data-form="delete-form-{{ $surat->id }}" title="Hapus">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-slate-400 text-sm italic">Belum ada surat keluar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Card View -->
<div class="md:hidden space-y-4">
    @forelse($suratKeluar as $index => $surat)
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-start mb-3">
            <span class="text-xs font-semibold text-slate-400">#{{ $suratKeluar->firstItem() + $index }}</span>
            @php
                $statusClass = 'bg-slate-100 text-slate-600';
                if($surat->status == 'Terkirim') $statusClass = 'bg-green-100 text-green-700';
                if($surat->status == 'Selesai') $statusClass = 'bg-blue-100 text-blue-700';
            @endphp
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                {{ $surat->status }}
            </span>
        </div>
        <div class="mb-3">
            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-1">Nomor Surat</div>
            <div class="text-sm font-semibold text-slate-800">{{ $surat->nomor_surat }}</div>
        </div>
        <div class="mb-3">
            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-1">Perihal</div>
            <div class="text-sm text-slate-700 leading-relaxed font-medium">{{ $surat->perihal }}</div>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-1">Tujuan</div>
                <div class="text-xs text-slate-600">{{ $surat->tujuan }}</div>
            </div>
            <div>
                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-1">Tanggal</div>
                <div class="flex items-center gap-1.5 text-xs text-slate-600">
                    <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i>
                    {{ $surat->tanggal_surat->format('d/m/Y') }}
                </div>
            </div>
        </div>
        <div class="pt-3 border-t border-slate-50 flex items-center justify-between">
            <div class="flex gap-1">
                <a href="{{ route('surat-keluar.show', $surat->id) }}" class="p-2 text-indigo-500 bg-indigo-50 rounded-lg">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </a>
                @if($surat->status == 'Draft')
                <form id="send-form-mobile-{{ $surat->id }}" action="{{ route('surat-keluar.send', $surat->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="button" class="p-2 text-green-500 bg-green-50 rounded-lg btn-send-confirm" data-form="send-form-mobile-{{ $surat->id }}">
                        <i data-lucide="send" class="w-4 h-4"></i>
                    </button>
                </form>
                @endif
                <a href="{{ $surat->file_path ? asset('storage/' . $surat->file_path) : '#' }}" class="p-2 text-emerald-500 bg-emerald-50 rounded-lg" target="_blank">
                    <i data-lucide="download" class="w-4 h-4"></i>
                </a>
                <a href="{{ route('surat-keluar.edit', $surat->id) }}" class="p-2 text-orange-500 bg-orange-50 rounded-lg">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                </a>
            </div>
            <button type="button" class="p-2 text-rose-500 bg-rose-50 rounded-lg btn-delete-confirm" data-form="delete-form-{{ $surat->id }}">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
    @empty
    <div class="bg-white p-12 rounded-2xl text-center text-slate-400 text-sm italic border border-dashed border-slate-200">
        Belum ada surat keluar.
    </div>
    @endforelse
</div>

<!-- Pagination Container -->
@if($suratKeluar->hasPages())
<div class="mt-6 flex justify-center">
    <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
        {{ $suratKeluar->links() }}
    </div>
</div>
@endif
