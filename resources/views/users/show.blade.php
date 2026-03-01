@extends('layouts.app')

@section('content')
<div class="p-6 lg:p-10">
    <!-- Breadcrumbs/Back -->
    <div class="mb-8 flex items-center gap-2 text-slate-400">
        <a href="{{ route('users.index') }}" class="hover:text-emerald-500 transition-colors">Manajemen Pengguna</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-slate-700 font-medium">Detail Pengguna</span>
    </div>

    <!-- User Detail Card -->
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-12 text-center relative">
            <div class="absolute top-6 right-6">
                 @php
                    $statusClass = 'bg-white/20 text-white';
                    if($user->status == 'Aktif') $statusClass = 'bg-emerald-400/30 text-white';
                @endphp
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $statusClass }} border border-white/20 backdrop-blur-sm">
                    {{ $user->status }}
                </span>
            </div>
            
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white/20 backdrop-blur-md border-4 border-white/30 text-white text-3xl font-bold mb-4">
                {{ strtoupper(substr($user->nama, 0, 1)) }}
            </div>
            <h2 class="text-2xl font-bold text-white">{{ $user->nama }}</h2>
            <p class="text-emerald-50/80 mt-1">{{ $user->email }}</p>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <!-- Info Section -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Role Akses</label>
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-indigo-50 text-indigo-500 rounded-xl">
                                <i data-lucide="shield-check" class="w-5 h-5"></i>
                            </div>
                            <span class="font-semibold text-slate-700">{{ $user->role }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Instansi/Unit Kerja</label>
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-amber-50 text-amber-500 rounded-xl">
                                <i data-lucide="building" class="w-5 h-5"></i>
                            </div>
                            <span class="font-semibold text-slate-700">{{ $user->instansi ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Terdaftar</label>
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-emerald-50 text-emerald-500 rounded-xl">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </div>
                            <span class="font-semibold text-slate-700">{{ $user->tanggal_daftar ? $user->tanggal_daftar->format('d F Y') : '-' }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Terakhir Diperbarui</label>
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-slate-50 text-slate-500 rounded-xl">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <span class="font-semibold text-slate-700">{{ $user->updated_at->format('d F Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Area -->
            <div class="pt-8 border-t border-slate-50 flex flex-wrap justify-center gap-4">
                <a href="{{ route('users.index') }}" class="px-8 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-2xl transition-all">
                    Kembali
                </a>
                @if($user->status == 'Pending')
                    <form action="{{ route('users.approve', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-8 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-2xl transition-all shadow-lg shadow-emerald-200">
                            Setujui Akun
                        </button>
                    </form>
                @endif
                <button onclick="editUser({{ json_encode($user) }})" class="px-8 py-3 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-200">
                    Edit Profil
                </button>
                <form id="delete-form-detail" action="{{ route('users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-delete-confirm px-8 py-3 bg-rose-50 text-rose-500 hover:bg-rose-100 font-bold rounded-2xl transition-all" data-form="delete-form-detail">
                        Hapus Akun
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<div class="hidden">
    @include('users._modal')
</div>
<script>
    function editUser(user) {
        // Since we are on a separate page, we need to handle the modal differently or just include it.
        // Actually, for simplicity on the detail page, we can just redirect to index with an edit query
        // or just include the modal as we did above.
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
        document.getElementById('formPassword').placeholder = '••••••••';
        
        lucide.createIcons();
    }

    function toggleModal() {
        document.getElementById('userModal').classList.toggle('hidden');
    }

    document.querySelector('.btn-delete-confirm').addEventListener('click', function() {
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
</script>
@endpush
@endsection
