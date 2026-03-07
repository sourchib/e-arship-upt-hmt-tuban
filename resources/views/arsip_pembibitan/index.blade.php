@extends('layouts.app')

@section('title', 'Arsip Pembibitan - E-Arsip')

@section('content')

{{-- ====== Page Header ====== --}}
<div class="page-header">
    <div class="page-header-left">
        <h1>Arsip Pembibitan</h1>
        <p>Kelola data pembibitan ternak sapi PO</p>
    </div>
    <div class="page-header-actions">
        @if(Auth::check() && Auth::user()->role === 'Admin')
        <button type="button" class="btn btn-primary" id="openCreateModal">
            <i data-lucide="plus" style="width:16px;height:16px;"></i>
            Tambah Data Pembibitan
        </button>
        @endif
    </div>
</div>

{{-- ====== Mini Stats ====== --}}
<div id="statsContainer">
    @include('arsip_pembibitan._stats')
</div>

{{-- ====== Toolbar ====== --}}
<div class="toolbar">
    <div class="toolbar-search">
        <i data-lucide="search" class="search-icon"></i>
        <input type="text" id="searchInput" placeholder="Cari kode, jenis ternak, atau tujuan...">
    </div>
    <div class="filter-tabs">
        <button class="filter-tab active" data-status="Semua">Semua</button>
        <button class="filter-tab" data-status="Proses">Proses</button>
        <button class="filter-tab" data-status="Terdistribusi">Terdistribusi</button>
    </div>
</div>

{{-- ====== List Container ====== --}}
<div id="listContainer">
    @include('arsip_pembibitan._list')
</div>

