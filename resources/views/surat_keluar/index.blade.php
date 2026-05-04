@extends('layouts.app')

@section('title', 'Surat Keluar - E-Arsip')

@section('content')

{{-- ====== Page Header ====== --}}
<div class="page-header">
    <div class="page-header-left">
        <h1>Surat Keluar</h1>
        <p>Kelola surat keluar dan pengiriman</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('surat-keluar.print') }}" target="_blank" class="btn btn-outline-secondary me-2">
            <i data-lucide="printer" style="width:16px;height:16px;"></i>
            Cetak Laporan
        </a>
        @if(Auth::check() && Auth::user()->role === 'Admin')
        <button type="button" class="btn btn-primary" id="openCreateModal">
            <i data-lucide="plus" style="width:16px;height:16px;"></i>
            Buat Surat Keluar
        </button>
        @endif
    </div>
</div>

{{-- ====== Toolbar ====== --}}
<div class="toolbar">
    <div class="toolbar-search">
        <i data-lucide="search" class="search-icon"></i>
        <input type="text" id="searchInput" placeholder="Cari nomor, perihal, atau tujuan...">
    </div>
    <div class="toolbar-actions-wrapper">
        <button type="button" id="openDateFilter" class="btn date-filter-btn">
            <i data-lucide="calendar" style="width: 18px; height: 18px;"></i>
            <span id="dateRangeText">Filter Tanggal</span>
        </button>
        <input type="hidden" id="startDateInput" value="{{ request('start_date') }}">
        <input type="hidden" id="endDateInput" value="{{ request('end_date') }}">
    </div>
    <div class="filter-tabs">
        <button class="filter-tab active" data-status="Semua">Semua</button>
        <button class="filter-tab" data-status="Draft">Draft</button>
        <button class="filter-tab" data-status="Terkirim">Terkirim</button>
    </div>
</div>

{{-- ====== List Container ====== --}}
<div id="listContainer">
    @include('surat_keluar._list')
</div>

