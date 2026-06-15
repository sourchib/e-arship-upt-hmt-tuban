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
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->string('penerima')->nullable()->after('pengirim');
            $table->text('disposisi')->nullable()->after('keterangan');
            $table->string('penerima_disposisi')->nullable()->after('disposisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuk', function (Blueprint $table) {
            $table->dropColumn(['penerima', 'disposisi', 'penerima_disposisi']);
        });
    }
};
