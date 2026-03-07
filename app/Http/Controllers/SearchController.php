<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\ArsipPembibitan;
use App\Models\ArsipHijauan;
use App\Models\Dokumen;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $results = [];

        if (!empty($q)) {
            // Search Surat Masuk
            $suratMasuk = SuratMasuk::where('nomor_surat', 'like', "%{$q}%")
                ->orWhere('pengirim', 'like', "%{$q}%")
                ->orWhere('perihal', 'like', "%{$q}%")
                ->get()->map(function($item) {
                    return (object)[
                        'type' => 'Surat Masuk',
                        'title' => $item->nomor_surat . ' - ' . $item->perihal,
                        'desc' => 'Pengirim: ' . $item->pengirim,
                        'date' => $item->tanggal_surat,
                        'url' => route('surat-masuk.index', ['search' => $item->nomor_surat]),
                        'icon' => 'mail',
                        'color' => 'blue'
                    ];
                });
            $results = array_merge($results, $suratMasuk->toArray());

            // Search Surat Keluar
            $suratKeluar = SuratKeluar::where('nomor_surat', 'like', "%{$q}%")
                ->orWhere('tujuan', 'like', "%{$q}%")
                ->orWhere('perihal', 'like', "%{$q}%")
                ->get()->map(function($item) {
                    return (object)[
                        'type' => 'Surat Keluar',
                        'title' => $item->nomor_surat . ' - ' . $item->perihal,
                        'desc' => 'Tujuan: ' . $item->tujuan,
                        'date' => $item->tanggal_surat,
                        'url' => route('surat-keluar.index', ['search' => $item->nomor_surat]),
                        'icon' => 'send',
                        'color' => 'green'
                    ];
                });
            $results = array_merge($results, $suratKeluar->toArray());

            // Search Arsip Pembibitan
            $arsipPembibitan = ArsipPembibitan::where('jenis_ternak', 'like', "%{$q}%")
                ->orWhere('kode', 'like', "%{$q}%")
                ->orWhere('tujuan', 'like', "%{$q}%")
                ->get()->map(function($item) {
                    return (object)[
                        'type' => 'Arsip Pembibitan',
                        'title' => 'Ternak: ' . $item->kode . ' - ' . $item->jenis_ternak,
                        'desc' => 'Tujuan: ' . $item->tujuan,
                        'date' => $item->tanggal,
                        'url' => route('arsip-pembibitan.index', ['search' => $item->kode]),
                        'icon' => 'book-open',
                        'color' => 'orange'
                    ];
                });
            $results = array_merge($results, $arsipPembibitan->toArray());

            // Search Arsip Hijauan
            $arsipHijauan = ArsipHijauan::where('jenis_hijauan', 'like', "%{$q}%")
                ->orWhere('kode_lahan', 'like', "%{$q}%")
                ->orWhere('lokasi', 'like', "%{$q}%")
                ->get()->map(function($item) {
                    return (object)[
                        'type' => 'Arsip Hijauan',
                        'title' => 'Hijauan: ' . $item->kode_lahan . ' - ' . $item->jenis_hijauan,
                        'desc' => 'Lokasi: ' . $item->lokasi,
                        'date' => $item->tanggal_panen,
                        'url' => route('arsip-hijauan.index', ['search' => $item->kode_lahan]),
                        'icon' => 'leaf',
                        'color' => 'teal'
                    ];
                });
            $results = array_merge($results, $arsipHijauan->toArray());

            // Search Dokumen
            $dokumen = Dokumen::where('nama', 'like', "%{$q}%")
                ->orWhere('kategori', 'like', "%{$q}%")
                ->orWhere('deskripsi', 'like', "%{$q}%")
                ->get()->map(function($item) {
                    return (object)[
                        'type' => 'Dokumen',
                        'title' => $item->nama,
                        'desc' => 'Kategori: ' . $item->kategori,
                        'date' => $item->created_at,
                        'url' => route('dokumen.index', ['search' => $item->nama]),
                        'icon' => 'file-text',
                        'color' => 'gray'
                    ];
                });
            $results = array_merge($results, $dokumen->toArray());
            
            // Sort by date descending
            usort($results, function($a, $b) {
                return strtotime($b->date) - strtotime($a->date);
            });
        }

        return view('search.index', compact('results', 'q'));
    }
}
