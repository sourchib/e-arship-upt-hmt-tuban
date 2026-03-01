<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratMasuk::latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%");
            });
        }

        $suratMasuk = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('surat_masuk._list', compact('suratMasuk'))->render(),
            ]);
        }

        return view('surat_masuk.index', compact('suratMasuk'));
    }

    public function show(SuratMasuk $suratMasuk)
    {
        return view('surat_masuk.show', compact('suratMasuk'));
    }

    public function create()
    {
        return view('surat_masuk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat_masuk',
            'pengirim' => 'required|string',
            'perihal' => 'required|string',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah',
            'status' => 'required|in:Pending,Diproses,Terarsip',
            'file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('surat_masuk', 'public');
        }

        $validated['created_by'] = Auth::id();
        
        $surat = SuratMasuk::create($validated);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Create',
            'modul' => 'Surat Masuk',
            'deskripsi' => "Menambahkan surat masuk baru: {$surat->nomor_surat}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        return view('surat_masuk.edit', compact('suratMasuk'));
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surat_masuk,nomor_surat,' . $suratMasuk->id,
            'pengirim' => 'required|string',
            'perihal' => 'required|string',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'prioritas' => 'required|in:Tinggi,Sedang,Rendah',
            'status' => 'required|in:Pending,Diproses,Terarsip',
            'file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            if ($suratMasuk->file_path) {
                Storage::disk('public')->delete($suratMasuk->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('surat_masuk', 'public');
        }

        $suratMasuk->update($validated);

        LogAktivitas::create([
            'jenis_aktivitas' => 'Update',
            'modul' => 'Surat Masuk',
            'deskripsi' => "Memperbarui surat masuk: {$suratMasuk->nomor_surat}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(Request $request, SuratMasuk $suratMasuk)
    {
        if ($suratMasuk->file_path) {
            Storage::disk('public')->delete($suratMasuk->file_path);
        }

        $nomor_surat = $suratMasuk->nomor_surat;
        $suratMasuk->delete();

        LogAktivitas::create([
            'jenis_aktivitas' => 'Delete',
            'modul' => 'Surat Masuk',
            'deskripsi' => "Menghapus surat masuk: {$nomor_surat}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus.');
    }
}
