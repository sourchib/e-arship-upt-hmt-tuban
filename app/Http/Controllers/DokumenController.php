<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumen::latest();

        // Filtering by category if present
        if ($request->has('kategori') && $request->kategori != 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        // Search by name or category
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $documents = $query->paginate(12);
        
        // Calculate stats
        $totalDokumen = Dokumen::count();
        $pdfCount = Dokumen::where('mime_type', 'application/pdf')->count();
        $excelCount = Dokumen::whereIn('mime_type', [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ])->count();
        $categoryCount = Dokumen::distinct('kategori')->count('kategori');

        // Available categories for tabs
        $categories = ['Semua', 'Laporan', 'Dokumen Teknis', 'Data', 'Panduan', 'Surat', 'Lainnya'];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('dokumen._grid', compact('documents'))->render(),
                'stats' => view('dokumen._stats', compact('totalDokumen', 'pdfCount', 'excelCount', 'categoryCount'))->render(),
                'kategori' => $request->kategori ?? 'Semua',
                'search' => $request->search ?? ''
            ]);
        }

        return view('dokumen.index', compact('documents', 'totalDokumen', 'pdfCount', 'excelCount', 'categoryCount', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'file' => 'required|file|max:10240', // Max 10MB
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('documents', 'public');

            $dokumen = Dokumen::create([
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'file_path' => $path,
                'ukuran' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'tanggal_upload' => now(),
                'deskripsi' => $request->deskripsi,
                'uploaded_by' => Auth::id(),
            ]);

            LogAktivitas::create([
                'jenis_aktivitas' => 'Create',
                'modul' => 'Manajemen Dokumen',
                'deskripsi' => "Mengupload dokumen baru: {$dokumen->nama}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diupload.');
        }

        return back()->with('error', 'Gagal mengupload file.');
    }

    public function download(Dokumen $dokumen)
    {
        if (Storage::disk('public')->exists($dokumen->file_path)) {
            $dokumen->increment('download_counter');
            
            LogAktivitas::create([
                'jenis_aktivitas' => 'Update',
                'modul' => 'Manajemen Dokumen',
                'deskripsi' => "Mendownload dokumen: {$dokumen->nama}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id' => Auth::id(),
            ]);

            return Storage::disk('public')->download($dokumen->file_path, $dokumen->nama);
        }

        return back()->with('error', 'File tidak ditemukan.');
    }

    public function destroy(Dokumen $dokumen)
    {
        if (Storage::disk('public')->exists($dokumen->file_path)) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $nama = $dokumen->nama;
        $dokumen->delete();

        LogAktivitas::create([
            'jenis_aktivitas' => 'Delete',
            'modul' => 'Manajemen Dokumen',
            'deskripsi' => "Menghapus dokumen: {$nama}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
