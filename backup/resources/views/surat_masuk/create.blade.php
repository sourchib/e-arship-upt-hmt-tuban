@extends('layouts.app')

@section('title', 'Tambah Surat Masuk - E-Arsip')

@section('content')
<div class="container-fluid d-flex justify-content-center py-4">
    <!-- Center the form like a modal -->
    <div class="card border-0 shadow" style="border-radius: 20px; max-width: 600px; width: 100%;">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0">Tambah Surat Masuk</h4>
                <p class="text-muted small mb-0">Masukkan detail surat masuk baru</p>
            </div>
            <a href="{{ route('surat-masuk.index') }}" class="text-muted">
                <i data-lucide="x" style="width: 24px;"></i>
            </a>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="nomor_surat" class="form-label fw-medium">Nomor Surat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nomor_surat') is-invalid @enderror" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat') }}" placeholder="Contoh: 001/SM/UPT-PTHMT/II/2026" required style="border-radius: 12px; padding: 12px; border: 2px solid #2ecc71;">
                    @error('nomor_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="perihal" class="form-label fw-medium">Perihal <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('perihal') is-invalid @enderror" id="perihal" name="perihal" value="{{ old('perihal') }}" placeholder="Masukkan perihal surat" required style="border-radius: 12px; padding: 12px;">
                    @error('perihal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="pengirim" class="form-label fw-medium">Pengirim <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('pengirim') is-invalid @enderror" id="pengirim" name="pengirim" value="{{ old('pengirim') }}" placeholder="Nama pengirim" required style="border-radius: 12px; padding: 12px;">
                        @error('pengirim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_surat" class="form-label fw-medium">Tanggal Surat <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required style="border-radius: 12px; padding: 12px;">
                        @error('tanggal_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="kategori" class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select" id="kategori" name="kategori" style="border-radius: 12px; padding: 12px;">
                        <option value="Permohonan">Permohonan</option>
                        <option value="Undangan">Undangan</option>
                        <option value="Laporan">Laporan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="file" class="form-label fw-medium">Upload File (PDF) <span class="text-danger">*</span></label>
                    <div class="border rounded-3 p-2 d-flex align-items-center" style="border-radius: 12px !important;">
                        <input type="file" class="form-control border-0 @error('file') is-invalid @enderror" id="file" name="file" required>
                    </div>
                    @error('file')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Hidden/Default fields for backend compatibility -->
                <input type="hidden" name="tanggal_terima" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="prioritas" value="Sedang">
                <input type="hidden" name="status" value="Pending">

                <div class="d-flex gap-3">
                    <button type="submit" class="btn flex-grow-1 fw-bold py-3" style="background-color: #00C853; color: white; border-radius: 12px; border: none;">
                        Simpan
                    </button>
                    <a href="{{ route('surat-masuk.index') }}" class="btn flex-grow-1 fw-bold py-3 text-muted" style="background-color: white; border: 1px solid #dee2e6; border-radius: 12px;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .form-control:focus, .form-select:focus {
        border-color: #00C853;
        box-shadow: 0 0 0 0.25rem rgba(0, 200, 83, 0.1);
    }
    input::placeholder {
        color: #adb5bd !important;
        font-size: 0.9rem;
    }
    .card {
        margin-top: 2rem;
    }
</style>
@endpush
