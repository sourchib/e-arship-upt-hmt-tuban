<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'instansi',
        'status',
        'tanggal_daftar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'tanggal_daftar' => 'date',
    ];

    public function suratMasuk()
    {
        return $this->hasMany(SuratMasuk::class, 'created_by');
    }

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class, 'created_by');
    }

    public function arsipPembibitan()
    {
        return $this->hasMany(ArsipPembibitan::class, 'created_by');
    }

    public function arsipHijauan()
    {
        return $this->hasMany(ArsipHijauan::class, 'created_by');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'uploaded_by');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }
}
