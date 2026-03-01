<!-- Desktop Table View -->
<div class="hidden md:block bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 border-0">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="ps-4 py-4 text-slate-700 font-semibold text-sm border-0">No</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Kode Lahan</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Jenis Hijauan</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Luas (Ha)</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Produksi (Kg)</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Tanggal Panen</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Lokasi</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm border-0">Status</th>
                    <th class="pe-4 py-4 text-center text-slate-700 font-semibold text-sm border-0">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($arsipHijauan as $index => $item)
                <tr>
                    <td class="ps-4 text-slate-600 text-sm">{{ $arsipHijauan->firstItem() + $index }}</td>
                    <td>
                        <div class="text-slate-900 font-bold text-sm">{{ $item->kode_lahan }}</div>
                    </td>
                    <td>
                        <div class="text-slate-700 text-sm">{{ $item->jenis_hijauan }}</div>
                    </td>
                    <td>
                        <div class="text-slate-700 text-sm">{{ $item->luas }} Ha</div>
                    </td>
                    <td>
                        <div class="text-slate-700 text-sm">{{ number_format($item->produksi, 0, ',', '.') }} Kg</div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2 text-slate-500 text-sm">
                            <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                            {{ $item->tanggal_panen->format('d/m/Y') }}
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2 text-slate-500 text-sm">
                            <i data-lucide="map-pin" class="w-4 h-4 text-slate-400"></i>
                            {{ Str::limit($item->lokasi, 20) }}
                        </div>
                    </td>
                    <td>
                        @php
                            $statusClass = $item->status == 'Tersedia' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold {{ $statusClass }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="pe-4">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('arsip-hijauan.show', $item->id) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors" title="Detail">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                            <a href="{{ route('arsip-hijauan.edit', $item->id) }}" class="p-2 text-orange-500 hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                <i data-lucide="edit-3" class="w-5 h-5"></i>
                            </a>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('arsip-hijauan.destroy', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg btn-delete-confirm" data-form="delete-form-{{ $item->id }}" title="Hapus">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-12 text-slate-400 text-sm italic">Belum ada data hijauan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Card View -->
<div class="md:hidden space-y-4">
    @forelse($arsipHijauan as $index => $item)
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex justify-between items-start mb-3">
            <span class="text-xs font-semibold text-slate-400">#{{ $arsipHijauan->firstItem() + $index }}</span>
            @php
                $statusClass = $item->status == 'Tersedia' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700';
            @endphp
            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                {{ $item->status }}
            </span>
        </div>
        <div class="mb-3">
            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-1">Kode Lahan</div>
            <div class="text-sm font-bold text-slate-800">{{ $item->kode_lahan }}</div>
        </div>
        <div class="mb-3">
            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-1">Jenis Hijauan</div>
            <div class="text-sm text-slate-700 font-medium">{{ $item->jenis_hijauan }}</div>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-1">Luas</div>
                <div class="text-xs text-slate-600 font-semibold">{{ $item->luas }} Ha</div>
            </div>
            <div>
                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-1">Produksi</div>
                <div class="text-xs text-slate-600">{{ number_format($item->produksi, 0, ',', '.') }} Kg</div>
            </div>
            <div class="col-span-2">
                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-1">Lokasi</div>
                <div class="text-xs text-slate-600 flex items-center gap-1.5">
                    <i data-lucide="map-pin" class="w-3 h-3 text-slate-400"></i>
                    {{ $item->lokasi }}
                </div>
            </div>
            <div>
                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mb-1">Panen</div>
                <div class="flex items-center gap-1.5 text-xs text-slate-600">
                    <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i>
                    {{ $item->tanggal_panen->format('d/m/Y') }}
                </div>
            </div>
        </div>
        <div class="pt-3 border-t border-slate-50 flex items-center justify-between">
            <div class="flex gap-1">
                <a href="{{ route('arsip-hijauan.show', $item->id) }}" class="p-2 text-indigo-500 bg-indigo-50 rounded-lg">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </a>
                <a href="{{ route('arsip-hijauan.edit', $item->id) }}" class="p-2 text-orange-500 bg-orange-50 rounded-lg">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                </a>
            </div>
            <button type="button" class="p-2 text-rose-500 bg-rose-50 rounded-lg btn-delete-confirm" data-form="delete-form-{{ $item->id }}">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
    @empty
    <div class="bg-white p-12 rounded-2xl text-center text-slate-400 text-sm italic border border-dashed border-slate-200">
        Belum ada data hijauan.
    </div>
    @endforelse
</div>

<!-- Pagination Container -->
@if($arsipHijauan->hasPages())
<div class="mt-6 flex justify-center">
    <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
        {{ $arsipHijauan->links() }}
    </div>
</div>
@endif
