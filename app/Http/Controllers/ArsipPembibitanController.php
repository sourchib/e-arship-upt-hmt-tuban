<?php

namespace App\Http\Controllers;

use App\Models\ArsipPembibitan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArsipPembibitanController extends Controller
{
    public function index(Request $request)
    {
        $query = ArsipPembibitan::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('jenis_ternak', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%");
            });
        }

        $arsipPembibitan = $query->paginate(10);
        
        // Calculate stats
        $totalTernak = ArsipPembibitan::sum('jumlah');
        $terdistribusi = ArsipPembibitan::where('status', 'Terdistribusi')->sum('jumlah');
        $dalamProses = ArsipPembibitan::where('status', 'Proses')->sum('jumlah');

        if ($request->ajax()) {
            return response()->json([
                'html' => view('arsip_pembibitan._list', compact('arsipPembibitan'))->render(),
                'stats' => view('arsip_pembibitan._stats', compact('totalTernak', 'terdistribusi', 'dalamProses'))->render(),
            ]);
        }

        return view('arsip_pembibitan.index', compact('arsipPembibitan', 'totalTernak', 'terdistribusi', 'dalamProses'));
    }

    public function show(ArsipPembibitan $arsipPembibitan)
    {
        return view('arsip_pembibitan.show', compact('arsipPembibitan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:arsip_pembibitan',
            'jenis_ternak' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'umur' => 'required|string',
            'tujuan' => 'required|string',
            'tanggal' => 'required|date',
            'status' => 'required|in:Terdistribusi,Proses',
        ]);

        $validated['created_by'] = Auth::id();
        
        $arsip = ArsipPembibitan::create($validated);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Create',
            'modul' => 'Arsip Pembibitan',
            'deskripsi' => "Menambahkan data pembibitan baru: {$arsip->kode}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('arsip-pembibitan.index')->with('success', 'Data pembibitan berhasil ditambahkan.');
    }

    public function edit(ArsipPembibitan $arsipPembibitan)
    {
        return view('arsip_pembibitan.edit', compact('arsipPembibitan'));
    }

    public function update(Request $request, ArsipPembibitan $arsipPembibitan)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:arsip_pembibitan,kode,' . $arsipPembibitan->id,
            'jenis_ternak' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'umur' => 'required|string',
            'tujuan' => 'required|string',
            'tanggal' => 'required|date',
            'status' => 'required|in:Terdistribusi,Proses',
        ]);

        $arsipPembibitan->update($validated);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Update',
            'modul' => 'Arsip Pembibitan',
            'deskripsi' => "Memperbarui data pembibitan: {$arsipPembibitan->kode}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('arsip-pembibitan.index')->with('success', 'Data pembibitan berhasil diperbarui.');
    }

    public function destroy(Request $request, ArsipPembibitan $arsipPembibitan)
    {
        $kode = $arsipPembibitan->kode;
        $arsipPembibitan->delete();

        LogAktivitas::create([
            'jenis_aktivitas' => 'Delete',
            'modul' => 'Arsip Pembibitan',
            'deskripsi' => "Menghapus data pembibitan: {$kode}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('arsip-pembibitan.index')->with('success', 'Data pembibitan berhasil dihapus.');
    }
}
