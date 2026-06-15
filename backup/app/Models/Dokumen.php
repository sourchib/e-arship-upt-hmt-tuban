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
        'is_public' => 'boolean',
    ];

    protected $fillable = [
        'nama',
        'kategori',
        'folder',
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
        'sifat_arsip',
        'is_public',
        'download_counter',
        'folder_id',
        'uploaded_by',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope a query to only include visible documents for the current user.
     */
    public function scopeVisible($query)
    {
        // Hide 'Dirahasiakan' if guest or not Admin
        $hideDirahasiakan = !auth()->check() || auth()->user()->role !== 'Admin';
        
        if ($hideDirahasiakan) {
            return $query->where('sifat_arsip', '!=', 'Dirahasiakan')
                         ->where('is_public', true);
        }
        
        return $query;
    }
}