{{-- ====== Modal Create ====== --}}
<div class="modal-backdrop-custom" id="modalBackdrop"></div>
<div class="modal-container-custom" id="createArsipModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <div>
                <h4>Tambah Data Pembibitan</h4>
                <p>Masukkan detail pembibitan ternak baru</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeCreateModal">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="modal-body-custom">
            <form action="{{ route('arsip-pembibitan.store') }}" method="POST">
                @csrf

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Kode <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control @error('kode') is-invalid @enderror"
                               name="kode" value="{{ old('kode') }}"
                               placeholder="Contoh: PO-2026-001" required>
                        @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Ternak <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control @error('jenis_ternak') is-invalid @enderror"
                               name="jenis_ternak" value="{{ old('jenis_ternak', 'Sapi Peranakan Ongole (PO)') }}" required>
                        @error('jenis_ternak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Jumlah (Ekor) <span style="color:#dc2626">*</span></label>
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                               name="jumlah" value="{{ old('jumlah') }}" required min="1">
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Umur <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control @error('umur') is-invalid @enderror"
                               name="umur" value="{{ old('umur') }}"
                               placeholder="Contoh: 12-18 bulan" required>
                        @error('umur')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tujuan <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control @error('tujuan') is-invalid @enderror"
                           name="tujuan" value="{{ old('tujuan') }}"
                           placeholder="Nama kelompok tani/tujuan" required>
                    @error('tujuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal <span style="color:#dc2626">*</span></label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                               name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span style="color:#dc2626">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                            <option value="Proses">Proses</option>
                            <option value="Terdistribusi">Terdistribusi</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer-btns">
                    <button type="submit" class="btn-save-modal">Simpan Data</button>
                    <button type="button" class="btn-cancel-modal" id="cancelCreateModal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ====== Modal Detail ====== --}}
<div class="modal-container-custom" id="detailArsipModal">
    <div class="modal-content-custom" style="max-width: 550px;">
        <div class="modal-header-custom">
            <div>
                <h4>Detail Pembibitan</h4>
                <p>Informasi lengkap data pembibitan</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeDetailModal">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="modal-body-custom">
            <div class="detail-row-modern">
                <div class="detail-item">
                    <div class="detail-label-modern">
                        <i data-lucide="hash" style="color: #8b5cf6;"></i>
                        Kode
                    </div>
                    <div class="detail-value-modern" id="detailKode" style="font-weight: 600;"></div>
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
                    <i data-lucide="tag" style="color: #ec4899;"></i>
                    Jenis Ternak
                </div>
                <div class="detail-value-modern" id="detailJenis" style="font-weight: 600;"></div>
            </div>

            <div class="detail-row-modern mt-3">
                <div class="detail-item">
                    <div class="detail-label-modern">
                        <i data-lucide="users" style="color: #3b82f6;"></i>
                        Jumlah
                    </div>
                    <div class="detail-value-modern" id="detailJumlah"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label-modern">
                        <i data-lucide="clock" style="color: #f59e0b;"></i>
                        Umur
                    </div>
                    <div class="detail-value-modern" id="detailUmur"></div>
                </div>
            </div>

            <div class="detail-item full-width mt-3">
                <div class="detail-label-modern">
                    <i data-lucide="map-pin" style="color: #f97316;"></i>
                    Tujuan
                </div>
                <div class="detail-value-modern" id="detailTujuan"></div>
            </div>

            <div class="detail-item mt-3 border-top pt-3">
                <div class="detail-label-modern">Status</div>
                <div id="detailStatus"></div>
            </div>

            <div class="modal-footer-btns" style="justify-content: flex-end;">
                <button type="button" class="btn-cancel-modal" id="cancelDetailModal" style="flex: none; min-width: 100px; padding: 10px 24px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ====== Modal Edit ====== --}}
<div class="modal-container-custom" id="editArsipModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <div>
                <h4>Edit Data Pembibitan</h4>
                <p>Perbarui informasi data pembibitan</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeEditModal">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="modal-body-custom">
            <form id="editArsipForm" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Kode <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control" name="kode" id="editKodeInput" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Ternak <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control" name="jenis_ternak" id="editJenisInput" required>
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Jumlah (Ekor) <span style="color:#dc2626">*</span></label>
                        <input type="number" class="form-control" name="jumlah" id="editJumlahInput" required min="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Umur <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control" name="umur" id="editUmurInput" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tujuan <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control" name="tujuan" id="editTujuanInput" required>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal <span style="color:#dc2626">*</span></label>
                        <input type="date" class="form-control" name="tanggal" id="editTanggalInput" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span style="color:#dc2626">*</span></label>
                        <select class="form-control" name="status" id="editStatusSelect" required>
                            <option value="Proses">Proses</option>
                            <option value="Terdistribusi">Terdistribusi</option>
                        </select>
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
    const modalCreate    = document.getElementById('createArsipModal');
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
    const modalDetail    = document.getElementById('detailArsipModal');
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
    const modalEdit      = document.getElementById('editArsipModal');
    const closeEditBtn   = document.getElementById('closeEditModal');
    const cancelEditBtn  = document.getElementById('cancelEditBtn');
    const editForm       = document.getElementById('editArsipForm');

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
                document.getElementById('detailKode').innerText    = this.dataset.kode;
                document.getElementById('detailJenis').innerText   = this.dataset.jenis;
                document.getElementById('detailJumlah').innerText  = this.dataset.jumlah + ' ekor';
                document.getElementById('detailUmur').innerText    = this.dataset.umur;
                document.getElementById('detailTujuan').innerText  = this.dataset.tujuan;
                document.getElementById('detailTanggal').innerText = this.dataset.tanggal;
                const statusEl = document.getElementById('detailStatus');
                statusEl.innerHTML = `<span class="status-badge ${this.dataset.statusClass}">${this.dataset.status}</span>`;
                toggleDetailModal();
            });
        });

        // Edit Buttons
        document.querySelectorAll('.btn-edit-arsip').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('editKodeInput').value   = this.dataset.kode;
                document.getElementById('editJenisInput').value  = this.dataset.jenis;
                document.getElementById('editJumlahInput').value = this.dataset.jumlah;
                document.getElementById('editUmurInput').value   = this.dataset.umur;
                document.getElementById('editTujuanInput').value = this.dataset.tujuan;
                document.getElementById('editTanggalInput').value = this.dataset.tanggal;
                document.getElementById('editStatusSelect').value = this.dataset.status;
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
    const statsContainer = document.getElementById('statsContainer');
    let typingTimer;
    let currentStatus = 'Semua';

    const performUpdate = () => {
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput.value);
        url.searchParams.set('status', currentStatus);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                listContainer.innerHTML = data.html;
                if(data.stats) statsContainer.innerHTML = data.stats;
                lucide.createIcons();
                bindDeleteConfirm();
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
                Swal.fire({ title: 'Hapus Data?', text: 'Data pembibitan ini akan dihapus secara permanen!', icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal', reverseButtons: true
                }).then(result => { if (result.isConfirmed) document.getElementById(this.dataset.form).submit(); });
            });
        });
    }
    bindDeleteConfirm();

    window.addEventListener('DOMContentLoaded', () => {
        if (new URLSearchParams(window.location.search).get('create') === 'true') {
            toggleCreateModal();
            window.history.replaceState({}, '', window.location.pathname);
        }
    });
</script>
@endpush
