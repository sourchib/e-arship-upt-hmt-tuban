@extends('layouts.app')

@section('title', 'Manajemen Dokumen - E-Arsip')

@section('content')

{{-- ====== Page Header ====== --}}
<div class="page-header">
    <div class="page-header-left">
        <h1>Manajemen Dokumen</h1>
        <p>Kelola semua dokumen arsip digital</p>
    </div>
    <div class="page-header-actions">
        <button type="button" class="btn btn-primary" id="openUploadModal">
            <i data-lucide="upload-cloud" style="width:16px;height:16px;"></i>
            Upload Dokumen
        </button>
    </div>
</div>

{{-- ====== Mini Stats ====== --}}
<div id="statsContainer">
    @include('dokumen._stats')
</div>

{{-- ====== Toolbar ====== --}}
<div class="toolbar">
    <div class="toolbar-search">
        <i data-lucide="search" class="search-icon"></i>
        <input type="text" id="searchInput" name="search" value="{{ request()->search }}" placeholder="Cari nama dokumen atau kategori...">
        <input type="hidden" id="kategoriInput" name="kategori" value="{{ request()->kategori ?? 'Semua' }}">
    </div>
    <div class="filter-tabs" id="filterTabs">
        @foreach($categories as $cat)
        <button class="filter-tab {{ ($cat == (request()->kategori ?? 'Semua')) ? 'active' : '' }}" data-kategori="{{ $cat }}">
            {{ $cat }}
        </button>
        @endforeach
    </div>
</div>

{{-- ====== Documents Grid ====== --}}
<div id="documentsGrid">
    @include('dokumen._grid')
</div>

{{-- ====== Upload Modal ====== --}}
<div class="modal-backdrop-custom" id="modalBackdrop"></div>
<div class="modal-container-custom" id="uploadModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <div>
                <h4>Upload Dokumen Baru</h4>
                <p>Tambahkan file ke arsip digital</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeUploadModal">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="modal-body-custom">
            <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Dokumen <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                           name="nama" value="{{ old('nama') }}"
                           placeholder="Contoh: Laporan Tahunan 2025" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori <span style="color:#dc2626">*</span></label>
                    <select class="form-control @error('kategori') is-invalid @enderror" name="kategori" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach($categories as $cat)
                            @if($cat != 'Semua')
                            <option value="{{ $cat }}" {{ old('kategori') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih File <span style="color:#dc2626">*</span></label>
                    <div class="drop-zone" id="dropZone">
                        <i data-lucide="upload-cloud"></i>
                        <p>Tarik file ke sini atau klik untuk memilih</p>
                        <small>Maks. 10MB (PDF, Excel, Docx, dsb)</small>
                        <input type="file" name="file" id="fileInput" required>
                    </div>
                    <div class="file-info" id="fileInfo">
                        <i data-lucide="check-circle"></i>
                        <span id="fileName">File dipilih</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi (Opsional)</label>
                    <textarea class="form-control" name="deskripsi" rows="3" placeholder="Tambahkan keterangan dokumen jika perlu"></textarea>
                </div>

                <div class="modal-footer-btns">
                    <button type="submit" class="btn-save-modal">Upload Sekarang</button>
                    <button type="button" class="btn-cancel-modal" id="cancelUploadModal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const openModalBtn   = document.getElementById('openUploadModal');
    const closeModalBtn  = document.getElementById('closeUploadModal');
    const cancelModalBtn = document.getElementById('cancelUploadModal');
    const modal          = document.getElementById('uploadModal');
    const backdrop       = document.getElementById('modalBackdrop');
    const fileInput      = document.getElementById('fileInput');
    const fileInfo       = document.getElementById('fileInfo');
    const fileName       = document.getElementById('fileName');

    const toggleModal = () => {
        modal.classList.toggle('show');
        backdrop.classList.toggle('show');
        document.body.style.overflow = modal.classList.contains('show') ? 'hidden' : '';
    };

    if(openModalBtn)   openModalBtn.addEventListener('click', toggleModal);
    if(closeModalBtn)  closeModalBtn.addEventListener('click', toggleModal);
    if(cancelModalBtn) cancelModalBtn.addEventListener('click', toggleModal);
    if(backdrop)       backdrop.addEventListener('click', toggleModal);

    fileInput.addEventListener('change', function() {
        if(this.files && this.files[0]) {
            fileName.textContent = this.files[0].name;
            fileInfo.classList.add('show');
        }
    });

    const searchInput    = document.getElementById('searchInput');
    const kategoriInput  = document.getElementById('kategoriInput');
    const documentsGrid  = document.getElementById('documentsGrid');
    const statsContainer = document.getElementById('statsContainer');
    let currentKategori  = '{{ request()->kategori ?? "Semua" }}';
    let typingTimer;

    const performUpdate = () => {
        const url = new URL(window.location.href);
        url.searchParams.set('kategori', currentKategori);
        url.searchParams.set('search', searchInput.value);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                documentsGrid.innerHTML = data.html;
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
            currentKategori = this.dataset.kategori;
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
                Swal.fire({ title: 'Hapus Dokumen?', text: 'Dokumen ini akan dihapus permanen dari server!', icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal', reverseButtons: true
                }).then(result => { if (result.isConfirmed) document.getElementById(this.dataset.form).submit(); });
            });
        });
    }
    bindDeleteConfirm();
</script>
@endpush
