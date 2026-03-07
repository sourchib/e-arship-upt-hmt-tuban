@extends('layouts.app')

@section('title', 'Manajemen Kategori Dokumen - E-Arsip')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Manajemen Kategori Dokumen</h1>
        <p>Kelola daftar kategori untuk dokumen arsip</p>
    </div>
    <div class="page-header-actions">
        <button type="button" class="btn btn-primary" id="openCreateModal">
            <i data-lucide="plus-circle" style="width:16px;height:16px;"></i>
            Tambah Kategori
        </button>
    </div>
</div>

<div class="section-card">
    <div id="listContainer">
        @include('kategori_dokumen._list')
    </div>
</div>

{{-- ====== Modal Create/Edit ====== --}}
<div class="modal-backdrop-custom" id="modalBackdrop"></div>
<div class="modal-container-custom" id="formModal">
    <div class="modal-content-custom" style="max-width: 500px;">
        <div class="modal-header-custom">
            <div>
                <h4 id="modalTitle">Tambah Kategori</h4>
                <p id="modalSub">Tambahkan kategori dokumen baru</p>
            </div>
            <button type="button" class="btn-close-custom" id="closeFormModal">
                <i data-lucide="x"></i>
            </button>
        </div>

        <div class="modal-body-custom">
            <form id="kategoriForm" method="POST">
                @csrf
                <div id="formMethod"></div>

                <div class="form-group">
                    <label class="form-label">Nama Kategori <span style="color:#dc2626">*</span></label>
                    <input type="text" class="form-control" name="nama" id="inputNama" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea class="form-control" name="keterangan" id="inputKeterangan" rows="3"></textarea>
                </div>

                <div class="modal-footer-btns">
                    <button type="submit" class="btn-save-modal" id="btnSubmit">Simpan</button>
                    <button type="button" class="btn-cancel-modal" id="cancelFormModal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const backdrop    = document.getElementById('modalBackdrop');
    const modalForm   = document.getElementById('formModal');
    const form        = document.getElementById('kategoriForm');
    const title       = document.getElementById('modalTitle');
    const subtitle    = document.getElementById('modalSub');
    const methodDiv   = document.getElementById('formMethod');
    
    const toggleModal = (mode = 'create') => {
        modalForm.classList.toggle('show');
        backdrop.classList.toggle('show');
        document.body.style.overflow = modalForm.classList.contains('show') ? 'hidden' : '';
        
        if (mode === 'create') {
            title.innerText = 'Tambah Kategori';
            subtitle.innerText = 'Tambahkan kategori dokumen baru';
            form.action = "{{ route('kategori-dokumen.store') }}";
            methodDiv.innerHTML = '';
            form.reset();
        }
    };

    document.getElementById('openCreateModal').addEventListener('click', () => toggleModal('create'));
    document.getElementById('closeFormModal').addEventListener('click', toggleModal);
    document.getElementById('cancelFormModal').addEventListener('click', toggleModal);
    backdrop.addEventListener('click', () => { modalForm.classList.remove('show'); backdrop.classList.remove('show'); });

    const bindActions = () => {
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                toggleModal('edit');
                title.innerText = 'Edit Kategori';
                subtitle.innerText = 'Ubah nama atau keterangan kategori';
                form.action = this.dataset.url;
                methodDiv.innerHTML = '@method("PUT")';
                document.getElementById('inputNama').value = this.dataset.nama;
                document.getElementById('inputKeterangan').value = this.dataset.keterangan;
            });
        });

        document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
            btn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Hapus Kategori?',
                    text: 'Kategori ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch(this.dataset.url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Dihapus!', data.message, 'success');
                                performRefresh();
                            } else {
                                Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                            }
                        })
                        .catch(err => Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error'));
                    }
                });
            });
        });
    };

    const performRefresh = () => {
        fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                document.getElementById('listContainer').innerHTML = data.html;
                lucide.createIcons();
                bindActions();
            });
    };

    bindActions();

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitUrl = this.action;
        const submitMethod = methodDiv.innerHTML.includes('PUT') ? 'POST' : 'POST';

        fetch(submitUrl, {
            method: submitMethod,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async r => {
            const data = await r.json();
            if (r.ok && data.success) {
                Swal.fire({icon: 'success', title: 'Berhasil!', text: data.message, showConfirmButton: false, timer: 1500});
                toggleModal();
                performRefresh();
            } else {
                Swal.fire({icon: 'error', title: 'Gagal!', text: data.message || 'Periksa kembali isian Anda.', confirmButtonColor: '#dc2626'});
            }
        })
        .catch(err => Swal.fire({icon: 'error', title: 'Error', text: 'Terjadi kesalahan jaringan.', confirmButtonColor: '#dc2626'}));
    });
</script>
@endpush
