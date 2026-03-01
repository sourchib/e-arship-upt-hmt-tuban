@extends('layouts.app')

@section('title', 'Edit Arsip Pembibitan - E-Arsip')

@section('content')
<div class="container-fluid d-flex justify-content-center py-4">
    <div class="card border-0 shadow-sm" style="border-radius: 24px; max-width: 800px; width: 100%;">
        <div class="card-body p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Edit Data Pembibitan</h2>
                    <p class="text-muted small">Perbarui data pembibitan ternak #{{ $arsipPembibitan->kode }}</p>
                </div>
                <a href="{{ route('arsip-pembibitan.index') }}" class="btn-close-custom p-2">
                    <i data-lucide="x"></i>
                </a>
            </div>

            <form action="{{ route('arsip-pembibitan.update', $arsipPembibitan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="kode" class="form-label fw-bold text-slate-700">Kode <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('kode') is-invalid @enderror" id="kode" name="kode" value="{{ old('kode', $arsipPembibitan->kode) }}" required>
                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_ternak" class="form-label fw-bold text-slate-700">Jenis Ternak <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('jenis_ternak') is-invalid @enderror" id="jenis_ternak" name="jenis_ternak" value="{{ old('jenis_ternak', $arsipPembibitan->jenis_ternak) }}" required>
                        @error('jenis_ternak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="jumlah" class="form-label fw-bold text-slate-700">Jumlah (Ekor) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah', $arsipPembibitan->jumlah) }}" required min="1">
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="umur" class="form-label fw-bold text-slate-700">Umur <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('umur') is-invalid @enderror" id="umur" name="umur" value="{{ old('umur', $arsipPembibitan->umur) }}" required>
                        @error('umur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="tujuan" class="form-label fw-bold text-slate-700">Tujuan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('tujuan') is-invalid @enderror" id="tujuan" name="tujuan" value="{{ old('tujuan', $arsipPembibitan->tujuan) }}" required>
                    @error('tujuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label fw-bold text-slate-700">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $arsipPembibitan->tanggal->format('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold text-slate-700">Status <span class="text-danger">*</span></label>
                        <select class="form-select rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="Proses" {{ old('status', $arsipPembibitan->status) == 'Proses' ? 'selected' : '' }}>Proses</option>
                            <option value="Terdistribusi" {{ old('status', $arsipPembibitan->status) == 'Terdistribusi' ? 'selected' : '' }}>Terdistribusi</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-3 pt-2">
                    <button type="submit" class="btn btn-save-modal py-3 fw-bold flex-grow-1" style="background-color: #00C853; border-radius: 16px;">
                        Perbarui Data
                    </button>
                    <a href="{{ route('arsip-pembibitan.index') }}" class="btn btn-cancel-modal py-3 fw-bold flex-grow-1 d-flex align-items-center justify-center" style="border-radius: 16px;">
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
    .btn-close-custom { background: none; border: none; color: #94a3b8; cursor: pointer; transition: color 0.2s; }
    .btn-close-custom:hover { color: #ef4444; }
    .form-control:focus, .form-select:focus {
        border-color: #00C853 !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 200, 83, 0.1) !important;
    }
    .btn-save-modal { color: white !important; border: none; transition: transform 0.2s, background-color 0.2s; }
    .btn-save-modal:hover { background-color: #00b34a !important; transform: translateY(-1px); }
    .btn-cancel-modal { background-color: #f8fafc !important; color: #64748b !important; border: 1px solid #e2e8f0 !important; }
</style>
@endpush
