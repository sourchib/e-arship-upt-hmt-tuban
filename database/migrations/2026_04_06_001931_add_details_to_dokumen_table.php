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
        Schema::table('dokumen', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('kategori');
            $table->string('kode')->nullable()->after('tanggal');
            $table->string('lokasi')->nullable()->after('kode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'kode', 'lokasi']);
        });
    }
};
