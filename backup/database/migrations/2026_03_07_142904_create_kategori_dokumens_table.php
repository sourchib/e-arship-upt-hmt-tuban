<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Insert initial data
        $categories = [
            ['nama' => 'Semua', 'keterangan' => 'Tab semua dokumen (default)'],
            ['nama' => 'Laporan', 'keterangan' => 'Dokumen laporan'],
            ['nama' => 'Dokumen Teknis', 'keterangan' => 'Dokumen yang bersifat teknis'],
            ['nama' => 'Data', 'keterangan' => 'Kumpulan data'],
            ['nama' => 'Panduan', 'keterangan' => 'Panduan penggunaan atau SOP'],
            ['nama' => 'Surat', 'keterangan' => 'Surat-surat'],
            ['nama' => 'Lainnya', 'keterangan' => 'Dokumen jenis lainnya'],
        ];
        
        // Remove 'Semua' from DB perhaps?
        // Wait, 'Semua' is not a real category, it's just a filter tab. We shouldn't store 'Semua' in DB.
        
        $categories = [
            ['nama' => 'Laporan', 'keterangan' => 'Dokumen laporan', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dokumen Teknis', 'keterangan' => 'Dokumen yang bersifat teknis', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Data', 'keterangan' => 'Kumpulan data', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Panduan', 'keterangan' => 'Panduan penggunaan atau SOP', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Surat', 'keterangan' => 'Surat-surat', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Lainnya', 'keterangan' => 'Dokumen jenis lainnya', 'created_at' => now(), 'updated_at' => now()],
        ];

        \Illuminate\Support\Facades\DB::table('kategori_dokumen')->insert($categories);
    }

    public function down()
    {
        Schema::dropIfExists('kategori_dokumen');
    }
};
