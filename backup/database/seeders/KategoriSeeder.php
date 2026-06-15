<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Laporan', 'Dokumen Teknis', 'Data', 'Panduan', 'Surat', 'Lainnya'];
        
        foreach ($categories as $cat) {
            \App\Models\Kategori::updateOrCreate(['nama' => $cat]);
        }
    }
}
