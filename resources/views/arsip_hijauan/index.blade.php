@extends('layouts.app')

@section('title', 'Arsip Hijauan - E-Arsip')

@section('content')

{{-- ====== Page Header ====== --}}
<div class="page-header">
    <div class="page-header-left">
        <h1>Arsip Hijauan</h1>
        <p>Kelola data produksi hijauan makanan ternak</p>
    </div>
    <div class="page-header-actions">
        <button type="button" class="btn btn-primary" id="openCreateModal">
            <i data-lucide="plus" style="width:16px;height:16px;"></i>
            Tambah Data Hijauan
        </button>
    </div>
</div>

{{-- ====== Mini Stats ====== --}}
<div id="statsContainer">
    @include('arsip_hijauan._stats')
</div>

{{-- ====== Toolbar ====== --}}
<div class="toolbar">
    <div class="toolbar-search">
        <i data-lucide="search" class="search-icon"></i>
        <input type="text" id="searchInput" placeholder="Cari kode lahan, jenis hijauan, atau lokasi...">
    </div>
    <div class="filter-tabs">
        <button class="filter-tab active" data-status="Semua">Semua</button>
        <button class="filter-tab" data-status="Tersedia">Tersedia</button>
        <button class="filter-tab" data-status="Terdistribusi">Terdistribusi</button>
    </div>
</div>

{{-- ====== List Container ====== --}}
<div id="listContainer">
    @include('arsip_hijauan._list')
</div>

{{-- ====== Modal Create ====== --}}
<div class="modal-backdrop-custom" id="modalBackdrop"></div>
<div class="modal-container-custom" id="createArsipModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <div>
                <h4>Tambah Data Hijauan</h4>
                <p>Masukkan detail produksi hijauan baru</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeCreateModal">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="modal-body-custom">
            <form action="{{ route('arsip-hijauan.store') }}" method="POST">
                @csrf

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Kode Lahan <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control @error('kode_lahan') is-invalid @enderror"
                               name="kode_lahan" value="{{ old('kode_lahan') }}"
                               placeholder="Contoh: LHN-A01" required>
                        @error('kode_lahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Hijauan <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control @error('jenis_hijauan') is-invalid @enderror"
                               name="jenis_hijauan" value="{{ old('jenis_hijauan') }}"
                               placeholder="Contoh: Rumput Gajah" required>
                        @error('jenis_hijauan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Luas (Ha) <span style="color:#dc2626">*</span></label>
                        <input type="number" step="0.1" class="form-control @error('luas') is-invalid @enderror"
                               name="luas" value="{{ old('luas') }}" required min="0">
                        @error('luas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Produksi (Kg) <span style="color:#dc2626">*</span></label>
                        <input type="number" step="0.1" class="form-control @error('produksi') is-invalid @enderror"
                               name="produksi" value="{{ old('produksi') }}" required min="0">
                        @error('produksi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control @error('lokasi') is-invalid @enderror"
                           name="lokasi" value="{{ old('lokasi') }}"
                           placeholder="Contoh: Sidorejo Blok A" required>
                    @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal Panen <span style="color:#dc2626">*</span></label>
                        <input type="date" class="form-control @error('tanggal_panen') is-invalid @enderror"
                               name="tanggal_panen" value="{{ old('tanggal_panen', date('Y-m-d')) }}" required>
                        @error('tanggal_panen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span style="color:#dc2626">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                            <option value="Tersedia">Tersedia</option>
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

@endsection

@push('scripts')
<script>
    const openModalBtn   = document.getElementById('openCreateModal');
    const closeModalBtn  = document.getElementById('closeCreateModal');
    const cancelModalBtn = document.getElementById('cancelCreateModal');
    const modal          = document.getElementById('createArsipModal');
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
                Swal.fire({ title: 'Hapus Data?', text: 'Data hijauan ini akan dihapus secara permanen!', icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal', reverseButtons: true
                }).then(result => { if (result.isConfirmed) document.getElementById(this.dataset.form).submit(); });
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
