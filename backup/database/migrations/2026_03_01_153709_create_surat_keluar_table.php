<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('tujuan');
            $table->string('perihal');
            $table->date('tanggal_surat');
            $table->date('tanggal_kirim')->nullable();
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah']);
            $table->enum('status', ['Draft', 'Terkirim', 'Selesai'])->default('Draft');
            $table->string('file_path');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
