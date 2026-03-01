@extends('layouts.app')

@section('title', 'Detail Surat Keluar - E-Arsip')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <a href="{{ route('surat-keluar.index') }}" class="text-slate-500 hover:text-slate-700 transition-colors flex items-center gap-2 mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar
        </a>
        <h1 class="h3 fw-bold mb-1">Detail Surat Keluar</h1>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-4">
                <div class="p-4 bg-slate-50/50 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Informasi Surat</h5>
                    @php
                        $statusClass = 'bg-slate-100 text-slate-600';
                        if($suratKeluar->status == 'Terkirim') $statusClass = 'bg-green-100 text-green-700';
                        if($suratKeluar->status == 'Selesai') $statusClass = 'bg-blue-100 text-blue-700';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                        {{ $suratKeluar->status }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Nomor Surat</label>
                            <div class="text-slate-800 fw-medium">{{ $suratKeluar->nomor_surat }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Perihal</label>
                            <div class="text-slate-800 fw-medium">{{ $suratKeluar->perihal }}</div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Tujuan</label>
                            <div class="text-slate-800 fw-medium">{{ $suratKeluar->tujuan }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Prioritas</label>
                            <div>
                                @php
                                    $prioClass = match($suratKeluar->prioritas) {
                                        'Tinggi' => 'text-rose-600 bg-rose-50',
                                        'Sedang' => 'text-amber-600 bg-amber-50',
                                        'Rendah' => 'text-emerald-600 bg-emerald-50',
                                        default => 'text-slate-600 bg-slate-50'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $prioClass }}">
                                    {{ $suratKeluar->prioritas }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Tanggal Surat</label>
                            <div class="text-slate-800 fw-medium">
                                <i data-lucide="calendar" class="w-4 h-4 inline-block mr-1 text-slate-400"></i>
                                {{ $suratKeluar->tanggal_surat->format('d F Y') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Tanggal Kirim</label>
                            <div class="text-slate-800 fw-medium">
                                <i data-lucide="send" class="w-4 h-4 inline-block mr-1 text-slate-400"></i>
                                {{ $suratKeluar->tanggal_kirim ? $suratKeluar->tanggal_kirim->format('d F Y') : '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="text-xs text-slate-400 font-bold uppercase tracking-tight mb-1">Keterangan</label>
                        <div class="text-slate-600">{{ $suratKeluar->keterangan ?: 'Tidak ada keterangan.' }}</div>
                    </div>
                </div>
            </div>

            <!-- File Preview (Simplified for placeholder) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-4 bg-slate-50/50 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Lampiran File</h5>
                    <a href="{{ asset('storage/' . $suratKeluar->file_path) }}" target="_blank" class="btn btn-sm btn-outline-emerald d-flex align-items-center gap-2" style="border-radius: 8px;">
                        <i data-lucide="download" class="w-4 h-4"></i> Download PDF
                    </a>
                </div>
                <div class="p-0" style="height: 500px;">
                    <iframe src="{{ asset('storage/' . $suratKeluar->file_path) }}" class="w-full h-full border-0"></iframe>
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
                                {{ substr($suratKeluar->creator->nama ?? 'A', 0, 1) }}
                            </div>
                            <div class="text-sm font-medium text-slate-700">{{ $suratKeluar->creator->nama ?? 'Admin' }}</div>
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Waktu Input</label>
                        <div class="text-sm text-slate-600">{{ $suratKeluar->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">Terakhir Diperbarui</label>
                        <div class="text-sm text-slate-600">{{ $suratKeluar->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-2">
                <a href="{{ route('surat-keluar.edit', $suratKeluar->id) }}" class="btn btn-warning text-white w-full rounded-xl py-3 fw-bold shadow-sm d-flex align-items-center justify-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5"></i> Edit Surat
                </a>
                @if($suratKeluar->status == 'Draft')
                <form action="{{ route('surat-keluar.send', $suratKeluar->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-full rounded-xl py-3 fw-bold shadow-sm d-flex align-items-center justify-center gap-2">
                        <i data-lucide="send" class="w-5 h-5"></i> Kirim Sekarang
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
