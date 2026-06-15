<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKeluar::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%");
            });
        }

        $suratKeluar = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('surat_keluar._list', compact('suratKeluar'))->render(),
            ]);
        }

        return view('surat_keluar.index', compact('suratKeluar'));
    }

    public function show(SuratKeluar $suratKeluar)
    {
        return view('surat_keluar.show', compact('suratKeluar'));
    }

    public function create()
    {
        return view('surat_keluar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat_keluar',
            'tujuan' => 'required|string',
            'perihal' => 'required|string',
            'tanggal_surat' => 'required|date',
            'tanggal_kirim' => 'nullable|date',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah',
            'status' => 'required|in:Draft,Terkirim,Selesai',
            'file' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('surat_keluar', 'public');
        }

        $validated['created_by'] = Auth::id();
        
        $surat = SuratKeluar::create($validated);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Create',
            'modul' => 'Surat Keluar',
            'deskripsi' => "Menambahkan surat keluar baru: {$surat->nomor_surat}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil ditambahkan.');
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        return view('surat_keluar.edit', compact('suratKeluar'));
    }

    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat_keluar,nomor_surat,' . $suratKeluar->id,
            'tujuan' => 'required|string',
            'perihal' => 'required|string',
            'tanggal_surat' => 'required|date',
            'tanggal_kirim' => 'nullable|date',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah',
            'status' => 'required|in:Draft,Terkirim,Selesai',
            'file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            if ($suratKeluar->file_path) {
                Storage::disk('public')->delete($suratKeluar->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('surat_keluar', 'public');
        }

        $suratKeluar->update($validated);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Update',
            'modul' => 'Surat Keluar',
            'deskripsi' => "Memperbarui surat keluar: {$suratKeluar->nomor_surat}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(Request $request, SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->file_path) {
            Storage::disk('public')->delete($suratKeluar->file_path);
        }

        $nomor_surat = $suratKeluar->nomor_surat;
        $suratKeluar->delete();

        LogAktivitas::create([
            'jenis_aktivitas' => 'Delete',
            'modul' => 'Surat Keluar',
            'deskripsi' => "Menghapus surat keluar: {$nomor_surat}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil dihapus.');
    }

    public function send(Request $request, SuratKeluar $suratKeluar)
    {
        $suratKeluar->update([
            'status' => 'Terkirim',
            'tanggal_kirim' => now(),
        ]);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Update',
            'modul' => 'Surat Keluar',
            'deskripsi' => "Mengirim surat keluar: {$suratKeluar->nomor_surat}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil dikirim.');
    }
}
