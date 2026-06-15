<?php

namespace Database\Factories;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogAktivitasFactory extends Factory
{
    protected $model = LogAktivitas::class;

    public function definition(): array
    {
        return [
            'jenis_aktivitas' => fake()->randomElement(['Login', 'Logout', 'Create', 'Update', 'Delete', 'Download']),
            'modul' => fake()->randomElement(['Surat Masuk', 'Surat Keluar', 'Arsip Pembibitan', 'Arsip Hijauan', 'Dokumen']),
            'deskripsi' => fake()->sentence(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'user_id' => User::factory(),
        ];
    }
}
