@extends('layouts.app')

@push('styles')
<style>
    .filter-tab.active {
        background-color: #10b981;
        color: white;
        border-color: #10b981;
    }
    .filter-tab:not(.active) {
        background-color: #f8fafc;
        color: #64748b;
        border-color: #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="p-6 lg:p-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengguna</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola akses pengguna sistem E-Arsip</p>
        </div>
        <button onclick="toggleModal()" class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-sm shadow-emerald-200">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Tambah Pengguna
        </button>
    </div>

    <!-- Stats Container -->
    <div id="statsContainer">
        @include('users._stats')
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 mb-6">
        <div class="flex flex-col lg:flex-row justify-between gap-4">
            <!-- Filter Tabs -->
            <div class="flex flex-wrap gap-2">
                @foreach(['Semua', 'Admin', 'Operator', 'Pimpinan'] as $role)
                <button type="button" 
                        class="filter-tab px-5 py-2 rounded-xl text-sm font-semibold border transition-all {{ ($role == 'Semua') ? 'active' : '' }}"
                        data-role="{{ $role }}">
                    {{ $role }}
                </button>
                @endforeach
            </div>

            <!-- Search -->
            <div class="flex items-center gap-3">
                <div class="relative flex-1 lg:w-80">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama, email, atau instansi..." 
                           class="w-full pl-12 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                </div>
                <button class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all">
                    <i data-lucide="filter" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- List Container -->
    <div id="listContainer">
        @include('users._list')
    </div>

    @include('users._modal')
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    const filterTabs = document.querySelectorAll('.filter-tab');
    const listContainer = document.getElementById('listContainer');
    const statsContainer = document.getElementById('statsContainer');
    let activityRole = 'Semua';
    let typingTimer;

    const performUpdate = () => {
        const search = searchInput.value;
        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('role', activityRole);

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

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            activityRole = this.getAttribute('data-role');
            performUpdate();
        });
    });

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

    function toggleModal(mode = 'create', userData = null) {
        const modal = document.getElementById('userModal');
        const form = document.getElementById('userForm');
        const methodDiv = document.getElementById('formMethod');
        const title = document.getElementById('modalTitle');
        const passwordHint = document.getElementById('passwordHint');

        modal.classList.toggle('hidden');

        if (!modal.classList.contains('hidden')) {
            if (mode === 'edit' && userData) {
                title.innerText = 'Edit Pengguna';
                form.action = `/users/${userData.id}`;
                methodDiv.innerHTML = '@method("PUT")';
                document.getElementById('formNama').value = userData.nama;
                document.getElementById('formEmail').value = userData.email;
                document.getElementById('formRole').value = userData.role;
                document.getElementById('formInstansi').value = userData.instansi || '';
                document.getElementById('formPassword').placeholder = '••••••••';
                passwordHint.classList.remove('hidden');
            } else {
                title.innerText = 'Tambah Pengguna';
                form.action = "{{ route('users.store') }}";
                methodDiv.innerHTML = '';
                form.reset();
                document.getElementById('formPassword').placeholder = '••••••••';
                passwordHint.classList.add('hidden');
            }
            lucide.createIcons();
        }
    }

    window.editUser = function(user) {
        toggleModal('edit', user);
    }
</script>
@endpush
@endsection
