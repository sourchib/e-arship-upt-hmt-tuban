@extends('layouts.app')

@section('title', 'Detail Surat Masuk - E-Arsip')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="mb-4">
        <a href="{{ route('surat-masuk.index') }}" class="text-decoration-none text-muted small d-flex align-items-center gap-2 mb-2">
            <i data-lucide="arrow-left" style="width: 14px;"></i> Kembali ke Daftar
        </a>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Detail Surat Masuk</h1>
                <p class="text-muted small mb-0">{{ $suratMasuk->nomor_surat }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('surat-masuk.edit', $suratMasuk->id) }}" class="btn btn-warning d-flex align-items-center gap-2" style="border-radius: 8px; color: white;">
                    <i data-lucide="edit-3" style="width: 18px;"></i> Edit
                </a>
                @if($suratMasuk->file_path)
                <a href="{{ asset('storage/' . $suratMasuk->file_path) }}" class="btn btn-success d-flex align-items-center gap-2" style="border-radius: 8px;" target="_blank">
                    <i data-lucide="download" style="width: 18px;"></i> Download
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Informasi Surat</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="text-muted small mb-1">Perihal</label>
                            <p class="fw-semibold mb-0">{{ $suratMasuk->perihal }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Pengirim</label>
                            <p class="fw-medium mb-0">{{ $suratMasuk->pengirim }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Nomor Surat</label>
                            <p class="fw-medium mb-0">{{ $suratMasuk->nomor_surat }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">Tanggal Surat</label>
                            <p class="mb-0">{{ $suratMasuk->tanggal_surat->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">Tanggal Terima</label>
                            <p class="mb-0">{{ $suratMasuk->tanggal_terima->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">Prioritas</label>
                            <p class="mb-0">
                                <span class="badge {{ $suratMasuk->prioritas == 'Tinggi' ? 'bg-danger' : ($suratMasuk->prioritas == 'Sedang' ? 'bg-primary' : 'bg-info') }}">
                                    {{ $suratMasuk->prioritas }}
                                </span>
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small mb-1">Keterangan</label>
                            <p class="mb-0 text-muted">{{ $suratMasuk->keterangan ?? 'Tidak ada keterangan.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($suratMasuk->file_path)
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Pratinjau Dokumen</h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $extension = pathinfo($suratMasuk->file_path, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                        <img src="{{ asset('storage/' . $suratMasuk->file_path) }}" class="img-fluid rounded" alt="Scan Surat">
                    @elseif(strtolower($extension) == 'pdf')
                        <iframe src="{{ asset('storage/' . $suratMasuk->file_path) }}" width="100%" height="600px" style="border: none; border-radius: 8px;"></iframe>
                    @else
                        <div class="alert alert-info">File tidak dapat dipratinjau. Silakan unduh file untuk melihat kontennya.</div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Status & Metadata</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="text-muted small mb-1">Status Saat Ini</label>
                        <div>
                            @php
                                $statusClass = 'bg-pending';
                                if($suratMasuk->status == 'Diproses') $statusClass = 'bg-diproses';
                                if($suratMasuk->status == 'Terarsip') $statusClass = 'bg-terarsip';
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $suratMasuk->status }}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="text-muted small mb-1">Dibuat Oleh</label>
                        <p class="mb-0 fw-medium">{{ $suratMasuk->creator->nama ?? 'System' }}</p>
                    </div>
                    <div>
                        <label class="text-muted small mb-1">Terakhir Diperbarui</label>
                        <p class="mb-0 text-muted small">{{ $suratMasuk->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
