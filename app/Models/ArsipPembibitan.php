<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipPembibitan extends Model
{
    use HasFactory;

    protected $table = 'arsip_pembibitan';

    protected $casts = [
        'tanggal' => 'date',
    ];

    protected $fillable = [
        'kode',
        'jenis_ternak',
        'jumlah',
        'umur',
        'tujuan',
        'tanggal',
        'status',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
