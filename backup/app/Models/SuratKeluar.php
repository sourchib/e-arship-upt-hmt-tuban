<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_kirim' => 'date',
    ];

    protected $fillable = [
        'nomor_surat',
        'tujuan',
        'perihal',
        'tanggal_surat',
        'tanggal_kirim',
        'prioritas',
        'status',
        'file_path',
        'keterangan',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
