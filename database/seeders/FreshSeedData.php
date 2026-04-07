<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dokumen;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\LogAktivitas;
use App\Models\ArsipPembibitan;
use App\Models\ArsipHijauan;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FreshSeedData extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to truncate safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        User::truncate();
        Dokumen::truncate();
        SuratMasuk::truncate();
        SuratKeluar::truncate();
        LogAktivitas::truncate();
        ArsipPembibitan::truncate();
        ArsipHijauan::truncate();
        // Kategori::truncate(); // Maybe keep categories? No, let's keep it if not asked.

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Seed Users (Only 5 as requested, with 1 Admin)
        User::factory()->create([
            'nama' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'Admin',
            'status' => 'Aktif',
        ]);

        User::factory(4)->create([
            'status' => 'Aktif',
        ]);

        // 2. Seed Documents (Only 5 as requested)
        $categories = ['Laporan', 'Nota Dinas', 'Surat Keterangan', 'Panduan', 'Lainnya'];
        foreach (range(1, 5) as $i) {
            Dokumen::factory()->create([
                'nama' => "Dokumen Dummy {$i}.pdf",
                'kode' => "ARC-00{$i}/2026",
                'kategori' => $categories[$i-1] ?? 'Lainnya',
                'masa_retensi' => '5 Tahun',
                'lokasi' => 'Rak A1',
                'tanggal' => now()->subDays($i * 10),
            ]);
        }

        // 3. Seed other "transactions" (5 each)
        SuratMasuk::factory(5)->create();
        SuratKeluar::factory(5)->create();
        ArsipPembibitan::factory(5)->create();
        ArsipHijauan::factory(5)->create();

        // 4. Activity Logs
        LogAktivitas::factory(5)->create([
            'deskripsi' => 'Melakukan pembersihan data sistem dan pengisian data dummy.',
        ]);
        
        $this->command->info('Database cleaned and refreshed with 5 dummy records per table.');
    }
}
