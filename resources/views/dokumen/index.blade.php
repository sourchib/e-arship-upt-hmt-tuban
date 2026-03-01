@extends('layouts.app')

@section('title', 'Manajemen Dokumen - E-Arsip')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Manajemen Dokumen</h1>
            <p class="text-muted small mb-0">Kelola semua dokumen arsip digital</p>
        </div>
        <button type="button" class="btn" id="openUploadModal" style="background-color: #00C853; color: white; border-radius: 8px; padding: 10px 20px; font-weight: 500;">
            <i data-lucide="plus" style="width: 18px; margin-right: 5px;"></i> Upload Dokumen
        </button>
    </div>

    <!-- Stats Cards -->
    <div id="statsContainer">
        @include('dokumen._stats')
    </div>

    <!-- Filter Tabs & Search -->
    <div class="bg-white p-4 rounded-2xl shadow-sm mb-4 border border-slate-100">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0" id="filterTabs">
                @foreach($categories as $cat)
                <a href="{{ route('dokumen.index', ['kategori' => $cat]) }}" 
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all whitespace-nowrap {{ (request()->kategori ?? 'Semua') == $cat ? 'bg-green-500 text-white shadow-md shadow-green-100' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    {{ $cat }}
                </a>
                @endforeach
            </div>
            
            <form action="{{ route('dokumen.index') }}" method="GET" class="flex-grow md:flex-grow-0" style="min-width: 300px;">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="text-slate-400 w-4 h-4"></i>
                    </div>
                    <input type="hidden" name="kategori" value="{{ request()->kategori ?? 'Semua' }}">
                    <input type="text" name="search" value="{{ request()->search }}" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" placeholder="Cari nama dokumen atau kategori...">
                </div>
            </form>
        </div>
    </div>

    <!-- Documents Grid -->
    <div id="documentsGrid">
        @include('dokumen._grid')
    </div>
</div>

<!-- Upload Modal -->
<div class="modal-backdrop-custom" id="modalBackdrop"></div>
<div class="modal-container-custom" id="uploadModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <div>
                <h4 class="fw-bold mb-0">Upload Dokumen Baru</h4>
                <p class="text-muted small mb-0">Tambahkan file ke arsip digital</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeUploadModal">
                <i data-lucide="x"></i>
            </button>
        </div>
        
        <div class="modal-body-custom">
            <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="nama" class="form-label fw-medium">Nama Dokumen <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Laporan Tahunan 2025">
                </div>

                <div class="mb-3">
                    <label for="kategori" class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach($categories as $cat)
                            @if($cat != 'Semua')
                            <option value="{{ $cat }}">{{ $cat }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label fw-medium">Pilih File <span class="text-danger">*</span></label>
                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 flex flex-col items-center justify-center bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer relative" id="dropZone">
                        <i data-lucide="upload-cloud" class="w-10 h-10 text-slate-400 mb-2"></i>
                        <p class="text-xs text-slate-500 mb-1 font-medium text-center">Tarik file ke sini atau klik untuk memilih</p>
                        <p class="text-[10px] text-slate-400">Maks. 10MB (PDF, Excel, Docx, dsb)</p>
                        <input type="file" name="file" id="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                    </div>
                    <div id="fileInfo" class="mt-2 hidden flex items-center gap-2 p-2 bg-green-50 rounded-xl border border-green-100">
                        <i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i>
                        <span class="text-xs text-green-700 font-medium truncate" id="fileName"></span>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="form-label fw-medium">Deskripsi (Opsional)</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" placeholder="Tambahkan keterangan dokumen jika perlu"></textarea>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-save-modal">
                        Upload Sekarang
                    </button>
                    <button type="button" class="btn btn-cancel-modal" id="cancelUploadModal">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .rounded-2xl { border-radius: 16px; }
    .rounded-3xl { border-radius: 24px; }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    
    #filterTabs::-webkit-scrollbar { display: none; }
    #filterTabs { -ms-overflow-style: none; scrollbar-width: none; }

    .form-control:focus, .form-select:focus {
        border-color: #00C853;
        box-shadow: 0 0 0 0.25rem rgba(0, 200, 83, 0.1);
    }
    
    /* Modal Styles */
    .modal-backdrop-custom { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); z-index: 1050; }
    .modal-backdrop-custom.show { display: block; }
    .modal-container-custom { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1060; width: 100%; max-width: 500px; padding: 20px; }
    .modal-container-custom.show { display: block; }
    .modal-content-custom { background: white; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); border: 1px solid #f1f5f9; }
    .modal-header-custom { display: flex; justify-content: space-between; align-items: center; padding: 24px 24px 12px 24px; }
    .modal-body-custom { padding: 24px; }
    .btn-close-custom { background: none; border: none; color: #94a3b8; cursor: pointer; transition: color 0.2s; }
    .btn-close-custom:hover { color: #f43f5e; }
    .modal-body-custom .form-control, .modal-body-custom .form-select { border-radius: 12px; padding: 12px; border: 1px solid #e2e8f0; font-size: 14px; }
    .btn-save-modal { background-color: #00C853 !important; color: white !important; border-radius: 14px !important; padding: 14px !important; font-weight: 600 !important; flex: 1; border: none; font-size: 14px; transition: transform 0.2s; }
    .btn-save-modal:hover { transform: translateY(-2px); }
    .btn-cancel-modal { background-color: #f8fafc !important; color: #64748b !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; padding: 14px !important; font-weight: 600 !important; flex: 1; font-size: 14px; }
</style>
@endpush

@push('scripts')
<script>
    const openModalBtn = document.getElementById('openUploadModal');
    const closeModalBtn = document.getElementById('closeUploadModal');
    const cancelModalBtn = document.getElementById('cancelUploadModal');
    const modal = document.getElementById('uploadModal');
    const backdrop = document.getElementById('modalBackdrop');
    const fileInput = document.getElementById('fileInput');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');

    const toggleModal = () => {
        modal.classList.toggle('show');
        backdrop.classList.toggle('show');
    };

    if(openModalBtn) openModalBtn.addEventListener('click', toggleModal);
    if(closeModalBtn) closeModalBtn.addEventListener('click', toggleModal);
    if(cancelModalBtn) cancelModalBtn.addEventListener('click', toggleModal);
    if(backdrop) backdrop.addEventListener('click', toggleModal);

    fileInput.addEventListener('change', function(e) {
        if(this.files && this.files[0]) {
            fileName.textContent = this.files[0].name;
            fileInfo.classList.remove('hidden');
        }
    });

    // AJAX Filtering & Searching
    const filterContainer = document.getElementById('filterTabs');
    const searchInput = document.querySelector('input[name="search"]');
    const documentsGrid = document.getElementById('documentsGrid');
    const statsContainer = document.getElementById('statsContainer');
    let currentKategori = '{{ request()->kategori ?? "Semua" }}';
    let typingTimer;

    const performUpdate = () => {
        const search = searchInput.value;
        const url = new URL(window.location.href);
        url.searchParams.set('kategori', currentKategori);
        url.searchParams.set('search', search);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            documentsGrid.innerHTML = data.html;
            statsContainer.innerHTML = data.stats;
            lucide.createIcons();
            
            // Re-bind delete buttons
            bindDeleteConfirm();
            
            // Update URL without reload
            window.history.pushState({}, '', url);
        });
    };

    filterContainer.querySelectorAll('a').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            filterContainer.querySelectorAll('a').forEach(t => {
                t.classList.remove('bg-green-500', 'text-white', 'shadow-md', 'shadow-green-100');
                t.classList.add('bg-slate-50', 'text-slate-600');
            });
            this.classList.add('bg-green-500', 'text-white', 'shadow-md', 'shadow-green-100');
            this.classList.remove('bg-slate-50', 'text-slate-600');
            
            currentKategori = this.innerText.trim();
            performUpdate();
        });
    });

    searchInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(performUpdate, 300);
    });

    function bindDeleteConfirm() {
        document.querySelectorAll('.btn-delete-confirm').forEach(button => {
            button.addEventListener('click', function() {
                const formId = this.getAttribute('data-form');
                Swal.fire({
                    title: 'Hapus Dokumen?',
                    text: "Dokumen ini akan dihapus permanen dari server!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            });
        });
    }

    bindDeleteConfirm();
</script>
@endpush
