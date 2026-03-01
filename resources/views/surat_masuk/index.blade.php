@extends('layouts.app')

@section('title', 'Surat Masuk - E-Arsip')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Surat Masuk</h1>
            <p class="text-muted small mb-0">Kelola surat masuk dan disposisi</p>
        </div>
        <button type="button" class="btn" id="openCreateModal" style="background-color: var(--primary-green); color: white; border-radius: 8px; padding: 10px 20px; font-weight: 500;">
            <i data-lucide="plus" style="width: 18px; margin-right: 5px;"></i> Tambah Surat Masuk
        </button>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl shadow-sm mb-4 d-flex flex-wrap gap-3 align-items-center justify-content-between">
        <div class="flex-grow-1" style="max-width: 800px;">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="text-slate-400 w-4 h-4"></i>
                </div>
                <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" placeholder="Cari nomor, perihal, atau pengirim...">
            </div>
        </div>
        <button class="flex items-center gap-2 px-4 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all text-sm font-medium">
            <i data-lucide="filter" class="w-4 h-4"></i> Filter
        </button>
    </div>

    <!-- List Container -->
    <div id="listContainer">
        @include('surat_masuk._list')
    </div>
</div>
</div>

<!-- Modal Create Surat Masuk -->
<div class="modal-backdrop-custom" id="modalBackdrop"></div>
<div class="modal-container-custom" id="createSuratModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <div>
                <h4 class="fw-bold mb-0">Tambah Surat Masuk</h4>
                <p class="text-muted small mb-0">Masukkan detail surat masuk baru</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeCreateModal">
                <i data-lucide="x"></i>
            </button>
        </div>
        
        <div class="modal-body-custom">
            <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="nomor_surat" class="form-label fw-medium">Nomor Surat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nomor_surat') is-invalid @enderror" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat') }}" placeholder="Contoh: 001/SM/UPT-PTHMT/II/2026" required>
                    @error('nomor_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="perihal" class="form-label fw-medium">Perihal <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('perihal') is-invalid @enderror" id="perihal" name="perihal" value="{{ old('perihal') }}" placeholder="Masukkan perihal surat" required>
                    @error('perihal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="pengirim" class="form-label fw-medium">Pengirim <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('pengirim') is-invalid @enderror" id="pengirim" name="pengirim" value="{{ old('pengirim') }}" placeholder="Nama pengirim" required>
                        @error('pengirim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_surat" class="form-label fw-medium">Tanggal Surat <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required>
                        </div>
                        @error('tanggal_surat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="kategori" class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="Permohonan">Permohonan</option>
                        <option value="Undangan">Undangan</option>
                        <option value="Laporan">Laporan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="file" class="form-label fw-medium">Upload File (PDF) <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required>
                    @error('file')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Hidden/Default fields for backend compatibility -->
                <input type="hidden" name="tanggal_terima" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="prioritas" value="Sedang">
                <input type="hidden" name="status" value="Pending">

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-save-modal">
                        Simpan
                    </button>
                    <button type="button" class="btn btn-cancel-modal" id="cancelCreateModal">
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
    .table-hover tbody tr:hover {
        background-color: #f1f5f9;
        transition: background-color 0.2s;
    }
    .input-group-text {
        border-color: #e2e8f0;
    }
    .form-control:focus, .form-select:focus {
        border-color: #00C853;
        box-shadow: 0 0 0 0.25rem rgba(0, 200, 83, 0.1);
    }
    
    /* Custom Modal Styles to match mockup */
    .modal-backdrop-custom {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
    }
    .modal-backdrop-custom.show {
        display: block;
    }
    
    .modal-container-custom {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1060;
        width: 100%;
        max-width: 600px;
        padding: 20px;
    }
    .modal-container-custom.show {
        display: block;
    }
    
    .modal-content-custom {
        background: white;
        border-radius: 20px;
        padding: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .modal-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 20px 10px 20px;
    }
    
    .modal-body-custom {
        padding: 20px;
    }
    
    .btn-close-custom {
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
    }
    
    .modal-body-custom .form-control, .modal-body-custom .form-select {
        border-radius: 12px;
        padding: 12px;
        border: 1px solid #e2e8f0;
    }
    
    #nomor_surat:focus {
        border: 2px solid #00C853 !important;
    }
    
    .btn-save-modal {
        background-color: #00C853 !important;
        color: white !important;
        border-radius: 12px !important;
        padding: 12px !important;
        font-weight: 600 !important;
        flex: 1;
        border: none;
    }
    
    .btn-cancel-modal {
        background-color: white !important;
        color: #64748b !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 12px !important;
        font-weight: 600 !important;
        flex: 1;
    }
</style>
@endpush

@push('scripts')
<script>
    const openModalBtn = document.getElementById('openCreateModal');
    const closeModalBtn = document.getElementById('closeCreateModal');
    const cancelModalBtn = document.getElementById('cancelCreateModal');
    const modal = document.getElementById('createSuratModal');
    const backdrop = document.getElementById('modalBackdrop');

    const toggleModal = () => {
        modal.classList.toggle('show');
        backdrop.classList.toggle('show');
    };

    openModalBtn.addEventListener('click', toggleModal);
    closeModalBtn.addEventListener('click', toggleModal);
    cancelModalBtn.addEventListener('click', toggleModal);
    backdrop.addEventListener('click', toggleModal);

    // Auto-open modal if there are errors (useful for Laravel validation redirect back)
    @if ($errors->any())
        toggleModal();
    @endif
    // SweetAlert2 Delete Confirmation
    // AJAX Searching
    const searchInput = document.querySelector('input[placeholder="Cari nomor, perihal, atau pengirim..."]');
    const listContainer = document.getElementById('listContainer');
    let typingTimer;

    const performUpdate = () => {
        const search = searchInput.value;
        const url = new URL(window.location.href);
        url.searchParams.set('search', search);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            listContainer.innerHTML = data.html;
            lucide.createIcons();
            
            // Re-bind delete buttons
            bindDeleteConfirm();
            
            // Update URL without reload
            window.history.pushState({}, '', url);
        });
    };

    if(searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performUpdate, 300);
        });
    }

    function bindDeleteConfirm() {
        document.querySelectorAll('.btn-delete-confirm').forEach(button => {
            button.addEventListener('click', function() {
                const formId = this.getAttribute('data-form');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
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

    // Auto-open modal if 'create' parameter is present
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('create') === 'true') {
            toggleModal();
            // Optional: clean up the URL
            const newUrl = window.location.pathname;
            window.history.replaceState({}, '', newUrl);
        }
    });
</script>
@endpush
