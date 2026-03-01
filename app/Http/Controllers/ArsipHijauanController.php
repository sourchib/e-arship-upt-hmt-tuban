<?php

namespace App\Http\Controllers;

use App\Models\ArsipHijauan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArsipHijauanController extends Controller
{
    public function index(Request $request)
    {
        $query = ArsipHijauan::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_lahan', 'like', "%{$search}%")
                  ->orWhere('jenis_hijauan', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $arsipHijauan = $query->paginate(10);
        
        // Calculate stats (based on current filtered data or total?)
        // Mockup usually implies total stats, but sometimes users want filtered stats.
        // Let's stick to total for now as per mockup visuals.
        $totalLahan = ArsipHijauan::count();
        $totalProduksi = ArsipHijauan::sum('produksi');
        $luasTotal = ArsipHijauan::sum('luas');

        if ($request->ajax()) {
            return response()->json([
                'html' => view('arsip_hijauan._list', compact('arsipHijauan'))->render(),
                'stats' => view('arsip_hijauan._stats', compact('totalLahan', 'totalProduksi', 'luasTotal'))->render(),
            ]);
        }

        return view('arsip_hijauan.index', compact('arsipHijauan', 'totalLahan', 'totalProduksi', 'luasTotal'));
    }

    public function show(ArsipHijauan $arsipHijauan)
    {
        return view('arsip_hijauan.show', compact('arsipHijauan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_lahan' => 'required|string|unique:arsip_hijauan',
            'jenis_hijauan' => 'required|string',
            'luas' => 'required|numeric|min:0',
            'produksi' => 'required|numeric|min:0',
            'tanggal_panen' => 'required|date',
            'lokasi' => 'required|string',
            'status' => 'required|in:Tersedia,Terdistribusi',
        ]);

        $validated['created_by'] = Auth::id();
        
        $arsip = ArsipHijauan::create($validated);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Create',
            'modul' => 'Arsip Hijauan',
            'deskripsi' => "Menambahkan data hijauan baru: {$arsip->kode_lahan}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('arsip-hijauan.index')->with('success', 'Data hijauan berhasil ditambahkan.');
    }

    public function edit(ArsipHijauan $arsipHijauan)
    {
        return view('arsip_hijauan.edit', compact('arsipHijauan'));
    }

    public function update(Request $request, ArsipHijauan $arsipHijauan)
    {
        $validated = $request->validate([
            'kode_lahan' => 'required|string|unique:arsip_hijauan,kode_lahan,' . $arsipHijauan->id,
            'jenis_hijauan' => 'required|string',
            'luas' => 'required|numeric|min:0',
            'produksi' => 'required|numeric|min:0',
            'tanggal_panen' => 'required|date',
            'lokasi' => 'required|string',
            'status' => 'required|in:Tersedia,Terdistribusi',
        ]);

        $arsipHijauan->update($validated);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Update',
            'modul' => 'Arsip Hijauan',
            'deskripsi' => "Memperbarui data hijauan: {$arsipHijauan->kode_lahan}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('arsip-hijauan.index')->with('success', 'Data hijauan berhasil diperbarui.');
    }

    public function destroy(Request $request, ArsipHijauan $arsipHijauan)
    {
        $kode = $arsipHijauan->kode_lahan;
        $arsipHijauan->delete();

        LogAktivitas::create([
            'jenis_aktivitas' => 'Delete',
            'modul' => 'Arsip Hijauan',
            'deskripsi' => "Menghapus data hijauan: {$kode}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('arsip-hijauan.index')->with('success', 'Data hijauan berhasil dihapus.');
    }
}
