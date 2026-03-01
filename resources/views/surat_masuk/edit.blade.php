@extends('layouts.app')

@section('title', 'Edit Surat Masuk - E-Arsip')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="mb-4">
        <a href="{{ route('surat-masuk.index') }}" class="text-decoration-none text-muted small d-flex align-items-center gap-2 mb-2">
            <i data-lucide="arrow-left" style="width: 14px;"></i> Kembali ke Daftar
        </a>
        <h1 class="h3 fw-bold mb-1">Edit Surat Masuk</h1>
        <p class="text-muted small mb-0">Perbarui data surat masuk: {{ $suratMasuk->nomor_surat }}</p>
    </div>

    <!-- Form Container -->
    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-4">
            <form action="{{ route('surat-masuk.update', $suratMasuk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <!-- Nomor Surat -->
                    <div class="col-md-6">
                        <label for="nomor_surat" class="form-label fw-semibold">Nomor Surat</label>
                        <input type="text" class="form-control @error('nomor_surat') is-invalid @enderror" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $suratMasuk->nomor_surat) }}" required style="border-radius: 10px; padding: 12px;">
                        @error('nomor_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pengirim -->
                    <div class="col-md-6">
                        <label for="pengirim" class="form-label fw-semibold">Pengirim</label>
                        <input type="text" class="form-control @error('pengirim') is-invalid @enderror" id="pengirim" name="pengirim" value="{{ old('pengirim', $suratMasuk->pengirim) }}" required style="border-radius: 10px; padding: 12px;">
                        @error('pengirim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Perihal -->
                    <div class="col-12">
                        <label for="perihal" class="form-label fw-semibold">Perihal</label>
                        <input type="text" class="form-control @error('perihal') is-invalid @enderror" id="perihal" name="perihal" value="{{ old('perihal', $suratMasuk->perihal) }}" required style="border-radius: 10px; padding: 12px;">
                        @error('perihal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Surat -->
                    <div class="col-md-4">
                        <label for="tanggal_surat" class="form-label fw-semibold">Tanggal Surat</label>
                        <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', $suratMasuk->tanggal_surat->format('Y-m-d')) }}" required style="border-radius: 10px; padding: 12px;">
                        @error('tanggal_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Terima -->
                    <div class="col-md-4">
                        <label for="tanggal_terima" class="form-label fw-semibold">Tanggal Terima</label>
                        <input type="date" class="form-control @error('tanggal_terima') is-invalid @enderror" id="tanggal_terima" name="tanggal_terima" value="{{ old('tanggal_terima', $suratMasuk->tanggal_terima->format('Y-m-d')) }}" required style="border-radius: 10px; padding: 12px;">
                        @error('tanggal_terima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Prioritas -->
                    <div class="col-md-4">
                        <label for="prioritas" class="form-label fw-semibold">Prioritas</label>
                        <select class="form-select @error('prioritas') is-invalid @enderror" id="prioritas" name="prioritas" required style="border-radius: 10px; padding: 12px;">
                            <option value="Sedang" {{ old('prioritas', $suratMasuk->prioritas) == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Tinggi" {{ old('prioritas', $suratMasuk->prioritas) == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="Rendah" {{ old('prioritas', $suratMasuk->prioritas) == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                        </select>
                        @error('prioritas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required style="border-radius: 10px; padding: 12px;">
                            <option value="Pending" {{ old('status', $suratMasuk->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Diproses" {{ old('status', $suratMasuk->status) == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="Terarsip" {{ old('status', $suratMasuk->status) == 'Terarsip' ? 'selected' : '' }}>Terarsip</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div class="col-md-6">
                        <label for="file" class="form-label fw-semibold">Update Scan Surat (Biarkan kosong jika tidak diubah)</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" style="border-radius: 10px; padding: 10px;">
                        @if($suratMasuk->file_path)
                            <div class="form-text">File saat ini: <a href="{{ asset('storage/' . $suratMasuk->file_path) }}" target="_blank">Lihat Dokumen</a></div>
                        @endif
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="col-12">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan (Opsional)</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" style="border-radius: 10px; padding: 12px;">{{ old('keterangan', $suratMasuk->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-5 d-flex gap-3">
                    <button type="submit" class="btn px-4 py-2 fw-bold" style="background-color: var(--primary-green); color: white; border-radius: 10px;">
                        Perbarui Surat
                    </button>
                    <a href="{{ route('surat-masuk.index') }}" class="btn px-4 py-2 fw-bold" style="background-color: #f1f5f9; color: #64748b; border-radius: 10px;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
