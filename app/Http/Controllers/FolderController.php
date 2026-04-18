<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Dokumen;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
            'deskripsi' => 'nullable|string'
        ]);

        $folder = Folder::create($request->all());

        LogAktivitas::create([
            'jenis_aktivitas' => 'Create',
            'modul' => 'Manajemen Dokumen',
            'deskripsi' => "Membuat folder baru: {$folder->nama}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Folder berhasil dibuat.',
            'folder' => $folder
        ]);
    }

    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $oldName = $folder->nama;
        $folder->update($request->only('nama', 'deskripsi'));

        LogAktivitas::create([
            'jenis_aktivitas' => 'Update',
            'modul' => 'Manajemen Dokumen',
            'deskripsi' => "Mengubah nama folder dari '{$oldName}' menjadi '{$folder->nama}'",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Folder berhasil diperbarui.'
        ]);
    }

    public function destroy(Folder $folder)
    {
        $nama = $folder->nama;
        $folder->delete();

        LogAktivitas::create([
            'jenis_aktivitas' => 'Delete',
            'modul' => 'Manajemen Dokumen',
            'deskripsi' => "Menghapus folder: {$nama}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Folder berhasil dihapus.'
        ]);
    }

    /**
     * Move documents to a folder
     */
    public function moveDocuments(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:dokumen,id',
            'folder_id' => 'nullable|exists:folders,id'
        ]);

        $folder = $request->folder_id ? Folder::find($request->folder_id) : null;
        $folderName = $folder ? $folder->nama : 'Root/Utama';

        Dokumen::whereIn('id', $request->document_ids)->update([
            'folder_id' => $request->folder_id,
            'folder' => $folder ? $folder->nama : null
        ]);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Update',
            'modul' => 'Manajemen Dokumen',
            'deskripsi' => "Memindahkan " . count($request->document_ids) . " dokumen ke folder: {$folderName}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil dipindahkan.'
        ]);
    }

    /**
     * Copy documents to a folder
     */
    public function copyDocuments(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:dokumen,id',
            'folder_id' => 'nullable|exists:folders,id'
        ]);

        $folder = $request->folder_id ? Folder::find($request->folder_id) : null;
        $folderName = $folder ? $folder->nama : 'Root/Utama';
        $documents = Dokumen::whereIn('id', $request->document_ids)->get();
        $copyCount = 0;

        foreach ($documents as $doc) {
            $newDoc = $doc->replicate();
            $newDoc->folder_id = $request->folder_id;
            $newDoc->folder = $folder ? $folder->nama : null;
            $newDoc->nama = $doc->nama . ' (Salinan)';
            
            // Check if file exists then copy
            if (Storage::disk('public')->exists($doc->file_path)) {
                $extension = pathinfo($doc->file_path, PATHINFO_EXTENSION);
                $newPath = 'documents/' . uniqid() . '.' . $extension;
                Storage::disk('public')->copy($doc->file_path, $newPath);
                $newDoc->file_path = $newPath;
                $newDoc->save();
                $copyCount++;
            }
        }

        LogAktivitas::create([
            'jenis_aktivitas' => 'Create',
            'modul' => 'Manajemen Dokumen',
            'deskripsi' => "Menyalin {$copyCount} dokumen ke folder: {$folderName}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Berhasil menyalin {$copyCount} dokumen ke {$folderName}."
        ]);
    }
}
