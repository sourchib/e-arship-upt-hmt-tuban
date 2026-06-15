@extends('layouts.app')

@section('title', 'Edit Surat Keluar - E-Arsip')

@section('content')
<div class="container-fluid d-flex justify-content-center py-4">
    <div class="card border-0 shadow-sm" style="border-radius: 24px; max-width: 800px; width: 100%;">
        <div class="card-body p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Edit Surat Keluar</h2>
                    <p class="text-muted small">Perbarui informasi surat keluar #{{ $suratKeluar->nomor_surat }}</p>
                </div>
                <a href="{{ route('surat-keluar.index') }}" class="btn-close-custom p-2">
                    <i data-lucide="x"></i>
                </a>
            </div>

            <form action="{{ route('surat-keluar.update', $suratKeluar->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="nomor_surat" class="form-label fw-bold text-slate-700">Nomor Surat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('nomor_surat') is-invalid @enderror" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $suratKeluar->nomor_surat) }}" required>
                    @error('nomor_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="perihal" class="form-label fw-bold text-slate-700">Perihal <span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('perihal') is-invalid @enderror" id="perihal" name="perihal" value="{{ old('perihal', $suratKeluar->perihal) }}" required>
                    @error('perihal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tujuan" class="form-label fw-bold text-slate-700">Tujuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('tujuan') is-invalid @enderror" id="tujuan" name="tujuan" value="{{ old('tujuan', $suratKeluar->tujuan) }}" required>
                        @error('tujuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_surat" class="form-label fw-bold text-slate-700">Tanggal Surat <span class="text-danger">*</span></label>
                        <input type="date" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('tanggal_surat') is-invalid @enderror" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', $suratKeluar->tanggal_surat->format('Y-m-d')) }}" required>
                        @error('tanggal_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="prioritas" class="form-label fw-bold text-slate-700">Prioritas <span class="text-danger">*</span></label>
                        <select class="form-select rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('prioritas') is-invalid @enderror" id="prioritas" name="prioritas" required>
                            <option value="Tinggi" {{ old('prioritas', $suratKeluar->prioritas) == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="Sedang" {{ old('prioritas', $suratKeluar->prioritas) == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Rendah" {{ old('prioritas', $suratKeluar->prioritas) == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold text-slate-700">Status <span class="text-danger">*</span></label>
                        <select class="form-select rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="Draft" {{ old('status', $suratKeluar->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Terkirim" {{ old('status', $suratKeluar->status) == 'Terkirim' ? 'selected' : '' }}>Terkirim</option>
                            <option value="Selesai" {{ old('status', $suratKeluar->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="file" class="form-label fw-bold text-slate-700">Ganti File (PDF) <span class="text-muted small font-normal">(Kosongkan jika tidak ingin ganti)</span></label>
                    <input type="file" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('file') is-invalid @enderror" id="file" name="file">
                    <div class="mt-2 text-xs text-slate-400">
                        <i data-lucide="file-text" class="w-3 h-3 inline-block mr-1"></i> File saat ini: <a href="{{ asset('storage/' . $suratKeluar->file_path) }}" target="_blank" class="text-primary hover:underline">Lihat Dokumen</a>
                    </div>
                    @error('file')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="keterangan" class="form-label fw-bold text-slate-700">Keterangan</label>
                    <textarea class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100" id="keterangan" name="keterangan" rows="3" placeholder="Tambahkan catatan tambahan...">{{ old('keterangan', $suratKeluar->keterangan) }}</textarea>
                </div>

                <div class="d-flex gap-3 pt-2">
                    <button type="submit" class="btn btn-save-modal py-3 fw-bold flex-grow-1" style="background-color: #00C853; border-radius: 16px;">
                        Perbarui Data
                    </button>
                    <a href="{{ route('surat-keluar.index') }}" class="btn btn-cancel-modal py-3 fw-bold flex-grow-1 d-flex align-items-center justify-center" style="border-radius: 16px;">
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
    .btn-close-custom {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        transition: color 0.2s;
    }
    .btn-close-custom:hover {
        color: #ef4444;
    }
    .form-control:focus, .form-select:focus {
        border-color: #00C853 !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 200, 83, 0.1) !important;
    }
    .btn-save-modal {
        color: white !important;
        border: none;
        transition: transform 0.2s, background-color 0.2s;
    }
    .btn-save-modal:hover {
        background-color: #00b34a !important;
        transform: translateY(-1px);
    }
    .btn-cancel-modal {
        background-color: #f8fafc !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
    }
</style>
@endpush
