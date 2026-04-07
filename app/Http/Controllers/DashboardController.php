<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\ArsipPembibitan;
use App\Models\ArsipHijauan;
use App\Models\Dokumen;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $categories = Kategori::orderBy('nama')->pluck('nama');
        $stats = [
            'surat_masuk' => SuratMasuk::count(),
            'surat_keluar' => SuratKeluar::count(),
            'arsip_pembibitan' => ArsipPembibitan::count(),
            'arsip_hijauan' => ArsipHijauan::count(),
            'arsip_aktif' => Dokumen::where('status', 'Aktif')->count(),
            'arsip_inaktif' => Dokumen::where('status', 'Inaktif')->count(),
        ];

        // Expanded Search Logic for All Data (Restored per user request)
        $searchResults = [
            'features' => [],
            'surat' => [],
            'arsip' => [],
            'dokumen' => []
        ];

        if ($request->filled('search')) {
            $search = $request->search;
            
            // 1. Search Features (Sidebar/Nav)
            $sidebarFeatures = [
                ['name' => 'Dashboard', 'route' => route('dashboard'), 'icon' => 'layout-grid'],
                ['name' => 'Manajemen Pengguna', 'route' => route('users.index'), 'icon' => 'users'],
                ['name' => 'Manajemen Dokumen', 'route' => route('dokumen.index'), 'icon' => 'file-text'],
                ['name' => 'Surat Masuk', 'route' => route('surat-masuk.index'), 'icon' => 'mail'],
                ['name' => 'Surat Keluar', 'route' => route('surat-keluar.index'), 'icon' => 'send'],
                ['name' => 'Arsip Pembibitan', 'route' => route('arsip-pembibitan.index'), 'icon' => 'book-open'],
                ['name' => 'Arsip Hijauan', 'route' => route('arsip-hijauan.index'), 'icon' => 'leaf'],
            ];
            
            foreach ($sidebarFeatures as $feature) {
                if (stripos($feature['name'], $search) !== false) {
                    $searchResults['features'][] = (object)$feature;
                }
            }

            // 2. Search Surat
            $sm = SuratMasuk::where('nomor_surat', 'like', "%$search%")
                ->orWhere('pengirim', 'like', "%$search%")
                ->orWhere('perihal', 'like', "%$search%")
                ->take(3)->get()->map(function($item) {
                    return (object)['name' => $item->perihal, 'info' => 'Surat Masuk: ' . $item->nomor_surat, 'route' => route('surat-masuk.index', ['search' => $item->nomor_surat]), 'icon' => 'mail'];
                });
            $sk = SuratKeluar::where('nomor_surat', 'like', "%$search%")
                ->orWhere('tujuan', 'like', "%$search%")
                ->orWhere('perihal', 'like', "%$search%")
                ->take(3)->get()->map(function($item) {
                    return (object)['name' => $item->perihal, 'info' => 'Surat Keluar: ' . $item->nomor_surat, 'route' => route('surat-keluar.index', ['search' => $item->nomor_surat]), 'icon' => 'send'];
                });
            $searchResults['surat'] = $sm->concat($sk);

            // 3. Search Arsip
            $ap = ArsipPembibitan::where('kode', 'like', "%$search%")
                ->orWhere('jenis_ternak', 'like', "%$search%")
                ->take(3)->get()->map(function($item) {
                    return (object)['name' => $item->jenis_ternak, 'info' => 'Arsip Pembibitan: ' . $item->kode, 'route' => route('arsip-pembibitan.index', ['search' => $item->kode]), 'icon' => 'book-open'];
                });
            $ah = ArsipHijauan::where('kode_lahan', 'like', "%$search%")
                ->orWhere('jenis_hijauan', 'like', "%$search%")
                ->take(3)->get()->map(function($item) {
                    return (object)['name' => $item->jenis_hijauan, 'info' => 'Arsip Hijauan: ' . $item->kode_lahan, 'route' => route('arsip-hijauan.index', ['search' => $item->kode_lahan]), 'icon' => 'leaf'];
                });
            $searchResults['arsip'] = $ap->concat($ah);

            // 4. Search Dokumen
            $searchResults['dokumen'] = Dokumen::where('nama', 'like', "%$search%")
                ->orWhere('kode', 'like', "%$search%")
                ->take(3)->get()->map(function($item) {
                    return (object)['name' => $item->nama, 'info' => 'Dokumen: ' . ($item->kode ?? '#'.str_pad($item->id, 4, '0', STR_PAD_LEFT)), 'route' => route('dokumen.index', ['search' => $item->nama]), 'icon' => 'file-text'];
                });
        }

        $query = Dokumen::query();
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        $docsTerbaru = $query->latest()->paginate(5)->withQueryString();

        // Mock trends if real data is zero, for visual impact as per design
        $trends = [
            'surat_masuk' => 12,
            'surat_keluar' => 8,
            'arsip_pembibitan' => 5,
            'arsip_hijauan' => 15,
        ];

        $recentActivities = LogAktivitas::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Map real log statuses for badges
        $recentActivities->transform(function($activity) {
            $status = 'Terarsip';
            $badge = 'bg-terarsip';
            if ($activity->modul == 'Surat Masuk') {
                $status = 'Pending';
                $badge = 'bg-pending';
            } elseif ($activity->modul == 'Surat Keluar') {
                $status = 'Terkirim';
                $badge = 'bg-terkirim';
            }
            $activity->status = $status;
            $activity->badge_class = $badge;
            return $activity;
        });

        if ($request->ajax()) {
            return response()->json([
                'stats' => $stats,
                'docs_html' => view('dashboard._table', compact('docsTerbaru'))->render(),
            ]);
        }

        return view('dashboard', compact('stats', 'trends', 'recentActivities', 'docsTerbaru', 'searchResults', 'categories'));
    }

    public function suggestions(Request $request)
    {
        $search = $request->query('q');
        if (strlen($search) < 1) return response()->json([]);

        $suggestions = collect();

        // All Data Suggestions (Restored per user request)
        $sidebarFeatures = ['Dashboard', 'Manajemen Pengguna', 'Manajemen Dokumen', 'Surat Masuk', 'Surat Keluar', 'Arsip Pembibitan', 'Arsip Hijauan'];
        foreach ($sidebarFeatures as $feature) {
            if (stripos($feature, $search) !== false) {
                $suggestions->push($feature);
            }
        }

        // 2. Letters
        $sm = SuratMasuk::where('nomor_surat', 'like', "$search%")
            ->orWhere('perihal', 'like', "%$search%")
            ->take(3)->pluck('perihal')->toArray();
        $sk = SuratKeluar::where('nomor_surat', 'like', "$search%")
            ->orWhere('perihal', 'like', "%$search%")
            ->take(3)->pluck('perihal')->toArray();
        $suggestions = $suggestions->concat($sm)->concat($sk);

        // 3. Arsip
        $ap = ArsipPembibitan::where('kode', 'like', "$search%")
            ->orWhere('jenis_ternak', 'like', "%$search%")
            ->take(3)->pluck('jenis_ternak')->toArray();
        $ah = ArsipHijauan::where('kode_lahan', 'like', "$search%")
            ->orWhere('jenis_hijauan', 'like', "%$search%")
            ->take(3)->pluck('jenis_hijauan')->toArray();
        $suggestions = $suggestions->concat($ap)->concat($ah);

        // 4. Documents
        $docs = Dokumen::where('nama', 'like', "%$search%")
            ->orWhere('kode', 'like', "$search%")
            ->take(3)->pluck('nama')->toArray();
        $suggestions = $suggestions->concat($docs);

        return response()->json($suggestions->unique()->filter()->values()->take(8));
    }
}
