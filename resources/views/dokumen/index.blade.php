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
            <form id="uploadForm" action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Dokumen <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control"
                           name="nama" id="input_nama"
                           placeholder="Contoh: Laporan Tahunan 2025" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori <span style="color:#dc2626">*</span></label>
                    <select class="form-control" name="kategori" id="input_kategori" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach($categories as $cat)
                            @if($cat != 'Semua')
                            <option value="{{ $cat }}">{{ $cat }}</option>
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
                    <textarea class="form-control" name="deskripsi" id="input_deskripsi" rows="3" placeholder="Tambahkan keterangan dokumen jika perlu"></textarea>
                </div>

                <div class="modal-footer-btns">
                    <button type="submit" class="btn-save-modal" id="btnSubmitUpload">Upload Sekarang</button>
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

    const uploadForm     = document.getElementById('uploadForm');
    const btnSubmit      = document.getElementById('btnSubmitUpload');

    const performUpdate = () => {
        const url = new URL(window.location.href);
        url.searchParams.set('kategori', currentKategori);
        url.searchParams.set('search', searchInput.value);
        url.searchParams.set('_v', Math.random()); // Even stronger anti-cache

        // Visual feedback
        documentsGrid.style.opacity = '0.5';

        fetch(url, { 
            method: 'GET',
            cache: 'no-store', // Important: don't use cache
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            } 
        })
            .then(r => r.json())
            .then(data => {
                if (data.html) {
                    documentsGrid.innerHTML = data.html;
                    if(data.stats) statsContainer.innerHTML = data.stats;
                    lucide.createIcons();
                    bindDeleteConfirm();
                    window.history.pushState({}, '', url);
                }
            })
            .catch(err => {
                console.error('Update Error:', err);
                // Fallback: full refresh if AJAX fails often
            })
            .finally(() => {
                documentsGrid.style.opacity = '1';
                documentsGrid.style.pointerEvents = 'all';
            });
    };

    if(uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            btnSubmit.disabled = true;
            btnSubmit.textContent = 'Sedang Mengupload...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire('Berhasil!', data.message, 'success');
                    toggleModal();
                    uploadForm.reset();
                    fileInfo.classList.remove('show');
                    performUpdate();
                } else {
                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan saat upload.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
            })
            .finally(() => {
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Upload Sekarang';
            });
        });
    }

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
            btn.onclick = async function(e) {
                e.preventDefault();
                const formId = this.dataset.form;
                const form = document.getElementById(formId);
                
                const result = await Swal.fire({ 
                    title: 'Hapus Dokumen?', 
                    text: 'Dokumen ini akan dihapus permanen dari server!', 
                    icon: 'warning',
                    showCancelButton: true, 
                    confirmButtonColor: '#dc2626', 
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, hapus!', 
                    cancelButtonText: 'Batal', 
                    reverseButtons: true
                });

                if (result.isConfirmed) {
                    const card = this.closest('.doc-card');
                    try {
                        // Optimistic UI: Hide immediately
                        if(card) card.style.display = 'none';

                        // Show minimal loading toast instead of blocking modal
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });

                        const formData = new FormData(form);
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: { 
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if(data.success) {
                            toast.fire({ icon: 'success', title: data.message });
                            performUpdate();
                        } else {
                            if(card) card.style.display = ''; // Show back if failed
                            Swal.fire('Gagal!', data.message || 'Gagal menghapus.', 'error');
                        }
                    } catch (err) {
                        console.error('Delete Error:', err);
                        if(card) card.style.display = '';
                        Swal.fire('Error!', 'Koneksi terputus atau terjadi kesalahan server.', 'error');
                    }
                }
            };
        });
    }
    bindDeleteConfirm();
</script>
@endpush
