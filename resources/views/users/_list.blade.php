{{-- Desktop Table --}}
<div class="data-card desktop-table">
    <div class="data-card-header">
        <span class="data-card-title">Daftar Pengguna</span>
        <span style="font-size:12px;color:#94a3b8;">Total: {{ $users->total() }} pengguna</span>
    </div>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:48px;">No</th>
                    <th>Pengguna</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Instansi</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">{{ $users->firstItem() + $index }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:#10b981;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13.5px;color:#0f172a;">{{ $user->nama }}</div>
                                <div style="font-size:11px;color:#94a3b8;">Terdaftar: {{ $user->tanggal_daftar ? $user->tanggal_daftar->format('j/n/Y') : '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="color:#64748b;font-size:13px;display:flex;align-items:center;gap:6px;">
                            <i data-lucide="mail" style="width:14px;height:14px;color:#94a3b8;"></i>
                            {{ $user->email }}
                        </span>
                    </td>
                    <td>
                        @php
                            $roleClass = 'bg-pending';
                            if($user->role == 'Admin') $roleClass = 'bg-diproses';
                            if($user->role == 'Pimpinan') $roleClass = 'bg-terkirim';
                        @endphp
                        <span class="status-badge {{ $roleClass }}">{{ $user->role }}</span>
                    </td>
                    <td><span style="color:#64748b;font-size:13px;">{{ $user->instansi ?? '-' }}</span></td>
                    <td>
                        @php
                            $statusClass = 'bg-pending';
                            if($user->status == 'Aktif') $statusClass = 'bg-terkirim';
                            if($user->status == 'Nonaktif') $statusClass = 'bg-terarsip';
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $user->status }}</span>
                    </td>
                    <td>
                        <div class="action-btns">
                            @if($user->status == 'Pending')
                                <form action="{{ route('users.approve', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" style="background:transparent;border:none;color:#10b981;font-weight:700;font-size:12px;cursor:pointer;">Setujui</button>
                                </form>
                                <form action="{{ route('users.reject', $user->id) }}" method="POST" style="display:inline;margin-left:8px;">
                                    @csrf
                                    <button type="submit" style="background:transparent;border:none;color:#ef4444;font-weight:700;font-size:12px;cursor:pointer;">Tolak</button>
                                </form>
                            @else
                                <button type="button" class="action-btn action-btn-view btn-view-detail" 
                                        data-nama="{{ $user->nama }}"
                                        data-email="{{ $user->email }}"
                                        data-role="{{ $user->role }}"
                                        data-instansi="{{ $user->instansi ?? '-' }}"
                                        data-status="{{ $user->status }}"
                                        data-status-class="{{ $statusClass }}"
                                        data-tanggal="{{ $user->tanggal_daftar ? $user->tanggal_daftar->format('d/m/Y') : '-' }}"
                                        title="Detail">
                                    <i data-lucide="eye"></i>
                                </button>
                                <button type="button" class="action-btn action-btn-edit btn-edit-user"
                                        data-id="{{ $user->id }}"
                                        data-nama="{{ $user->nama }}"
                                        data-email="{{ $user->email }}"
                                        data-role="{{ $user->role }}"
                                        data-instansi="{{ $user->instansi ?? '' }}"
                                        data-url="{{ route('users.update', $user->id) }}"
                                        title="Edit">
                                    <i data-lucide="edit-3"></i>
                                </button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="action-btn action-btn-delete btn-delete-confirm" data-form="delete-form-{{ $user->id }}" title="Hapus">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i data-lucide="users" style="display:block;margin:0 auto 14px;"></i>
                            <p>Data tidak ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Cards --}}
