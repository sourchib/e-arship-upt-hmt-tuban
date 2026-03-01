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
        Schema::create('arsip_pembibitan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('jenis_ternak');
            $table->integer('jumlah');
            $table->string('umur');
            $table->string('tujuan');
            $table->date('tanggal');
            $table->enum('status', ['Terdistribusi', 'Proses']);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_pembibitan');
    }
};
