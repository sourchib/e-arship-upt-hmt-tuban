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
        
        // Debugging to log if records are truly being fetched
        \Illuminate\Support\Facades\Log::info("Fetching documents. Count in DB: " . Dokumen::count() . ". Count in query: " . $documents->total());

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

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil diupload.'
                ]);
            }

            return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diupload.');
        }

        return back()->with('error', 'Gagal mengupload file.');
    }

    public function download(Dokumen $dokumen)
    {
        if (!empty($dokumen->file_path) && is_string($dokumen->file_path) && Storage::disk('public')->exists($dokumen->file_path)) {
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

        return back()->with('error', 'File tidak ditemukan atau path tidak valid.');
    }

    public function destroy(Request $request, Dokumen $dokumen)
    {
        try {
            // Ensure document exists
            if (!$dokumen->exists) {
                // If route model binding failed silently or we have an empty instance
                return response()->json(['success' => false, 'message' => 'Dokumen tidak ditemukan di database.'], 404);
            }

            $nama = $dokumen->nama;
            $id = $dokumen->id;
            $filePath = $dokumen->file_path;

            // Delete physical file
            if (!empty($filePath) && is_string($filePath)) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            // Perform delete - use DB::table if Eloquent instance delete fails
            $deleted = $dokumen->delete();
            
            if (!$deleted) {
                \Illuminate\Support\Facades\Log::warning("Eloquent delete failed for ID {$id}, attempting DB::table delete.");
                $deletedCount = \Illuminate\Support\Facades\DB::table('dokumen')->where('id', $id)->delete();
                if ($deletedCount === 0 && Dokumen::find($id)) {
                    throw new \Exception("Gagal menghapus data dari database (ID: {$id}).");
                }
            }

            LogAktivitas::create([
                'jenis_aktivitas' => 'Delete',
                'modul' => 'Manajemen Dokumen',
                'deskripsi' => "Menghapus dokumen: {$nama}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => Auth::id(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil dihapus secara permanen.'
                ]);
            }

            return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("CRITICAL DELETE ERROR: " . $e->getMessage());
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
