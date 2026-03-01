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
        Schema::create('arsip_hijauan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lahan')->unique();
            $table->string('jenis_hijauan');
            $table->float('luas');
            $table->float('produksi');
            $table->date('tanggal_panen');
            $table->string('lokasi');
            $table->enum('status', ['Tersedia', 'Terdistribusi']);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_hijauan');
    }
};
