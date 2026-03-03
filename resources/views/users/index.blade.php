@extends('layouts.app')

@section('title', 'Manajemen Pengguna - E-Arsip')

@section('content')

{{-- ====== Page Header ====== --}}
<div class="page-header">
    <div class="page-header-left">
        <h1>Manajemen Pengguna</h1>
        <p>Kelola akses pengguna sistem E-Arsip</p>
    </div>
    <div class="page-header-actions">
        <button type="button" class="btn btn-primary" onclick="toggleModal()">
            <i data-lucide="user-plus" style="width:16px;height:16px;"></i>
            Tambah Pengguna
        </button>
    </div>
</div>

{{-- ====== Mini Stats ====== --}}
<div id="statsContainer">
    @include('users._stats')
</div>

{{-- ====== Toolbar ====== --}}
<div class="toolbar">
    <div class="toolbar-search">
        <i data-lucide="search" class="search-icon"></i>
        <input type="text" id="searchInput" placeholder="Cari nama, email, atau instansi...">
    </div>
    <div class="filter-tabs">
        @foreach(['Semua', 'Admin', 'Operator', 'Pimpinan'] as $role)
        <button class="filter-tab {{ $role == 'Semua' ? 'active' : '' }}" data-role="{{ $role }}">
            {{ $role }}
        </button>
        @endforeach
    </div>
</div>

{{-- ====== List Container ====== --}}
<div id="listContainer">
    @include('users._list')
</div>

{{-- ====== Modal (reuse existing) ====== --}}
@include('users._modal')

@endsection

@push('scripts')
<script>
    const searchInput    = document.getElementById('searchInput');
    const filterTabs     = document.querySelectorAll('.filter-tab');
    const listContainer  = document.getElementById('listContainer');
    const statsContainer = document.getElementById('statsContainer');
    let activityRole = 'Semua';
    let typingTimer;

    const performUpdate = () => {
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput.value);
        url.searchParams.set('role', activityRole);
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                listContainer.innerHTML  = data.html;
                if(data.stats) statsContainer.innerHTML = data.stats;
                lucide.createIcons();
                bindDeleteConfirm();
                window.history.pushState({}, '', url);
            });
    };

    if(searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performUpdate, 300);
        });
    }

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            activityRole = this.dataset.role;
            performUpdate();
        });
    });

    function bindDeleteConfirm() {
        document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
            btn.addEventListener('click', function() {
                Swal.fire({ title: 'Apakah Anda yakin?', text: 'Data yang dihapus tidak dapat dikembalikan!', icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal', reverseButtons: true
                }).then(result => { if (result.isConfirmed) document.getElementById(this.dataset.form).submit(); });
            });
        });
    }
    bindDeleteConfirm();

    function toggleModal(mode = 'create', userData = null) {
        const modal       = document.getElementById('userModal');
        const form        = document.getElementById('userForm');
        const methodDiv   = document.getElementById('formMethod');
        const title       = document.getElementById('modalTitle');
        const passwordHint = document.getElementById('passwordHint');

        modal.classList.toggle('hidden');

        if (!modal.classList.contains('hidden')) {
            if (mode === 'edit' && userData) {
                title.innerText = 'Edit Pengguna';
                form.action = `/users/${userData.id}`;
                methodDiv.innerHTML = '@method("PUT")';
                document.getElementById('formNama').value     = userData.nama;
                document.getElementById('formEmail').value    = userData.email;
                document.getElementById('formRole').value     = userData.role;
                document.getElementById('formInstansi').value = userData.instansi || '';
                document.getElementById('formPassword').placeholder = '••••••••';
                passwordHint.classList.remove('hidden');
            } else {
                title.innerText = 'Tambah Pengguna';
                form.action     = "{{ route('users.store') }}";
                methodDiv.innerHTML = '';
                form.reset();
                document.getElementById('formPassword').placeholder = '••••••••';
                passwordHint.classList.add('hidden');
            }
            lucide.createIcons();
        }
    }

    window.editUser = function(user) { toggleModal('edit', user); }
</script>
@endpush
