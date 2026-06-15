<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipHijauan extends Model
{
    use HasFactory;

    protected $table = 'arsip_hijauan';

    protected $casts = [
        'tanggal_panen' => 'date',
    ];

    protected $fillable = [
        'kode_lahan',
        'jenis_hijauan',
        'luas',
        'produksi',
        'tanggal_panen',
        'lokasi',
        'status',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
