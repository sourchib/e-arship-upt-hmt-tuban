@extends('layouts.app')

@section('title', 'Manajemen Pengguna - E-Arsip')

@section('content')

{{-- ====== Page Header ====== --}}
<div class="page-header">
    <div class="page-header-left">
        <h1>Manajemen Pengguna</h1>
        <p>Kelola akses pengguna sistem E-Arsip</p>
    </div>
    <div class="page-header-actions">
        <button type="button" class="btn btn-primary" id="openCreateModal">
            <i data-lucide="user-plus" style="width:16px;height:16px;"></i>
            Tambah Pengguna
        </button>
    </div>
</div>

{{-- ====== Mini Stats ====== --}}
<div id="statsContainer">
    @include('users._stats')
</div>

{{-- ====== Toolbar ====== --}}
<div class="toolbar">
    <div class="toolbar-search">
        <i data-lucide="search" class="search-icon"></i>
        <input type="text" id="searchInput" placeholder="Cari nama, email, atau instansi...">
    </div>
    <div class="filter-tabs">
        @foreach(['Semua', 'Admin', 'Operator', 'Pimpinan'] as $role)
        <button class="filter-tab {{ $role == 'Semua' ? 'active' : '' }}" data-role="{{ $role }}">
            {{ $role }}
        </button>
        @endforeach
    </div>
</div>

{{-- ====== List Container ====== --}}
<div id="listContainer">
    @include('users._list')
</div>

{{-- ====== Modal Detail ====== --}}
<div class="modal-backdrop-custom" id="modalBackdrop"></div>
<div class="modal-container-custom" id="detailUserModal">
    <div class="modal-content-custom" style="max-width: 500px;">
        <div class="modal-header-custom">
            <div>
                <h4>Detail Pengguna</h4>
                <p>Informasi profil pengguna</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeDetailModal">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="modal-body-custom">
            <div style="display:flex;flex-direction:column;align-items:center;margin-bottom:24px;text-align:center;">
                <div id="detailAvatar" style="width:80px;height:80px;border-radius:50%;background:#10b981;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:32px;margin-bottom:12px;box-shadow:0 10px 15px -3px rgba(16, 185, 129, 0.2);"></div>
                <h4 id="detailNama" style="margin:0;font-size:18px;color:#0f172a;"></h4>
                <p id="detailEmail" style="margin:4px 0 0;color:#64748b;font-size:14px;"></p>
            </div>

            <div class="detail-row-modern border-top pt-3">
                <div class="detail-item">
                    <div class="detail-label-modern">Role</div>
                    <div class="detail-value-modern" id="detailRole"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label-modern">Status</div>
                    <div id="detailStatus"></div>
                </div>
            </div>

            <div class="detail-item full-width mt-3">
                <div class="detail-label-modern">Instansi</div>
                <div class="detail-value-modern" id="detailInstansi"></div>
            </div>

            <div class="detail-item full-width mt-3">
                <div class="detail-label-modern">Terdaftar Sejak</div>
                <div class="detail-value-modern" id="detailTanggal"></div>
            </div>

            <div class="modal-footer-btns" style="justify-content: flex-end;">
                <button type="button" class="btn-cancel-modal" id="cancelDetailModal" style="flex: none; min-width: 100px; padding: 10px 24px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ====== Modal Create/Edit ====== --}}
