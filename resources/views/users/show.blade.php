@extends('layouts.app')

@section('title', 'Detail Pengguna - E-Arsip')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="mb-4">
        <a href="{{ route('users.index') }}" class="text-decoration-none text-muted small d-flex align-items-center gap-2 mb-2">
            <i data-lucide="arrow-left" style="width: 14px;"></i> Kembali ke Manajemen Pengguna
        </a>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1">Detail Pengguna</h1>
                <p class="text-muted small mb-0">Kelola informasi profil dan hak akses pengguna.</p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="editUser({{ json_encode($user) }})" class="btn btn-warning d-flex align-items-center gap-2 px-4 shadow-sm" style="border-radius: 12px; color: white;">
                    <i data-lucide="edit-3" style="width: 18px;"></i> Edit Profil
                </button>
                <button onclick="addUser()" class="btn btn-primary d-flex align-items-center gap-2 px-4 shadow-sm" style="border-radius: 12px;">
                    <i data-lucide="user-plus" style="width: 18px;"></i> Tambah Pengguna
                </button>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Main Profile Card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 24px; overflow: hidden; background: #fff;">
                <div class="card-body p-0">
                    <!-- Profile Header Background -->
                    <div class="position-relative p-4 text-center py-5" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); overflow: hidden;">
                        <!-- Subtle Background Pattern -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                        
                        <div class="position-absolute p-3 top-0 end-0">
                             @php
                                $statusClass = 'bg-pending';
                                if($user->status == 'Aktif') $statusClass = 'bg-terkirim';
                            @endphp
                            <span class="status-badge {{ $statusClass }} shadow-sm border border-white/20 px-3 py-1">
                                <i data-lucide="check-circle" class="me-1" style="width: 12px; height: 12px;"></i>
                                {{ $user->status }}
                            </span>
                        </div>
                        
                        <div class="avatar-lg bg-white/20 backdrop-blur-md text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 border border-white/40 shadow-lg" style="width: 100px; height: 100px; font-size: 40px; font-weight: 800; position: relative; z-index: 1;">
                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                        </div>
                        <h3 class="text-white fw-bold mb-1 position-relative" style="z-index: 1;">{{ $user->nama }}</h3>
                        <p class="text-white/80 mb-0 position-relative" style="z-index: 1; font-weight: 500;">{{ $user->email }}</p>
                    </div>

                    <!-- Detailed Info Area -->
                    <div class="p-4 p-md-5">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-4 rounded-4 border border-light bg-light/30 h-100 transition-hover">
                                    <label class="text-muted small mb-2 d-block text-uppercase fw-bold tracking-widest" style="font-size: 10px;">Role Akses</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-2.5 bg-primary/10 text-primary rounded-3">
                                            <i data-lucide="shield-check" style="width: 22px;"></i>
                                        </div>
                                        <div>
                                            <span class="h6 fw-bold mb-0 d-block">{{ $user->role }}</span>
                                            <span class="text-muted" style="font-size: 11px;">Tingkat Hak Akses</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 rounded-4 border border-light bg-light/30 h-100 transition-hover">
                                    <label class="text-muted small mb-2 d-block text-uppercase fw-bold tracking-widest" style="font-size: 10px;">Instansi/Unit Kerja</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-2.5 bg-warning/10 text-warning rounded-3">
                                            <i data-lucide="building" style="width: 22px;"></i>
                                        </div>
                                        <div>
                                            <span class="h6 fw-bold mb-0 d-block text-truncate" style="max-width: 180px;">{{ $user->instansi ?? 'Tidak Ditentukan' }}</span>
                                            <span class="text-muted" style="font-size: 11px;">Lokasi Tugas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Metadata & Security Card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 fw-bold">Metadata Akun</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 d-flex align-items-start gap-3">
                        <div class="p-2 bg-light rounded-3 text-muted">
                            <i data-lucide="calendar" style="width: 20px;"></i>
                        </div>
                        <div>
                            <label class="text-muted small d-block mb-1">Tanggal Terdaftar</label>
                            <p class="fw-medium mb-0">{{ $user->tanggal_daftar ? $user->tanggal_daftar->format('d F Y') : 'Data tidak tersedia' }}</p>
                        </div>
                    </div>
                    <div class="mb-4 d-flex align-items-start gap-3">
                        <div class="p-2 bg-light rounded-3 text-muted">
                            <i data-lucide="clock" style="width: 20px;"></i>
                        </div>
                        <div>
                            <label class="text-muted small d-block mb-1">Terakhir Diperbarui</label>
                            <p class="fw-medium mb-0">{{ $user->updated_at->format('d/m/Y, H:i') }}</p>
                            <span class="text-muted small fst-italic">({{ $user->updated_at->diffForHumans() }})</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-0 shadow-sm" style="border-radius: 20px; background-color: #fff1f2;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-danger d-flex align-items-center gap-2">
                        <i data-lucide="alert-triangle" style="width: 18px;"></i> Zona Bahaya
                    </h6>
                    <p class="text-muted small mb-4">Menghapus akun akan mencabut seluruh akses pengguna ini secara permanen.</p>
                    <form id="delete-form-detail" action="{{ route('users.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger w-100 py-2 fw-bold d-flex align-items-center justify-content-center gap-2 btn-delete-confirm" data-form="delete-form-detail" style="border-radius: 12px;">
                            <i data-lucide="user-minus" style="width: 18px;"></i> Hapus Akun Selamanya
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Partials -->
@include('users._modal')

@endsection

@push('scripts')
<script>
    function addUser() {
        const modal = document.getElementById('userModal');
        const form = document.getElementById('userForm');
        const methodDiv = document.getElementById('formMethod');
        const title = document.getElementById('modalTitle');

        modal.classList.remove('hidden');
        title.innerText = 'Tambah Pengguna Baru';
        form.action = "{{ route('users.store') }}";
        methodDiv.innerHTML = '';
        form.reset();
        document.getElementById('passwordHint').classList.add('hidden');
        document.getElementById('formPassword').placeholder = 'Masukkan password';
        lucide.createIcons();
    }

    function editUser(user) {
        const modal = document.getElementById('userModal');
        const form = document.getElementById('userForm');
        const methodDiv = document.getElementById('formMethod');
        const title = document.getElementById('modalTitle');

        modal.classList.remove('hidden');
        title.innerText = 'Edit Pengguna';
        form.action = `/users/${user.id}`;
        methodDiv.innerHTML = '@method("PUT")';
        document.getElementById('formNama').value = user.nama;
        document.getElementById('formEmail').value = user.email;
        document.getElementById('formRole').value = user.role;
        document.getElementById('formInstansi').value = user.instansi || '';
        document.getElementById('formPassword').value = '';
        document.getElementById('formPassword').placeholder = '••••••••';
        document.getElementById('passwordHint').classList.remove('hidden');
        
        lucide.createIcons();
    }

    function toggleModal() {
        document.getElementById('userModal').classList.toggle('hidden');
    }

    document.querySelectorAll('.btn-delete-confirm').forEach(button => {
        button.addEventListener('click', function() {
            const formId = this.getAttribute('data-form');
            Swal.fire({
                title: 'Hapus Akun?',
                text: "Akses pengguna ini akan dicabut secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
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
</script>
@endpush
