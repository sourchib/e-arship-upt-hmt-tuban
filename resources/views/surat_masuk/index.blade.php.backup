@extends('layouts.app')

@section('title', 'Surat Masuk - E-Arsip')

@section('content')

    {{-- ====== Page Header ====== --}}
    <div class="page-header">
        <div class="page-header-left">
            <h1>Surat Masuk</h1>
            <p>Kelola surat masuk dan disposisi</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('surat-masuk.print') }}" target="_blank" class="btn btn-outline-secondary me-2">
                <i data-lucide="printer" style="width:16px;height:16px;"></i>
                Cetak Laporan
            </a>
            @if(Auth::check() && Auth::user()->role === 'Admin')
                <button type="button" class="btn btn-primary" id="openCreateModal">
                    <i data-lucide="plus" style="width:16px;height:16px;"></i>
                    Tambah Surat Masuk
                </button>
            @endif
        </div>
    </div>

    {{-- ====== Toolbar ====== --}}
    <div class="toolbar">
        <div class="toolbar-search">
            <i data-lucide="search" class="search-icon"></i>
            <input type="text" id="searchInput" placeholder="Cari nomor, perihal, pengirim, atau penerima...">
        </div>
        <div class="toolbar-actions-wrapper">
            <div style="position: relative; display: inline-block;">
                <select id="sortSelect" class="btn date-filter-btn" style="padding-right: 32px; appearance: none; -webkit-appearance: none; cursor: pointer;">
                    <option value="created_at-desc">Urutkan: Terbaru</option>
                    <option value="tanggal_surat-desc">Tgl Surat (Terbaru)</option>
                    <option value="tanggal_surat-asc">Tgl Surat (Terlama)</option>
                    <option value="tanggal_terima-desc">Tgl Terima (Terbaru)</option>
                    <option value="tanggal_terima-asc">Tgl Terima (Terlama)</option>
                </select>
                <i data-lucide="chevron-down" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #64748b; pointer-events: none;"></i>
            </div>

            <div style="position: relative; display: inline-block;">
                <select id="prioritasSelect" class="btn date-filter-btn" style="padding-right: 32px; appearance: none; -webkit-appearance: none; cursor: pointer;">
                    <option value="Semua">Semua Prioritas</option>
                    <option value="Tinggi">⚡ Tinggi</option>
                    <option value="Sedang">🟢 Sedang</option>
                    <option value="Rendah">🔵 Rendah</option>
                </select>
                <i data-lucide="chevron-down" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #64748b; pointer-events: none;"></i>
            </div>

            <button type="button" id="openDateFilter" class="btn date-filter-btn">
                <i data-lucide="calendar" style="width: 18px; height: 18px;"></i>
                <span id="dateRangeText">Filter Tanggal</span>
            </button>
            <input type="hidden" id="startDateInput" value="{{ request('start_date') }}">
            <input type="hidden" id="endDateInput" value="{{ request('end_date') }}">

            <button type="button" id="resetFiltersBtn" class="btn date-filter-btn" style="border-color: #f87171; color: #ef4444; background: #fef2f2; display: none; align-items: center; gap: 6px; padding: 0 12px;">
                <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                <span>Reset</span>
            </button>
        </div>
        <div class="filter-tabs">
            <button class="filter-tab active" data-status="Semua">Semua</button>
            <button class="filter-tab" data-status="Pending">Pending</button>
            <button class="filter-tab" data-status="Diproses">Diproses</button>
            <button class="filter-tab" data-status="Terarsip">Terarsip</button>
            <button class="filter-tab" data-status="Selesai">Selesai</button>
        </div>
    </div>

    {{-- ====== List Container ====== --}}
    <div id="listContainer">
        @include('surat_masuk._list')
    </div>

    @if(Auth::check() && Auth::user()->role === 'Admin')
        {{-- ====== Modal Create ====== --}}
        <div class="modal-backdrop-custom" id="modalBackdrop"></div>
        <div class="modal-container-custom" id="createSuratModal">
            <div class="modal-content-custom">
                <div class="modal-header-custom">
                    <div>
                        <h4>Tambah Surat Masuk</h4>
                        <p>Masukkan detail surat masuk baru</p>
                    </div>
                    <button type="button" class="btn-close-custom" id="closeCreateModal">
                        <i data-lucide="x"></i>
                    </button>
                </div>

                <div class="modal-body-custom">
                    <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Nomor Surat <span style="color:#dc2626">*</span></label>
                            <input type="text" class="form-control @error('nomor_surat') is-invalid @enderror"
                                name="nomor_surat" value="{{ old('nomor_surat') }}"
                                placeholder="Contoh: 001/SM/UPT-PTHMT/II/2026" required>
                            @error('nomor_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Perihal <span style="color:#dc2626">*</span></label>
                            <input type="text" class="form-control @error('perihal') is-invalid @enderror" name="perihal"
                                value="{{ old('perihal') }}" placeholder="Masukkan perihal surat" required>
                            @error('perihal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Pengirim <span style="color:#dc2626">*</span></label>
                                <input type="text" class="form-control @error('pengirim') is-invalid @enderror" name="pengirim"
                                    value="{{ old('pengirim') }}" placeholder="Nama pengirim" required>
                                @error('pengirim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Penerima</label>
                                <input type="text" class="form-control @error('penerima') is-invalid @enderror" name="penerima"
                                    value="{{ old('penerima') }}" placeholder="Nama penerima">
                            </div>
                        </div>

                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label">Tgl Surat <span style="color:#dc2626">*</span></label>
                                <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror"
                                    name="tanggal_surat" value="{{ old('tanggal_surat') }}" required>
                                @error('tanggal_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tgl Terima <span style="color:#dc2626">*</span></label>
                                <input type="date" class="form-control @error('tanggal_terima') is-invalid @enderror"
                                    name="tanggal_terima" value="{{ old('tanggal_terima', date('Y-m-d')) }}" required>
                                @error('tanggal_terima')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Prioritas <span style="color:#dc2626">*</span></label>
                                <select class="form-control" name="prioritas" required>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Tinggi">Tinggi</option>
                                    <option value="Rendah">Rendah</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Disposisi</label>
                            <textarea class="form-control" name="disposisi" rows="2"
                                placeholder="Isi disposisi jika ada"></textarea>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Penerima Disposisi</label>
                                <input type="text" class="form-control" name="penerima_disposisi"
                                    placeholder="Nama penerima disposisi">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori <span style="color:#dc2626">*</span></label>
                                <select class="form-control" name="kategori" required>
                                    <option value="Permohonan">Permohonan</option>
                                    <option value="Undangan">Undangan</option>
                                    <option value="Laporan">Laporan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Upload File (PDF) <span style="color:#dc2626">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" required>
                            @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <input type="hidden" name="status" value="Pending">

                        <div class="modal-footer-btns">
                            <button type="submit" class="btn-save-modal">Simpan</button>
                            <button type="button" class="btn-cancel-modal" id="cancelCreateModal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== Modal Filter Tanggal ====== --}}
    <div class="modal-container-custom" id="dateFilterModal" style="z-index: 1060;">
        <div class="modal-content-custom" style="max-width: 450px; position: relative;">
            <div class="modal-header-custom"
                style="border: none; padding-bottom: 0; display: flex; justify-content: center; align-items: center;">
                <h4 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;">Filter Tanggal</h4>
                <button type="button" class="btn-close-custom" id="closeDateFilter"
                    style="position: absolute; right: 16px; top: 16px; background: #f1f5f9; border-radius: 8px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: none; transition: all 0.2s;">
                    <i data-lucide="x" style="width: 18px; height: 18px; color: #64748b;"></i>
                </button>
            </div>

            <div class="modal-body-custom" style="padding: 30px;">
                <div
                    style="background: #f8fafc; border-radius: 20px; padding: 24px; margin-bottom: 24px; display: flex; align-items: center; gap: 20px; border: 1px solid #f1f5f9;">
                    <div
                        style="width: 56px; height: 56px; background: #00c853; color: white; border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px rgba(0, 200, 83, 0.2);">
                        <i data-lucide="file-text" style="width: 28px; height: 28px;"></i>
                    </div>
                    <div>
                        <div id="modalDateRangeText"
                            style="font-size: 16px; font-weight: 800; color: #1e293b; margin-bottom: 4px;">Semua Tanggal
                        </div>
                        <div style="font-size: 13px; color: #94a3b8; font-weight: 500;">Pilih rentang waktu dokumen</div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label"
                            style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Dari
                            Tanggal</label>
                        <input type="date" id="modalStartDate" class="form-control"
                            style="height: 48px; border-radius: 12px; font-weight: 600;">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label"
                            style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Sampai
                            Tanggal</label>
                        <input type="date" id="modalEndDate" class="form-control"
                            style="height: 48px; border-radius: 12px; font-weight: 600;">
                    </div>
                </div>

                <div class="mt-4" style="display: flex; flex-direction: column; gap: 12px;">
                    <button type="button" id="applyDateFilter" class="btn btn-primary"
                        style="width: 100%; height: 52px; border-radius: 16px; font-weight: 800; font-size: 15px; background: #e50000; border: none; box-shadow: 0 8px 20px rgba(229, 0, 0, 0.25);">
                        Lanjut
                    </button>
                    <button type="button" id="resetDateFilter"
                        style="width: 100%; background: none; border: none; color: #64748b; font-weight: 700; font-size: 14px; padding: 10px; cursor: pointer;">
                        Hapus Filter Tanggal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== Modal Detail ====== --}}
    <div class="modal-container-custom" id="detailSuratModal">
        <div class="modal-content-custom" style="max-width: 550px;">
            <div class="modal-header-custom">
                <div>
                    <h4>Detail Surat Masuk</h4>
                    <p>Informasi lengkap surat masuk</p>
                </div>
                <button type="button" class="btn-close-custom" id="closeDetailModal">
                    <i data-lucide="x"></i>
                </button>
            </div>

            <div class="modal-body-custom">
                <div class="detail-row-modern">
                    <div class="detail-item">
                        <div class="detail-label-modern">
                            <i data-lucide="file-text" style="color: #8b5cf6;"></i>
                            Nomor Surat
                        </div>
                        <div class="detail-value-modern" id="detailNomor" style="font-weight: 600;"></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label-modern">
                            <i data-lucide="calendar" style="color: #22c55e;"></i>
                            Tgl Surat
                        </div>
                        <div class="detail-value-modern" id="detailTanggalSurat"></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label-modern">
                            <i data-lucide="calendar-check" style="color: #3b82f6;"></i>
                            Tgl Terima
                        </div>
                        <div class="detail-value-modern" id="detailTanggalTerima"></div>
                    </div>
                </div>

                <div class="detail-item full-width mt-3">
                    <div class="detail-label-modern">
                        <i data-lucide="mail" style="color: #ec4899;"></i>
                        Perihal
                    </div>
                    <div class="detail-value-modern" id="detailPerihal" style="font-weight: 600;"></div>
                </div>

                <div class="detail-item full-width mt-3">
                    <div class="detail-label-modern">
                        <i data-lucide="user" style="color: #f97316;"></i>
                        Pengirim
                    </div>
                    <div class="detail-value-modern" id="detailPengirim"></div>
                </div>

                <div class="detail-item full-width mt-3">
                    <div class="detail-label-modern">
                        <i data-lucide="user-check" style="color: #14b8a6;"></i>
                        Penerima
                    </div>
                    <div class="detail-value-modern" id="detailPenerima"></div>
                </div>

                <div class="detail-item full-width mt-3">
                    <div class="detail-label-modern">
                        <i data-lucide="message-square" style="color: #f59e0b;"></i>
                        Disposisi
                    </div>
                    <div class="detail-value-modern" id="detailDisposisi"></div>
                </div>

                <div class="detail-row-modern mt-3 border-top pt-3">
                    <div class="detail-item">
                        <div class="detail-label-modern">Kategori</div>
                        <div class="detail-value-modern" id="detailKategori"></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label-modern">Status</div>
                        <div id="detailStatus"></div>
                    </div>
                </div>

                <div class="modal-footer-btns" style="justify-content: flex-end; gap: 12px;">
                    <a id="btnLihatFile" href="#" target="_blank" class="btn-save-modal"
                        style="background: #3b82f6; text-decoration: none; display: flex; align-items: center; gap: 8px; justify-content: center; flex: none; min-width: 140px; padding: 10px 20px;">
                        <i data-lucide="external-link" style="width: 16px; height: 16px;"></i>
                        Lihat
                    </a>
                    <a id="btnDownloadFile" href="#" class="btn-save-modal"
                        style="background: #10b981; text-decoration: none; display: flex; align-items: center; gap: 8px; justify-content: center; flex: none; min-width: 140px; padding: 10px 20px;">
                        <i data-lucide="download" style="width: 16px; height: 16px;"></i>
                        Download
                    </a>
                    <button type="button" class="btn-cancel-modal" id="cancelDetailModal"
                        style="flex: none; min-width: 100px; padding: 10px 24px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::check() && Auth::user()->role === 'Admin')
        {{-- ====== Modal Edit ====== --}}
        <div class="modal-container-custom" id="editSuratModal">
            <div class="modal-content-custom">
                <div class="modal-header-custom">
                    <div>
                        <h4>Edit Surat Masuk</h4>
                        <p>Perbarui informasi surat masuk</p>
                    </div>
                    <button type="button" class="btn-close-custom" id="closeEditModal">
                        <i data-lucide="x"></i>
                    </button>
                </div>

                <div class="modal-body-custom">
                    <form id="editSuratForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label">Nomor Surat <span style="color:#dc2626">*</span></label>
                            <input type="text" class="form-control" name="nomor_surat" id="editNomorSurat" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Perihal <span style="color:#dc2626">*</span></label>
                            <input type="text" class="form-control" name="perihal" id="editPerihalInput" required>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Pengirim <span style="color:#dc2626">*</span></label>
                                <input type="text" class="form-control" name="pengirim" id="editPengirimInput" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Penerima</label>
                                <input type="text" class="form-control" name="penerima" id="editPenerimaInput">
                            </div>
                        </div>

                        <div class="form-row-3">
                            <div class="form-group">
                                <label class="form-label">Tgl Surat <span style="color:#dc2626">*</span></label>
                                <input type="date" class="form-control" name="tanggal_surat" id="editTanggalSurat" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tgl Terima <span style="color:#dc2626">*</span></label>
                                <input type="date" class="form-control" name="tanggal_terima" id="editTanggalTerimaInput"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Prioritas <span style="color:#dc2626">*</span></label>
                                <select class="form-control" name="prioritas" id="editPrioritasSelect">
                                    <option value="Sedang">Sedang</option>
                                    <option value="Tinggi">Tinggi</option>
                                    <option value="Rendah">Rendah</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Disposisi</label>
                            <textarea class="form-control" name="disposisi" id="editDisposisiInput" rows="2"></textarea>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Penerima Disposisi</label>
                                <input type="text" class="form-control" name="penerima_disposisi"
                                    id="editPenerimaDisposisiInput">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kategori <span style="color:#dc2626">*</span></label>
                                <select class="form-control" name="kategori" id="editKategoriSelect">
                                    <option value="Permohonan">Permohonan</option>
                                    <option value="Undangan">Undangan</option>
                                    <option value="Laporan">Laporan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status <span style="color:#dc2626">*</span></label>
                            <select class="form-control" name="status" id="editStatusSelect">
                                <option value="Pending">Pending</option>
                                <option value="Diproses">Diproses</option>
                                <option value="Terarsip">Terarsip</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ganti File (PDF) <small class="text-muted">(Opsional)</small></label>
                            <input type="file" class="form-control" name="file">
                        </div>

                        <div class="modal-footer-btns">
                            <button type="submit" class="btn-save-modal">Simpan Perubahan</button>
                            <button type="button" class="btn-cancel-modal" id="cancelEditBtn">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .toolbar-search {
            flex: 1;
            min-width: 250px;
        }

        .toolbar-actions-wrapper {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .date-filter-btn {
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            height: 44px;
            padding: 0 16px;
            color: #64748b;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .filter-tabs {
            display: flex;
            overflow-x: auto;
            padding-bottom: 4px;
            -webkit-overflow-scrolling: touch;
        }

        .filter-tabs::-webkit-scrollbar {
            display: none;
        }

        @media (max-width: 768px) {
            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar-actions-wrapper {
                justify-content: space-between;
            }

            .date-filter-btn {
                flex: 1;
                justify-content: center;
            }

            .modal-content-custom {
                width: 95% !important;
                margin: 10px auto;
                padding: 15px !important;
            }

            .modal-body-custom {
                padding: 15px !important;
            }
        }

        .detail-row-modern {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }

        .detail-label-modern {
            font-size: 13px;
            font-weight: 800;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .detail-label-modern i {
            width: 16px;
            height: 16px;
        }

        .detail-value-modern {
            font-size: 14px;
            color: var(--text-secondary);
            padding-left: 24px;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .border-top {
            border-top: 1px solid #f1f5f9;
        }

        .pt-3 {
            padding-top: 1rem;
        }
    </style>

@endsection

@push('scripts')
    <script>
        // Elements
        const backdrop = document.getElementById('modalBackdrop');

        // Create Modal
        const modalCreate = document.getElementById('createSuratModal');
        const openCreateBtn = document.getElementById('openCreateModal');
        const closeCreateBtn = document.getElementById('closeCreateModal');
        const cancelCreateBtn = document.getElementById('cancelCreateModal');

        const toggleCreateModal = () => {
            if (!modalCreate) return;
            modalCreate.classList.toggle('show');
            backdrop.classList.toggle('show');
            document.body.style.overflow = modalCreate.classList.contains('show') ? 'hidden' : '';
        };

        if (openCreateBtn) openCreateBtn.addEventListener('click', toggleCreateModal);
        if (closeCreateBtn) closeCreateBtn.addEventListener('click', toggleCreateModal);
        if (cancelCreateBtn) cancelCreateBtn.addEventListener('click', toggleCreateModal);

        // Detail Modal
        const modalDetail = document.getElementById('detailSuratModal');
        const closeDetailBtn = document.getElementById('closeDetailModal');
        const cancelDetailBtn = document.getElementById('cancelDetailModal');

        const toggleDetailModal = () => {
            if (!modalDetail) return;
            modalDetail.classList.toggle('show');
            backdrop.classList.toggle('show');
            document.body.style.overflow = modalDetail.classList.contains('show') ? 'hidden' : '';
        };

        if (closeDetailBtn) closeDetailBtn.addEventListener('click', toggleDetailModal);
        if (cancelDetailBtn) cancelDetailBtn.addEventListener('click', toggleDetailModal);

        // Edit Modal
        const modalEdit = document.getElementById('editSuratModal');
        const closeEditBtn = document.getElementById('closeEditModal');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const editForm = document.getElementById('editSuratForm');

        const toggleEditModal = () => {
            if (!modalEdit) return;
            modalEdit.classList.toggle('show');
            backdrop.classList.toggle('show');
            document.body.style.overflow = modalEdit.classList.contains('show') ? 'hidden' : '';
        };

        if (closeEditBtn) closeEditBtn.addEventListener('click', toggleEditModal);
        if (cancelEditBtn) cancelEditBtn.addEventListener('click', toggleEditModal);

        // Global Backdrop Click
        if (backdrop) {
            backdrop.addEventListener('click', () => {
                if (modalCreate) modalCreate.classList.remove('show');
                if (modalDetail) modalDetail.classList.remove('show');
                if (modalEdit) modalEdit.classList.remove('show');
                backdrop.classList.remove('show');
                document.body.style.overflow = '';
            });
        }

        const bindActionButtons = () => {
            // Detail Buttons
            document.querySelectorAll('.btn-view-detail').forEach(btn => {
                btn.onclick = function (e) {
                    e.preventDefault();
                    const d = this.dataset;
                    const setVal = (id, val) => {
                        const el = document.getElementById(id);
                        if (el) el.innerText = val || '-';
                    };

                    setVal('detailNomor', d.nomor);
                    setVal('detailPerihal', d.perihal);
                    setVal('detailPengirim', d.pengirim);
                    setVal('detailTanggalSurat', d.tanggalSurat);
                    setVal('detailTanggalTerima', d.tanggalTerima);
                    setVal('detailKategori', d.kategori);
                    setVal('detailPenerima', d.penerima);
                    setVal('detailDisposisi', d.disposisi);

                    const fileBtn = document.getElementById('btnLihatFile');
                    const dlBtn = document.getElementById('btnDownloadFile');
                    if (fileBtn) {
                        if (d.filePath) {
                            const encodedPath = d.filePath.replace(/\//g, '|');
                            fileBtn.href = `/view-dokumen/${encodedPath}`;
                            if (dlBtn) dlBtn.href = `/download-dokumen/${encodedPath}`;
                            fileBtn.style.display = 'flex';
                            if (dlBtn) dlBtn.style.display = 'flex';
                        } else {
                            fileBtn.style.display = 'none';
                            if (dlBtn) dlBtn.style.display = 'none';
                        }
                    }

                    const statusEl = document.getElementById('detailStatus');
                    if (statusEl) {
                        statusEl.innerHTML = `<span class="status-badge ${d.statusClass}">${d.status || 'Pending'}</span>`;
                    }

                    toggleDetailModal();
                };
            });

            // Edit Buttons
            document.querySelectorAll('.btn-edit-surat').forEach(btn => {
                btn.onclick = function (e) {
                    e.preventDefault();
                    const d = this.dataset;
                    const setInput = (id, val) => {
                        const el = document.getElementById(id);
                        if (el) el.value = val || '';
                    };

                    setInput('editNomorSurat', d.nomor);
                    setInput('editPerihalInput', d.perihal);
                    setInput('editPengirimInput', d.pengirim);
                    setInput('editPenerimaInput', d.penerima);
                    setInput('editTanggalSurat', d.tanggal);
                    setInput('editTanggalTerimaInput', d.tanggalTerima);
                    setInput('editPrioritasSelect', d.prioritas);
                    setInput('editKategoriSelect', d.kategori);
                    setInput('editStatusSelect', d.status);
                    setInput('editDisposisiInput', d.disposisi);
                    setInput('editPenerimaDisposisiInput', d.penerimaDisposisi);

                    if (editForm) editForm.action = d.url;

                    toggleEditModal();
                };
            });
        };

        bindActionButtons();

                    @if($errors->any()) toggleCreateModal(); @endif

                    // Date Filter Logic
                    const dateFilterModal = document.getElementById('dateFilterModal');
        const openDateFilterBtn = document.getElementById('openDateFilter');
        const closeDateFilterBtn = document.getElementById('closeDateFilter');
        const applyDateFilterBtn = document.getElementById('applyDateFilter');
        const resetDateFilterBtn = document.getElementById('resetDateFilter');
        const startDateInput = document.getElementById('startDateInput');
        const endDateInput = document.getElementById('endDateInput');
        const modalStartDate = document.getElementById('modalStartDate');
        const modalEndDate = document.getElementById('modalEndDate');
        const dateRangeText = document.getElementById('dateRangeText');
        const modalDateRangeText = document.getElementById('modalDateRangeText');

        const updateDateRangeText = () => {
            if (startDateInput.value && endDateInput.value) {
                const start = new Date(startDateInput.value).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                const end = new Date(endDateInput.value).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                const text = `${start} - ${end}`;
                dateRangeText.innerText = text;
                modalDateRangeText.innerText = text;
                openDateFilterBtn.style.borderColor = '#16a34a';
                openDateFilterBtn.style.color = '#16a34a';
                openDateFilterBtn.style.background = '#f0fdf4';
            } else {
                dateRangeText.innerText = 'Filter Tanggal';
                modalDateRangeText.innerText = 'Semua Tanggal';
                openDateFilterBtn.style.borderColor = '#e2e8f0';
                openDateFilterBtn.style.color = '#64748b';
                openDateFilterBtn.style.background = '#fff';
            }
        };

        updateDateRangeText();

        openDateFilterBtn.addEventListener('click', () => {
            modalStartDate.value = startDateInput.value;
            modalEndDate.value = endDateInput.value;
            dateFilterModal.classList.add('show');
            backdrop.classList.add('show');
        });

        closeDateFilterBtn.addEventListener('click', () => {
            dateFilterModal.classList.remove('show');
            backdrop.classList.remove('show');
        });

        applyDateFilterBtn.addEventListener('click', () => {
            startDateInput.value = modalStartDate.value;
            endDateInput.value = modalEndDate.value;
            updateDateRangeText();
            dateFilterModal.classList.remove('show');
            backdrop.classList.remove('show');
            performUpdate();
        });

        resetDateFilterBtn.addEventListener('click', () => {
            startDateInput.value = '';
            endDateInput.value = '';
            modalStartDate.value = '';
            modalEndDate.value = '';
            updateDateRangeText();
            dateFilterModal.classList.remove('show');
            backdrop.classList.remove('show');
            performUpdate();
        });

        // AJAX Search
        const searchInput = document.getElementById('searchInput');
        const prioritasSelect = document.getElementById('prioritasSelect');
        const sortSelect = document.getElementById('sortSelect');
        const resetFiltersBtn = document.getElementById('resetFiltersBtn');
        const listContainer = document.getElementById('listContainer');
        let typingTimer;
        let currentStatus = 'Semua';

        // Read initial state from URL
        const initializeFiltersFromUrl = () => {
            const params = new URLSearchParams(window.location.search);
            
            // Search Input
            if (params.has('search') && searchInput) {
                searchInput.value = params.get('search');
            }
            
            // Status Tab
            if (params.has('status')) {
                currentStatus = params.get('status');
                document.querySelectorAll('.filter-tab').forEach(tab => {
                    if (tab.dataset.status === currentStatus) {
                        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                        tab.classList.add('active');
                    }
                });
            }
            
            // Prioritas Select
            if (params.has('prioritas') && prioritasSelect) {
                prioritasSelect.value = params.get('prioritas');
            }
            
            // Sort Select
            if (params.has('sort_by') && params.has('sort_order') && sortSelect) {
                sortSelect.value = `${params.get('sort_by')}-${params.get('sort_order')}`;
            }
            
            // Date Filter inputs
            if (params.has('start_date') && startDateInput) {
                startDateInput.value = params.get('start_date');
            }
            if (params.has('end_date') && endDateInput) {
                endDateInput.value = params.get('end_date');
            }
            updateDateRangeText();
            
            // Toggle Reset Button
            checkResetFiltersButton();
        };

        const checkResetFiltersButton = () => {
            const hasActiveFilters = (searchInput && searchInput.value) || 
                                     currentStatus !== 'Semua' || 
                                     (prioritasSelect && prioritasSelect.value !== 'Semua') || 
                                     (sortSelect && sortSelect.value !== 'created_at-desc') ||
                                     (startDateInput && startDateInput.value) || 
                                     (endDateInput && endDateInput.value);
            if (resetFiltersBtn) {
                resetFiltersBtn.style.display = hasActiveFilters ? 'flex' : 'none';
            }
        };

        const performUpdate = (status = currentStatus, page = 1) => {
            const url = new URL(window.location.href);
            url.searchParams.set('search', searchInput ? searchInput.value : '');
            url.searchParams.set('status', status);
            url.searchParams.set('page', page);
            
            const prioritasVal = prioritasSelect ? prioritasSelect.value : 'Semua';
            if (prioritasVal !== 'Semua') {
                url.searchParams.set('prioritas', prioritasVal);
            } else {
                url.searchParams.delete('prioritas');
            }

            if (sortSelect) {
                const [sortBy, sortOrder] = sortSelect.value.split('-');
                url.searchParams.set('sort_by', sortBy);
                url.searchParams.set('sort_order', sortOrder);
            }

            if (startDateInput && startDateInput.value) url.searchParams.set('start_date', startDateInput.value);
            else url.searchParams.delete('start_date');
            
            if (endDateInput && endDateInput.value) url.searchParams.set('end_date', endDateInput.value);
            else url.searchParams.delete('end_date');

            checkResetFiltersButton();

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    listContainer.innerHTML = data.html;
                    lucide.createIcons();
                    bindDeleteConfirm();
                    bindActionButtons(); // Re-bind dynamic buttons
                    bindPagination();    // Bind AJAX pagination
                    window.history.pushState({}, '', url);
                });
        };

        const bindPagination = () => {
            document.querySelectorAll('#listContainer .pagination-list a.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.dataset.page || 1;
                    performUpdate(currentStatus, page);
                });
            });
        };

        // Tab selection event listener
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                currentStatus = this.dataset.status;
                performUpdate(currentStatus);
            });
        });

        // Search input keydown listener (debounced)
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => performUpdate(), 300);
            });
        }

        // Prioritas select listener
        if (prioritasSelect) {
            prioritasSelect.addEventListener('change', () => {
                performUpdate();
            });
        }

        // Sort select listener
        if (sortSelect) {
            sortSelect.addEventListener('change', () => {
                performUpdate();
            });
        }

        // Reset Filters Button listener
        if (resetFiltersBtn) {
            resetFiltersBtn.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                if (prioritasSelect) prioritasSelect.value = 'Semua';
                if (sortSelect) sortSelect.value = 'created_at-desc';
                if (startDateInput) startDateInput.value = '';
                if (endDateInput) endDateInput.value = '';
                if (modalStartDate) modalStartDate.value = '';
                if (modalEndDate) modalEndDate.value = '';
                
                currentStatus = 'Semua';
                document.querySelectorAll('.filter-tab').forEach(tab => {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    if (tab.dataset.status === 'Semua') tab.classList.add('active');
                });
                
                updateDateRangeText();
                performUpdate('Semua');
            });
        }

        function bindDeleteConfirm() {
            document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
                btn.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Data yang dihapus tidak dapat dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then(result => {
                        if (result.isConfirmed) document.getElementById(this.dataset.form).submit();
                    });
                });
            });
        }

        // Initial bindings
        bindDeleteConfirm();
        bindPagination();
        initializeFiltersFromUrl();

        window.addEventListener('DOMContentLoaded', () => {
            if (new URLSearchParams(window.location.search).get('create') === 'true') {
                toggleCreateModal();
                window.history.replaceState({}, '', window.location.pathname);
            }
        });
    </script>
@endpush