<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\LogAktivitas;
use App\Models\KategoriDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumen::visible();

        // Folder filtering (File Manager Style)
        $parentId = $request->get('parent_id');
        if ($parentId) {
            $query->where('folder_id', $parentId);
        } else {
            // In Root, if not searching and no category filter, only show documents with no folder
            if (!$request->search && (!$request->filled('kategori') || $request->kategori === 'Semua')) {
                $query->whereNull('folder_id');
            }
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('tanggal', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('nama', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama', 'desc');
                break;
            case 'kode_asc':
                $query->orderBy('kode', 'asc');
                break;
            case 'kode_desc':
                $query->orderBy('kode', 'desc');
                break;
            case 'kategori_asc':
                $query->orderBy('kategori', 'asc');
                break;
            case 'kategori_desc':
                $query->orderBy('kategori', 'desc');
                break;
            case 'folder_asc':
                $query->orderBy('folder', 'asc');
                break;
            case 'folder_desc':
                $query->orderBy('folder', 'desc');
                break;
            case 'status_asc':
                $query->orderBy('status', 'asc');
                break;
            case 'status_desc':
                $query->orderBy('status', 'desc');
                break;
            case 'lokasi_asc':
                $query->orderBy('lokasi', 'asc');
                break;
            case 'lokasi_desc':
                $query->orderBy('lokasi', 'desc');
                break;
            case 'retensi_asc':
                $query->orderBy('masa_retensi', 'asc');
                break;
            case 'retensi_desc':
                $query->orderBy('masa_retensi', 'desc');
                break;
            case 'no_asc':
                $query->orderBy('id', 'asc');
                break;
            case 'no_desc':
                $query->orderBy('id', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('tanggal', 'desc');
                break;
        }

        // Filtering by category if present
        if ($request->has('kategori') && $request->kategori != 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        // Search by name, category, or other details
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $documents = $query->paginate(12);
        
        \Illuminate\Support\Facades\Log::info("Fetching documents. Count in DB: " . Dokumen::count() . ". Count in query: " . $documents->total());

        // Calculate stats
        $totalDokumen = Dokumen::count();
        $pdfCount = Dokumen::where('mime_type', 'application/pdf')->count();
        $excelCount = Dokumen::whereIn('mime_type', [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ])->count();
        $categoryCount = Dokumen::distinct('kategori')->count('kategori');
        
        $aktifCount = Dokumen::where('status', 'Aktif')->count();
        $inaktifCount = Dokumen::where('status', 'Inaktif')->count();

        // Dynamic categories from existing documents + predefined categories
        $docCategories = Dokumen::visible()->distinct()->pluck('kategori')->filter()->toArray();
        $dbCategories = \App\Models\KategoriDokumen::orderBy('nama', 'asc')->pluck('nama')->toArray();
        $categories = array_values(array_unique(array_merge(['Semua'], $docCategories, $dbCategories)));

        // Folder Navigation
        $currentFolderId = $request->get('parent_id');
        if($currentFolderId === "") $currentFolderId = null;
        $currentFolder = $currentFolderId ? \App\Models\Folder::find($currentFolderId) : null;
        
        // Breadcrumbs logic
        $breadcrumbs = [];
        $temp = $currentFolder;
        while ($temp) {
            array_unshift($breadcrumbs, ['id' => $temp->id, 'nama' => $temp->nama]);
            $temp = $temp->parent;
        }

        // Folder Navigation (Hide if filtering by specific category)
        $allFolders = collect();
        if (!$request->filled('kategori') || $request->kategori === 'Semua') {
            $folderQuery = \App\Models\Folder::where('parent_id', $currentFolderId)
                ->withCount(['dokumen' => function($q) {
                    $q->visible();
                }]);
            
            // Dynamic sorting for folders
            if ($sort === 'name_asc') {
                $folderQuery->orderBy('nama', 'asc');
            } elseif ($sort === 'name_desc') {
                $folderQuery->orderBy('nama', 'desc');
            } else {
                $folderQuery->orderBy('nama', 'asc');
            }
            
            $allFolders = $folderQuery->get();
        }
        
        // Get all folders for select inputs (flattened)
        $flatFolders = \App\Models\Folder::orderBy('nama', 'asc')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('dokumen._table', compact('documents', 'allFolders'))->render(),
                'stats' => view('dokumen._stats', compact('totalDokumen', 'pdfCount', 'excelCount', 'categoryCount', 'aktifCount', 'inaktifCount'))->render(),
                'kategori' => $request->kategori ?? 'Semua',
                'search' => $request->search ?? '',
                'categories' => $categories,
                'folders' => $allFolders,
                'flatFolders' => $flatFolders,
                'breadcrumbs' => $breadcrumbs,
                'currentFolder' => $currentFolder
            ]);
        }

        return view('dokumen.index', compact('documents', 'totalDokumen', 'pdfCount', 'excelCount', 'categoryCount', 'categories', 'aktifCount', 'inaktifCount', 'allFolders', 'flatFolders', 'breadcrumbs', 'currentFolder'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kategori_dokumen,nama',
        ]);

        $kategori = KategoriDokumen::create([
            'nama' => $request->nama,
        ]);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Create',
            'modul' => 'Manajemen Dokumen',
            'deskripsi' => "Menambahkan kategori dokumen baru: {$kategori->nama}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'nama' => $kategori->nama
        ]);
    }

    public function print(Request $request)
    {
        $query = Dokumen::visible();

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'kode_asc': $query->orderBy('kode', 'asc'); break;
            case 'kode_desc': $query->orderBy('kode', 'desc'); break;
            case 'name_asc': $query->orderBy('nama', 'asc'); break;
            case 'name_desc': $query->orderBy('nama', 'desc'); break;
            case 'kategori_asc': $query->orderBy('kategori', 'asc'); break;
            case 'kategori_desc': $query->orderBy('kategori', 'desc'); break;
            case 'status_asc': $query->orderBy('status', 'asc'); break;
            case 'status_desc': $query->orderBy('status', 'desc'); break;
            case 'lokasi_asc': $query->orderBy('lokasi', 'asc'); break;
            case 'lokasi_desc': $query->orderBy('lokasi', 'desc'); break;
            case 'retensi_asc': $query->orderBy('masa_retensi', 'asc'); break;
            case 'retensi_desc': $query->orderBy('masa_retensi', 'desc'); break;
            case 'oldest': $query->orderBy('tanggal', 'asc'); break;
            case 'no_asc': $query->orderBy('id', 'asc'); break;
            case 'no_desc': $query->orderBy('id', 'desc'); break;
            case 'latest':
            default: $query->orderBy('tanggal', 'desc'); break;
        }

        // Filtering
        if ($request->has('kategori') && $request->kategori != 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $documents = $query->get();
        return view('dokumen.print', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'folder' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'kode' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'masa_retensi' => 'nullable|string|max:100',
            'status' => 'required|in:Aktif,Inaktif',
            'sifat_arsip' => 'nullable|string|max:50',
            'file' => 'required|file|max:51200', // Max 50MB
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->has('kategori')) {
            KategoriDokumen::firstOrCreate(['nama' => $request->kategori]);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('documents', 'public');

            $folder = $request->folder_id ? \App\Models\Folder::find($request->folder_id) : null;

            $dokumen = Dokumen::create([
                'nama' => $request->nama,
                'kategori' => $request->kategori,
                'folder_id' => $request->folder_id,
                'folder' => $folder ? $folder->nama : null,
                'tanggal' => $request->tanggal,
                'kode' => $request->kode,
                'lokasi' => $request->lokasi,
                'masa_retensi' => $request->masa_retensi,
                'status' => $request->status,
                'sifat_arsip' => $request->sifat_arsip,
                'is_public' => $request->sifat_arsip !== 'Dirahasiakan',
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

    public function edit(Dokumen $dokumen)
    {
        return response()->json($dokumen);
    }

    public function update(Request $request, Dokumen $dokumen)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'folder' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'kode' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'masa_retensi' => 'nullable|string|max:100',
            'status' => 'required|in:Aktif,Inaktif',
            'sifat_arsip' => 'nullable|string|max:50',
            'file' => 'nullable|file|max:51200', // Max 50MB
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->has('kategori')) {
            KategoriDokumen::firstOrCreate(['nama' => $request->kategori]);
        }

        $folder = $request->folder_id ? \App\Models\Folder::find($request->folder_id) : null;
        
        $data = [
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'folder_id' => $request->folder_id,
            'folder' => $folder ? $folder->nama : null,
            'tanggal' => $request->tanggal,
            'kode' => $request->kode,
            'lokasi' => $request->lokasi,
            'masa_retensi' => $request->masa_retensi,
            'status' => $request->status,
            'sifat_arsip' => $request->sifat_arsip,
            'is_public' => $request->sifat_arsip !== 'Dirahasiakan',
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            if (!empty($dokumen->file_path) && Storage::disk('public')->exists($dokumen->file_path)) {
                Storage::disk('public')->delete($dokumen->file_path);
            }
            
            $file = $request->file('file');
            $data['file_path'] = $file->store('documents', 'public');
            $data['ukuran'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
        }

        $dokumen->update($data);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Update',
            'modul' => 'Manajemen Dokumen',
            'deskripsi' => "Memperbarui dokumen: {$dokumen->nama}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diperbarui.'
            ]);
        }

        return redirect()->back()->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function preview(Dokumen $dokumen)
    {
        if (empty($dokumen->file_path) || !Storage::disk('public')->exists($dokumen->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $mimeType = $dokumen->mime_type ?: 'application/octet-stream';
        $content  = Storage::disk('public')->get($dokumen->file_path);
        $filename = basename($dokumen->file_path);

        return response($content, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function download(Dokumen $dokumen)
    {
        if (!empty($dokumen->file_path) && is_string($dokumen->file_path) && Storage::disk('public')->exists($dokumen->file_path)) {
            $dokumen->increment('download_counter');
            
            if (Auth::check()) {
                LogAktivitas::create([
                    'jenis_aktivitas' => 'Update',
                    'modul' => 'Manajemen Dokumen',
                    'deskripsi' => "Mendownload dokumen: {$dokumen->nama}",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'user_id' => Auth::id(),
                ]);
            }

            $extension = pathinfo($dokumen->file_path, PATHINFO_EXTENSION);
            $downloadName = $dokumen->nama;
            
            if (!empty($extension) && !str_ends_with(strtolower($downloadName), '.' . strtolower($extension))) {
                $downloadName .= '.' . $extension;
            }

            return Storage::disk('public')->download($dokumen->file_path, $downloadName);
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
