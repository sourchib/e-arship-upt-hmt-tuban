@extends('layouts.app')

@section('title', 'Edit Arsip Hijauan - E-Arsip')

@section('content')
<div class="container-fluid d-flex justify-content-center py-4">
    <div class="card border-0 shadow-sm" style="border-radius: 24px; max-width: 800px; width: 100%;">
        <div class="card-body p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Edit Data Hijauan</h2>
                    <p class="text-muted small">Perbarui data produksi hijauan #{{ $arsipHijauan->kode_lahan }}</p>
                </div>
                <a href="{{ route('arsip-hijauan.index') }}" class="btn-close-custom p-2">
                    <i data-lucide="x"></i>
                </a>
            </div>

            <form action="{{ route('arsip-hijauan.update', $arsipHijauan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="kode_lahan" class="form-label fw-bold text-slate-700">Kode Lahan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('kode_lahan') is-invalid @enderror" id="kode_lahan" name="kode_lahan" value="{{ old('kode_lahan', $arsipHijauan->kode_lahan) }}" required>
                        @error('kode_lahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_hijauan" class="form-label fw-bold text-slate-700">Jenis Hijauan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('jenis_hijauan') is-invalid @enderror" id="jenis_hijauan" name="jenis_hijauan" value="{{ old('jenis_hijauan', $arsipHijauan->jenis_hijauan) }}" required>
                        @error('jenis_hijauan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="luas" class="form-label fw-bold text-slate-700">Luas (Ha) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('luas') is-invalid @enderror" id="luas" name="luas" value="{{ old('luas', $arsipHijauan->luas) }}" required min="0">
                        @error('luas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="produksi" class="form-label fw-bold text-slate-700">Produksi (Kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('produksi') is-invalid @enderror" id="produksi" name="produksi" value="{{ old('produksi', $arsipHijauan->produksi) }}" required min="0">
                        @error('produksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="lokasi" class="form-label fw-bold text-slate-700">Lokasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi', $arsipHijauan->lokasi) }}" required>
                    @error('lokasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-5">
                    <div class="col-md-6">
                        <label for="tanggal_panen" class="form-label fw-bold text-slate-700">Tanggal Panen <span class="text-danger">*</span></label>
                        <input type="date" class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('tanggal_panen') is-invalid @enderror" id="tanggal_panen" name="tanggal_panen" value="{{ old('tanggal_panen', $arsipHijauan->tanggal_panen->format('Y-m-d')) }}" required>
                        @error('tanggal_panen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold text-slate-700">Status <span class="text-danger">*</span></label>
                        <select class="form-select rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="Tersedia" {{ old('status', $arsipHijauan->status) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Terdistribusi" {{ old('status', $arsipHijauan->status) == 'Terdistribusi' ? 'selected' : '' }}>Terdistribusi</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-3 pt-2">
                    <button type="submit" class="btn btn-save-modal py-3 fw-bold flex-grow-1" style="background-color: #00C853; border-radius: 16px;">
                        Perbarui Data
                    </button>
                    <a href="{{ route('arsip-hijauan.index') }}" class="btn btn-cancel-modal py-3 fw-bold flex-grow-1 d-flex align-items-center justify-center" style="border-radius: 16px;">
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
