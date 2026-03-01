@extends('layouts.app')

@section('title', 'Arsip Hijauan - E-Arsip')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Arsip Hijauan</h1>
            <p class="text-muted small mb-0">Kelola data produksi hijauan makanan ternak</p>
        </div>
        <button type="button" class="btn" id="openCreateModal" style="background-color: #00C853; color: white; border-radius: 8px; padding: 10px 20px; font-weight: 500;">
            <i data-lucide="plus" style="width: 18px; margin-right: 5px;"></i> Tambah Data Hijauan
        </button>
    </div>

    <!-- Stats Cards -->
    <div id="statsContainer">
        @include('arsip_hijauan._stats')
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl shadow-sm mb-4 d-flex flex-wrap gap-3 align-items-center justify-content-between border border-slate-100">
        <div class="flex-grow-1" style="max-width: 800px;">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="text-slate-400 w-4 h-4"></i>
                </div>
                <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all" placeholder="Cari kode lahan, jenis hijauan, atau lokasi...">
            </div>
        </div>
        <button class="flex items-center gap-2 px-4 py-2.5 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all text-sm font-medium">
            <i data-lucide="filter" class="w-4 h-4"></i> Filter
        </button>
    </div>

    <!-- List Container -->
    <div id="listContainer">
        @include('arsip_hijauan._list')
    </div>
</div>

<!-- Modal Create Arsip Hijauan -->
<div class="modal-backdrop-custom" id="modalBackdrop"></div>
<div class="modal-container-custom" id="createArsipModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <div>
                <h4 class="fw-bold mb-0">Tambah Data Hijauan</h4>
                <p class="text-muted small mb-0">Masukkan detail produksi hijauan baru</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeCreateModal">
                <i data-lucide="x"></i>
            </button>
        </div>
        
        <div class="modal-body-custom">
            <form action="{{ route('arsip-hijauan.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kode_lahan" class="form-label fw-medium">Kode Lahan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_lahan') is-invalid @enderror" id="kode_lahan" name="kode_lahan" value="{{ old('kode_lahan') }}" placeholder="Contoh: LHN-A01" required>
                        @error('kode_lahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_hijauan" class="form-label fw-medium">Jenis Hijauan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('jenis_hijauan') is-invalid @enderror" id="jenis_hijauan" name="jenis_hijauan" value="{{ old('jenis_hijauan') }}" placeholder="Contoh: Rumput Gajah" required>
                        @error('jenis_hijauan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="luas" class="form-label fw-medium">Luas (Ha) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control @error('luas') is-invalid @enderror" id="luas" name="luas" value="{{ old('luas') }}" required min="0">
                        @error('luas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="produksi" class="form-label fw-medium">Produksi (Kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control @error('produksi') is-invalid @enderror" id="produksi" name="produksi" value="{{ old('produksi') }}" required min="0">
                        @error('produksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="lokasi" class="form-label fw-medium">Lokasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Sidorejo Blok A" required>
                    @error('lokasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="tanggal_panen" class="form-label fw-medium">Tanggal Panen <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_panen') is-invalid @enderror" id="tanggal_panen" name="tanggal_panen" value="{{ old('tanggal_panen', date('Y-m-d')) }}" required>
                        @error('tanggal_panen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="Tersedia">Tersedia</option>
                            <option value="Terdistribusi">Terdistribusi</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-save-modal">
                        Simpan Data
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
    .rounded-3xl { border-radius: 24px; }
    .table-hover tbody tr:hover { background-color: #f8fafc; transition: background-color 0.2s; }
    .form-control:focus, .form-select:focus {
        border-color: #00C853;
        box-shadow: 0 0 0 0.25rem rgba(0, 200, 83, 0.1);
    }
    
    /* Custom Modal Styles */
    .modal-backdrop-custom { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1050; }
    .modal-backdrop-custom.show { display: block; }
    .modal-container-custom { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1060; width: 100%; max-width: 600px; padding: 20px; }
    .modal-container-custom.show { display: block; }
    .modal-content-custom { background: white; border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    .modal-header-custom { display: flex; justify-content: space-between; align-items: center; padding: 24px 24px 12px 24px; }
    .modal-body-custom { padding: 24px; }
    .btn-close-custom { background: none; border: none; color: #94a3b8; cursor: pointer; }
    .modal-body-custom .form-control, .modal-body-custom .form-select { border-radius: 12px; padding: 12px; border: 1px solid #e2e8f0; font-size: 14px; }
    .btn-save-modal { background-color: #00C853 !important; color: white !important; border-radius: 12px !important; padding: 14px !important; font-weight: 600 !important; flex: 1; border: none; font-size: 14px; }
    .btn-cancel-modal { background-color: white !important; color: #64748b !important; border: 1px solid #e2e8f0 !important; border-radius: 12px !important; padding: 14px !important; font-weight: 600 !important; flex: 1; font-size: 14px; }
</style>
@endpush

@push('scripts')
<script>
    const openModalBtn = document.getElementById('openCreateModal');
    const closeModalBtn = document.getElementById('closeCreateModal');
    const cancelModalBtn = document.getElementById('cancelCreateModal');
    const modal = document.getElementById('createArsipModal');
    const backdrop = document.getElementById('modalBackdrop');

    const toggleModal = () => {
        modal.classList.toggle('show');
        backdrop.classList.toggle('show');
    };

    if(openModalBtn) openModalBtn.addEventListener('click', toggleModal);
    if(closeModalBtn) closeModalBtn.addEventListener('click', toggleModal);
    if(cancelModalBtn) cancelModalBtn.addEventListener('click', toggleModal);
    if(backdrop) backdrop.addEventListener('click', toggleModal);

    @if ($errors->any())
        toggleModal();
    @endif

    // AJAX Searching
    const searchInput = document.querySelector('input[placeholder="Cari kode lahan, jenis hijauan, atau lokasi..."]');
    const listContainer = document.getElementById('listContainer');
    const statsContainer = document.getElementById('statsContainer');
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
            statsContainer.innerHTML = data.stats;
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
                    title: 'Hapus Data?',
                    text: "Data hijauan ini akan dihapus secara permanen!",
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
            const newUrl = window.location.pathname;
            window.history.replaceState({}, '', newUrl);
        }
    });
</script>
@endpush
