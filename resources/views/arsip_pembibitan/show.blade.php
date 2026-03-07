@extends('layouts.app')

@section('title', 'Detail Arsip Pembibitan - E-Arsip')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="mb-4">
        <a href="{{ route('arsip-pembibitan.index') }}" class="text-decoration-none text-muted small d-flex align-items-center gap-2 mb-2">
            <i data-lucide="arrow-left" style="width: 14px;"></i> Kembali ke Daftar
        </a>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Detail Arsip Pembibitan</h1>
                <p class="text-muted small mb-0">Kode: {{ $arsipPembibitan->kode }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('arsip-pembibitan.edit', $arsipPembibitan->id) }}" class="btn btn-warning d-flex align-items-center gap-2" style="border-radius: 8px; color: white;">
                    <i data-lucide="edit-3" style="width: 18px;"></i> Edit Data
                </a>
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Informasi Ternak</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Kode Arsip</label>
                            <p class="fw-bold mb-0 text-primary">{{ $arsipPembibitan->kode }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Jenis Ternak</label>
                            <p class="fw-semibold mb-0">{{ $arsipPembibitan->jenis_ternak }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Jumlah</label>
                            <p class="fw-medium mb-0">{{ $arsipPembibitan->jumlah }} Ekor</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Umur</label>
                            <p class="fw-medium mb-0">{{ $arsipPembibitan->umur }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small mb-1">Tujuan Distribusi</label>
                            <p class="mb-0">{{ $arsipPembibitan->tujuan }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Tanggal Pendataan</label>
                            <p class="mb-0">
                                <i data-lucide="calendar" class="w-4 h-4 inline-block mr-1 text-muted"></i>
                                {{ $arsipPembibitan->tanggal->format('d F Y') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-1">Status Distribusi</label>
                            <div>
                                @php
                                    $statusClass = $arsipPembibitan->status == 'Terdistribusi' ? 'bg-terkirim' : 'bg-pending';
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $arsipPembibitan->status }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Sidebar Card: Metadata -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Metadata</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="text-muted small mb-1">Dibuat Oleh</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-sm bg-light text-primary d-flex align-items-center justify-content-center rounded-circle fw-bold" style="width: 32px; height: 32px; font-size: 12px;">
                                {{ strtoupper(substr($arsipPembibitan->creator->nama ?? 'A', 0, 1)) }}
                            </div>
                            <p class="mb-0 fw-medium">{{ $arsipPembibitan->creator->nama ?? 'Admin' }}</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="text-muted small mb-1">Waktu Input</label>
                        <p class="mb-0 text-muted small">{{ $arsipPembibitan->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-muted small mb-1">Terakhir Diperbarui</label>
                        <p class="mb-0 text-muted small">{{ $arsipPembibitan->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px; background: #fff1f2;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-danger">Zona Bahaya</h6>
                    <form id="delete-form-{{ $arsipPembibitan->id }}" action="{{ route('arsip-pembibitan.destroy', $arsipPembibitan->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger w-100 py-2 fw-bold d-flex align-items-center justify-content-center gap-2 btn-delete-confirm" data-form="delete-form-{{ $arsipPembibitan->id }}" style="border-radius: 12px;">
                            <i data-lucide="trash-2" style="width: 18px;"></i> Hapus Data Permanen
                        </button>
                    </form>
                </div>
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
                text: "Data pembibitan ini akan dihapus secara permanen dari server!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
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