<div class="modal-container-custom" id="userModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <div>
                <h4 id="userModalTitle">Tambah Pengguna</h4>
                <p id="userModalSub">Masukkan detail akun untuk pengguna baru</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeUserModal">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="modal-body-custom">
            <form id="userForm" method="POST">
                @csrf
                <div id="formMethod"></div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control" name="nama" id="inputNama" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span style="color:#dc2626">*</span></label>
                    <input type="email" class="form-control" name="email" id="inputEmail" required>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Role <span style="color:#dc2626">*</span></label>
                        <select class="form-control" name="role" id="inputRole" required>
                            <option value="Operator">Operator</option>
                            <option value="Admin">Admin</option>
                            <option value="Pimpinan">Pimpinan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Instansi</label>
                        <input type="text" class="form-control" name="instansi" id="inputInstansi">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password <span id="pwdReqStar" style="color:#dc2626">*</span></label>
                    <input type="password" class="form-control" name="password" id="inputPassword">
                    <small class="text-muted" id="passwordHint" style="display:none;">Kosongkan jika tidak ingin mengubah password.</small>
                </div>

                <div class="modal-footer-btns">
                    <button type="submit" class="btn-save-modal" id="btnSubmitUser">Simpan</button>
                    <button type="button" class="btn-cancel-modal" id="cancelUserModal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .detail-row-modern { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .detail-label-modern { font-size: 13px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; }
    .detail-value-modern { font-size: 14px; color: var(--text-secondary); }
    .mt-3 { margin-top: 1rem; }
    .border-top { border-top: 1px solid #f1f5f9; }
    .pt-3 { padding-top: 1rem; }
</style>

@endsection

@push('scripts')
<script>
    // Elements
    const backdrop        = document.getElementById('modalBackdrop');
    
    // User Modal (Create/Edit)
    const modalUser       = document.getElementById('userModal');
    const userForm        = document.getElementById('userForm');
    const userTitle       = document.getElementById('userModalTitle');
    const userSub         = document.getElementById('userModalSub');
    const formMethod      = document.getElementById('formMethod');
    const openCreateBtn   = document.getElementById('openCreateModal');
    const closeUserBtn    = document.getElementById('closeUserModal');
    const cancelUserBtn   = document.getElementById('cancelUserModal');
    const passwordHint    = document.getElementById('passwordHint');
    const pwdReqStar      = document.getElementById('pwdReqStar');
    const inputPassword   = document.getElementById('inputPassword');

    const toggleUserModal = (mode = 'create') => {
        modalUser.classList.toggle('show');
        backdrop.classList.toggle('show');
        document.body.style.overflow = modalUser.classList.contains('show') ? 'hidden' : '';
        
        if (mode === 'create') {
            userTitle.innerText = 'Tambah Pengguna';
            userSub.innerText = 'Masukkan detail akun untuk pengguna baru';
            userForm.action = "{{ route('users.store') }}";
            formMethod.innerHTML = '';
            userForm.reset();
            passwordHint.style.display = 'none';
            pwdReqStar.style.display = 'inline';
            inputPassword.required = true;
        }
    };

    if(openCreateBtn)  openCreateBtn.addEventListener('click', () => toggleUserModal('create'));
    if(closeUserBtn)   closeUserBtn.addEventListener('click', () => toggleUserModal());
    if(cancelUserBtn)  cancelUserBtn.addEventListener('click', () => toggleUserModal());

    // Detail Modal
    const modalDetail     = document.getElementById('detailUserModal');
    const closeDetailBtn  = document.getElementById('closeDetailModal');
    const cancelDetailBtn = document.getElementById('cancelDetailModal');

    const toggleDetailModal = () => {
        modalDetail.classList.toggle('show');
        backdrop.classList.toggle('show');
        document.body.style.overflow = modalDetail.classList.contains('show') ? 'hidden' : '';
    };

    if(closeDetailBtn)  closeDetailBtn.addEventListener('click', toggleDetailModal);
    if(cancelDetailBtn) cancelDetailBtn.addEventListener('click', toggleDetailModal);

    // Global Backdrop
    if(backdrop) {
        backdrop.addEventListener('click', () => {
            modalUser.classList.remove('show');
            modalDetail.classList.remove('show');
            backdrop.classList.remove('show');
            document.body.style.overflow = '';
        });
    }

    const bindActionButtons = () => {
        // Detail Buttons
        document.querySelectorAll('.btn-view-detail').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('detailNama').innerText    = this.dataset.nama;
                document.getElementById('detailEmail').innerText   = this.dataset.email;
                document.getElementById('detailRole').innerText    = this.dataset.role;
                document.getElementById('detailInstansi').innerText = this.dataset.instansi;
                document.getElementById('detailTanggal').innerText = this.dataset.tanggal;
                document.getElementById('detailAvatar').innerText  = this.dataset.nama.charAt(0).toUpperCase();
                const statusEl = document.getElementById('detailStatus');
                statusEl.innerHTML = `<span class="status-badge ${this.dataset.statusClass}">${this.dataset.status}</span>`;
                toggleDetailModal();
            });
        });

        // Edit Buttons
        document.querySelectorAll('.btn-edit-user').forEach(btn => {
            btn.addEventListener('click', function() {
                toggleUserModal('edit');
                userTitle.innerText = 'Edit Pengguna';
                userSub.innerText = 'Perbarui informasi profil pengguna';
                userForm.action = this.dataset.url;
                formMethod.innerHTML = '@method("PUT")';
                
                document.getElementById('inputNama').value     = this.dataset.nama;
                document.getElementById('inputEmail').value    = this.dataset.email;
                document.getElementById('inputRole').value     = this.dataset.role;
                document.getElementById('inputInstansi').value = this.dataset.instansi;
                
                passwordHint.style.display = 'block';
                pwdReqStar.style.display = 'none';
                inputPassword.required = false;
            });
        });
    };

    bindActionButtons();

    // AJAX Logic
    const searchInput    = document.getElementById('searchInput');
    const filterTabs     = document.querySelectorAll('.filter-tab');
    const listContainer  = document.getElementById('listContainer');
    const statsContainer = document.getElementById('statsContainer');
    let activityRole = 'Semua';
    let typingTimer;

    const performUpdate = () => {
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput.value);
        url.searchParams.set('role', activityRole);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                listContainer.innerHTML  = data.html;
                if(data.stats) statsContainer.innerHTML = data.stats;
                lucide.createIcons();
                bindDeleteConfirm();
                bindActionButtons(); // Re-bind
                window.history.pushState({}, '', url);
            });
    };

    if(searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performUpdate, 300);
        });
    }

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            activityRole = this.dataset.role;
            performUpdate();
        });
    });

    function bindDeleteConfirm() {
        document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
            btn.addEventListener('click', function() {
                Swal.fire({ title: 'Apakah Anda yakin?', text: 'Data yang dihapus tidak dapat dikembalikan!', icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal', reverseButtons: true
                }).then(result => { if (result.isConfirmed) document.getElementById(this.dataset.form).submit(); });
            });
        });
    }
    bindDeleteConfirm();
</script>
@endpush
