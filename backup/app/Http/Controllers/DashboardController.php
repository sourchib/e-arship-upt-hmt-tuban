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
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $hideDirahasiakan = !Auth::check() || Auth::user()->role !== 'Admin';

        $docCategories = Dokumen::visible()->distinct()->pluck('kategori')->filter()->toArray();
        $dbCategories = Kategori::orderBy('nama', 'asc')->pluck('nama')->toArray();
        $categories = array_values(array_unique(array_merge(['Semua'], $docCategories, $dbCategories)));
        $stats = [
            'surat_masuk' => SuratMasuk::count(),
            'surat_keluar' => SuratKeluar::count(),
            'arsip_pembibitan' => ArsipPembibitan::count(),
            'arsip_hijauan' => ArsipHijauan::count(),
            'arsip_aktif' => Dokumen::visible()->where('status', 'Aktif')->count(),
            'arsip_inaktif' => Dokumen::visible()->where('status', 'Inaktif')->count(),
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
            $searchResults['dokumen'] = Dokumen::visible()->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%$search%")
                      ->orWhere('kode', 'like', "%$search%");
                })
                ->take(3)->get()->map(function($item) {
                    return (object)['name' => $item->nama, 'info' => 'Dokumen: ' . ($item->kode ?? '#'.str_pad($item->id, 4, '0', STR_PAD_LEFT)), 'route' => route('dokumen.index', ['search' => $item->nama]), 'icon' => 'file-text'];
                });
        }

        // Folder Navigation (File Manager Style)
        $parentId = $request->get('parent_id');
        $currentFolder = $parentId ? \App\Models\Folder::find($parentId) : null;
        
        // Breadcrumbs for Dashboard
        $breadcrumbs = [];
        $temp = $currentFolder;
        while ($temp) {
            array_unshift($breadcrumbs, ['id' => $temp->id, 'nama' => $temp->nama]);
            $temp = $temp->parent;
        }

        $query = Dokumen::visible();
        if ($request->filled('kategori') && $request->kategori !== 'Semua') {
            $query->where('kategori', $request->kategori);
        }

        // Apply folder filter
        if ($parentId) {
            $query->where('folder_id', $parentId);
        } else {
            // Only show root documents if no search
            // Only show root documents if no search and no category filter
            if (!$request->search && (!$request->filled('kategori') || $request->kategori === 'Semua')) {
                $query->whereNull('folder_id');
            }
        }

        // Sorting (Keep same Switch as DokumenController or current)
        switch ($request->get('sort')) {
            case 'kode_asc':
                $query->orderBy('kode', 'asc');
                break;
            case 'kode_desc':
                $query->orderBy('kode', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('nama', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('tanggal', 'desc');
                break;
        }

        $docsTerbaru = $query->paginate(5)->withQueryString();

        // Get subfolders for dashboard grid (Hide if filtering by specific category)
        $allFolders = collect();
        if (!$request->filled('kategori') || $request->kategori === 'Semua') {
            $folderQuery = \App\Models\Folder::where('parent_id', $parentId)
                ->withCount(['dokumen' => function($q) {
                    $q->visible();
                }]);
            
            // Apply name sorting to folders if requested
            if ($request->get('sort') === 'name_asc') {
                $folderQuery->orderBy('nama', 'asc');
            } elseif ($request->get('sort') === 'name_desc') {
                $folderQuery->orderBy('nama', 'desc');
            } else {
                $folderQuery->orderBy('nama', 'asc'); // Default
            }

            $allFolders = $folderQuery->get();
        }
        
        $totalDokumen = Dokumen::visible()->count(); // Need for "Utama" card count

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

        // Map real log statuses for badges and mask confidential names
        $recentActivities->transform(function($activity) use ($hideDirahasiakan) {
            // Masking logic: if modul is Manajemen Dokumen and user is not admin
            if ($hideDirahasiakan && $activity->modul == 'Manajemen Dokumen') {
                // If the description contains the name of a document that is currently confidential
                // We simplify by looking for "Dirahasiakan" documents
                static $confidentialDocs = null;
                if ($confidentialDocs === null) {
                    $confidentialDocs = \App\Models\Dokumen::where('sifat_arsip', 'Dirahasiakan')->pluck('nama')->toArray();
                }
                
                foreach ($confidentialDocs as $name) {
                    if (stripos($activity->deskripsi, $name) !== false) {
                        $activity->deskripsi = str_replace($name, "[DOKUMEN RAHASIA]", $activity->deskripsi);
                    }
                }
            }

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
                'docs_html' => view('dashboard._table', compact('docsTerbaru', 'allFolders'))->render(),
                'activities_html' => view('dashboard._activities', compact('recentActivities'))->render(),
                'folders' => $allFolders,
                'breadcrumbs' => $breadcrumbs,
                'parentId' => $parentId,
                'totalDokumen' => $totalDokumen,
                'categories' => $categories
            ]);
        }

        return view('dashboard', compact('stats', 'trends', 'recentActivities', 'docsTerbaru', 'searchResults', 'categories', 'allFolders', 'breadcrumbs', 'currentFolder', 'parentId', 'totalDokumen'));
    }

    public function suggestions(Request $request)
    {
        $search = $request->query('q');
        if (strlen($search) < 2) return response()->json([]);

        $suggestions = collect();

        // 1. Features
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
                $suggestions->push([
                    'text' => $feature['name'],
                    'type' => 'Fitur',
                    'icon' => $feature['icon'],
                    'url' => $feature['route']
                ]);
            }
        }

        // 2. Surat (Masuk & Keluar)
        $sm = SuratMasuk::where('nomor_surat', 'like', "%$search%")
            ->orWhere('perihal', 'like', "%$search%")
            ->take(3)->get()->map(function($item) {
                return [
                    'text' => $item->perihal,
                    'type' => 'Surat Masuk',
                    'icon' => 'mail',
                    'url' => route('surat-masuk.index', ['search' => $item->nomor_surat])
                ];
            });
        
        $sk = SuratKeluar::where('nomor_surat', 'like', "%$search%")
            ->orWhere('perihal', 'like', "%$search%")
            ->take(3)->get()->map(function($item) {
                return [
                    'text' => $item->perihal,
                    'type' => 'Surat Keluar',
                    'icon' => 'send',
                    'url' => route('surat-keluar.index', ['search' => $item->nomor_surat])
                ];
            });
        
        $suggestions = $suggestions->concat($sm)->concat($sk);

        // 3. Arsip
        $ap = ArsipPembibitan::where('kode', 'like', "%$search%")
            ->orWhere('jenis_ternak', 'like', "%$search%")
            ->take(2)->get()->map(function($item) {
                return [
                    'text' => $item->jenis_ternak,
                    'type' => 'Arsip Pembibitan',
                    'icon' => 'book-open',
                    'url' => route('arsip-pembibitan.index', ['search' => $item->kode])
                ];
            });
            
        $ah = ArsipHijauan::where('kode_lahan', 'like', "%$search%")
            ->orWhere('jenis_hijauan', 'like', "%$search%")
            ->take(2)->get()->map(function($item) {
                return [
                    'text' => $item->jenis_hijauan,
                    'type' => 'Arsip Hijauan',
                    'icon' => 'leaf',
                    'url' => route('arsip-hijauan.index', ['search' => $item->kode_lahan])
                ];
            });
        
        $suggestions = $suggestions->concat($ap)->concat($ah);

        // 4. Documents
        $docs = Dokumen::visible()->where(function($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('kode', 'like', "%$search%");
            })
            ->take(5)->get()->map(function($item) {
                return [
                    'text' => $item->nama,
                    'type' => 'Dokumen',
                    'icon' => 'file-text',
                    'url' => route('dokumen.index', ['search' => $item->nama])
                ];
            });
            
        $suggestions = $suggestions->concat($docs);

        return response()->json($suggestions->values()->take(10));
    }

    public function getNotifications()
    {
        $recentActivities = LogAktivitas::with('user')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'activities_html' => view('dashboard._activities', compact('recentActivities'))->render(),
            'count' => $recentActivities->count(),
            'latest_id' => $recentActivities->first() ? $recentActivities->first()->id : 0
        ]);
    }
}
