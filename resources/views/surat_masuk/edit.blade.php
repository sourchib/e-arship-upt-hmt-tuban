@extends('layouts.app')

@section('title', 'Edit Surat Masuk - E-Arsip')

@section('content')
    <div class="container-fluid d-flex justify-content-center py-4">
        <div class="card border-0 shadow-sm" style="border-radius: 24px; max-width: 800px; width: 100%;">
            <div class="card-body p-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Edit Surat Masuk</h2>
                        <p class="text-muted small">Perbarui informasi surat masuk #{{ $suratMasuk->nomor_surat }}</p>
                    </div>
                    <a href="{{ route('surat-masuk.index') }}" class="btn-close-custom p-2">
                        <i data-lucide="x"></i>
                    </a>
                </div>

                <form action="{{ route('surat-masuk.update', $suratMasuk->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="nomor_surat" class="form-label fw-bold text-slate-700">Nomor Surat <span
                                class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('nomor_surat') is-invalid @enderror"
                            id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $suratMasuk->nomor_surat) }}"
                            required>
                        @error('nomor_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="perihal" class="form-label fw-bold text-slate-700">Perihal <span
                                class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('perihal') is-invalid @enderror"
                            id="perihal" name="perihal" value="{{ old('perihal', $suratMasuk->perihal) }}" required>
                        @error('perihal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="pengirim" class="form-label fw-bold text-slate-700">Pengirim <span
                                    class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('pengirim') is-invalid @enderror"
                                id="pengirim" name="pengirim" value="{{ old('pengirim', $suratMasuk->pengirim) }}" required>
                            @error('pengirim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="penerima" class="form-label fw-bold text-slate-700">Penerima</label>
                            <input type="text"
                                class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('penerima') is-invalid @enderror"
                                id="penerima" name="penerima" value="{{ old('penerima', $suratMasuk->penerima) }}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="tanggal_surat" class="form-label fw-bold text-slate-700">Tgl Surat <span
                                    class="text-danger">*</span></label>
                            <input type="date"
                                class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('tanggal_surat') is-invalid @enderror"
                                id="tanggal_surat" name="tanggal_surat"
                                value="{{ old('tanggal_surat', $suratMasuk->tanggal_surat->format('Y-m-d')) }}" required>
                            @error('tanggal_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_terima" class="form-label fw-bold text-slate-700">Tgl Terima <span
                                    class="text-danger">*</span></label>
                            <input type="date"
                                class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('tanggal_terima') is-invalid @enderror"
                                id="tanggal_terima" name="tanggal_terima"
                                value="{{ old('tanggal_terima', $suratMasuk->tanggal_terima->format('Y-m-d')) }}" required>
                            @error('tanggal_terima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="prioritas" class="form-label fw-bold text-slate-700">Prioritas <span
                                    class="text-danger">*</span></label>
                            <select
                                class="form-select rounded-xl py-3 px-4 bg-slate-50 border-slate-100"
                                id="prioritas" name="prioritas" required>
                                <option value="Sedang" {{ old('prioritas', $suratMasuk->prioritas) == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Tinggi" {{ old('prioritas', $suratMasuk->prioritas) == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                                <option value="Rendah" {{ old('prioritas', $suratMasuk->prioritas) == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="kategori" class="form-label fw-bold text-slate-700">Kategori <span
                                    class="text-danger">*</span></label>
                            <select
                                class="form-select rounded-xl py-3 px-4 bg-slate-50 border-slate-100"
                                id="kategori" name="kategori" required>
                                <option value="Permohonan" {{ old('kategori', $suratMasuk->kategori) == 'Permohonan' ? 'selected' : '' }}>Permohonan</option>
                                <option value="Undangan" {{ old('kategori', $suratMasuk->kategori) == 'Undangan' ? 'selected' : '' }}>Undangan</option>
                                <option value="Laporan" {{ old('kategori', $suratMasuk->kategori) == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                                <option value="Lainnya" {{ old('kategori', $suratMasuk->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-bold text-slate-700">Status <span
                                    class="text-danger">*</span></label>
                            <select
                                class="form-select rounded-xl py-3 px-4 bg-slate-50 border-slate-100"
                                id="status" name="status" required>
                                <option value="Pending" {{ old('status', $suratMasuk->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Diproses" {{ old('status', $suratMasuk->status) == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Terarsip" {{ old('status', $suratMasuk->status) == 'Terarsip' ? 'selected' : '' }}>Terarsip</option>
                                <option value="Selesai" {{ old('status', $suratMasuk->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="disposisi" class="form-label fw-bold text-slate-700">Disposisi</label>
                        <textarea class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100" id="disposisi"
                            name="disposisi" rows="2" placeholder="Isi disposisi jika ada">{{ old('disposisi', $suratMasuk->disposisi) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="penerima_disposisi" class="form-label fw-bold text-slate-700">Penerima Disposisi</label>
                        <input type="text"
                            class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100"
                            id="penerima_disposisi" name="penerima_disposisi" value="{{ old('penerima_disposisi', $suratMasuk->penerima_disposisi) }}"
                            placeholder="Nama penerima disposisi">
                    </div>

                    <div class="mb-5">
                        <label for="file" class="form-label fw-bold text-slate-700">Ganti File (PDF) <span
                                class="text-muted small font-normal">(Kosongkan jika tidak ingin ganti)</span></label>
                        <input type="file"
                            class="form-control rounded-xl py-3 px-4 bg-slate-50 border-slate-100 @error('file') is-invalid @enderror"
                            id="file" name="file">
                        @if($suratMasuk->file_path)
                        <div class="mt-2 text-xs text-slate-400">
                            <i data-lucide="file-text" class="w-3 h-3 inline-block mr-1"></i> File saat ini: <a
                                href="{{ asset('storage/' . $suratMasuk->file_path) }}" target="_blank"
                                class="text-primary hover:underline">Lihat Dokumen</a>
                        </div>
                        @endif
                        @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3 pt-2">
                        <button type="submit" class="btn btn-save-modal py-3 fw-bold flex-grow-1"
                            style="background-color: #00C853; border-radius: 16px;">
                            Perbarui Data
                        </button>
                        <a href="{{ route('surat-masuk.index') }}"
                            class="btn btn-cancel-modal py-3 fw-bold flex-grow-1 d-flex align-items-center justify-content-center"
                            style="border-radius: 16px;">
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

        .form-control:focus,
        .form-select:focus {
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
