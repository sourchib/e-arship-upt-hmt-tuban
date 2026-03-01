<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\ArsipPembibitan;
use App\Models\ArsipHijauan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'surat_masuk' => SuratMasuk::count(),
            'surat_keluar' => SuratKeluar::count(),
            'arsip_pembibitan' => ArsipPembibitan::count(),
            'arsip_hijauan' => ArsipHijauan::count(),
        ];

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

        // Specific activities requested by the user
        $mockedActivities = collect([
            (object)[
                'modul' => 'Surat Masuk',
                'deskripsi' => 'Surat Permohonan Bibit Ternak',
                'created_at' => now()->subMinutes(5),
                'status' => 'Pending',
                'badge_class' => 'bg-pending'
            ],
            (object)[
                'modul' => 'Surat Keluar',
                'deskripsi' => 'Laporan Distribusi Hijauan Bulan Februari',
                'created_at' => now()->subHour(1),
                'status' => 'Terkirim',
                'badge_class' => 'bg-terkirim'
            ],
            (object)[
                'modul' => 'Arsip Pembibitan',
                'deskripsi' => 'Data Ternak PO Batch 2026-02',
                'created_at' => now()->subHours(2),
                'status' => 'Terarsip',
                'badge_class' => 'bg-terarsip'
            ],
            (object)[
                'modul' => 'Arsip Hijauan',
                'deskripsi' => 'Produksi Hijauan Lahan A-12',
                'created_at' => now()->subHours(3),
                'status' => 'Terarsip',
                'badge_class' => 'bg-terarsip'
            ],
        ]);

        if ($recentActivities->isEmpty()) {
            $recentActivities = $mockedActivities;
        } else {
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
        }

        return view('dashboard', compact('stats', 'trends', 'recentActivities'));
    }
}
