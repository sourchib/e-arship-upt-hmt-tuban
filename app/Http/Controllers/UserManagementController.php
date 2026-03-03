<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::latest();

        // Filter by search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role != 'Semua') {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(10);

        // Stats
        $stats = [
            'total' => User::count(),
            'aktif' => User::where('status', 'Aktif')->count(),
            'pending' => User::where('status', 'Pending')->count(),
            'admin' => User::where('role', 'Admin')->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('users._list', compact('users'))->render(),
                'stats' => view('users._stats', compact('stats'))->render(),
            ]);
        }

        return view('users.index', compact('users', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:Admin,Operator,Pimpinan',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'instansi' => $request->instansi,
            'status' => 'Aktif',
            'tanggal_daftar' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:Admin,Operator,Pimpinan',
        ]);

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
            'instansi' => $request->instansi,
        ];

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function approve(User $user)
    {
        $user->update(['status' => 'Aktif']);
        return back()->with('success', 'Pengguna berhasil disetujui.');
    }

    public function reject(User $user)
    {
        $user->update(['status' => 'Nonaktif']);
        return back()->with('success', 'Pengguna ditolak.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        // Delete related logs first to avoid foreign key constraint error
        $user->logAktivitas()->delete();
        
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
