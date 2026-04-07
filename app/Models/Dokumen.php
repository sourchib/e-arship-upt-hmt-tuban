<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $casts = [
        'tanggal_upload' => 'date',
        'tanggal' => 'date',
    ];

    protected $fillable = [
        'nama',
        'kategori',
        'tanggal',
        'kode',
        'lokasi',
        'file_path',
        'ukuran',
        'mime_type',
        'tanggal_upload',
        'deskripsi',
        'masa_retensi',
        'status',
        'download_counter',
        'uploaded_by',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
