@extends('layouts.app')

@section('title', 'Detail Arsip Pembibitan - E-Arsip')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('arsip-pembibitan.index') }}" class="text-slate-500 hover:text-slate-700 transition-colors flex items-center gap-2 mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar
        </a>
        <h1 class="h3 fw-bold mb-1">Detail Arsip Pembibitan</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-4">
                <div class="p-4 bg-slate-50/50 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Informasi Ternak</h5>
                    @php
                        $statusClass = $arsipPembibitan->status == 'Terdistribusi' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                        {{ $arsipPembibitan->status }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Kode</label>
                            <div class="text-slate-900 fw-bold border-bottom pb-2">{{ $arsipPembibitan->kode }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Jenis Ternak</label>
                            <div class="text-slate-800 fw-medium border-bottom pb-2">{{ $arsipPembibitan->jenis_ternak }}</div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Jumlah</label>
                            <div class="text-slate-800 fw-medium border-bottom pb-2">{{ $arsipPembibitan->jumlah }} Ekor</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Umur</label>
                            <div class="text-slate-800 fw-medium border-bottom pb-2">{{ $arsipPembibitan->umur }}</div>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Tujuan Distribusi</label>
                            <div class="text-slate-800 fw-medium">{{ $arsipPembibitan->tujuan }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Tanggal</label>
                            <div class="text-slate-800 fw-medium">
                                <i data-lucide="calendar" class="w-4 h-4 inline-block mr-1 text-slate-400"></i>
                                {{ $arsipPembibitan->tanggal->format('d F Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-4">
                <h6 class="fw-bold mb-3 text-slate-700">Metadata</h6>
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Dibuat Oleh</label>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                {{ substr($arsipPembibitan->creator->nama ?? 'A', 0, 1) }}
                            </div>
                            <div class="text-sm font-medium text-slate-700">{{ $arsipPembibitan->creator->nama ?? 'Admin' }}</div>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Waktu Input</label>
                        <div class="text-sm text-slate-600">{{ $arsipPembibitan->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-2">
                <a href="{{ route('arsip-pembibitan.edit', $arsipPembibitan->id) }}" class="btn btn-warning text-white w-full rounded-xl py-3 fw-bold shadow-sm d-flex align-items-center justify-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5"></i> Edit Data
                </a>
                <form id="delete-form-{{ $arsipPembibitan->id }}" action="{{ route('arsip-pembibitan.destroy', $arsipPembibitan->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-rose-500 text-white w-full rounded-xl py-3 fw-bold shadow-sm d-flex align-items-center justify-center gap-2 btn-delete-confirm" data-form="delete-form-{{ $arsipPembibitan->id }}" style="background-color: #ef4444;">
                        <i data-lucide="trash-2" class="w-5 h-5"></i> Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-delete-confirm').forEach(button => {
        button.addEventListener('click', function() {
            const formId = this.getAttribute('data-form');
            Swal.fire({
                title: 'Hapus Data?',
                text: "Data pembibitan ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        });
    });
</script>
@endpush