{{-- ====== Modal Create ====== --}}
<div class="modal-backdrop-custom" id="modalBackdrop"></div>
<div class="modal-container-custom" id="createSuratModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <div>
                <h4>Tambah Surat Keluar</h4>
                <p>Masukkan detail surat keluar baru</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeCreateModal">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="modal-body-custom">
            <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nomor Surat <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control @error('nomor_surat') is-invalid @enderror"
                           name="nomor_surat" value="{{ old('nomor_surat') }}"
                           placeholder="Contoh: 001/SK/UPT-PTHMT/II/2026" required>
                    @error('nomor_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Perihal <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control @error('perihal') is-invalid @enderror"
                           name="perihal" value="{{ old('perihal') }}"
                           placeholder="Masukkan perihal surat" required>
                    @error('perihal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Tujuan <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control @error('tujuan') is-invalid @enderror"
                               name="tujuan" value="{{ old('tujuan') }}"
                               placeholder="Nama tujuan/penerima" required>
                        @error('tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                        <label class="form-label">Tgl Kirim</label>
                        <input type="date" class="form-control @error('tanggal_kirim') is-invalid @enderror"
                               name="tanggal_kirim" value="{{ old('tanggal_kirim') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prioritas <span style="color:#dc2626">*</span></label>
                        <select class="form-control @error('prioritas') is-invalid @enderror" name="prioritas" required>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Rendah">Rendah</option>
                        </select>
                    </div>
                </div>
                    <div class="form-group">
                        <label class="form-label">Upload File (PDF) <span style="color:#dc2626">*</span></label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" required>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <input type="hidden" name="status" value="Draft">

                <div class="modal-footer-btns">
                    <button type="submit" class="btn-save-modal">Simpan</button>
                    <button type="button" class="btn-cancel-modal" id="cancelCreateModal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ====== Modal Filter Tanggal ====== --}}
<div class="modal-container-custom" id="dateFilterModal" style="z-index: 1060;">
    <div class="modal-content-custom" style="max-width: 450px; position: relative;">
        <div class="modal-header-custom" style="border: none; padding-bottom: 0; display: flex; justify-content: center; align-items: center;">
            <h4 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;">Filter Tanggal</h4>
            <button type="button" class="btn-close-custom" id="closeDateFilter" style="position: absolute; right: 16px; top: 16px; background: #f1f5f9; border-radius: 8px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: none; transition: all 0.2s;">
                <i data-lucide="x" style="width: 18px; height: 18px; color: #64748b;"></i>
            </button>
        </div>

        <div class="modal-body-custom" style="padding: 30px;">
            <div style="background: #f8fafc; border-radius: 20px; padding: 24px; margin-bottom: 24px; display: flex; align-items: center; gap: 20px; border: 1px solid #f1f5f9;">
                <div style="width: 56px; height: 56px; background: #00c853; color: white; border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px rgba(0, 200, 83, 0.2);">
                    <i data-lucide="file-text" style="width: 28px; height: 28px;"></i>
                </div>
                <div>
                    <div id="modalDateRangeText" style="font-size: 16px; font-weight: 800; color: #1e293b; margin-bottom: 4px;">Semua Tanggal</div>
                    <div style="font-size: 13px; color: #94a3b8; font-weight: 500;">Pilih rentang waktu dokumen</div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Dari Tanggal</label>
                    <input type="date" id="modalStartDate" class="form-control" style="height: 48px; border-radius: 12px; font-weight: 600;">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Sampai Tanggal</label>
                    <input type="date" id="modalEndDate" class="form-control" style="height: 48px; border-radius: 12px; font-weight: 600;">
                </div>
            </div>

            <div class="mt-4" style="display: flex; flex-direction: column; gap: 12px;">
                <button type="button" id="applyDateFilter" class="btn btn-primary" style="width: 100%; height: 52px; border-radius: 16px; font-weight: 800; font-size: 15px; background: #e50000; border: none; box-shadow: 0 8px 20px rgba(229, 0, 0, 0.25);">
                    Lanjut
                </button>
                <button type="button" id="resetDateFilter" style="width: 100%; background: none; border: none; color: #64748b; font-weight: 700; font-size: 14px; padding: 10px; cursor: pointer;">
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
                <h4>Detail Surat Keluar</h4>
                <p>Informasi lengkap surat keluar</p>
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
                        <i data-lucide="send" style="color: #3b82f6;"></i>
                        Tgl Kirim
                    </div>
                    <div class="detail-value-modern" id="detailTanggalKirim"></div>
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
                    Tujuan
                </div>
                <div class="detail-value-modern" id="detailTujuan"></div>
            </div>

            <div class="detail-row-modern mt-3 border-top pt-3">
                <div class="detail-item">
                    <div class="detail-label-modern">Prioritas</div>
                    <div class="detail-value-modern" id="detailPrioritas"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label-modern">Status</div>
                    <div id="detailStatus"></div>
                </div>
            </div>

            <div class="modal-footer-btns" style="justify-content: flex-end;">
                <button type="button" class="btn-cancel-modal" id="cancelDetailModal" style="flex: none; min-width: 100px; padding: 10px 24px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ====== Modal Edit ====== --}}
<div class="modal-container-custom" id="editSuratModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <div>
                <h4>Edit Surat Keluar</h4>
                <p>Perbarui informasi surat keluar</p>
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
                        <label class="form-label">Tujuan <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control" name="tujuan" id="editTujuanInput" required>
                    </div>
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Tgl Surat <span style="color:#dc2626">*</span></label>
                        <input type="date" class="form-control" name="tanggal_surat" id="editTanggalSurat" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tgl Kirim</label>
                        <input type="date" class="form-control" name="tanggal_kirim" id="editTanggalKirimInput">
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
                        <label class="form-label">Ganti File (PDF) <small class="text-muted">(Opsional)</small></label>
                        <input type="file" class="form-control" name="file">
                    </div>
                </div>

                <div class="modal-footer-btns">
                    <button type="submit" class="btn-save-modal">Simpan Perubahan</button>
                    <button type="button" class="btn-cancel-modal" id="cancelEditBtn">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    .mt-3 { margin-top: 1rem; }
    .border-top { border-top: 1px solid #f1f5f9; }
    .pt-3 { padding-top: 1rem; }
</style>

@endsection

@push('scripts')
<script>
    // Elements
    const backdrop       = document.getElementById('modalBackdrop');
    
    // Create Modal
    const modalCreate    = document.getElementById('createSuratModal');
    const openCreateBtn  = document.getElementById('openCreateModal');
    const closeCreateBtn = document.getElementById('closeCreateModal');
    const cancelCreateBtn = document.getElementById('cancelCreateModal');

    const toggleCreateModal = () => {
        modalCreate.classList.toggle('show');
        backdrop.classList.toggle('show');
        document.body.style.overflow = modalCreate.classList.contains('show') ? 'hidden' : '';
    };

    if(openCreateBtn)   openCreateBtn.addEventListener('click', toggleCreateModal);
    if(closeCreateBtn)  closeCreateBtn.addEventListener('click', toggleCreateModal);
    if(cancelCreateBtn) cancelCreateBtn.addEventListener('click', toggleCreateModal);

    // Detail Modal
    const modalDetail    = document.getElementById('detailSuratModal');
    const closeDetailBtn = document.getElementById('closeDetailModal');
    const cancelDetailBtn = document.getElementById('cancelDetailModal');

    const toggleDetailModal = () => {
        modalDetail.classList.toggle('show');
        backdrop.classList.toggle('show');
        document.body.style.overflow = modalDetail.classList.contains('show') ? 'hidden' : '';
    };

    if(closeDetailBtn) closeDetailBtn.addEventListener('click', toggleDetailModal);
    if(cancelDetailBtn) cancelDetailBtn.addEventListener('click', toggleDetailModal);

    // Edit Modal
    const modalEdit      = document.getElementById('editSuratModal');
    const closeEditBtn   = document.getElementById('closeEditModal');
    const cancelEditBtn  = document.getElementById('cancelEditBtn');
    const editForm       = document.getElementById('editSuratForm');

    const toggleEditModal = () => {
        modalEdit.classList.toggle('show');
        backdrop.classList.toggle('show');
        document.body.style.overflow = modalEdit.classList.contains('show') ? 'hidden' : '';
    };

    if(closeEditBtn)  closeEditBtn.addEventListener('click', toggleEditModal);
    if(cancelEditBtn) cancelEditBtn.addEventListener('click', toggleEditModal);

    // Global Backdrop Click
    if(backdrop) {
        backdrop.addEventListener('click', () => {
            modalCreate.classList.remove('show');
            modalDetail.classList.remove('show');
            modalEdit.classList.remove('show');
            backdrop.classList.remove('show');
            document.body.style.overflow = '';
        });
    }

    const bindActionButtons = () => {
        // Detail Buttons
        document.querySelectorAll('.btn-view-detail').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('detailNomor').innerText    = this.dataset.nomor;
                document.getElementById('detailPerihal').innerText  = this.dataset.perihal;
                document.getElementById('detailTujuan').innerText   = this.dataset.tujuan;
                document.getElementById('detailTanggalSurat').innerText = this.dataset.tanggalSurat;
                document.getElementById('detailTanggalKirim').innerText = this.dataset.tanggalKirim;
                document.getElementById('detailPrioritas').innerText = this.dataset.prioritas;
                const statusEl = document.getElementById('detailStatus');
                statusEl.innerHTML = `<span class="status-badge ${this.dataset.statusClass}">${this.dataset.status}</span>`;
                toggleDetailModal();
            });
        });

        // Edit Buttons
        document.querySelectorAll('.btn-edit-surat').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('editNomorSurat').value     = this.dataset.nomor;
                document.getElementById('editPerihalInput').value   = this.dataset.perihal;
                document.getElementById('editTujuanInput').value    = this.dataset.tujuan;
                document.getElementById('editTanggalSurat').value   = this.dataset.tanggal;
                document.getElementById('editTanggalKirimInput').value = this.dataset.tanggalKirim;
                document.getElementById('editPrioritasSelect').value = this.dataset.prioritas;
                editForm.action = this.dataset.url;
                toggleEditModal();
            });
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
    const searchInput   = document.getElementById('searchInput');
    const listContainer = document.getElementById('listContainer');
    let typingTimer;
    let currentStatus = 'Semua';

    const performUpdate = () => {
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput.value);
        url.searchParams.set('status', currentStatus);
        if (startDateInput.value) url.searchParams.set('start_date', startDateInput.value);
        else url.searchParams.delete('start_date');
        if (endDateInput.value) url.searchParams.set('end_date', endDateInput.value);
        else url.searchParams.delete('end_date');

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                listContainer.innerHTML = data.html;
                lucide.createIcons();
                bindDeleteConfirm();
                bindSendConfirm();
                bindActionButtons(); // Re-bind dynamic buttons
                window.history.pushState({}, '', url);
            });
    };

    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            currentStatus = this.dataset.status;
            performUpdate();
        });
    });

    if(searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performUpdate, 300);
        });
    }

    function bindDeleteConfirm() {
        document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
            btn.addEventListener('click', function() {
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

    function bindSendConfirm() {
        document.querySelectorAll('.btn-send-confirm').forEach(btn => {
            btn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Kirim Surat?',
                    text: "Status surat akan berubah menjadi 'Terkirim'.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then(result => {
                    if (result.isConfirmed) document.getElementById(this.dataset.form).submit();
                });
            });
        });
    }

    bindDeleteConfirm();
    bindSendConfirm();

    window.addEventListener('DOMContentLoaded', () => {
        if (new URLSearchParams(window.location.search).get('create') === 'true') {
            toggleCreateModal();
            window.history.replaceState({}, '', window.location.pathname);
        }
    });
</script>
@endpush
