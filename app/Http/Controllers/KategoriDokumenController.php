<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriDokumen;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class KategoriDokumenController extends Controller
{
    public function index(Request $request)
    {
        $kategori = KategoriDokumen::orderBy('nama', 'asc')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('kategori_dokumen._list', compact('kategori'))->render()
            ]);
        }

        return view('kategori_dokumen.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_dokumen,nama',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = KategoriDokumen::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Create',
            'modul' => 'Kategori Dokumen',
            'deskripsi' => "Menambahkan Kategori Dokumen baru: {$kategori->nama}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan.']);
        }

        return redirect()->route('kategori-dokumen.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriDokumen $kategoriDokuman)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_dokumen,nama,' . $kategoriDokuman->id,
            'keterangan' => 'nullable|string',
        ]);

        $kategoriDokuman->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Update',
            'modul' => 'Kategori Dokumen',
            'deskripsi' => "Mengedit Kategori Dokumen: {$kategoriDokuman->nama}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kategori berhasil diperbarui.']);
        }

        return redirect()->route('kategori-dokumen.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Request $request, KategoriDokumen $kategoriDokuman)
    {
        $nama = $kategoriDokuman->nama;
        $kategoriDokuman->delete();

        LogAktivitas::create([
            'jenis_aktivitas' => 'Delete',
            'modul' => 'Kategori Dokumen',
            'deskripsi' => "Menghapus Kategori Dokumen: {$nama}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus.']);
        }

        return redirect()->route('kategori-dokumen.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
