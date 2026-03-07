@extends('layouts.app')

@section('title', 'Detail Surat Keluar - E-Arsip')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="mb-4">
        <a href="{{ route('surat-keluar.index') }}" class="text-decoration-none text-muted small d-flex align-items-center gap-2 mb-2">
            <i data-lucide="arrow-left" style="width: 14px;"></i> Kembali ke Daftar
        </a>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Detail Surat Keluar</h1>
                <p class="text-muted small mb-0">{{ $suratKeluar->nomor_surat }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('surat-keluar.edit', $suratKeluar->id) }}" class="btn btn-warning d-flex align-items-center gap-2" style="border-radius: 8px; color: white;">
                    <i data-lucide="edit-3" style="width: 18px;"></i> Edit
                </a>
                @if($suratKeluar->file_path)
                <a href="{{ asset('storage/' . $suratKeluar->file_path) }}" class="btn btn-success d-flex align-items-center gap-2" style="border-radius: 8px;" target="_blank">
                    <i data-lucide="download" style="width: 18px;"></i> Download PDF
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
                            <p class="fw-semibold mb-0">{{ $suratKeluar->perihal }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Tujuan</label>
                            <p class="fw-medium mb-0">{{ $suratKeluar->tujuan }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Nomor Surat</label>
                            <p class="fw-medium mb-0">{{ $suratKeluar->nomor_surat }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">Tanggal Surat</label>
                            <p class="mb-0">{{ $suratKeluar->tanggal_surat->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">Tanggal Kirim</label>
                            <p class="mb-0">{{ $suratKeluar->tanggal_kirim ? $suratKeluar->tanggal_kirim->format('d F Y') : '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small mb-1">Prioritas</label>
                            <p class="mb-0">
                                @php
                                    $prioClass = match($suratKeluar->prioritas) {
                                        'Tinggi' => 'bg-danger',
                                        'Sedang' => 'bg-warning text-dark',
                                        'Rendah' => 'bg-info',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $prioClass }}">
                                    {{ $suratKeluar->prioritas }}
                                </span>
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small mb-1">Keterangan</label>
                            <p class="mb-0 text-muted">{{ $suratKeluar->keterangan ?? 'Tidak ada keterangan.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($suratKeluar->file_path)
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Pratinjau Dokumen</h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $extension = pathinfo($suratKeluar->file_path, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                        <img src="{{ asset('storage/' . $suratKeluar->file_path) }}" class="img-fluid rounded" alt="Scan Surat">
                    @elseif(strtolower($extension) == 'pdf')
                        <iframe src="{{ asset('storage/' . $suratKeluar->file_path) }}" width="100%" height="600px" style="border: none; border-radius: 8px;"></iframe>
                    @else
                        <div class="alert alert-info">File tidak dapat dipratinjau. Silakan unduh file untuk melihat kontennya.</div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Sidebar Card: Status & Meta -->
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
                                if($suratKeluar->status == 'Terkirim') $statusClass = 'bg-terkirim';
                                if($suratKeluar->status == 'Selesai') $statusClass = 'bg-terarsip';
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $suratKeluar->status }}</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="text-muted small mb-1">Dibuat Oleh</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-sm bg-light text-primary d-flex align-items-center justify-content-center rounded-circle fw-bold" style="width: 32px; height: 32px; font-size: 12px;">
                                {{ strtoupper(substr($suratKeluar->creator->nama ?? 'A', 0, 1)) }}
                            </div>
                            <p class="mb-0 fw-medium">{{ $suratKeluar->creator->nama ?? 'System' }}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="text-muted small mb-1">Waktu Input</label>
                        <p class="mb-0 text-muted small">{{ $suratKeluar->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-muted small mb-1">Terakhir Diperbarui</label>
                        <p class="mb-0 text-muted small">{{ $suratKeluar->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            @if($suratKeluar->status == 'Draft')
            <div class="card border-0 shadow-sm" style="border-radius: 16px; background: #f8fafc;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Tindakan Cepat</h6>
                    <form action="{{ route('surat-keluar.send', $suratKeluar->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px;">
                            <i data-lucide="send" style="width: 18px;"></i> Kirim Surat Sekarang
                        </button>
                    </form>
                    <p class="text-muted x-small mt-2 mb-0 text-center">Mengubah status surat menjadi "Terkirim" dan mencatat tanggal kirim.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
