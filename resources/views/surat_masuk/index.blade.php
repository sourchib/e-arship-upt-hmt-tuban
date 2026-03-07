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
        <button type="button" class="btn btn-primary" id="openCreateModal">
            <i data-lucide="plus" style="width:16px;height:16px;"></i>
            Tambah Surat Masuk
        </button>
    </div>
</div>

{{-- ====== Toolbar ====== --}}
<div class="toolbar">
    <div class="toolbar-search">
        <i data-lucide="search" class="search-icon"></i>
        <input type="text" id="searchInput" placeholder="Cari nomor, perihal, atau pengirim...">
    </div>
    <div class="filter-tabs">
        <button class="filter-tab active" data-status="Semua">Semua</button>
        <button class="filter-tab" data-status="Pending">Pending</button>
        <button class="filter-tab" data-status="Diproses">Diproses</button>
        <button class="filter-tab" data-status="Terarsip">Terarsip</button>
    </div>
</div>

{{-- ====== List Container ====== --}}
<div id="listContainer">
    @include('surat_masuk._list')
</div>

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
                    <input type="text" class="form-control @error('perihal') is-invalid @enderror"
                           name="perihal" value="{{ old('perihal') }}"
                           placeholder="Masukkan perihal surat" required>
                    @error('perihal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Pengirim <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control @error('pengirim') is-invalid @enderror"
                               name="pengirim" value="{{ old('pengirim') }}"
                               placeholder="Nama pengirim" required>
                        @error('pengirim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Surat <span style="color:#dc2626">*</span></label>
                        <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror"
                               name="tanggal_surat" value="{{ old('tanggal_surat') }}" required>
                        @error('tanggal_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color:#dc2626">*</span></label>
                        <select class="form-control" name="kategori">
                            <option value="Permohonan">Permohonan</option>
                            <option value="Undangan">Undangan</option>
                            <option value="Laporan">Laporan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Upload File (PDF) <span style="color:#dc2626">*</span></label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                               name="file" required>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <input type="hidden" name="tanggal_terima" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="prioritas" value="Sedang">
                <input type="hidden" name="status" value="Pending">

                <div class="modal-footer-btns">
                    <button type="submit" class="btn-save-modal">Simpan</button>
                    <button type="button" class="btn-cancel-modal" id="cancelCreateModal">Batal</button>
                </div>
            </form>
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
                        Tanggal
                    </div>
                    <div class="detail-value-modern" id="detailTanggal"></div>
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
                        <label class="form-label">Tanggal Surat <span style="color:#dc2626">*</span></label>
                        <input type="date" class="form-control" name="tanggal_surat" id="editTanggalSurat" required>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color:#dc2626">*</span></label>
                        <select class="form-control" name="kategori" id="editKategoriSelect">
                            <option value="Permohonan">Permohonan</option>
                            <option value="Undangan">Undangan</option>
                            <option value="Laporan">Laporan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
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
    .detail-row-modern {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 16px;
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
                document.getElementById('detailPengirim').innerText = this.dataset.pengirim;
                document.getElementById('detailTanggal').innerText  = this.dataset.tanggal;
                document.getElementById('detailKategori').innerText = this.dataset.kategori;
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
                document.getElementById('editPengirimInput').value  = this.dataset.pengirim;
                document.getElementById('editTanggalSurat').value   = this.dataset.tanggal;
                document.getElementById('editKategoriSelect').value = this.dataset.kategori;
                editForm.action = this.dataset.url;
                toggleEditModal();
            });
        });
    };

    bindActionButtons();

    @if($errors->any()) toggleCreateModal(); @endif

    // AJAX Search
    const searchInput    = document.getElementById('searchInput');
    const listContainer  = document.getElementById('listContainer');
    let typingTimer;

    const performUpdate = (status = currentStatus) => {
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput.value);
        url.searchParams.set('status', status);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                listContainer.innerHTML = data.html;
                lucide.createIcons();
                bindDeleteConfirm();
                bindActionButtons(); // Re-bind dynamic buttons
                window.history.pushState({}, '', url);
            });
    };

    let currentStatus = 'Semua';
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            currentStatus = this.dataset.status;
            performUpdate(currentStatus);
        });
    });

    if(searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => performUpdate(), 300);
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
    bindDeleteConfirm();

    window.addEventListener('DOMContentLoaded', () => {
        if (new URLSearchParams(window.location.search).get('create') === 'true') {
            toggleModal();
            window.history.replaceState({}, '', window.location.pathname);
        }
    });
</script>
@endpush
