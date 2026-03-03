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
        <button type="button" class="btn btn-primary" id="openCreateModal">
            <i data-lucide="plus" style="width:16px;height:16px;"></i>
            Buat Surat Keluar
        </button>
    </div>
</div>

{{-- ====== Toolbar ====== --}}
<div class="toolbar">
    <div class="toolbar-search">
        <i data-lucide="search" class="search-icon"></i>
        <input type="text" id="searchInput" placeholder="Cari nomor, perihal, atau tujuan...">
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
                    <div class="form-group">
                        <label class="form-label">Tanggal Surat <span style="color:#dc2626">*</span></label>
                        <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror"
                               name="tanggal_surat" value="{{ old('tanggal_surat') }}" required>
                        @error('tanggal_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Prioritas <span style="color:#dc2626">*</span></label>
                        <select class="form-control @error('prioritas') is-invalid @enderror" name="prioritas" required>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Rendah">Rendah</option>
                        </select>
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

@endsection

@push('scripts')
<script>
    const openModalBtn   = document.getElementById('openCreateModal');
    const closeModalBtn  = document.getElementById('closeCreateModal');
    const cancelModalBtn = document.getElementById('cancelCreateModal');
    const modal          = document.getElementById('createSuratModal');
    const backdrop       = document.getElementById('modalBackdrop');

    const toggleModal = () => {
        modal.classList.toggle('show');
        backdrop.classList.toggle('show');
        document.body.style.overflow = modal.classList.contains('show') ? 'hidden' : '';
    };

    if(openModalBtn)   openModalBtn.addEventListener('click', toggleModal);
    if(closeModalBtn)  closeModalBtn.addEventListener('click', toggleModal);
    if(cancelModalBtn) cancelModalBtn.addEventListener('click', toggleModal);
    if(backdrop)       backdrop.addEventListener('click', toggleModal);

    @if($errors->any()) toggleModal(); @endif

    const searchInput   = document.getElementById('searchInput');
    const listContainer = document.getElementById('listContainer');
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
                lucide.createIcons();
                bindDeleteConfirm();
                bindSendConfirm();
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
            toggleModal();
            window.history.replaceState({}, '', window.location.pathname);
        }
    });
</script>
@endpush