<div class="mobile-card-list">
    @forelse($users as $index => $user)
    <div class="mobile-card">
        <div class="mobile-card-header">
            <span style="font-size:11px;color:#94a3b8;font-weight:600;">#{{ $users->firstItem() + $index }}</span>
            @php
                $statusClass = 'bg-pending';
                if($user->status == 'Aktif') $statusClass = 'bg-terkirim';
                if($user->status == 'Nonaktif') $statusClass = 'bg-terarsip';
            @endphp
            <span class="status-badge {{ $statusClass }}">{{ $user->status }}</span>
        </div>
        
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
            <div style="width:36px;height:36px;border-radius:50%;background:#10b981;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">
                {{ strtoupper(substr($user->nama, 0, 1)) }}
            </div>
            <div>
                <div style="font-weight:700;font-size:13.5px;color:#0f172a;">{{ $user->nama }}</div>
                <div style="font-size:11px;color:#94a3b8;">Terdaftar: {{ $user->tanggal_daftar ? $user->tanggal_daftar->format('j/n/Y') : '-' }}</div>
            </div>
        </div>

        <div class="mobile-card-field">
            <div class="mobile-card-label">Email</div>
            <div class="mobile-card-value" style="display:flex;align-items:center;gap:6px;">
                <i data-lucide="mail" style="width:14px;height:14px;color:#94a3b8;"></i>
                {{ $user->email }}
            </div>
        </div>
        
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
            <div>
                <div class="mobile-card-label">Role</div>
                <div class="mobile-card-value" style="font-size:12px;">
                    @php
                        $roleClass = 'bg-pending';
                        if($user->role == 'Admin') $roleClass = 'bg-diproses';
                        if($user->role == 'Pimpinan') $roleClass = 'bg-terkirim';
                    @endphp
                    <span class="status-badge {{ $roleClass }}" style="display:inline-block;margin-top:4px;">{{ $user->role }}</span>
                </div>
            </div>
            <div>
                <div class="mobile-card-label">Instansi</div>
                <div class="mobile-card-value" style="font-size:12px;">{{ $user->instansi ?? '-' }}</div>
            </div>
        </div>

        <div class="mobile-card-footer mt-2">
            <div class="action-btns">
            @if($user->status == 'Pending')
                <form action="{{ route('users.approve', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:transparent;border:none;color:#10b981;font-weight:700;font-size:12px;cursor:pointer;">Setujui</button>
                </form>
                <form action="{{ route('users.reject', $user->id) }}" method="POST" style="display:inline;margin-left:8px;">
                    @csrf
                    <button type="submit" style="background:transparent;border:none;color:#ef4444;font-weight:700;font-size:12px;cursor:pointer;">Tolak</button>
                </form>
            @else
                <button type="button" class="action-btn action-btn-view btn-view-detail" 
                        data-nama="{{ $user->nama }}"
                        data-email="{{ $user->email }}"
                        data-role="{{ $user->role }}"
                        data-instansi="{{ $user->instansi ?? '-' }}"
                        data-status="{{ $user->status }}"
                        data-status-class="{{ $statusClass }}"
                        data-tanggal="{{ $user->tanggal_daftar ? $user->tanggal_daftar->format('d/m/Y') : '-' }}"
                        title="Detail">
                    <i data-lucide="eye"></i>
                </button>
                <button type="button" class="action-btn action-btn-edit btn-edit-user"
                        data-id="{{ $user->id }}"
                        data-nama="{{ $user->nama }}"
                        data-email="{{ $user->email }}"
                        data-role="{{ $user->role }}"
                        data-instansi="{{ $user->instansi ?? '' }}"
                        data-url="{{ route('users.update', $user->id) }}"
                        title="Edit">
                    <i data-lucide="edit-3"></i>
                </button>
            @endif
            </div>
            @if($user->status != 'Pending')
            <button type="button" class="action-btn action-btn-delete btn-delete-confirm" data-form="delete-form-{{ $user->id }}">
                <i data-lucide="trash-2"></i>
            </button>
            @endif
        </div>
    </div>
    @empty
    <div class="mobile-card" style="text-align:center;padding:40px;">
        <p style="color:#94a3b8;font-size:13px;">Belum ada pengguna.</p>
    </div>
    @endforelse
</div>

{{ $users->links('vendor.pagination.custom') }}
