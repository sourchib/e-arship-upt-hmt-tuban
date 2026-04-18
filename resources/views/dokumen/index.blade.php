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
        <div class="page-header-actions" style="display: flex; gap: 8px; align-items: center;">
            <div class="toolbar-search" style="flex: 1; min-width: 200px; max-width: 320px;">
                <i data-lucide="search" class="search-icon"></i>
                <input type="text" id="searchInput" name="search" value="{{ request()->search }}" placeholder="Cari dokumen..." style="padding: 10px 16px 10px 42px; border-radius: 12px; height: 44px;">
            </div>

            <div class="sort-dropdown">
                <button type="button" class="btn-sort" id="mainFilterBtn" title="Urutkan & Filter" style="background: #fff; border: 1.5px solid #e2e8f0; width: 44px; height: 44px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 12px; color: #64748b;">
                    <i data-lucide="filter" style="width:20px;height:20px;"></i>
                </button>
                <div class="filter-sidebar" id="mainFilterMenu">
                    <div class="filter-sidebar-header">
                        <span style="font-size: 15px; font-weight: 800; color: #0f172a;">Filter Dokumen</span>
                        <button type="button" id="closeFilterSidebar" style="background:none; border:none; color:#64748b; cursor:pointer;">
                            <i data-lucide="x" style="width:20px;height:20px;"></i>
                        </button>
                    </div>
                    
                    <div style="padding: 20px; flex: 1; overflow-y: auto;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Aksi Cepat</span>
                            <span id="resetFilter" style="font-size: 11px; color: #ef4444; font-weight: 700; cursor: pointer;">Reset Semua</span>
                        </div>

                        <div class="filter-section">
                            <label class="filter-label">Urutkan Berdasarkan</label>
                            <div class="filter-options-grid">
                                <div class="filter-opt-box {{ (request('sort') == 'latest' || !request('sort')) ? 'active' : '' }}" data-type="sort" data-value="latest">
                                    <i data-lucide="clock"></i> Terbaru 
                                </div>
                                <div class="filter-opt-box {{ request('sort') == 'oldest' ? 'active' : '' }}" data-type="sort" data-value="oldest">
                                    <i data-lucide="history"></i> Terlama
                                </div>
                                <div class="filter-opt-box {{ request('sort') == 'no_asc' ? 'active' : '' }}" data-type="sort" data-value="no_asc">
                                    <i data-lucide="hash"></i> No Dok
                                </div>
                                <div class="filter-opt-box {{ request('sort') == 'kode_asc' ? 'active' : '' }}" data-type="sort" data-value="kode_asc">
                                    <i data-lucide="barcode"></i> Kode
                                </div>
                                <div class="filter-opt-box {{ request('sort') == 'name_asc' ? 'active' : '' }}" data-type="sort" data-value="name_asc">
                                    <i data-lucide="sort-asc"></i> A-Z
                                </div>
                                <div class="filter-opt-box {{ request('sort') == 'name_desc' ? 'active' : '' }}" data-type="sort" data-value="name_desc">
                                    <i data-lucide="sort-desc"></i> Z-A
                                </div>
                            </div>
                        </div>

                        <div class="filter-section" style="margin-top: 24px;">
                            <label class="filter-label">Kategori Dokumen</label>
                            <div class="filter-list">
                                <div class="filter-list-item {{ (request('kategori') == 'Semua' || !request('kategori')) ? 'active' : '' }}" data-cat="Semua">
                                    <i data-lucide="layers"></i> Semua Kategori
                                </div>
                                @foreach($categories as $cat)
                                    @if($cat != 'Semua')
                                    <div class="filter-list-item {{ request('kategori') == $cat ? 'active' : '' }}" data-cat="{{ $cat }}">
                                        <i data-lucide="tag" style="width:14px; height:14px;"></i> {{ $cat }}
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="filter-sidebar-footer">
                        <button type="button" class="btn btn-primary" id="applyFilterBtn" style="width: 100%; justify-content: center; height: 48px;">Terapkan Filter</button>
                    </div>
                </div>
            </div>
            
            <input type="hidden" id="sortValue" name="sort" value="{{ request('sort', 'latest') }}">
            <input type="hidden" id="kategoriInput" name="kategori" value="{{ request('kategori', 'Semua') }}">
            <input type="hidden" id="folderFilterInput" name="folder_id" value="{{ request('folder_id') }}">
            <input type="hidden" id="parentIdInput" name="parent_id" value="{{ request('parent_id') }}">

            @if(Auth::check() && Auth::user()->role === 'Admin')
            <button type="button" class="btn" id="openFolderModal" style="padding: 0 16px; height: 44px; border-radius: 12px; font-weight: 700; gap: 6px; background: #fff; border: 1.5px solid #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">
                <i data-lucide="folder-plus" style="width:18px;height:18px;"></i>
                <span class="d-none d-sm-inline">Folder Baru</span>
            </button>
            <button type="button" class="btn btn-primary" id="openUploadModal" style="padding: 0 16px; height: 44px; border-radius: 12px; font-weight: 700; gap: 6px; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="plus" style="width:18px;height:18px;"></i>
                <span>Baru</span>
            </button>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 0 16px; height: 44px; border-radius: 12px; font-weight: 700; gap: 6px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                <i data-lucide="log-in" style="width:18px;height:18px;"></i>
                <span>Login Admin</span>
            </a>
            @endif
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
<div class="toolbar" style="margin-top: 16px; display: none;">
    <input type="hidden" id="kategoriInput_old" name="kategori_old" value="{{ request()->kategori ?? 'Semua' }}">
