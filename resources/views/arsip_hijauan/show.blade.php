@extends('layouts.app')

@section('title', 'Detail Arsip Hijauan - E-Arsip')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('arsip-hijauan.index') }}" class="text-slate-500 hover:text-slate-700 transition-colors flex items-center gap-2 mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar
        </a>
        <h1 class="h3 fw-bold mb-1">Detail Arsip Hijauan</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-4">
                <div class="p-4 bg-slate-50/50 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Informasi Produksi Hijauan</h5>
                    @php
                        $statusClass = $arsipHijauan->status == 'Tersedia' ? 'bg-green-100 text-green-700' : 'bg-indigo-100 text-indigo-700';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                        {{ $arsipHijauan->status }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Kode Lahan</label>
                            <div class="text-slate-900 fw-bold border-bottom pb-2">{{ $arsipHijauan->kode_lahan }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Jenis Hijauan</label>
                            <div class="text-slate-800 fw-medium border-bottom pb-2">{{ $arsipHijauan->jenis_hijauan }}</div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Luas Lahan</label>
                            <div class="text-slate-800 fw-medium border-bottom pb-2">{{ $arsipHijauan->luas }} Ha</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Produksi</label>
                            <div class="text-slate-800 fw-medium border-bottom pb-2">{{ number_format($arsipHijauan->produksi, 0, ',', '.') }} Kg</div>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Lokasi</label>
                            <div class="text-slate-800 fw-medium">
                                <i data-lucide="map-pin" class="w-4 h-4 inline-block mr-1 text-slate-400"></i>
                                {{ $arsipHijauan->lokasi }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Tanggal Panen</label>
                            <div class="text-slate-800 fw-medium">
                                <i data-lucide="calendar" class="w-4 h-4 inline-block mr-1 text-slate-400"></i>
                                {{ $arsipHijauan->tanggal_panen->format('d F Y') }}
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
                                {{ substr($arsipHijauan->creator->nama ?? 'A', 0, 1) }}
                            </div>
                            <div class="text-sm font-medium text-slate-700">{{ $arsipHijauan->creator->nama ?? 'Admin' }}</div>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Waktu Input</label>
                        <div class="text-sm text-slate-600">{{ $arsipHijauan->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-2">
                <a href="{{ route('arsip-hijauan.edit', $arsipHijauan->id) }}" class="btn btn-warning text-white w-full rounded-xl py-3 fw-bold shadow-sm d-flex align-items-center justify-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5"></i> Edit Data
                </a>
                <form id="delete-form-{{ $arsipHijauan->id }}" action="{{ route('arsip-hijauan.destroy', $arsipHijauan->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-rose-500 text-white w-full rounded-xl py-3 fw-bold shadow-sm d-flex align-items-center justify-center gap-2 btn-delete-confirm" data-form="delete-form-{{ $arsipHijauan->id }}" style="background-color: #ef4444;">
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
                text: "Data hijauan ini akan dihapus secara permanen!",
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
