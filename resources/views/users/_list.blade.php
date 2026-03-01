<div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50/50 border-b border-slate-100">
                <tr>
                    <th class="ps-6 py-4 text-slate-700 font-semibold text-sm">No</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm">Nama</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm">Email</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm">Role</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm">Instansi</th>
                    <th class="py-4 text-slate-700 font-semibold text-sm text-center">Status</th>
                    <th class="pe-6 py-4 text-center text-slate-700 font-semibold text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $index => $user)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="ps-6 py-4 text-slate-600 text-sm">{{ $users->firstItem() + $index }}</td>
                    <td class="py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-slate-800 font-semibold text-sm">{{ $user->nama }}</div>
                                <div class="text-slate-400 text-[10px] font-medium mt-0.5">Terdaftar: {{ $user->tanggal_daftar ? $user->tanggal_daftar->format('j/n/Y') : '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="flex items-center gap-2 text-slate-500 text-sm">
                            <i data-lucide="mail" class="w-4 h-4 text-slate-300"></i>
                            {{ $user->email }}
                        </div>
                    </td>
                    <td class="py-4">
                        @php
                            $roleClass = 'bg-blue-100 text-blue-600';
                            if($user->role == 'Admin') $roleClass = 'bg-rose-100 text-rose-600';
                            if($user->role == 'Pimpinan') $roleClass = 'bg-purple-100 text-purple-600';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $roleClass }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="py-4 text-slate-600 text-sm">{{ $user->instansi ?? '-' }}</td>
                    <td class="py-4 text-center">
                        @php
                            $statusClass = 'bg-amber-100 text-amber-600';
                            if($user->status == 'Aktif') $statusClass = 'bg-emerald-100 text-emerald-600';
                            if($user->status == 'Nonaktif') $statusClass = 'bg-slate-100 text-slate-600';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td class="pe-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            @if($user->status == 'Pending')
                                <form action="{{ route('users.approve', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-emerald-500 hover:text-emerald-600 font-bold text-xs">Setujui</button>
                                </form>
                                <form action="{{ route('users.reject', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-rose-500 hover:text-rose-600 font-bold text-xs ms-2">Tolak</button>
                                </form>
                            @else
                                <a href="{{ route('users.show', $user->id) }}" class="p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-colors">
                                    <i data-lucide="eye" class="w-4.5 h-4.5"></i>
                                </a>
                                <a href="javascript:void(0)" onclick="editUser({{ json_encode($user) }})" class="p-2 text-orange-500 hover:bg-orange-50 rounded-lg transition-colors">
                                    <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                                </a>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors btn-delete-confirm" data-form="delete-form-{{ $user->id }}">
                                        <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-slate-400 text-sm italic">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($users->hasPages())
<div class="mt-6">
    {{ $users->links() }}
</div>
@endif