</div>

{{-- ====== Folder Explorer Grid ====== --}}
<div id="folderExplorerContainer">
    <div class="folder-grid-container" style="margin-top: 24px; margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <h5 style="margin: 0; font-weight: 700; color: #1e293b; font-size: 16px;">Folder Dokumen</h5>
                <div style="width: 1px; height: 16px; background: #e2e8f0; margin: 0 4px;"></div>
                {{-- Breadcrumbs --}}
                <div id="breadcrumbNav" style="display: flex; align-items: center; gap: 4px; font-size: 13px; color: #64748b;">
                    <a href="javascript:void(0)" class="breadcrumb-item" data-id="" style="color: #3b82f6; font-weight: 600; text-decoration: none;">Utama</a>
                    @foreach($breadcrumbs ?? [] as $bc)
                        <i data-lucide="chevron-right" style="width: 14px; height: 14px; color: #cbd5e1;"></i>
                        <a href="javascript:void(0)" class="breadcrumb-item" data-id="{{ $bc['id'] }}" style="color: #3b82f6; font-weight: 600; text-decoration: none;">{{ $bc['nama'] }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="folder-grid" id="folderGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px;">
            {{-- All Documents Card --}}
            <div class="folder-card folder-filter-item {{ !request('parent_id') ? 'active' : '' }}" data-id="" data-nama="Semua" style="background: #fff; border: 1px solid {{ !request('parent_id') ? '#3b82f6' : '#f1f5f9' }}; border-radius: 16px; padding: 16px; box-shadow: var(--card-shadow); transition: all 0.2s; display: flex; align-items: center; gap: 12px; cursor: pointer;">
                <div style="width: 40px; height: 40px; background: #eff6ff; color: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="files" style="width: 20px; height: 20px;"></i>
                </div>
                <div style="flex: 1;">
                    <div style="font-size: 14px; font-weight: 700; color: #1e293b;">Utama</div>
                    <div style="font-size: 11px; color: #94a3b8;">{{ $totalDokumen }} Dokumen</div>
                </div>
            </div>

            {{-- Add Folder Card --}}
            @if(Auth::check() && Auth::user()->role === 'Admin')
            <div class="folder-card add-card" id="gridOpenFolderModal" style="background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; height: 100%;">
                <div style="width: 44px; height: 44px; background: #fff; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <i data-lucide="plus" style="width: 20px; height: 20px;"></i>
                </div>
                <span style="font-size: 13px; font-weight: 700; color: #64748b;">Tambah Folder</span>
            </div>
            @endif

            {{-- Dynamic Folder Cards removed for simplicity and responsiveness as requested --}}
        </div>
    </div>
</div>

{{-- ====== Documents Table ====== --}}
<div id="documentsGrid">
    @include('dokumen._table')
</div>

<style>
    .folder-card:hover {
        transform: translateY(-4px);
        border-color: #3b82f6;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }
    .folder-card.add-card:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #3b82f6;
    }
    .folder-card.add-card:hover div {
        color: #3b82f6;
    }
</style>

{{-- ====== Upload Modal ====== --}}
<div class="modal-backdrop-custom" id="modalBackdrop"></div>
<div class="modal-container-custom" id="uploadModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <div>
                <h4 id="uploadModalTitle">Upload Arsip Baru</h4>
                <p>Tambahkan file ke arsip digital</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeUploadModal">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="modal-body-custom">
            <form id="uploadForm" action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodPlaceholder"></div>

                <div class="form-row-custom" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nama Dokumen <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control" name="nama" id="input_nama" placeholder="Contoh: Laporan Tahunan 2025" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kode Arsip / Nomor Surat</label>
                        <input type="text" class="form-control" name="kode" id="input_kode" placeholder="Contoh: 045/UPT-HMT/2025">
                    </div>
                </div>

                <div class="form-row-custom" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Kategori <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control" name="kategori" id="input_kategori" list="kategoriList" placeholder="Pilih atau ketik kategori..." required autocomplete="off">
                        <datalist id="kategoriList">
                            @foreach($categories as $cat)
                                @if($cat != 'Semua')
                                <option value="{{ $cat }}">
                                @endif
                            @endforeach
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Folder / Kelompok</label>
                        <select class="form-control" name="folder_id" id="input_folder_id">
                            <option value="">(Tanpa Folder / Utama)</option>
                            @foreach($flatFolders as $folder)
                            <option value="{{ $folder->id }}">{{ $folder->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row-custom" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal Dokumen <span style="color:#dc2626">*</span></label>
                        <input type="date" class="form-control" name="tanggal" id="input_tanggal" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Lokasi Penyimpanan <span style="color:#dc2626">*</span></label>
                        <input type="text" class="form-control" name="lokasi" id="input_lokasi" placeholder="Rak A1, Map Biru" required>
                    </div>
                </div>

                <div class="form-row-custom" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Masa Retensi</label>
                        <input type="text" class="form-control" name="masa_retensi" id="input_masa_retensi" placeholder="Contoh: 5 Tahun">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Arsip <span style="color:#dc2626">*</span></label>
                        <select class="form-control" name="status" id="input_status" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Inaktif">Inaktif</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Sifat Arsip</label>
                    <select class="form-control" name="sifat_arsip" id="input_sifat_arsip">
                        <option value="Biasa">Biasa / Umum</option>
                        <option value="Penting">Penting</option>
                        <option value="Dirahasiakan">Dirahasiakan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">File Dokumen <span id="fileRequiredStar" style="color:#dc2626">*</span></label>
                    <div class="drop-zone" id="dropZone">
                        <i data-lucide="upload-cloud"></i>
                        <p>Tarik file ke sini atau klik untuk memilih</p>
                        <small>PDF, JPG, PNG, dll (Maks. 50MB)</small>
                        <input type="file" name="file" id="fileInput">
                    </div>
                    <div class="file-info" id="fileInfo">
                        <i data-lucide="check-circle"></i>
                        <span id="fileName">File dipilih</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi (Opsional)</label>
                    <textarea class="form-control" name="deskripsi" id="input_deskripsi" rows="3" placeholder="Keterangan tambahan..."></textarea>
                </div>

                <div class="modal-footer-btns">
                    <button type="submit" class="btn-save-modal" id="btnSubmitUpload">Simpan Arsip</button>
                    <button type="button" class="btn-cancel-modal" id="cancelUploadModal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ====== Folder Modal ====== --}}
<div class="modal-container-custom" id="folderModal" style="z-index: 2200;">
    <div class="modal-content-custom" style="max-width: 400px;">
        <div class="modal-header-custom">
            <div>
                <h4 id="folderModalTitle">Buat Folder Baru</h4>
            </div>
            <button type="button" class="btn-close-custom" id="closeFolderModal">
                <i data-lucide="x"></i>
            </button>
        </div>
        <div class="modal-body-custom">
            <form id="folderForm">
            @csrf
            <div id="folderMethodPlaceholder"></div>
            <input type="hidden" id="folder_id_internal" name="id">
            <input type="hidden" id="folder_parent_id" name="parent_id">
            
            <div class="form-group">
                <label class="form-label">Nama Folder <span style="color:#dc2626">*</span></label>
                <input type="text" class="form-control" name="nama" id="folder_nama" placeholder="Contoh: Dokumen 2024" required>
            </div>
            
            <div class="modal-footer-btns">
                <button type="submit" class="btn-save-modal" id="btnSaveFolder">Simpan Folder</button>
                <button type="button" class="btn-cancel-modal" id="cancelFolderModal">Batal</button>
            </div>
        </form>
    </div>
</div>
</div>

{{-- ====== Move Multi Modal ====== --}}
<div class="modal-container-custom" id="moveModal" style="z-index: 2200;">
    <div class="modal-content-custom" style="max-width: 400px;">
        <div class="modal-header-custom">
            <div>
                <h4>Pindahkan ke Folder</h4>
            </div>
            <button type="button" class="btn-close-custom" id="closeMoveModal">
                <i data-lucide="x"></i>
            </button>
        </div>
        <div class="modal-body-custom">
            <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">Pilih folder tujuan untuk <span id="moveCountText">0</span> dokumen yang dipilih.</p>
            <form id="moveForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Folder Tujuan</label>
                    <select class="form-control" name="folder_id" id="move_folder_id">
                        <option value="">(Tanpa Folder / Utama)</option>
                        @foreach($flatFolders as $folder)
                        <option value="{{ $folder->id }}">{{ $folder->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="modal-footer-btns">
                    <button type="submit" class="btn-save-modal">Pindahkan Sekarang</button>
                    <button type="button" class="btn-cancel-modal" id="cancelMoveModal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ====== Bulk Action Toolbar ====== --}}
<div id="bulkToolbar" style="display:none; position:fixed; bottom:30px; left:50%; transform:translateX(-50%); background:#0f172a; color:#fff; padding:12px 24px; border-radius:100px; box-shadow:0 10px 30px rgba(0,0,0,0.3); display:flex; align-items:center; gap:20px; z-index:2000;">
    <div style="font-size:13px; font-weight:700;"><span id="bulkCount">0</span> Item Terpilih</div>
    <div style="width:1px; height:24px; background:rgba(255,255,255,0.2);"></div>
    <div style="display:flex; gap:12px;">
        <button type="button" id="bulkMoveBtn" class="btn" style="background:none; border:none; color:#fff; display:flex; align-items:center; gap:6px; font-size:13px; font-weight:600; cursor:pointer; padding:0;">
            <i data-lucide="move" style="width:16px; height:16px; color:#38bdf8;"></i> Pindah
        </button>
        <button type="button" id="bulkCopyBtn" class="btn" style="background:none; border:none; color:#fff; display:flex; align-items:center; gap:6px; font-size:13px; font-weight:600; cursor:pointer; padding:0;">
            <i data-lucide="copy" style="width:16px; height:16px; color:#c084fc;"></i> Salin
        </button>
        <button type="button" id="bulkCancelBtn" class="btn" style="background:none; border:none; color:rgba(255,255,255,0.6); font-size:13px; font-weight:600; cursor:pointer; padding:0;">
            Batal
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ====== Folder Management ======
    const openFolderModalBtn = document.getElementById('openFolderModal');
    const folderModal = document.getElementById('folderModal');
    const closeFolderModalBtn = document.getElementById('closeFolderModal');
    const cancelFolderModalBtn = document.getElementById('cancelFolderModal');
    const folderForm = document.getElementById('folderForm');
    
    const openModalBtn   = document.getElementById('openUploadModal');

    // Safe variable declarations for elements that only exist for Admin
    const modal         = document.getElementById('uploadModal');
    const backdrop      = document.getElementById('modalBackdrop');
    const closeModalBtn = document.getElementById('closeUploadModal');
    const cancelModalBtn= document.getElementById('cancelUploadModal');
    const fileInput     = document.getElementById('fileInput');
    const fileInfo      = document.getElementById('fileInfo');
    const fileName      = document.getElementById('fileName');
    const sortValueInput= document.getElementById('sortValue');
    const kategoriInput = document.getElementById('kategoriInput');
    const searchInput   = document.getElementById('searchInput');

    function openFolderModal(isEdit = false, id = '', nama = '', deskripsi = '') {
        document.getElementById('folderModalTitle').innerText = isEdit ? 'Ubah Folder' : 'Buat Folder Baru';
        document.getElementById('folderMethodPlaceholder').innerHTML = isEdit ? '<input type="hidden" name="_method" value="PUT">' : '';
        document.getElementById('folder_id_internal').value = id;
        document.getElementById('folder_nama').value = nama;
        
        // Handle nesting: if new folder, set parent_id from current navigation
        if (!isEdit) {
            document.getElementById('folder_parent_id').value = document.getElementById('parentIdInput').value;
        } else {
            document.getElementById('folder_parent_id').value = ''; // Don't change parent on rename for now
        }
        
        folderModal.classList.add('show');
        backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeFolderModal() {
        folderModal.classList.remove('show');
        if (!modal.classList.contains('show') && !moveModal.classList.contains('show')) {
            backdrop.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    if(openFolderModalBtn) openFolderModalBtn.onclick = () => checkAdmin(() => openFolderModal());
    if(document.getElementById('gridOpenFolderModal')) {
        document.getElementById('gridOpenFolderModal').onclick = () => checkAdmin(() => openFolderModal());
    }
    if(closeFolderModalBtn) closeFolderModalBtn.onclick = closeFolderModal;
    if(cancelFolderModalBtn) cancelFolderModalBtn.onclick = closeFolderModal;

    folderForm.onsubmit = function(e) {
        e.preventDefault();
        const id = document.getElementById('folder_id_internal').value;
        const url = id ? `/folders/${id}` : '/folders';
        const formData = new FormData(this);

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                closeFolderModal();
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                toast.fire({ icon: 'success', title: data.message }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
            }
        });
    };

    // Rename Folder
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-rename-folder');
        if(btn) {
            checkAdmin(() => {
                openFolderModal(true, btn.dataset.id, btn.dataset.nama, '');
            });
        }
    });

    // Delete Folder
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-delete-folder');
        if(btn) {
            checkAdmin(() => {
                Swal.fire({
                    title: 'Hapus Folder?',
                    text: "Seluruh dokumen di dalamnya akan kehilangan label foldernya (tidak dihapus).",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/folders/${btn.dataset.id}`, {
                            method: 'DELETE',
                            headers: { 
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if(data.success) {
                                Swal.fire('Dihapus!', data.message, 'success').then(() => window.location.reload());
                            }
                        });
                    }
                });
            });
        }
    });

    // ====== Bulk Actions & Selection ======
    const bulkToolbar = document.getElementById('bulkToolbar');
    const bulkCountText = document.getElementById('bulkCount');
    const bulkCancelBtn = document.getElementById('bulkCancelBtn');
    const bulkMoveBtn = document.getElementById('bulkMoveBtn');
    const moveModal = document.getElementById('moveModal');
    const moveForm = document.getElementById('moveForm');

    function updateBulkUI() {
        const selected = document.querySelectorAll('.doc-checkbox:checked, .folder-checkbox:checked');
        const count = selected.length;
        bulkCountText.innerText = count;
        bulkToolbar.style.display = count > 0 ? 'flex' : 'none';
        lucide.createIcons();
    }

    document.addEventListener('change', function(e) {
        if(e.target.id === 'selectAllItems') {
            const isChecked = e.target.checked;
            document.querySelectorAll('.doc-checkbox, .folder-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            updateBulkUI();
        }
        if(e.target.classList.contains('doc-checkbox') || e.target.classList.contains('folder-checkbox')) {
            updateBulkUI();
        }
    });

    if(bulkCancelBtn) {
        bulkCancelBtn.onclick = () => {
            document.querySelectorAll('.doc-checkbox:checked, .folder-checkbox:checked').forEach(cb => cb.checked = false);
            updateBulkUI();
        };
    }

    function openSelectionModal(mode = 'move') {
        const selectedDocs = Array.from(document.querySelectorAll('.doc-checkbox:checked')).map(cb => cb.dataset.id);
        if(selectedDocs.length === 0) {
            Swal.fire('Info', 'Pilih minimal satu dokumen.', 'info');
            return;
        }
        
        const title = mode === 'move' ? 'Pindahkan ke Folder' : 'Salin ke Folder';
        const countText = mode === 'move' ? 'dokumen yang akan dipindah' : 'dokumen yang akan disalin';
        const btnText = mode === 'move' ? 'Pindahkan Sekarang' : 'Salin Sekarang';
        
        document.querySelector('#moveModal h4').innerText = title;
        document.getElementById('moveCountText').innerText = selectedDocs.length + ' ' + countText;
        document.querySelector('#moveModal .btn-save-modal').innerText = btnText;
        document.getElementById('moveForm').dataset.mode = mode;
        
        moveModal.classList.add('show');
        backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeMoveModal() {
        moveModal.classList.remove('show');
        if (!modal.classList.contains('show') && !folderModal.classList.contains('show')) {
            backdrop.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    if(bulkMoveBtn) bulkMoveBtn.onclick = () => checkAdmin(() => openSelectionModal('move'));
    if(document.getElementById('bulkCopyBtn')) document.getElementById('bulkCopyBtn').onclick = () => checkAdmin(() => openSelectionModal('copy'));
    
    document.getElementById('closeMoveModal').onclick = closeMoveModal;
    document.getElementById('cancelMoveModal').onclick = closeMoveModal;

    moveForm.onsubmit = function(e) {
        e.preventDefault();
        const mode = this.dataset.mode || 'move';
        const selectedDocs = Array.from(document.querySelectorAll('.doc-checkbox:checked')).map(cb => cb.dataset.id);
        const folderId = document.getElementById('move_folder_id').value;
        const url = mode === 'move' ? '{{ route("folders.move-documents") }}' : '{{ route("folders.copy-documents") }}';

        const toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        fetch(url, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                document_ids: selectedDocs,
                folder_id: folderId
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                closeMoveModal();
                toast.fire({ icon: 'success', title: data.message }).then(() => {
                    window.location.reload();
                });
                
                // Clear selections
                document.querySelectorAll('.doc-checkbox:checked').forEach(cb => cb.checked = false);
                if(typeof updateBulkToolbar === 'function') updateBulkToolbar();
            } else {
                Swal.fire('Gagal!', data.message, 'error');
            }
        });
    };

    // Folder Navigation logic
    document.addEventListener('click', function(e) {
        // Folder Card Click (Grid) OR Folder Row Click (Table)
        const folderItem = e.target.closest('.folder-filter-item, .folder-row');
        
        if(folderItem && !e.target.closest('.folder-actions-dropdown') && !e.target.closest('.action-btn-table') && !e.target.closest('input')) {
            // Priority: data-id attribute on the element itself
            let folderId = folderItem.getAttribute('data-id');
            
            // Fallback for table rows missing data-id but having a checkbox
            if (folderId === null || folderId === undefined) {
                const cb = folderItem.querySelector('.folder-checkbox');
                if (cb) folderId = cb.getAttribute('data-id');
            }

            if (folderId !== null && folderId !== undefined) {
                document.getElementById('parentIdInput').value = folderId;
                document.getElementById('folderFilterInput').value = folderId; 
                performUpdate();
            }
        }
        // Breadcrumb Click
        const bcItem = e.target.closest('.breadcrumb-item');
        if(bcItem) {
            document.getElementById('parentIdInput').value = bcItem.dataset.id;
            document.getElementById('folderFilterInput').value = bcItem.dataset.id;
            performUpdate();
        }
    });
    const checkAdmin = (callback) => {
        // Restricted to Admin only per user request
        @if(Auth::check() && Auth::user()->role === 'Admin')
            callback();
        @else
            Swal.fire('Akses Dibatasi', 'Anda tidak memiliki izin untuk mengelola data.', 'warning');
        @endif
    };

    const toggleModal = () => {
        modal.classList.toggle('show');
        backdrop.classList.toggle('show');
        document.body.style.overflow = modal.classList.contains('show') ? 'hidden' : '';
    };




    if(closeModalBtn)  closeModalBtn.addEventListener('click', toggleModal);
    if(cancelModalBtn) cancelModalBtn.addEventListener('click', toggleModal);
    if(backdrop)       backdrop.addEventListener('click', () => {
        // Only close uploadModal if it's currently open (not folder/move modal)
        if(modal && modal.classList.contains('show')) toggleModal();
    });

    if(fileInput) fileInput.addEventListener('change', function() {
        if(this.files && this.files[0]) {
            if(fileName) fileName.textContent = this.files[0].name;
            if(fileInfo) fileInfo.classList.add('show');
        }
    });

    const mainFilterBtn     = document.getElementById('mainFilterBtn');
    const mainFilterMenu    = document.getElementById('mainFilterMenu');
    const closeFilterSidebar = document.getElementById('closeFilterSidebar');
    const filterOverlay     = document.getElementById('filterOverlay');
    const applyFilterBtn    = document.getElementById('applyFilterBtn');
    const resetFilter       = document.getElementById('resetFilter');

    const documentsGrid  = document.getElementById('documentsGrid');
    const statsContainer = document.getElementById('statsContainer');
    let currentKategori  = '{{ request()->kategori ?? "Semua" }}';
    let typingTimer;

    const uploadForm     = document.getElementById('uploadForm');
    const btnSubmit      = document.getElementById('btnSubmitUpload');

    const performUpdate = () => {
        const url = new URL(window.location.href);
        const parentId = document.getElementById('parentIdInput').value;
        const searchVal = searchInput.value.trim();
        const sortVal = sortValueInput.value;
        const catVal = currentKategori;
        
        url.searchParams.set('kategori', catVal);
        url.searchParams.set('search', searchVal);
        url.searchParams.set('sort', sortVal);
        url.searchParams.set('_v', Date.now()); // Anti-cache
        if (parentId) url.searchParams.set('parent_id', parentId);
        else url.searchParams.delete('parent_id');

        // Visual feedback
        documentsGrid.style.opacity = '0.5';

        fetch(url, { 
            method: 'GET',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            } 
        })
            .then(r => r.json())
            .then(data => {
                // 1. Update Documents Table
                if (data.html) {
                    documentsGrid.innerHTML = data.html;
                }

                // 2. Update Stats
                if (data.stats) {
                    statsContainer.innerHTML = data.stats;
                }

                // 3. Update Breadcrumbs (Dynamic BC)
                if (data.breadcrumbs) {
                    const bcNav = document.getElementById('breadcrumbNav');
                    if(bcNav) {
                        let bcHtml = '<a href="javascript:void(0)" class="breadcrumb-item" data-id="" style="color: #3b82f6; font-weight: 600; text-decoration: none;">Utama</a>';
                        data.breadcrumbs.forEach(bc => {
                            bcHtml += `<i data-lucide="chevron-right" style="width: 14px; height: 14px; color: #cbd5e1;"></i>
                                       <a href="javascript:void(0)" class="breadcrumb-item" data-id="${bc.id}" style="color: #3b82f6; font-weight: 600; text-decoration: none;">${bc.nama}</a>`;
                        });
                        bcNav.innerHTML = bcHtml;
                    }
                }

                // 4. Update Folder Grid (DYNAMIC FOLDER RE-RENDERING)
                if (data.folders) {
                    const grid = document.getElementById('folderGrid');
                    if(grid) {
                        let gridHtml = '';
                        
                        // Root/Utama Card
                        const isRoot = !data.currentFolder;
                        gridHtml += `
                            <div class="folder-card folder-filter-item ${isRoot ? 'active' : ''}" data-id="" data-nama="Semua" style="background: #fff; border: 1px solid ${isRoot ? '#3b82f6' : '#f1f5f9'}; border-radius: 16px; padding: 16px; box-shadow: var(--card-shadow); transition: all 0.2s; display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                <div style="width: 40px; height: 40px; background: #eff6ff; color: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i data-lucide="files" style="width: 20px; height: 20px;"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-size: 14px; font-weight: 700; color: #1e293b;">Utama</div>
                                    <div style="font-size: 11px; color: #94a3b8;">${data.totalDokumen ?? '...'} Dokumen</div>
                                </div>
                            </div>`;

                        // Add Folder Card (Always keep)
                        @if(Auth::check() && Auth::user()->role === 'Admin')
                        gridHtml += `
                            <div class="folder-card add-card" id="gridOpenFolderModal" style="background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; height: 100%;">
                                <div style="width: 44px; height: 44px; background: #fff; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                                    <i data-lucide="plus" style="width: 20px; height: 20px;"></i>
                                </div>
                                <span style="font-size: 13px; font-weight: 700; color: #64748b;">Tambah Folder</span>
                            </div>`;
                        @endif

                        // Dynamic Folder Items removed for responsiveness per user request
                        grid.innerHTML = gridHtml;

                        // Re-bind the Add Folder Grid button
                        const newAddBtn = document.getElementById('gridOpenFolderModal');
                        if(newAddBtn) newAddBtn.onclick = () => checkAdmin(() => openFolderModal());
                    }
                }

                // 5. Update Categories
                if (data.categories) {
                    const filterList = mainFilterMenu.querySelector('.filter-list');
                    if (filterList) {
                        const currentCat = kategoriInput.value;
                        let contentHtml = `<div class="filter-list-item ${currentCat == 'Semua' ? 'active' : ''}" data-cat="Semua"><i data-lucide="layers"></i> Semua Kategori</div>`;
                        data.categories.forEach(cat => {
                            if (cat !== 'Semua') {
                                contentHtml += `<div class="filter-list-item ${currentCat == cat ? 'active' : ''}" data-cat="${cat}"><i data-lucide="tag" style="width:14px; height:14px;"></i> ${cat}</div>`;
                            }
                        });
                        filterList.innerHTML = contentHtml;
                        bindCategoryEvents();
                    }
                }

                // 6. Final UI bindings
                bindSortableHeaders();
                bindDeleteConfirm();
                lucide.createIcons();
                window.history.pushState({}, '', url);
            })
            .catch(err => console.error('Update Error:', err))
            .finally(() => {
                documentsGrid.style.opacity = '1';
                documentsGrid.style.pointerEvents = 'all';
            });
    };

    function bindSortableHeaders() {
        document.querySelectorAll('.sortable-header').forEach(header => {
            header.onclick = function() {
                const sortKey = this.dataset.sort;
                sortValueInput.value = sortKey;
                
                // Visual feedback in dropdown too
                document.querySelectorAll('.sort-item').forEach(i => {
                    i.classList.toggle('active', i.dataset.value === sortKey);
                });
                
                performUpdate();
            };
        });
    }
    bindSortableHeaders();

    if(uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const isEdit = this.dataset.mode === 'edit';
            btnSubmit.disabled = true;
            btnSubmit.textContent = isEdit ? 'Menyimpan...' : 'Sedang Mengupload...';

            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    toggleModal();
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    toast.fire({ icon: 'success', title: data.message }).then(() => {
                        window.location.reload();
                    });
                } else {
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = isEdit ? 'Simpan Perubahan' : 'Simpan Arsip';
                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(err => {
                btnSubmit.disabled = false;
                btnSubmit.textContent = isEdit ? 'Simpan Perubahan' : 'Simpan Arsip';
                console.error('Upload error:', err);
                Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
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
                
                // Add method PUT for Laravel
                const methodPlaceholder = document.getElementById('methodPlaceholder');
                if (methodPlaceholder) {
                    methodPlaceholder.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                }
                
                // Hide file input requirement
                fileInput.required = false;
                document.querySelector('.drop-zone small').textContent = 'Biarkan kosong jika tidak ingin mengganti file';

                // Fill Data
                document.getElementById('input_nama').value = doc.nama || '';
                document.getElementById('input_kode').value = doc.kode || '';
                document.getElementById('input_kategori').value = doc.kategori || '';
                
                const folderSelect = document.getElementById('input_folder_id');
                if(folderSelect) {
                    folderSelect.value = doc.folder_id || '';
                }
                
                document.getElementById('input_tanggal').value = doc.tanggal ? doc.tanggal.split('T')[0] : '';
                document.getElementById('input_lokasi').value = doc.lokasi || '';
                document.getElementById('input_masa_retensi').value = doc.masa_retensi || '';
                document.getElementById('input_sifat_arsip').value = doc.sifat_arsip || 'Biasa';
                document.getElementById('input_status').value = doc.status || 'Aktif';
                document.getElementById('input_deskripsi').value = doc.deskripsi || '';

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
                // Change Modal to Upload Mode
                document.querySelector('#uploadModal h4').textContent = 'Upload Arsip Baru';
                document.querySelector('#uploadModal p').textContent = 'Tambahkan file ke arsip digital';
                uploadForm.action = "{{ route('dokumen.store') }}";
                uploadForm.dataset.mode = 'upload';
                btnSubmit.textContent = 'Upload Sekarang';
                
                // Clear method PUT if exists
                const methodPlaceholder = document.getElementById('methodPlaceholder');
                if (methodPlaceholder) methodPlaceholder.innerHTML = '';
                
                fileInput.required = true;
                uploadForm.reset();
                fileInfo.classList.remove('show');
                
                // Default values for "New" (Make it "Tidak Kosong")
                
                // 1. Default date to today (ensure it's set even if reset() cleared it)
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('input_tanggal').value = today;

                // 2. Default folder selection to current active folder
                const curFolderId = document.getElementById('parentIdInput').value;
                const folderSelect = document.getElementById('input_folder_id');
                if(folderSelect && curFolderId) {
                    folderSelect.value = curFolderId;
                }

                // 3. Default category from current filter
                const curKategori = document.getElementById('kategoriInput').value;
                if(curKategori && curKategori !== 'Semua') {
                    document.getElementById('input_kategori').value = curKategori;
                }

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

    function toggleFilterSidebar(show) {
        if(show) {
            mainFilterMenu.classList.add('show');
            filterOverlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
        } else {
            mainFilterMenu.classList.remove('show');
            filterOverlay.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    if(mainFilterBtn) {
        mainFilterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleFilterSidebar(!mainFilterMenu.classList.contains('show'));
        });
    }

    if(closeFilterSidebar) closeFilterSidebar.onclick = () => toggleFilterSidebar(false);
    if(filterOverlay) filterOverlay.onclick = () => toggleFilterSidebar(false);
    if(applyFilterBtn) applyFilterBtn.onclick = () => toggleFilterSidebar(false);

    if(resetFilter) {
        resetFilter.onclick = () => {
            sortValueInput.value = 'latest';
            kategoriInput.value = 'Semua';
            currentKategori = 'Semua';
            searchInput.value = '';
            
            // Visual reset
            document.querySelectorAll('.filter-opt-box').forEach(i => i.classList.remove('active'));
            document.querySelectorAll('.filter-list-item').forEach(i => i.classList.remove('active'));
            
            const latestBtn = document.querySelector('.filter-opt-box[data-value="latest"]');
            const allCatBtn = document.querySelector('.filter-list-item[data-cat="Semua"]');
            if(latestBtn) latestBtn.classList.add('active');
            if(allCatBtn) allCatBtn.classList.add('active');

            // URL reset
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            url.searchParams.delete('sort');
            url.searchParams.delete('kategori');
            window.history.pushState({}, '', url);

            toggleFilterSidebar(false);
            performUpdate();
        };
    }

    function bindCategoryEvents() {
        document.querySelectorAll('.filter-opt-box').forEach(item => {
            item.onclick = function() {
                document.querySelectorAll('.filter-opt-box').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                sortValueInput.value = this.dataset.value;
                performUpdate();
            };
        });

        document.querySelectorAll('.filter-list-item').forEach(item => {
            item.onclick = function() {
                document.querySelectorAll('.filter-list-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                kategoriInput.value = this.dataset.cat;
                currentKategori = this.dataset.cat;
                performUpdate();
            };
        });
    }
    bindCategoryEvents();

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
                            toast.fire({ icon: 'success', title: data.message }).then(() => {
                                window.location.reload();
                            });
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

    // Auto-open if create=true parameter exists (Must be at the bottom after listeners are attached)
    const finalUrlParams = new URLSearchParams(window.location.search);
    if (finalUrlParams.get('create') === 'true' && typeof openModalBtn !== 'undefined' && openModalBtn) {
        setTimeout(() => {
            openModalBtn.click();
        }, 300);
    }
</script>
@endpush
