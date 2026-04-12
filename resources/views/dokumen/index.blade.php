@extends('layouts.app')

@section('title', 'Manajemen Dokumen - E-Arsip')

@section('content')

{{-- ====== Page Header ====== --}}
<div class="page-header" style="flex-direction: column; align-items: flex-start; gap: 16px;">
    <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
        <div class="page-header-left">
            <h1>Manajemen Dokumen</h1>
            <p>Kelola semua dokumen arsip digital secara terpusat</p>
        </div>
        <div class="page-header-actions" style="display: flex; gap: 12px;">
            <button type="button" class="btn btn-primary" id="openUploadModal">
                <i data-lucide="folder-open" style="width:16px;height:16px;"></i>
                Buka Arsip
            </button>
        </div>
    </div>

    {{-- Metadata Detail Bar --}}
    <div class="dashboard-detail-bar" style="width: 100%; margin-bottom: 0;">
        <div class="detail-item">
            <span class="detail-label">Status Arsip:</span>
            <span class="detail-value" style="color: #16a34a;">Aktif</span>
        </div>
        <div class="detail-col-divider" style="width: 1px; height: 24px; background: #e2e8f0; margin-inline: 4px;"></div>
        <div class="detail-item">
            <span class="detail-label">Tanggal:</span>
            <span class="detail-value">{{ date('d M Y') }}</span>
        </div>
        <div class="detail-col-divider" style="width: 1px; height: 24px; background: #e2e8f0; margin-inline: 4px;"></div>
        <div class="detail-item">
            <span class="detail-label">Kode Unit:</span>
            <span class="detail-value">UPT-HMT</span>
        </div>
        <div class="detail-col-divider" style="width: 1px; height: 24px; background: #e2e8f0; margin-inline: 4px;"></div>
        <div class="detail-item">
            <span class="detail-label">Lokasi:</span>
            <span class="detail-value">Tuban, Jawa Timur</span>
        </div>
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
                <h4>Buka/Upload Arsip Baru</h4>
                <p>Tambahkan file ke arsip digital</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeUploadModal">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="modal-body-custom">
            <form id="uploadForm" action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-row-custom" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nama Dokumen <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control"
                               name="nama" id="input_nama"
                               placeholder="Contoh: Laporan Tahunan 2025" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kode Arsip / Nomor Surat</label>
                        <input type="text" class="form-control"
                               name="kode" id="input_kode"
                               placeholder="Contoh: 045/UPT-HMT/2025">
                    </div>
                </div>

                <div class="form-row-custom" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control" name="kategori" id="input_kategori" list="kategoriList" placeholder="Pilih yang ada atau ketik kategori baru..." required autocomplete="off">
                        <datalist id="kategoriList">
                            @foreach($categories as $cat)
                                @if($cat != 'Semua')
                                <option value="{{ $cat }}">
                                @endif
                            @endforeach
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Dokumen <span style="color:#dc2626">*</span></label>
                        <input type="date" class="form-control"
                               name="tanggal" id="input_tanggal"
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="form-row-custom" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Rak / Lokasi Penyimpanan <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control"
                               name="lokasi" id="input_lokasi"
                               placeholder="Contoh: Rak A1, Map Biru" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Masa Retensi</label>
                        <input type="text" class="form-control"
                               name="masa_retensi" id="input_masa_retensi"
                               placeholder="Contoh: 5 Tahun, Permanen">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label class="form-label">Sifat Arsip</label>
                            <select class="form-control" name="sifat_arsip" id="input_sifat_arsip">
                                <option value="Tidak Dirahasiakan">Tidak Dirahasiakan</option>
                                <option value="Dirahasiakan">Dirahasiakan</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Status Arsip <span style="color:#dc2626">*</span></label>
                            <select class="form-control" name="status" id="input_status" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Inaktif">Inaktif</option>
                            </select>
                        </div>

                    </div>
                </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih File <span style="color:#dc2626">*</span></label>
                    <div class="drop-zone" id="dropZone">
                        <i data-lucide="upload-cloud"></i>
                        <p>Tarik file ke sini atau klik untuk memilih</p>
                        <small>Semua Ekstensi File Diizinkan (Maks. 50MB)</small>
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

    const checkAdmin = (callback) => {
        @if(Auth::check() && Auth::user()->role === 'Admin')
            callback();
        @else
            window.location.href = "{{ route('login') }}";
        @endif
    };

    const toggleModal = () => {
        modal.classList.toggle('show');
        backdrop.classList.toggle('show');
        document.body.style.overflow = modal.classList.contains('show') ? 'hidden' : '';
    };




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
                    if (data.categories) {
                        const filterTabs = document.getElementById('filterTabs');
                        const inputKategori = document.getElementById('input_kategori');
                        
                        // Update Filter Tabs
                        let filterHtml = '';
                        data.categories.forEach(cat => {
                            filterHtml += `<button class="filter-tab ${ (cat == currentKategori) ? 'active' : '' }" data-kategori="${cat}">${cat}</button>`;
                        });
                        filterTabs.innerHTML = filterHtml;
                        
                        // Re-bind listeners for filter tabs
                        document.querySelectorAll('.filter-tab').forEach(tab => {
                            tab.onclick = function() {
                                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                                this.classList.add('active');
                                currentKategori = this.dataset.kategori;
                                performUpdate();
                            }
                        });

                        // Update Upload Datalist Option
                        const kategoriList = document.getElementById('kategoriList');
                        if (kategoriList) {
                            let selectHtml = '';
                            data.categories.forEach(cat => {
                                if (cat !== 'Semua') {
                                    selectHtml += `<option value="${cat}">`;
                                }
                            });
                            kategoriList.innerHTML = selectHtml;
                        }
                    }

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
            const isEdit = this.dataset.mode === 'edit';
            const method = isEdit ? 'POST' : 'POST'; // Both POST, but update uses _method: PUT
            
            if (isEdit) {
                formData.append('_method', 'PUT');
            }

            btnSubmit.disabled = true;
            btnSubmit.textContent = isEdit ? 'Menyimpan...' : 'Sedang Mengupload...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(async response => {
                const data = await response.json().catch(() => null);
                if (!response.ok) {
                    if (response.status === 422 && data && data.errors) {
                        throw { isValidationError: true, errors: data.errors, message: data.message };
                    }
                    throw new Error((data && data.message) ? data.message : 'Terjadi kesalahan sistem.');
                }
                return data;
            })
            .then(data => {
                if(data.success) {
                    Swal.fire('Berhasil!', data.message, 'success');
                    toggleModal();
                    uploadForm.reset();
                    fileInfo.classList.remove('show');
                    performUpdate();
                } else {
                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.isValidationError) {
                    let errorMessages = '';
                    for (const key in error.errors) {
                        errorMessages += error.errors[key][0] + '<br>';
                    }
                    Swal.fire('Peringatan!', errorMessages, 'warning');
                } else {
                    Swal.fire('Error!', error.message || 'Terjadi kesalahan sistem.', 'error');
                }
            })
            .finally(() => {
                btnSubmit.disabled = false;
                btnSubmit.textContent = isEdit ? 'Simpan Perubahan' : 'Upload Sekarang';
            });

        });
    }

    const editDoc = (id) => {
        checkAdmin(() => {
            fetch(`{{ url('dokumen') }}/${id}/edit`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(doc => {
                // Change Modal to Edit Mode
                document.querySelector('#uploadModal h4').textContent = 'Edit Dokumen';
                document.querySelector('#uploadModal p').textContent = 'Perbarui informasi dokumen';
                uploadForm.action = `{{ url('dokumen') }}/${id}`;
                uploadForm.dataset.mode = 'edit';
                btnSubmit.textContent = 'Simpan Perubahan';
                
                // Hide file input requirement
                fileInput.required = false;
                document.querySelector('.drop-zone small').textContent = 'Biarkan kosong jika tidak ingin mengganti file';

                // Fill Data
                document.getElementById('input_nama').value = doc.nama;
                document.getElementById('input_kode').value = doc.kode;
                document.getElementById('input_kategori').value = doc.kategori;
                document.getElementById('input_tanggal').value = doc.tanggal ? doc.tanggal.split('T')[0] : '';
                document.getElementById('input_lokasi').value = doc.lokasi;
                document.getElementById('input_masa_retensi').value = doc.masa_retensi;
                document.getElementById('input_sifat_arsip').value = doc.sifat_arsip || '';
                document.getElementById('input_status').value = doc.status || 'Aktif';
                document.getElementById('input_deskripsi').value = doc.deskripsi;

                toggleModal();
            });
        });
    };

    // Listen for Edit Button (Delegation for AJAX loaded content)
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-edit-dokumen');
        if (btn) {
            editDoc(btn.dataset.id);
        }
    });

    // Reset Modal on Open for Upload
    if(openModalBtn) {
        openModalBtn.addEventListener('click', () => {
            checkAdmin(() => {
                document.querySelector('#uploadModal h4').textContent = 'Buka/Upload Arsip Baru';
                document.querySelector('#uploadModal p').textContent = 'Tambahkan file ke arsip digital';
                uploadForm.action = "{{ route('dokumen.store') }}";
                uploadForm.dataset.mode = 'upload';
                btnSubmit.textContent = 'Upload Sekarang';
                fileInput.required = true;
                uploadForm.reset();
                fileInfo.classList.remove('show');
                toggleModal();
            });
        });
    }

    // Auto-open edit/create if from dashboard
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('edit')) {
        editDoc(urlParams.get('edit'));
    } else if(urlParams.get('create') === 'true') {
        if(openModalBtn) openModalBtn.click();
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
